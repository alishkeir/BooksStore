<?php

namespace Alomgyar\Products;

use Alomgyar\Authors\Author;
use Alomgyar\Comments\Comment;
use Alomgyar\Product_movements\ProductMovementItems;
use Alomgyar\Products\Services\IsbnService;
use Alomgyar\Promotions\Promotion;
use Alomgyar\Promotions\Scopes\NotShowFlashDealScope;
use Alomgyar\Publishers\Publisher;
use Alomgyar\RankedProducts\Model\RankedProduct;
use Alomgyar\Subcategories\Subcategory;
use Alomgyar\Suppliers\Supplier;
use Alomgyar\Warehouses\Inventory;
use Alomgyar\Warehouses\Warehouse;
use App\Helpers\BookIsbnHelper;
use App\Order;
use App\Traits\SlugifyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @method static whereSlug( mixed $slug )
 * @method static whereType( mixed $type )
 */
class Product extends Model implements HasMedia
{
    use LogsActivity, InteractsWithMedia, SoftDeletes, HasFactory;
    use SlugifyTrait;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    const BOOK = 0;

    const EBOOK = 1;

    const STATE_NORMAL = 0;

    const STATE_PRE = 1;

    const STATE_MANUAL = 2;

    const STOCK_LIMIT = 3;

    const NOT_NEW = 0;

    const IS_NEW = 1;

    const AUTHOR_EMAIL_NOT_SENT = 0;

    const AUTHOR_EMAIL_SENT = 1;

    const DISCOUNT_TYPE_NORMAL = 0;
    const DISCOUNT_TYPE_DEFAULT_RATE = 1;
    const DISCOUNT_TYPE_NEW_RATE = 2;

    public const CANVAS_BAG_STRING = 'vászontáska';

    protected $table = 'product';

    protected $fillable = [
        'title',
        'description',
        'slug',
        'state', //normál, előjegyezhető, manuális(készlet)
        'status', //látható, nem látható
        'type', //ebook, book, audio
        'store_0',
        'store_1',
        'store_2',
        'cover',
        'publisher_id',
        'legal_owner_id',
        'writer_commission',
        'order_count',
        'isbn',
        'release_year',
        'number_of_pages',
        'tax_rate',
        'published_at',
        'will_published_at',
        'authors',
        'mobi_url',
        'mobi_size',
        'epub_url',
        'epub_size',
        'published_before',
        'is_dependable_status',
        'is_stock_sensitive',
        'preorder_notified_at',
        'meta_title',
        'meta_description',
        'language',
        'book_binding_method',
        'is_created_by_kiajanlo',
        'do_not_update_price',
        'free_delivery',
        'only_prepay',
        'order_only_alone',
        'order_only_shop',
        'discount_type'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['author', 'prices'];

    protected static $logAttributes = ['*'];

    /**
     * Default value of status
     *
     * @var int[]
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
        'is_dependable_status' => 0,
        'is_stock_sensitive' => 0,
    ];

    protected $casts = [
        'published_before' => 'boolean',
        'published' => 'datetime',
        'will_published_at' => 'date',
        'is_dependable_status' => 'boolean',
        'is_stock_sensitive' => 'boolean',
        'is_new' => 'boolean',
        'do_not_update_price' => 'boolean',
        'free_delivery' => 'boolean',
        'only_prepay' => 'boolean',
        'order_only_alone' => 'boolean',
        'order_only_shop' => 'boolean',
    ];

    public static $messages = [
        'required' => 'A :attribute kitöltése kötelező!',
        'email.required' => 'Az :attribute kitöltése kötelező!',
        'max' => 'Nem lehet hosszabb, mint :max karakter!',
        'alpha_num' => 'Csak az ABC betűit és számokat használhatod!',
        'alpha' => 'Csak az ABC betűit használhatod!',
        'string' => 'Csak az ABC betűit használhatod!',
        'min' => 'Nem lehet kevesebb, mint :min karakter!',
        'date' => 'Nem megfelelő dátum formátum!',
        'numeric' => 'Csak szám lehet!',
        'integer' => 'Csak szám lehet!',
        'email' => 'Nem megfelelő formátum!',
        'unique' => 'Ezzel az email címmel már regisztráltak!',
        'phone.regex' => 'Nem megfelelő formátum!',
    ];

    public function price($store)
    {
        return $this->hasOne(ProductPrice::class, 'product_id')->where('store', $store)->first();
    }

    public function prices()
    {
        return $this->hasOne(ProductPrice::class, 'product_id');
    }

    public function everyPrices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product', 'product_id', 'promotion_id')->current();
    }

    public function promotionsWithFlashSales()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product', 'product_id', 'promotion_id')->current()->withoutGlobalScope(NotShowFlashDealScope::class);
    }

    public function author()
    {
        return $this->belongsToMany(Author::class, 'product_author', 'product_id', 'author_id')
            ->withPivot('primary')
            ->orderBy('primary', 'DESC');
    }

    public function primaryAuthor()
    {
        return $this->belongsToMany(Author::class, 'product_author', 'product_id', 'author_id')->wherePivot(
            'primary',
            1
        );
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'product_subcategory', 'product_id', 'subcategory_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class)->whereStatus(1);
    }

    public function productMovementItems()
    {
        return $this->hasMany(ProductMovementItems::class);
    }

    public function scopeSuppliers($query)
    {
        //        return $this->productMovementItems->map(function ($item) {
        //            $item->suppliers = collect($item->productMovement->where('destination_type', 3)->get()->transform(function ($item) {
        //                $item->supplier = Supplier::select('id', 'title')->find($item->source_id);
        //                return $item->only('supplier');
        //            }))->flatten()->unique();
        //
        //            return $item->only('suppliers');
        //        })->collapse()->first();
        return $query->leftJoin(DB::raw('(select  `product`.`title`, `product`.`isbn`, GROUP_CONCAT(DISTINCT `supplier`.`title` SEPARATOR \', \') as neve
        from `product`
        left join `product_movements_items`on `product_movements_items`.`product_id` = `product`.`id`
        inner join (select `product_movements`.`id`, `title` from `suppliers`
        inner join `product_movements` on `product_movements`.`source_id` = `suppliers`.`id`
        where `product_movements`.`destination_type` = 3) as supplier on `supplier`.`id` = `product_movements_items`.`product_movements_id`
        where `product`.`status` = 1 and `product`.`deleted_at` is null
        group by `product`.`title`, `product`.`isbn`) as beszallitok'), function ($join) {
            $join->on('beszallitok.isbn', '=', 'product.isbn');
        });
    }

    public function getBeszallitokAttribute()
    {
        return Product::leftJoin(DB::raw('(select  `product`.`title`, `product`.`isbn`, GROUP_CONCAT(DISTINCT `supplier`.`title` SEPARATOR \', \') as neve
        from `product`
        left join `product_movements_items`on `product_movements_items`.`product_id` = `product`.`id`
        inner join (select `product_movements`.`id`, `title` from `suppliers`
        inner join `product_movements` on `product_movements`.`source_id` = `suppliers`.`id`
        where `product_movements`.`destination_type` = 3) as supplier on `supplier`.`id` = `product_movements_items`.`product_movements_id`
        where `product`.`status` = 1 and `product`.`deleted_at` is null
        group by `product`.`title`, `product`.`isbn`) as beszallitok'), function ($join) {
            $join->on('beszallitok.isbn', '=', 'product.isbn');
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id')
            ->whereIn('status', [1, 2])->where('entity_type', 0)->latest();
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }

    public function getWebshopOrdersCountAttribute()
    {
        return DB::table('order_items')
            ->join('orders', function ($join) {
                $join->on('orders.id', '=', 'order_items.order_id');
            })
            ->where('product_id', $this->id)
            ->where('orders.status', '<', Order::STATUS_WAITING_FOR_SHIPPING)
            ->where('orders.store', '<', 3)
            ->sum('order_items.quantity');
    }

    public function scopeReview($query)
    {
        return $query->join('product_review', 'product.id', '=', 'product_review.product_id')
            ->select('product.*', 'product_review.review')
            ->whereStore(request('store'))
            ->where('customer_id', request()->user()->id);
    }

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
            : $query->where('product.id', 'like', '%'.$term.'%')
            ->orWhere('product.title', 'like', '%'.$term.'%')
            ->orWhere('product.isbn', 'like', '%'.$term.'%')
            ->orWhere('product.slug', 'like', '%'.$term.'%');
    }

    public function scopeApproved($query) //used for comments only
    {
        return $query->where('status', 2);
    }

    public function scopeNew($query) //used for comments only
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }

    public function getStockAttribute($warehouseId = null)
    {
        if ($warehouseId) {
            return Inventory::active()->where('product_id', $this->id)->where('warehouse_id', $warehouseId)->sum('stock');
        }

        return Inventory::active()->where('product_id', $this->id)->sum('stock');
    }

    public function getStockGPSAttribute()
    {
        $gps = Warehouse::where('type', 1)->first();

        return Inventory::active()->where('product_id', $this->id)->where('warehouse_id', $gps->id)->sum('stock');
    }

    public static function getLowStockProductsCount()
    {
        $gps = Warehouse::where('type', 1)->first();
        if (! empty($gps)) {
            return DB::table('inventories')
                ->select('product_id', DB::raw('SUM(stock)'))
                ->join('product', 'inventories.product_id', '=', 'product.id')
                ->where('warehouse_id', $gps->id)
                ->where('product.is_stock_sensitive', 1)
                ->groupBy('product_id')
                ->havingRaw('SUM(stock) <= ?', [3])
                ->count();
        }
    }

    public function scopeSelectForList($query)
    {
        return $query->select(
            'product.id',
            'product.title',
            'product.slug',
            'product.status',
            'product.cover',
            'product.state',
            'product.type',
            'product.authors',
            'product.is_new',
            'product.publisher_id'
        );
    }

    public function scopeThisStore($query)
    {
        return $query->where('product.store_'.request('store'), 1);
    }

    public function scopePre($query)
    {
        return $query->whereState(1);
    }

    public function scopeEbook($query)
    {
        return $query->whereType(1);
    }

    public function scopeBook($query)
    {
        return $query->whereType(0);
    }

    public function scopeAuthorMailSent($query, $value = 1)
    {
        return $query->where('author_mail_sent', $value);
    }

    public function scopeAuthorMailNotSentYet($query)
    {
        return $query->where('author_mail_sent', Product::AUTHOR_EMAIL_NOT_SENT);
    }

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function ranked(): HasOne
    {
        return $this->hasOne(RankedProduct::class, 'product_id', 'id');
    }

    public function getPublishedAttribute()
    {
        return $this->state === self::STATE_PRE
            ? $this->will_published_at ?? null
            : $this->published_at ?? null;
    }

    public static function inventoryLessThanOrEqual($productID, int $value): bool
    {
        return (self::find($productID)->stock ?? 0) <= $value;
    }

    public static function inventoryGreaterThanZero($productID): bool
    {
        return (self::find($productID)->stock ?? 0) > 0;
    }

    // public function getFormattedPublishedAtAttribute(){
    //     return $this->state === 1 ? ($this->will_published_at ? Carbon::parse($this->will_published_at)->format('Y.m.d.'): '') : ($this->published_at ? Carbon::parse($this->published_at)->format('Y.m.d.'): '');
    // }
    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? Carbon::parse($this->published_at)->format('Y-m-d') : '';
    }

    public function getFormattedWillPublishedAtAttribute()
    {
        return $this->will_published_at ? Carbon::parse($this->will_published_at)->format(config('pamadmin.date-format')) : '';
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            // IN CASE OF PRINT BOOKS
            if ($model->type == Product::BOOK) {
                // CHECK IF THE ISBN ALREADY EXISTS
                // WITH THE SAME TYPE HELPER (BOOK - BOOK OR EBOOK - EBOOK)
                // BECAUSE WE ONLY ACCEPT ISBN DUPLICATION WITH DIFFERENT TYPES
                if ((new IsbnService)->isIsbnAlreadyExistsWithSameType($model->isbn, $model->type)) {
                    // IF EXISTS, RETURN FALSE
                    Log::channel('isbn')->info('Not saved because duplication: '.$model->isbn);

                    return false;
                }
            }
            // if ((new IsbnService)->isIsbnAlreadyExistsWithSameType($model->isbn, $model->type)) {
            //     // IF EXISTS, RETURN FALSE
            //     Log::channel('book')->info('Not saved because duplication: '.$model->isbn);

            //     return false;
            // }
        });

        static::created(function ($model) {
            //(new BookIsbnHelper)->regenerateIsbnCollection();
        });
        static::updated(function ($model) {
            //(new BookIsbnHelper)->regenerateIsbnCollection();
        });
    }

    // public function statementToDelete()
    // {
        // https://gitlab.weborigo.eu/weborigo-projects/lomgy-r/documents/-/issues/202

        // SET FOREIGN_KEY_CHECKS=0;
        // DELETE
        // FROM product
        // WHERE id = 86363 OR id = 76509;
        // SET FOREIGN_KEY_CHECKS=1;

        // SET MOVEMENTS FROM 76509 TO 76507

        // UPDATE product_movements_items
        // SET product_id = 76507
        // WHERE product_id = 76509;
    // }

    // public function removeBook24BooksWithoutPrices()
    // {
    //     DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //     $products = Product::query()
    //         ->select('id', 'isbn', 'book24_id', 'created_at')
    //         ->without(['author', 'prices'])
    //         ->whereNotNull('book24_id')
    //         ->where('type', Product::BOOK)
    //         ->whereDoesntHave('prices')
    //         ->each(function ($product) {
    //             $product->forceDelete();
    //         });

    //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    //     return 'yeee';
    // }

    // public function removeBook24DuplicatesTinker()job
    // {
    //     // GET BOOKS WITH BOOK24 ID
    //     // WHICH ARE ACTIVE
    //     // AND BOOK TYPE
    //     $products = Product::query()
    //         ->select('id','isbn' ,'book24_id', 'created_at')
    //         ->without(['author', 'prices'])
    //         //->whereNotNull('book24_id')
    //         ->where('type', Product::BOOK)
    //         ->get();

    //     Log::channel('book24')->info('Start inactivating ');

    //     // CHECK IF THERE ARE MORE THAN ONE PRODUCT WITH THE SAME BOOK24 ID
    //     $products->groupBy('isbn')->filter(function ($group) {
    //         return $group->count() > 1;
    //     })->each(function ($group) {
    //         return $group->hasAny('book24_id');
    //     })->each(function ($group) {
    //         // IF THERE ARE, WE WILL MAKE INACTIVE THE NEW ONE
    //         $newer = $group->sortByDesc('id')->first();
    //         if($newer->created_at >= \Carbon\Carbon::now()->subDays(30))
    //         {
    //             // LOG IT FOR FUTURE REFERENCE
    //             Log::channel('book24')->info('Inactivated: '.$newer->id);
    //             $newer->newcomer = 0;
    //             $newer->delete();
    //         }

    //     });
    //     Log::channel('book24')->info('End of inactivating ');

    //     return 'Done'.PHP_EOL;
    // }

    // public function deleteStoryTinker()
    // {
    //     $ids = [
    //         85535,
    //         85465,
    //         85464,
    //         85462,
    //         85534,
    //         85463,
    //         85461,
    //         85459,
    //         85460,
    //         85456,
    //         85458,
    //         85455,
    //         85457,
    //         85467,
    //         85466,
    //         85452,
    //         85453,
    //         85454,
    //         85450,
    //         85451,
    //         85449,
    //         85447,
    //         85448,
    //         85445,
    //         85446,
    //         85444,
    //         85443,
    //         85442,
    //         85441,
    //         85440,
    //         85438,
    //         85439,
    //         85437,
    //         85436,
    //         85435,
    //         85434,
    //         85433,
    //         85432,
    //         85431,
    //         86342,
    //         86343,
    //     ];

    //     DB::table('product')->whereIn('id', $ids)->update(['newcomer' => 0]);

    // }

    // public function setDown()
    // {
        // $products = Product::whereNotNull('book24_id')->each(function ($product){
        //     $product->slug = 'book24slug'. rand(100000,10000000);
        //     $product->isbn = rand(100000,10000000);
        //     $product->book24_id = rand(100000,10000000);
        //     $product->saveQuietly();
        // });
    // }

    // public function tinkerSetEbookToFivePercent()
    // {
    //     $products = \Alomgyar\Products\Product::withoutGlobalScopes()->with('everyPrices')->ebook()->get();

    //     foreach ($products as $key => $product) {
    //         $discountPercentage = 5;
    //         $finalSalePercantage = (100 - $discountPercentage) / 100;
    //         if ($product->everyPrices) {
    //             foreach ($product->everyPrices as $key => $price) {
    //                 $salePrice = $price->price_list_original * $finalSalePercantage;
    //                 $price->price_sale = $salePrice;
    //                 $price->price_sale_original = $salePrice;
    //                 $price->discount_percent = $discountPercentage;
    //                 $price->save();
    //             }
    //         }
    //     }
    //     echo 'Done' .PHP_EOL;
    // }
}
