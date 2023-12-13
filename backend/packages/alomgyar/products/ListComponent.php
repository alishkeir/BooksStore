<?php

namespace Alomgyar\Products;

use Alomgyar\Authors\Author;
use Alomgyar\Categories\Category;
use Alomgyar\Publishers\Publisher;
use Alomgyar\Subcategories\Subcategory;
use Alomgyar\Suppliers\Supplier;
use Alomgyar\Warehouses\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;

    protected $listeners = ['setFilter', 'setAuthorId', 'selectAll', 'setSupplierId', 'setPublisherId'];

    protected $paginationTheme = 'bootstrap';

    public $filters = [
        'category' => false,
        'subcategory' => false,
        'author' => false,
        'supplier' => false,
        'only_book' => false,
        'only_ebook' => false,
        'active' => 1,
        'discount_from' => 0,
        'discount_to' => 100,
        'tax_rate' => false,
        'tab' => 'tab-1',
        'warehouse' => false,
        'search' => '',
        'stock' => false,
        'source' => false,
        'pre' => false,
        'b24_import' => false,

    ];

    public $perPage = 10;

    public $sortField = 'product.id';

    public $sortAsc = false;

    public $selection = [];

    public $warehouses;

    public $categories;

    public $subcategories;

    public $publishers;

    public $suppliers;

    public $isbnCodeInput = '';

    public $onlyselection = false;

    public $syncWaitingTimeInMinutes;

    public $showDownloadWaitingMessage = false;

    public $isDownloadSuccess = false;

    public $showSyncWaitingMessage = false;

    public $isSyncSuccess = false;

    public $lastDownloaded;

    public $hoursPerDownload = 3;

    public function mount()
    {
        $this->warehouses = Warehouse::get();
        $this->categories = Category::get();
        //        $this->suppliers     = Supplier::get();
    }

    public function render()
    {
        if ($lastDownload = Cache::get('book24LastDownload')) {
            $totalMinutes = Carbon::now()->diffInMinutes(Carbon::create($lastDownload));
            $this->lastDownloaded = (int) ($totalMinutes / 60).' óra '.($totalMinutes % 60).' perce';
        }
        $data = request()->all();
        foreach ($data ?? [] as $filter => $value) {
            if (isset($this->filters[$filter])) {
                $this->filters[$filter] = $value;
            }
        }
        if ($this->isbnCodeInput ?? false) {
            $inputs = explode(' ', $this->isbnCodeInput);
            foreach ($inputs as $input) {
                $productToAdd = Product::where('isbn', $input)->first();
                if ($productToAdd ?? false) {
                    $this->selection[$productToAdd->id] = true;
                    $this->filters['only_selection'] = true;
                    $this->isbnCodeInput = str_replace($input.' ', '', $this->isbnCodeInput);
                    $this->isbnCodeInput = str_replace($input, '', $this->isbnCodeInput);
                }
            }
        }
        $model = $this->query();
        $model = $model
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        $this->dispatchBrowserEvent('listUpdated');
        $publisherIds = $model->pluck('publisher_id')->toArray();

        $this->publishers = Cache::remember('get-publishers-in-array-for-prod-listing', config('cache.admin_default_cache_time'), function () use ($publisherIds) {
            return Publisher::whereIntegerInRaw('id', $publisherIds)->get();
        });

        $subcategoryIDs = Cache::remember('get-subcategory-ids-for-prod-listing', config('cache.admin_default_cache_time'), function () use ($publisherIds) {
            return DB::table('product_subcategory')
                ->select('subcategory_id')
                ->whereIntegerInRaw('product_id', $publisherIds)
                ->get()
                ->pluck('subcategory_id')->toArray();
        });

        $this->subcategories = Cache::remember('get-subcategories-in-array-format-for-prod-listing', config('cache.admin_default_cache_time'), function () use ($subcategoryIDs) {
            return Subcategory::whereIntegerInRaw('id', $subcategoryIDs)->get();
        });

        //$this->subcategories = Subcategory::whereIntegerInRaw('id', $subcategoryIDs)->get();
        return view('products::components.listcomponent', [
            'model' => $model,
        ]);
    }

    public function getAuthorProperty()
    {
        return Author::select('id', 'title')->find($this->filters['author']);
    }

    public function getSupplierProperty()
    {
        return Supplier::select('id', 'title')->find($this->filters['supplier']);
    }

    protected function query()
    {
        $model = Product::select(
            'product.id',
            'product.slug',
            'product.state',
            'product.type',
            'product.title',
            'product.isbn',
            'product.store_0',
            'product.store_1',
            'product.store_2',
            'product.status',
            'product.orders_count_0',
            'product.orders_count_1',
            'product.orders_count_2',
            /*'beszallitok.neve as suppliers',*/
            'product.publisher_id',
            'product.authors',
            // 'pp_0.price_sale as price_sale_0',
            // 'pp_0.discount_percent as discount_percent_0',
            // 'pp_1.price_sale as price_sale_1',
            // 'pp_1.discount_percent as discount_percent_1',
            // 'pp_2.price_sale as price_sale_2',
            // 'pp_2.discount_percent as discount_percent_2'
        )
            ->without(['author', 'prices']);

        if ($this->filters['search'] ?? false) {
            $model->search(trim($this->filters['search']));
        }
        if ($this->filters['author'] ?? false) {
            $model = $model->whereHas('author', function ($qqq) {
                $qqq->where('author_id', '=', $this->filters['author']);
            });
        }
        if ($this->filters['subcategory'] ?? false) {
            $model = $model->whereHas('subcategories', function ($qq) {
                $qq->where('subcategory_id', '=', $this->filters['subcategory']);
            });
        }
        if ($this->filters['b24_import'] ?? false) {
            $model->whereNotNull('product.book24_id')->where('product.newcomer', '!=', 0);
            $this->filters['active'] = false;
        }
        if ($this->filters['active'] ?? false) {
            $model = $model->where('product.status', 1);
        }
        if ($this->filters['tax_rate'] ?? false) {
            $model = $model->where('tax_rate', $this->filters['tax_rate']);
        }
        if (($this->filters['only_book'] == 1) && ($this->filters['only_ebook'] == 1)) {
            $this->filters['only_book'] = 0;
            $this->filters['only_ebook'] = 0;
        }
        if ($this->filters['only_book'] == 1) {
            $model = $model->where('type', 0);
        }
        if ($this->filters['only_ebook'] == 1) {
            $model = $model->where('type', 1);
        }
        if ($this->filters['pre'] ?? false) {
            $model = $model->where('state', 1);
        }
        if ($this->filters['normal'] ?? false) {
            $model = $model->where('state', 0);
        }
        if ($this->filters['publisher'] ?? false) {
            $model = $model->where('publisher_id', $this->filters['publisher']);
        }

        if ($this->filters['supplier'] ?? false) {
            $model = $model->join('product_movements_items', function ($join) {
                $join->on('product_movements_items.product_id', '=', 'product.id')
                    ->join('product_movements', function ($j) {
                        $j->on('product_movements_items.product_movements_id', '=', 'product_movements.id')
                            ->where('product_movements.destination_type', 3)
                            ->where('product_movements.source_id', $this->filters['supplier']);
                    });
            });
        }

        $model->with('everyPrices');

        // $model->leftJoin('product_price as pp_0', function ($join) {
        //     $join->on('product.id', '=', 'pp_0.product_id')->where('pp_0.store', '=', 0);
        // });
        // $model->leftJoin('product_price as pp_1', function ($join) {
        //     $join->on('product.id', '=', 'pp_1.product_id')->where('pp_1.store', '=', 1);
        // });
        // $model->leftJoin('product_price as pp_2', function ($join) {
        //     $join->on('product.id', '=', 'pp_2.product_id')->where('pp_2.store', '=', 2);
        // });

        if ($this->filters['discount_from'] != 0 || $this->filters['discount_to'] != 100) {
            $model->where('pp_0.discount_percent', '>=', $this->filters['discount_from']);
            $model->where('pp_0.discount_percent', '<=', $this->filters['discount_to']);
        }
        if ($this->filters['cart_price'] ?? false) {
            $model->where('pp_0.price_cart', '>', 0);
        }
        if (($this->filters['warehouse'] ?? false) && ! $this->filters['stock']) {
            $model->join('inventories', 'product.id', '=', 'inventories.product_id')->where(
                'inventories.stock',
                '>',
                0
            );
            $model->where('inventories.warehouse_id', $this->filters['warehouse']);
        }
        if ($this->filters['stock'] === 'in' ?? false) {
            $model->join('inventories as inventories_plus', ['product.id' => 'inventories_plus.product_id'])
                ->where('inventories_plus.stock', '>', 0);
            if ($this->filters['warehouse'] ?? false) {
                $model->where('inventories_plus.warehouse_id', $this->filters['warehouse']);
            }
            $model->distinct();
        }
        if ($this->filters['stock'] === 'no' ?? false) {
            $model = $model->leftJoin('inventories as inventories_null', function ($join) {
                $join->on('product.id', '=', 'inventories_null.product_id')
                    ->where('inventories_null.stock', '>', 0);
                if ($this->filters['warehouse'] ?? false) {
                    $join->where('inventories_null.warehouse_id', $this->filters['warehouse']);
                }
            })->whereNull('inventories_null.product_id');
        }
        if ($this->filters['stock'] === 'low' ?? false) {
            $model->join('inventories as inventories_low', ['product.id' => 'inventories_low.product_id'])
                ->where('inventories_low.stock', '<=', 3)
                ->where('inventories_low.warehouse_id', '=', $this->warehouses->where('type', 1)->first()->id)
                ->where('product.is_stock_sensitive', 1)
                ->distinct();
        }

        if ($this->filters['only_selection'] ?? false) {
            $selection = [];
            foreach ($this->selection as $id => $sel) {
                array_push($selection, $id);
            }
            $model = $model->whereIntegerInRaw('product.id', $selection);
            $this->filters['selected_ids'] = implode('-', $selection);

            $selection = [];
        }

        if ($this->filters['source'] === 'book24' ?? false) {
            $model->whereNotNull('product.book24_id')->where('product.book24_id', '!=', 0);
        }
        if ($this->filters['source'] === 'dibook' ?? false) {
            $model->whereNotNull('product.dibook_id')->where('product.dibook_id', '!=', 0);
        }
        if ($this->filters['source'] === 'kiajanlo' ?? false) {
            $model->where('product.is_created_by_kiajanlo', '!=', 0);
        }

        //        $model->leftJoin(DB::raw('(select  `product`.`title`, `product`.`isbn`, GROUP_CONCAT(DISTINCT `supplier`.`title` SEPARATOR \', \') as neve
        //        from `product`
        //        left join `product_movements_items`on `product_movements_items`.`product_id` = `product`.`id`
        //        inner join (select `product_movements`.`id`, `title` from `suppliers`
        //        inner join `product_movements` on `product_movements`.`source_id` = `suppliers`.`id`
        //        where `product_movements`.`destination_type` = 3) as supplier on `supplier`.`id` = `product_movements_items`.`product_movements_id`
        //        where `product`.`status` = 1 and `product`.`deleted_at` is null
        //        group by `product`.`title`, `product`.`isbn`) as beszallitok'), function ($join) {
        //            $join->on('beszallitok.isbn', '=', 'product.isbn');
        //        });

        return $model;
    }

    public function selectAll()
    {
        $this->selection = [];
        $model = $this->query();
        $model = $model->select('product.id', 'product.status')->take(10000)->get();
        foreach ($model as $toSelect) {
            $this->selection[$toSelect->id] = true;
        }
    }

    public function setProductStatuses($to)
    {
        foreach ($this->selection as $id => $status) {
            if ($status) {
                $p = Product::select('id', 'status', 'state')->find($id);
                $p->status = $to;
                $p->newcomer = 0;
                if ($p->status == 1 && $p->state == Product::STATE_NORMAL && $p->published_before == 0) {
                    event(new \Alomgyar\Products\Events\ProductOrderableEvent($p));
                    $p->published_before = 1;
                    //event(new \Alomgyar\Products\Events\AuthorNewBookEvent($p)); //\Alomgyar\Products\Product::find(69658)
                }
                $p->save();
                unset($this->selection[$id]);
            }
        }
        if ($to == 0) {
            $this->selection = [];
        }
    }

    public function sortBy($column)
    {
        if ($this->sortField === $column) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortField = $column;
    }

    public function setFilter($filter)
    {
        foreach ($filter as $key => $value) {
            if (
                in_array(
                    $key,
                    ['subcategory', 'category', 'author', 'tax_rate', 'warehouse', 'supplier', 'publisher', 'stock']
                )
            ) {
                $this->filters[$key] = $value;
                if ($key === 'warehouse' && $this->filters['stock'] === 'low') {
                    $this->filters['stock'] = false;
                }
            }
        }
    }

    public function updatedFilters($value, $key)
    {
        if ($key === 'stock' && $value === 'low') {
            $this->filters['warehouse'] = false;
        }
    }

    public function setTab($tab)
    {
        $this->filters['tab'] = $tab;
    }

    public function setAuthorId($id)
    {
        $this->filters['author'] = $id;
    }

    public function setSupplierId($id)
    {
        $this->filters['supplier'] = $id;
    }

    public function setPublisherId($id)
    {
        $this->filters['publisher'] = $id;
    }

    public function dehydrate()
    {
        $this->dispatchBrowserEvent('restartSelect2');
    }

    public function updatingFiltersSearch()
    {
        $this->resetPage();
    }

    public function manualDownloadBook24()
    {
        $lastDownloaded = Cache::get('book24LastDownload');
        if ($lastDownloaded && Carbon::create($lastDownloaded)->addHours($this->hoursPerDownload) >= Carbon::now()) {
            //allow running command once every 3 hours
            $this->showDownloadWaitingMessage = true;

            return;
        }

        Cache::put('book24LastDownload', Carbon::now());
        Artisan::call('sync:book24 justDownload --limited');
        $this->isDownloadSuccess = true;
    }

    public function manualSyncBook24()
    {
        $lastSync = Cache::get('manualBook24Sync');
        if ($lastSync && Carbon::create($lastSync)->addMinutes(30) >= Carbon::now()) {
            //allow running command once every 30 mins
            $this->syncWaitingTimeInMinutes = 30 - Carbon::now()->diffInMinutes($lastSync);
            $this->showSyncWaitingMessage = true;

            return;
        }
        Cache::put('manualBook24Sync', Carbon::now());

        if (! file_exists(public_path('products_list.xml'))) {
            Artisan::call('sync:book24 justDownload --limited');
        }

        Artisan::call('sync:book24 saveNewProducts --limited');
        $this->isSyncSuccess = true;
    }

    public function deleteBook($id)
    {
        abort_if(! auth()->user()->can('products.destroy'), JsonResponse::HTTP_FORBIDDEN, '403 Forbidden');
        $product = Product::find($id);
        $product->delete();
        $this->dispatchBrowserEvent('listUpdated');
    }

    public function deleteSelected()
    {
        abort_if(! auth()->user()->can('products.destroy'), JsonResponse::HTTP_FORBIDDEN, '403 Forbidden');
        foreach ($this->selection as $id => $status) {
            if ($status) {
                $product = Product::find($id);
                $product->delete();
                unset($this->selection[$id]);
            }
        }
        $this->dispatchBrowserEvent('listUpdated');
    }
}
