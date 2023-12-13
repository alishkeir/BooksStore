<?php

namespace App\Jobs\External\Book24;

use App\Helpers\External\Book24Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveBook24BookAuthorFromScript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $book, public $newBookId)
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

        $authorsArray = explode(',', $this->book->Author, 2);
        $primaryAuthorName = $authorsArray[0];

        (new Book24Helper)->handleAuthors($this->newBookId, $this->book->Author, $primaryAuthorName);
    }
}
