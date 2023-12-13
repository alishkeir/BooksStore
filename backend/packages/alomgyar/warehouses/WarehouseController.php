<?php

namespace Alomgyar\Warehouses;

use Alomgyar\Product_movements\ProductMovement;
use Alomgyar\Products\Product;
use Alomgyar\Shops\Shop;
use App\Exports\WarehouseExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseProductSelectResource;
use App\Http\Resources\WarehouseSelectResource;
use App\Imports\InventoryImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseController extends Controller
{
    public function index()
    {
        $model = Warehouse::latest()->paginate(25);

        return view('warehouses::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        $shops = Shop::active()->get();

        return view('warehouses::create', compact('shops'));
    }

    public function store()
    {
        $data = $this->validateRequest();
        $warehouse = Warehouse::create(request()->all());

        session()->flash('success', 'Raktár sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $warehouse, 'return_url' => route('warehouses.index')]);
        //        return redirect()->route('warehouses.index')->with('success', 'Warehouse sikeresen létrehozva!');
    }

    public function edit(Warehouse $warehouse)
    {
        $shops = Shop::active()->get();

        return view('warehouses::edit', [
            'model' => $warehouse,
            'shops' => $shops,
        ]);
    }

    public function update(Warehouse $warehouse)
    {
        $data = $this->validateRequest();
        $warehouse->update(request()->all());

        session()->flash('success', 'Raktár sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $warehouse, 'return_url' => route('warehouses.index')]);
        //        return redirect()->route('warehouses.index', ['warehouse' => $warehouse->id]);
    }

    public function show(Warehouse $warehouse)
    {
        return view('warehouses::show', [
            'model' => $warehouse,
        ]);
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('success', 'Raktár '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'email' => ['required', 'email:rfc,dns'],
        ]);
    }

    public function export($warehouseID)
    {
        return Excel::download(new WarehouseExport($warehouseID), $warehouseID.'_raktarkeszlet.xlsx');
    }

    public function import(Request $request)
    {
        $warehouse = Warehouse::findOrFail($request->warehouseID);

        return view('warehouses::import', compact('warehouse'));
    }

    public function importProducts(Request $request)
    {
        if (empty($request->importfile)) {
            return redirect()->back();
        }
        $params = $request->all();
        $products = Excel::toArray(
            new InventoryImport($params['warehouse_id']),
            Storage::disk('local')->path('public/imports/'.$params['importfile'])
        );
        $warehouse = Warehouse::findOrFail($request->warehouse_id);

        $counts['good'] = $counts['bad'] = 0;

        foreach ($products as $tabs) {
            foreach ($tabs as $product) {
                $isbn = $product[0];
                $warehouseID = $request->warehouse_id;

                if ($isbn && $warehouseID) {
                    $realProduct = Product::where('isbn', $isbn)->first();
                    $resp = [];
                    $status = 0;
                    if (! empty($product[4])) {
                        $status = 1;
                    } else {
                        $resp[] = 'Nincs új darabszám megadva';
                        $status = 0;
                    }

                    if (! is_numeric(($product[4] ?? 0))) {
                        $resp[] = 'A darabszámhoz csak szám adható meg';
                        $status = 0;
                    }

                    if (! $realProduct) {
                        $resp[] = 'Nincs ilyen termék';
                        $status = 0;
                    } else {
                        $inventory = Inventory::where('warehouse_id', $warehouseID)->where(
                            'product_id',
                            $realProduct->id
                        )->first();
                        if (! $inventory) {
                            $resp[] = 'Ez a termék nem szerepel ebben a raktárban';
                            $status = 0;
                        } elseif ($inventory->stock != $product[2]) {
                            $resp[] = 'Eltérés van a megadott és az adatbázisban található mennyiség között';
                            $status = 0;
                        }
                    }

                    $importableProducts[$isbn]['id'] = $realProduct?->id;
                    $importableProducts[$isbn]['isbn'] = $product[0];
                    $importableProducts[$isbn]['title'] = $product[1];
                    $importableProducts[$isbn]['original_stock_in_warehouse'] = $product[2];
                    $importableProducts[$isbn]['original_total_stock'] = $product[3];
                    $importableProducts[$isbn]['new_stock_in_warehouse'] = $product[4];
                    $importableProducts[$isbn]['resp'] = $resp;
                    $importableProducts[$isbn]['status'] = $status;

                    if ($status) {
                        $counts['good']++;
                    } else {
                        $counts['bad']++;
                    }
                } else {
                    session()->flash('error', 'Hiányzó ISBN vagy raktár ID!');
                }
            }
        }

        session()->flash('success', 'Az import fájlt sikeresen beolvastuk és ellenőriztük!');

        return view(
            'warehouses::import',
            [
                'count' => $counts,
                'products' => $importableProducts ?? [],
                'warehouse' => $warehouse,
                'importfile' => $request->importfile,
            ]
        );
    }

    public function runImport(Request $request)
    {
        $data = $request->all();
        $warehouseID = $request->warehouse_id;
        $modelStockIn = $modelStockOut = null;
        $stockInReference = $stockOutReference = null;
        $createStockInReference = $createStockOutReference = true;

        $result = [
            'stock_in' => [],
            'stock_out' => [],
            'no_change' => [],
            'total' => 0,
        ];

        foreach ($data['p'] as $isbn => $params) {
            if ($params['i'] ?? false) {
                if ($params['new_stock_in_warehouse'] > $params['original_stock_in_warehouse']) {
                    // Ha több van → Bevételezés, Forrás: Egyéb, Cél: Aktuális Raktár
                    if ($createStockInReference) {
                        $stockInReference = ProductMovement::generateReferenceNr();
                        $createStockInReference = false;
                    }
                    $modelStockIn = $this->modelStockIn($warehouseID, $stockInReference);

                    $result['stock_in'][] = [
                        'product_id' => $params['id'],
                        'product_movements_id' => $modelStockIn->id,
                        'stock_in' => (int) $params['new_stock_in_warehouse'] - (int) $params['original_stock_in_warehouse'],
                        'status' => 1,
                        'stock_out' => 0,
                    ];
                } elseif ($params['new_stock_in_warehouse'] < $params['original_stock_in_warehouse']) {
                    // Ha kevesebb → Kivételezés, Forrás: Aktuális raktár, Cél: Egyéb
                    if ($createStockOutReference) {
                        $stockOutReference = ProductMovement::generateReferenceNr();
                        $createStockOutReference = false;
                    }
                    $modelStockOut = $this->modelStockOut($warehouseID, $stockOutReference);

                    $result['stock_out'][] = [
                        'product_id' => $params['id'],
                        'product_movements_id' => $modelStockOut->id,
                        'stock_in' => 0,
                        'status' => 1,
                        'stock_out' => (int) $params['original_stock_in_warehouse'] - (int) $params['new_stock_in_warehouse'],
                    ];
                } else {
                    $result['no_change'][] = [
                        'product_id' => $params['id'],
                        'product_movements_id' => null,
                        'stock_in' => 0,
                        'status' => 0,
                        'stock_out' => 0,
                    ];
                }

                DB::table('inventories')->where([
                    ['product_id', $params['id']],
                    ['warehouse_id', $warehouseID],
                ])->update(['stock' => $params['new_stock_in_warehouse']]);
                $result['total']++;
            }
        }

        if ($modelStockIn) {
            ProductMovement::addItems($modelStockIn, $result['stock_in']);
        }

        if ($modelStockOut) {
            ProductMovement::addItems($modelStockOut, $result['stock_out']);
        }

        return view('warehouses::import', ['result' => $result]);
    }

    private function modelStockIn($warehouseID, $stockInReference)
    {
        return ProductMovement::firstOrCreate(
            ['reference_nr' => $stockInReference],
            [
                'causer_type' => 'App\User',
                'causer_id' => Auth::id(),
                'source_type' => 'other',
                'source_id' => null,
                'destination_type' => 4,
                'destination_id' => $warehouseID,
                'created_at' => time(),
                'updated_at' => time(),
            ]
        );
    }

    private function modelStockOut($warehouseID, $stockOutReference)
    {
        return ProductMovement::firstOrCreate(
            ['reference_nr' => $stockOutReference],
            [
                'causer_type' => 'App\User',
                'causer_id' => Auth::id(),
                'source_type' => 'warehouse',
                'source_id' => $warehouseID,
                'destination_type' => 4,
                'destination_id' => null,
                'created_at' => time(),
                'updated_at' => time(),
            ]
        );
    }

    public function search(Request $request)
    {
        $term = trim($request->q);
        $onlyMerchant = $request->onlyMerchant;

        $warehouses = Warehouse::select('id', 'title')->search($term)->latest();

        if ($onlyMerchant) {
            $warehouses->merchant();
        }

        $warehouses = $warehouses->paginate(25);

        return response([
            'results' => WarehouseSelectResource::collection($warehouses),
            'pagination' => [
                'more' => $warehouses->currentPage() !== $warehouses->lastPage(),
            ],
        ]);
    }

    public function searchProduct(Request $request)
    {
        $term = trim($request->q);
        $product_id = trim($request->product);

        $warehouses = Warehouse::query()
            ->select('id', 'title')
            ->active()
            ->search($term)
            ->latest()
            ->get()
            ->map(function ($warehouse) use ($product_id) {
                $warehouse->product_stock = $warehouse->productInventory($product_id);

                return $warehouse;
            });

        return response([
            'results' => WarehouseProductSelectResource::collection($warehouses, $product_id),
            'pagination' => [
                'more' => false,
            ],
        ]);
    }
}
