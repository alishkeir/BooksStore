<?php

namespace App\Jobs\External\Book24;

use Alomgyar\Products\Product;
use App\Helpers\External\Book24Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateBook24BookFromScript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $book)
    {
        // --
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
        $updateBook = Product::where('book24_id', $this->book->Identifier)->first();

        if(empty($updateBook)){
            return;
        }

        $hasStock = (((int)$this->book->Stock ?? 0) > 9 and Book24Helper::isAvailable($this->book));

        // update book24 stock
        if($hasStock){
            $updateBook->book24_stock = (int)$this->book->Stock;
        }else{
            $updateBook->book24_stock = 0;
        }

        if(!empty($updateBook->is_dependable_status)) {
            // handle status update
            if($hasStock or Product::inventoryGreaterThanZero($updateBook->id)){
                $updateBook->state = Product::STATE_NORMAL;
            }else{
                $updateBook->state = Product::STATE_PRE;
            }


            // HANDLE COVER UPDATE
            if ($updateBook->cover !== $this->book->ImageUrl) {
                $updateBook->cover = $this->book->ImageUrl;
            }
        }

        // if ($updateBook->description !== $this->book->Description) {
        //     $updateBook->description = $this->book->Description;
        //     $update = true;
        // }

        // IF UPDATE OK, THEN SAVE IT
        if ($updateBook->isDirty()) {
            $updateBook->save();
        }


        // check if there are NEW categories for the book
        // $receivedCategories = explode(" | ", $this->book->Category);
        // foreach ($updateBook->subcategories as $category){
        //     $matchIndex = array_search($category->title, $receivedCategories);
        //     if ($matchIndex !== NULL) {
        //         array_splice($receivedCategories, $matchIndex, 1);
        //     }
        // }
        // if (count($receivedCategories)){
        //     $update = true;
        //     $this->handleCategory(implode(" | ", $receivedCategories), $updateBook->id);
        // }
    }
}
