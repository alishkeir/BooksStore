<?php

namespace Alomgyar\Suppliers;

use App\Exports\SupplierStockExports;
use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierSelectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    public function index()
    {
        $model = Supplier::withTrashed()->latest()->paginate(25);

        return view('suppliers::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('suppliers::create');
    }

    public function store()
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $supplier = Supplier::create($data);

        session()->flash('success', 'Beszállító sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $supplier, 'return_url' => route('suppliers.index')]);
//        return redirect()->route('suppliers.index')->with('success', 'Supplier sikeresen létrehozva!');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers::edit', [
            'model' => $supplier,
        ]);
    }

    public function update(Supplier $supplier)
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $supplier->update($data);

        session()->flash('success', 'Supplier sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $supplier, 'return_url' => route('suppliers.index')]);
//        return redirect()->route('suppliers.index', ['supplier' => $supplier->id]);
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers::show', [
            'model' => $supplier,
        ]);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Beszállító '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'description' => 'nullable',
            'status' => 'boolean',
            'percent' => ['required'],
        ]);
    }

    public function search(Request $request)
    {
        $term = trim($request->q);

        $suppliers = Supplier::select('id', 'title')
                         ->search($term)
                         ->latest()
                         ->paginate(25);

        return response([
            'results' => SupplierSelectResource::collection($suppliers),
            'pagination' => [
                'more' => $suppliers->currentPage() !== $suppliers->lastPage(),
            ],
        ]);
    }

    public function downloadInventory(Supplier $supplier)
    {
        return Excel::download(new SupplierStockExports($supplier->id), Str::slug($supplier->title).'-beszallitoi-keszlet.xlsx');
    }
}
