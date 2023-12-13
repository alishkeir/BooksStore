<?php

namespace Alomgyar\BookRecommendation;

use Alomgyar\Authors\Author;
use Alomgyar\Products\Product;
use Alomgyar\Publishers\Publisher;
use App\Services\PriceCalculationService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class BookRecommendationComponent extends Component
{
    use WithPagination;

    protected $listeners = ['setFilter'];

    public $isbn;

    public $newPublisher = false;

    public $authors = [];

    public $categories = [];

    public $type = Product::BOOK;

    public $title;

    public $price;

    public $book_state = Product::STATE_NORMAL;

    public $cover;

    public $tax_rate = 5;

    public $description;

    public $published_at;

    public $publisher_id;

    public $newPublisherName;

    public $newPublisherEmail;

    public $release_year;

    public $number_of_pages;

    public $language;

    public $book_binding_method;

    public $newAuthor = false;

    public $newAuthorName;

    public $topMessage;

    protected $rules = [
        'type' => [
            'required',
        ],

        'title' => [
            'required',
        ],
        'book_state' => [
            'required',
        ],
        'description' => [
            'required',
        ],
        'release_year' => [
            'required',
        ],
        'price' => [
            'required',
        ],
        'tax_rate' => [
            'required',
        ],
        'number_of_pages' => [
            'required',
        ],

        // 'published_at' => [
        //     'date',
        //     'required_if' => 'book_state,'.Product::STATE_PRE,
        // ],

    ];

    public function mount()
    {
        // --
    }

    public function updatedIsbn($isbn)
    {
        if(Str::startsWith($isbn, 0))
        {
            // REMOVE THE STARTING 0 DIGIT FROM ISBN
            $this->isbn = substr($isbn, 1);
        }

    }

    public function render()
    {
        $book = false;

        if (isset($this->isbn) && $this->isbn != '') {
            $book = Product::where('isbn', $this->isbn)->exists();
        }
        $this->dispatchBrowserEvent('registerSelect2');

        return view('bookrecommendation::components.bookrecommendation', [
            'book' => $book,
            'isbn' => $this->isbn,
            'newPublisher' => $this->newPublisher,
        ]);
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $publisher_id = $this->publisher_id;
            if ($this->newPublisher) {
                $publisher = Publisher::create([
                    'title' => $this->newPublisherName,
                    'description' => $this->newPublisherEmail,
                    'status' => Publisher::STATUS_ACTIVE,
                ]);

                $publisher_id = $publisher->id;
            }

            $product = Product::create([
                'type' => $this->type,
                'title' => $this->title,
                'slug' => (new Product)->slugify($this->title),
                'isbn' => $this->isbn,
                'state' => $this->book_state,
                'status' => Product::STATUS_INACTIVE,
                'cover' => $this->cover,
                'description' => $this->description,
                'release_year' => $this->release_year,
                'price' => $this->price,
                'tax_rate' => $this->tax_rate,
                'number_of_pages' => $this->number_of_pages,
                'is_created_by_kiajanlo' => 1,
                'published_at' => $this->published_at,
                //'will_published_at' => strtotime($this->published_at) < strtotime('now') ? 1 : 0,
                'publisher_id' => $publisher_id,
                'language' => $this->language,
                'book_binding_method' => $this->book_binding_method,
            ]);

            $product->subcategories()->sync($this->categories);
            // foreach ($this->categories ?? [] as $key => $categoryId) {
            //     DB::table('product_subcategory')->insert([
            //         'product_id' => $product->id,
            //         'subcategory_id' => $categoryId,
            //     ]);
            // }
            $isNew = $this->published_at > date('Y-m-d') ? true : false;
            (new PriceCalculationService)->savePricesForProduct($product->id, $this->price, $isNew);


            if ($this->newAuthor) {
                $author = Author::create([
                    'title' => $this->newAuthorName,
                    'status' => Author::STATUS_ACTIVE,
                ]);

                $this->authors[] = $author->id;
            }

            foreach ($this->authors ?? [] as $key => $authorId) {
                DB::table('product_author')->insert([
                    'product_id' => $product->id,
                    'author_id' => $authorId,
                    'primary' => $key == 0 ? 1 : 0,
                ]);
            }

            DB::commit();
        } catch(Exception $e) {
            ray($e->getMessage())->red();
            Log::debug($e);
            DB::rollBack();

            session()->flash('error', 'Hiba történt a létrehozás közben');

            return redirect()->back();
        }

        $this->reset();

        $this->topMessage = 'Sikeresen feltöltötted az előző '.$product->title.' könyvet!';

        return redirect()->back();
    }

    // public function todosforthis()
    // {
        // --
    // }
}
