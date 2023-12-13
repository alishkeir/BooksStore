<?php

namespace Modules\Admin\Http\Controllers;

use Alomgyar\Comments\Comment;
use Alomgyar\Orders\EloquentOrdersRepository;
use Alomgyar\Orders\ListItemComponent;
use Alomgyar\Products\Product;
use Alomgyar\Warehouses\Warehouse;
use App\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Skvadcom\Logs\Log;

class AdminController extends Controller
{
    private ListItemComponent $listItemComponent;

    private EloquentOrdersRepository $ordersRepository;

    public function __construct(ListItemComponent $listItemComponent)
    {
        $this->listItemComponent = $listItemComponent;
        //$this->ordersRepository = $ordersRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('szerző') && auth()->user()->writer_id) {
            return redirect()->route('writers.show', ['writer' => auth()->user()->writer?->id]);
        }
        if (auth()->user()->hasRole('skvadmin')) {
            $lastLogins = Log::where(['description' => 'SuccessfulLogin'])->latest()->limit(10)->get();
            $users = Cache::remember('db-table-users-name-return', config('cache.admin_default_cache_time'), function () {
                return DB::table('users')->select('name')->get();
            });
            $usersTotal = $users->count();
            $lowStockProductsCount = Product::getLowStockProductsCount();
            $book24ImportCount = Product::where('newcomer', 1)->count();
            $latestComments = Comment::latest()->take(10)->get();
            $activity = Log::limit(7)->orderBy('id', 'DESC')->get();

            ///-----
            $okListCount = Cache::remember('order-item-count-ok-admin-dash', config('cache.admin_default_cache_time'), function () {
                return $this->getOrderItemCountInformations('ok')->count();
            });
            $almostListCount = Cache::remember('order-item-count-almos-list-admin-dash', config('cache.admin_default_cache_time'), function () {
                return $this->getOrderItemCountInformations('almost')->count();
            });
            $noListCount = Cache::remember('order-item-count-no-list-admin-dash', config('cache.admin_default_cache_time'), function () {
                return $this->getOrderItemCountInformations('no')->count();
            });

            // ----
            // $okListCount = 0;
            // $almostListCount = 3;
            // $noListCount = 10;
            ///-----

            return view('admin::index', [
                'activity' => $activity,
                'lastLogins' => $lastLogins,
                'usersTotal' => $usersTotal,
                'users' => $users,
                'lowStockProductsCount' => $lowStockProductsCount,
                'okListCount' => $okListCount,
                'almostListCount' => $almostListCount,
                'noListCount' => $noListCount,
                'latestComments' => $latestComments,
                'book24ImportCount' => $book24ImportCount,
            ]);
        } else {
            return view('admin::index');
        }
    }

    public function getOrderItemCountInformations($type)
    {
        $mainWarehouse = Warehouse::main();
        $model = OrderItem::query()
            ->select(
                'id'
            )
            ->leftJoin('product', function ($join) {
                $join->on('product.id', '=', 'order_items.product_id')->where('product.type', 0);
            })
            ->leftJoin('orders', function ($join) {
                $join->on('orders.id', '=', 'order_items.order_id');
            })->where('orders.status', '=', 1)
            ->leftJoin('inventories', ['order_items.product_id' => 'inventories.product_id']);

        if (! empty($mainWarehouse) && isset($mainWarehouse->id)) {
            if ($type === 'ok') {
                $model = $model->where('inventories.warehouse_id', $mainWarehouse->id)->where('inventories.stock', '>', 0)->groupBy('order_items.id');
            }
            if ($type === 'almost') {
                //ami nincs GPS-ben (nem is volt)
                $model = $model->leftJoin('inventories as inventories_null', function ($join) use ($mainWarehouse) {
                    $join->on('product.id', '=', 'inventories_null.product_id')
                        ->where('inventories_null.warehouse_id', $mainWarehouse->id);
                })->whereNull('inventories_null.product_id')->groupBy('order_items.id');

                //ami nincs GPS-ben (volt de most nincs)
                $model->leftJoin('inventories as inventories_zero', ['product.id' => 'inventories_zero.product_id'])
                    ->where('inventories_zero.warehouse_id', '!=', $mainWarehouse->id)
                    ->where('inventories_zero.stock', '>', 0)->groupBy('order_items.id');

                // de máshol viszont igen
                $model = $model->where('inventories.warehouse_id', '!=', $mainWarehouse->id)->where('inventories.stock', '>', 0)->groupBy('order_items.id');
            }
        }
        if ($type === 'no') {
            $model = $model->where(function ($q) {
                $q->havingRaw('SUM(stock) <= ?', [0])->orWhereNull('inventories.product_id');
            });
        }

        return $model;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
