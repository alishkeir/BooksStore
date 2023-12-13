<?php

namespace Alomgyar\Synchronizations;

use Alomgyar\Products\Product;
use App\Jobs\SetProductPriceJob;
use Prewk\XmlStreamer;

class SyncBook24UpdatePrice extends XmlStreamer
{
    public $all = 0;

    public $exist = 0;

    public $exist_2 = 0;

    public $i = 0;

    public $new = 0;

    public $updated = 0;

    public $b24ids = [];

    public $b24isbns = [];

    public $pile = [];

    public $updatePile = [];

    public function processNode($xmlString, $elementName, $nodeIndex)
    {
        $xml = simplexml_load_string($xmlString);

        $this->all++;

        if ($xml->Availability == 1) {
            if (! empty($xml->ISBN)) {
                //collect all items for current chunk
                $this->pile[$xml->Identifier.''] = $xml;
                $this->i++;
            }
        }

        //when hit the chunk count, process the load (to run for all, over a certain row run for every row)
        if (($this->i == 500 && $this->all <= 50000) || $this->all > 50000) {
            //select the products for collected xml items
            foreach ($this->pile as $id) {
                array_push($this->b24ids, $id->Identifier);
                if (isset($id->ISBN) && ! empty($id->ISBN)) {
                    // handle multiple isbn
                    // eg.: 9789639654124; 9789639654129
                    if (str_contains($id->ISBN, ';')) {
                        $fixedISBN = str_replace(';', ',', $id->ISBN);
                        array_push($this->b24isbns, $fixedISBN);
                    } else {
                        array_push($this->b24isbns, $id->ISBN);
                    }
                }
            }

            if (! empty($this->b24ids) || ! empty($this->b24isbns)) {
                $chunk = Product::query()
                ->select('id', 'book24_id', 'isbn')
                ->whereIn('book24_id', $this->b24ids)
                ->orWhereIn('isbn', $this->b24isbns)
                ->with('prices:id,product_id,price_list_original')
                ->get();

                foreach ($chunk as $product) {
                    $this->exist++;

                    if (isset($this->pile[$product->book24_id])) {
                        $xmlPrice = intval($this->pile[$product->book24_id]->ListPrice);

                        $productPrice = $product->prices?->price_list_original;

                        if ($xmlPrice !== $productPrice) {
                            $this->updated++;
                            SetProductPriceJob::dispatch($product, $xmlPrice);
                        }
                    }
                }
            }

            echo 'updated: '.$this->updated.' | all: '.$this->all.PHP_EOL;
            $this->i = 0;
            $this->pile = [];
            $this->updatePile = [];
            $this->b24ids = [];
            $this->b24isbns = [];
        }
    }
}
