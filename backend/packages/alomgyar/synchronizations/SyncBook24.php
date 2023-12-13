<?php

namespace Alomgyar\Synchronizations;

use Alomgyar\Authors\Author;
use Alomgyar\Products\Product;
use Alomgyar\Products\ProductPrice;
use App\Helpers\External\Book24Helper;
use App\Jobs\External\Book24\SaveBook24BookFromScript;
use App\Jobs\External\Book24\UpdateBook24BookFromScript;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;
use Prewk\XmlStreamer;
use Throwable;

class SyncBook24 extends XmlStreamer
{
    public $all = 0;

    public $exist = 0;

    public $i = 0;

    public $new = 0;

    public $updated = 0;

    public $b24ids = [];

    public $b24isbns = [];

    public $pile = [];

    public $updatePile = [];

    public $saveFlag = false;

    public $limited = false;

    public $reachedLimit = false;

    public $limit = 100;

    public $publishAndMoreCompanies = ['Publish and More Kft.', 'Álomgyár Kiadó'];

    public $test = 0;

    public function __construct($mixed, $limited = false)
    {
        parent::__construct($mixed);
        $this->limited = $limited; //only fetch 100 new books
        $this->reachedLimit = false;
    }

    public function processNode($xmlString, $elementName, $nodeIndex)
    {
        if (Book24Helper::limitCheck($this->limited, $this->reachedLimit)) {
            $xml = simplexml_load_string($xmlString);
            $this->all++;

            $isbn = Book24Helper::getProductIsbn($xml);
            if (! empty($isbn)) {
                //collect all items for current chunk
                $this->pile[$isbn.''] = $xml;
                $this->i++;
            }

            $this->processChunk();
        }
    }

    public function saveNew($book, $isbn)
    {
        try {
            SaveBook24BookFromScript::dispatch($book->asXml(), $isbn, $this->publishAndMoreCompanies);
        } catch (\Throwable $th) {
            Log::channel('book24')->info('saveNew error with book24_id: '.$book->Identifier);
        }
    }

    public function updateBook($book)
    {
        try {
            UpdateBook24BookFromScript::dispatch($book->asXml());
        } catch (\Throwable $th) {
            Log::channel('book24')->info('saveNew error with book24_id: '.$book->Identifier);
        }
    }

    public static function downloadXml($from, $to = '')
    {
        file_put_contents('products_list.xml', fopen($from, 'r'));

        return true;
    }

    public function chunkCompleted()
    {
        //handle save if chunk is less than 500
        if ($this->limited && $this->reachedLimit) {
            return;
        }
        if ($this->i < 500 && $this->i > 0 && $this->getReadBytes() == $this->getTotalBytes()) {
            $this->saveFlag = true;
            $this->processChunk();
        }
    }

    public function processChunk()
    {
        if ($this->limited) {
            if ($this->reachedLimit) {
                return;
            }
            if ($this->new <= $this->limit && $this->i >= 100) {
                $this->saveFlag = true;
            }
        }
        //when hit the chunk count, process the load (to run for all, over a certain row run for every row)
        if (($this->i == 500 && $this->all <= 150000) || $this->all > 150000 || $this->saveFlag) {
            //select the products for collected xml items
            foreach ($this->pile as $id) {
                array_push($this->b24ids, (int)$id->Identifier);
                $isbn = Book24Helper::getProductIsbn($id);
                if (isset($isbn) && ! empty($isbn)) {
                    // handle multiple isbn
                    // eg.: 9789639654124; 9789639654129
                    if (str_contains($isbn, ';')) {
                        $fixedISBN = str_replace(';', ',', $isbn);
                        array_push($this->b24isbns, (int)$fixedISBN);
                    } else {
                        array_push($this->b24isbns, (int)$isbn);
                    }
                }
            }

            if (! empty($this->b24ids) || ! empty($this->b24isbns)) {
                $chunk = DB::select(DB::raw('
                SELECT product.id, product.isbn, product.book24_id FROM product
                WHERE book24_id IN ('.implode(', ', $this->b24ids).')
                OR isbn IN ('.implode(', ', $this->b24isbns).')
                ;'));

                foreach ($chunk as $product) {
                    $this->exist++;

                    //if exist, take it to the update pile BY ISBN
                    $isbnExists = false;
                    $existentKey = '';
                    foreach (array_keys($this->pile) as $key) {
                        if (str_contains($key, $product->isbn)) {
                            $isbnExists = true;
                            $existentKey = $key;
                            break;
                        }
                    }

                    if ($isbnExists) {
                        $this->updatePile[$existentKey] = $this->pile[$existentKey];
                        //if exist in our db, cut out, so just the new ones stays (faster with this method)
                        unset($this->pile[$existentKey]);
                    }
                    ////////////////////////////////////////
                    // WILL ADD UPDATE PART
                    ////////////////////////////////////////
                    //if exist, take it to the update pile BY book24_id
                    // $matchIndex = array_search($product->book24_id, array_column($this->pile, 'Identifier'));
                    // if ($matchIndex !== false) {
                    //     array_merge($this->updatePile, array_splice($this->pile, $matchIndex, 1));
                    // }
                }
                if ($this->limited && $this->new + count($this->pile) > $this->limit) {
                    $this->pile = array_slice($this->pile, 0, $this->limit - $this->new); //take limit elements
                    $this->reachedLimit = true;
                }
                foreach ($this->pile as $newProductXml) {
                    $isbn = Book24Helper::getProductIsbn($newProductXml);
                    // IF IT CONTAINS MORE ISBN NUMBER
                    if (str_contains($isbn, ';')) {
                        $ISBNArray = explode(';', $isbn, 2);
                        $primaryISBN = $ISBNArray[0];
                    } else {
                        $primaryISBN = $isbn;
                    }



                    //take books that have price and page number data,
                    //take available books with stock over 10 and within 8 years,
                    //take preorder books in the past 2 years
                    if (
                        $newProductXml->ListPrice > 0 && $newProductXml->PageNumber > 0 && (
                            ($newProductXml->Availability == 1 && $newProductXml->IssueDateYear >= date('Y') - 8 && $newProductXml->Stock > 9)
                            ||
                            (Book24Helper::isPreorder($newProductXml) && $newProductXml->IssueDateYear >= date('Y') - 2)
                        )
                    ) {
                        // CAST TO STRING
                        $primaryISBN = (int) $primaryISBN;
                        $this->saveNew($newProductXml, $primaryISBN);
                        $this->new++;
                    }
                }

                ////////////////////////////////////////
                // WILL ADD UPDATE PART
                ////////////////////////////////////////
                //don't run update for fetching 100 new books

                 if (!$this->limited) {
                     foreach ($this->updatePile as $newProductXml) {
                         $this->updateBook($newProductXml);
                     }
                     $this->updated += count($this->updatePile);
                 }
            }
            //foreach($xml->authors as $a){

            //dd($a->author->name.'');
            //}
            //don't print out restults for fetching 100 books
            if (! $this->limited) {
                echo 'exist: '.$this->exist.' | new: '.$this->new.'. | updated: '.$this->updated.' | all: '.$this->all.PHP_EOL;
            }
            $this->i = 0;
            $this->pile = [];
            $this->updatePile = [];
            $this->b24ids = [];
            $this->b24isbns = [];
        }
        $this->saveFlag = false;
        //}
    }
}
