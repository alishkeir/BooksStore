<?php

namespace App\Jobs\External\Book24;

use Alomgyar\Products\Product;
use App\Helpers\BookIsbnHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SaveBook24BookFromScript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $book, public $isbn, public $publishAndMoreCompanies)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // CONVERT BACK TO XML
        $this->book = simplexml_load_string($this->book);

        $xmlVersionBook = $this->book->asXml();

        // LAST ISBN CHECK
        // DON'T SAVE IF ISBN IS NOT IN THE DATABASE
        // if ((new BookIsbnHelper)->isbnExists($this->isbn)) {
        //     return;
        // }
        if (DB::table('product')->where('isbn', $this->isbn)->exists()) {
            return;
        }

        $newBook = new Product();
        $newBook->title = htmlspecialchars($this->book->Name);
        $slug = Str::slug($this->book->Name);

        if (Product::where('slug', $slug)->exists()) {
            $isbnRelatedSlug = $slug.'-'.$this->isbn;
            $newBook->slug = $isbnRelatedSlug;
            if (Product::where('slug', $isbnRelatedSlug)->exists()) {
                // SIMPLE SLUG & WITH ISBN RELATED SLUG IS RESERVED
                // LOOKS LIKE SOMETHING WE DUPLICATE HERE
                Log::info('Book24: '.$this->book->Name.' is duplicated, not saved because of slug: '.$isbnRelatedSlug.' exists.');

                return;
            }
        } else {
            $newBook->slug = $slug;
        }

        $newBook->description = $this->book->Description;
        $newBook->type = Product::BOOK; //1 book

        $newBook->isbn = $this->isbn;

        $newBook->number_of_pages = $this->book->PageNumber ?? 0;
        $newBook->release_year = $this->book->IssueDateYear ?? null;
        $newBook->published_at = $this->book->PublishDate ?? null;

        //$newBook->cover =$this->handleImage($this->book->ImageUrl, $slug);
        $newBook->cover = $this->book->ImageUrl;

        $newBook->store_0 = 1;
        $newBook->store_1 = 1;
        $newBook->store_2 = 1;
        $newBook->status = 0;
        $newBook->tax_rate = $this->book->Vat;

        $newBook->published_before = $newBook->release_year >= date('Y') ? 0 : 1;
        $newBook->state = Product::STATE_PRE;
        $isNew = false;
        if ($newBook->published_before == 0 && $newBook->state == Product::STATE_NORMAL) {
            $isNew = true;
        }

        // $authors=[];
        // foreach ($this->book->authors as $author) {
        //     $authors[]=$author->author->name.'';
        // }
        // $newBook->authors = implode(', ', $authors);
        $newBook->authors = $this->book->Author;
        $newBook->book24_id = $this->book->Identifier;
        $newBook->book24_sync = 1;
        $newBook->newcomer = 1;
        $newBook->language = $this->book->Language;
        $newBook->book_binding_method = $this->book->Binding;

        $newBook->is_dependable_status = 1;
        foreach ($this->publishAndMoreCompanies as $companyName) {
            if (str_contains($this->book->Manufacturer, $companyName)) {
                $newBook->is_dependable_status = 0;
                break;
            }
        }
        if ($newBook->save()) {
            if ($this->book->Author ?? false) {
                SaveBook24BookAuthorFromScript::dispatch($xmlVersionBook, $newBook->id);

                // $authorsArray = explode(",", $this->book->Author, 2);
                // $primaryAuthorName = $authorsArray[0];
                // $book24Helper->handleAuthors($newBook->id, $this->book->Author, $primaryAuthorName);
            }
            if ($this->book->Manufacturer ?? false) {
                SaveBook24BookPublisherFromScript::dispatch($xmlVersionBook, $newBook);

                //$book24Helper->handlePublisher($newBook, $this->book->Manufacturer);
            }

            // THIS CATEGORY FORMAT IS EQUAL
            // <Category>Fantasy | Paranormális misztikus fantasy</Category>
            if (! empty($this->book->Category)) {
                //////
                SaveBook24BookCategoryFromScript::dispatch($xmlVersionBook, $newBook->id);
                //$book24Helper->handleCategory($this->book->Category, $newBook->id);
            }

            SaveBook24BookPriceFromScript::dispatch($xmlVersionBook, $newBook, $isNew);
        }
    }
}
