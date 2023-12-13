<?php

namespace Alomgyar\Promotions;

ini_set('max_execution_time', '450'); //300 seconds = 5 minutes
ini_set('memory_limit', '2048M');

use Alomgyar\Products\Product;
use App\Http\Controllers\Controller;
use App\Imports\ProductPriceImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PromotionController extends Controller
{
    public function index()
    {
        $model = Promotion::latest()->paginate(25);

        return view('promotions::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('promotions::create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $activity = explode(' - ', $data['active']);
        $data['active_from'] = $activity[0];
        $data['active_to'] = $activity[1];

        $this->validateRequest();
        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $promotion = Promotion::create($data);

        session()->flash('success', 'Akció sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $promotion, 'return_url' => route('promotions.index')]);
//        return redirect()->route('promotions.index')->with('success', 'Promotion sikeresen létrehozva!');
    }

    public function edit(Promotion $promotion)
    {
        return view('promotions::edit', [
            'model' => $promotion,
        ]);
    }

    public function update(Promotion $promotion, Request $request)
    {
        $data = $request->all();

        $activity = explode(' - ', $data['active']);
        $data['active_from'] = $activity[0];
        $data['active_to'] = $activity[1];

        $this->validateRequest();

        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }

        $promotion->update($data);

        //handle attached products
        //$promotion->products()->sync($data['selected']);

        session()->flash('success', 'Akció sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $promotion, 'return_url' => route('promotions.edit', ['promotion' => $promotion->id])]);
    }

    public function show(Promotion $promotion)
    {
        return view('promotions::show', [
            'model' => $promotion,
        ]);
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('promotions.index')->with('success', 'Akció '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
        ]);
    }

    public function addtopromotion(Request $request)
    {
        $params = $request->all();
        $products = Excel::toArray(new ProductPriceImport,
            Storage::disk('local')->path('public/imports/'.$params['importfile']));

        $counts['good'] = $counts['bad'] = 0;

        PromotionPrice::where('promotion_id', $params['promotion'])->delete();

        $isbn_set = [];
        foreach ($products as $tabs) {
            foreach ($tabs as $product) {
                array_push($isbn_set, $product[0]);
            }
        }
        $realproducts = Product::query()
            ->select('isbn', 'id')
            ->whereIn('isbn', $isbn_set)
            ->active() // ADDING ONLY TO ACTIVE ONES
            ->get();

        foreach ($realproducts as $rp) {
            $isset[$rp->isbn] = $rp->id;
        }

        foreach ($products as $tabs) {
            foreach ($tabs as $product) {
                $isbn = $product[0];
                if ($isbn) {
                    //$realproduct = Product::select('id')->where('isbn', $product[0])->first();
                    if ($product[5] > 0 || $product[9] > 0 || $product[13] > 0) {
                        $status = 1;
                    } else {
                        $counts['bad']++; //hiányzó adat

                        continue;
                    }
                    if (! is_numeric(($product[5] ?? 0)) || ! is_numeric(($product[9] ?? 0)) || ! is_numeric(($product[13] ?? 0))) {
                        $counts['bad']++; //az árhoz csak szám adható meg

                        continue;
                    }
                    if (! isset($isset[$isbn])) {
                        $counts['bad']++; //nincs ilyen termék

                        continue;
                    }
                    $addr[] = '('.$params['promotion'].', '.$isset[$isbn].', '.($product[5] ?? 0).', '.($product[9] ?? 0).', '.($product[13] ?? 0).')';
                    $counts['good']++;

                    //if (count($addr) == 100){
                    $sql = '
                        INSERT INTO `promotion_product` (promotion_id, product_id, price_sale_0, price_sale_1, price_sale_2)
                        VALUES '.implode(', ', $addr).'
                        ';
                    DB::statement($sql);
                    $addr = [];
                    //}
                }
            }
        }
        if ($counts['good'] > 0) {
            session()->flash('success', 'Akcióhoz termékek rendelése akciós árral sikeresen megtörtént! '.$counts['good']);
        } else {
            session()->flash('error', 'Nincs egy felhasználható termék sem az excelben, így nem változott semmi.');
        }

        $promotion = Promotion::find($params['promotion']);
        //return view('products::import', ['count' => $counts, 'products' => $importableProducts ?? []]);
        return redirect()->route('promotions.edit', $params['promotion']);
    }
}
