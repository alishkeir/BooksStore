<?php

namespace Alomgyar\InventoryExport;

use Alomgyar\Warehouses\Warehouse;
use App\Exports\InventoryExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryExportController extends Controller
{
    public function index()
    {
        $shouldContain = 'álomgyár könyvesbolt';
        $warehouses = Warehouse::query()
            ->select('id', 'shop_id', 'title')
            ->with('shop:id,title')
            ->where('warehouse.title', 'LIKE', "%$shouldContain%")
            ->orWhere('id', Warehouse::WEBSHOP_ID)
            ->orWhere('id', Warehouse::FAIR_EVENT_ID)
            ->get();

        return view('inventory_export::index', compact('warehouses'));
    }

    public function download(Request $request)
    {
        if ($request->warehouse_id == -1) {
            $prefix = 'bolt_minden';
        } else {
            $prefix = 'bolt_'.$request->warehouse_id;
        }

        return Excel::download(new InventoryExport($request->warehouse_id), $prefix.'_leltár.xlsx');
    }

    public function countPage()
    {
        return view('inventory_export::count');
    }

    public function inventory()
    {
        return view('inventory_export::inventory', ['warehouseID' => request('warehouseID')]);
    }

    public function createProduct()
    {
        return view('inventory_export::create', ['warehouseID' => request('warehouseID')]);
    }

    public function createProductGetQuantity(Request $request)
    {
        $count = 0;
        $productId = $request->get('productId');
        $warehouseId = $request->get('warehouseId');

        $inventoryZeroQuantity = InventoryZero::query()
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('state', InventoryZero::STATE_ACTIVE)
            ->first();

        if ($inventoryZeroQuantity) {
            $count = $inventoryZeroQuantity->stock;
        }

        return response()->json($count, 200);
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'stock' => 'required',
        ]);

        InventoryZero::updateOrCreate([
            'product_id' => $request->product_id,
            'warehouse_id' => $request->warehouseID,
            'created_by_id' => auth()->id(),
        ], [
            'stock' => $request->stock,
            'state' => InventoryZero::STATE_ACTIVE,
        ]);

        return redirect()->route('inventory_export.inventory', ['warehouseID' => $request->warehouseID])->with('success', __('messages.saved'));
    }

    public function editProduct(Request $request)
    {
        return view('inventory_export::edit', ['model' => InventoryZero::find(request('inventoryProduct')), 'warehouseID' => request('warehouseID')]);
    }

    public function updateProduct(Request $request)
    {
        $request->validate([
            'stock' => 'required',
        ]);
        $inventoryProduct = InventoryZero::find($request->product);
        $inventoryProduct->update([
            'stock' => $request->stock,
        ]);

        return redirect()->route('inventory_export.inventory', ['warehouseID' => $inventoryProduct->warehouse_id])->with('success', __('messages.updated'));
    }

    public function deleteProduct(Request $request)
    {
        $product = InventoryZero::find($request->product);
        $product->delete();

        return redirect()->route('inventory_export.inventory', ['warehouseID' => $product->warehouse_id])->with('success', __('messages.deleted'));
    }
}
