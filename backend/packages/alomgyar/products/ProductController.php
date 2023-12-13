<?php

namespace Alomgyar\Products;

ini_set('max_execution_time', '1000'); //300 seconds = 5 minutes
ini_set('memory_limit', '3048M');

use Alomgyar\Legal_owners\LegalOwner;
use Alomgyar\Products\Events\ProductOrderableEvent;
use Alomgyar\Promotions\PromotionPrice;
use Alomgyar\Publishers\Publisher;
use Alomgyar\Subcategories\Subcategory;
use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductSelectResource;
use App\Imports\ProductPriceImport;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        return view('products::index');
    }

    public function create()
    {
        $subcategories = Subcategory::get();
        $publishers = Publisher::get();

        return view('products::create', [
            'subcategories' => $subcategories,
            'publishers' => $publishers,
        ]);
    }

    public function store()
    {
        $data = request()->all();
        $this->validateRequest(true);
        $checks = ['status', 'store_0', 'store_1', 'store_2'];

        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        if ($data['status'] == 1 and empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($data['author'] ?? false) {
            $data['authors'] = DB::table('author')->select('title')->whereIn('id', $data['author'])->get()->pluck('title')->join(', ');
        }

        $productA = Product::create($data);
        $product = Product::find($productA->id);

        foreach ($data['store'] as $store_id => $price_data) {
            $price_data['product_id'] = $product->id;
            $price_data['store'] = $store_id;
            ProductPrice::create($price_data);
        }

        //handle subcategories
        if (! empty($data['subcategory'] ?? false)) {
            $product->subcategories()->sync($data['subcategory']);
        }

        //handle authors
        if (! empty($data['author'] ?? false)) {
            $product->author()->sync($data['author']);
        }

        session()->flash('success', 'Könyv sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $product, 'return_url' => route('products.index')]);
        //        return redirect()->route('products.index')->with('success', 'Product sikeresen létrehozva!');
    }

    public function edit(Product $product)
    {
        $subcategories = Subcategory::get();
        $publishers = Publisher::get();
        $legalOwners = LegalOwner::get();

        return view('products::edit', [
            'model' => $product,
            'subcategories' => $subcategories,
            'publishers' => $publishers,
            'legalOwners' => $legalOwners,
        ]);
    }

    public function update(Product $product)
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status', 'store_0', 'store_1', 'store_2'];

        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }

        if ($product->state == Product::STATE_PRE  //Ha a mentés előtt még előjegyezhető volt
        && $data['state'] == Product::STATE_NORMAL //Ha most már viszont normál rendelhető állapotú lesz
        && $data['status'] == Product::STATUS_ACTIVE //és mindeközben aktív
        && $data['published_before'] == 0) { //Illetve korábban még nem volt elérhető
            if(empty($data['published_at'])) {
                $data['published_at'] = now();
            }
            event(new ProductOrderableEvent($product));

            //This called from cron regularly instead
            //event(new \Alomgyar\Products\Events\AuthorNewBookEvent($product)); //\Alomgyar\Products\Product::find(69658)
        }

        if ($product->state === Product::STATE_NORMAL && (int) $data['state'] === Product::STATE_PRE) {
            $data['published_before'] = 1;
        }
        if ($data['author'] ?? false) {
            $data['authors'] = DB::table('author')->select('title')->whereIn('id', $data['author'])->get()->pluck('title')->join(', ');
        } else {
            $data['authors'] = '';
        }
        $product->newComer = 0; //remove from new books filter when edited
        $product->update($data);

        //handle price
        foreach ($data['store'] as $store_id => $price_data) {
            if ($price_data['price_list'] != null) {
                $product->price($store_id)->update($price_data);
            }
        }
        //handle promotion prices
        if ($data['promotion_price'] ?? false) {
            foreach ($data['promotion_price'] as $price_id => $prices) {
                $promotionPrice = PromotionPrice::find($price_id);

                if ($prices[0] ?? false) {
                    $price['price_sale_0'] = $prices[0];
                }
                if ($prices[1] ?? false) {
                    $price['price_sale_1'] = $prices[1];
                }
                if ($prices[2] ?? false) {
                    $price['price_sale_2'] = $prices[2];
                }
                $promotionPrice->update($price);
            }
        }
        //handle subcategories
        if (! empty($data['subcategory'] ?? false)) {
            $product->subcategories()->sync($data['subcategory']);
        }
        //handle authors
        if (! empty($data['author'] ?? false)) {
            $product->author()->sync($data['author']);
        } else {
            //delete
        }

        session()->flash('success', 'Könyv sikeresen frissítve!');

        return response()->json([
            'success' => true, 'model' => $product,
            'return_url' => route('products.edit', ['product' => $product->id]),
        ]);
    }

    public function show(Product $product)
    {
        return view('products::show', [
            'model' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product '.__('messages.deleted'));
    }

    protected function validateRequest($store_request = false)
    {
        if ($store_request) {
            return request()->validate([
                'title' => 'required',
                'description' => 'required',
                //'unique:product,isbn',
                'isbn' => [
                    'required',
                    Rule::unique('product', 'isbn')->where(fn (Builder $query) => $query->where('type', Product::BOOK)),
                ],
            ]);
        }

        return request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    }

    //exports
    public function export()
    {
        $filters = request()->all();

        return ( new ProductExport )->withFilters($filters)->download('konyvek-'.date('Y-m-d').'.xls');
    }

    public function import()
    {
        return view('products::import');
    }

    public function importproduct(Request $request)
    {
        $params = $request->all();
        $products = Excel::toArray(
            new ProductPriceImport,
            Storage::disk('local')->path('public/imports/'.$params['importfile'])
        );

        $counts['good'] = $counts['bad'] = 0;

        foreach ($products as $tabs) {
            foreach ($tabs as $product) {
                $isbn = $product[0];

                if ($isbn) {
                    $realproduct = Product::where('isbn', $isbn)->first();
                    $resp = [];
                    $status = 0;
                    if ($product[4] > 0 || $product[5] > 0 || $product[8] > 0 || $product[9] > 0 || $product[12] > 0 || $product[13] > 0) {
                        $status = 1;
                    } else {
                        $resp[] = 'nincs új ár megadva';
                        $status = 0;
                    }
                    if (! is_numeric(($product[4] ?? 0)) || ! is_numeric(($product[5] ?? 0)) || ! is_numeric(($product[8] ?? 0)) || ! is_numeric(($product[9] ?? 0)) || ! is_numeric(($product[12] ?? 0)) || ! is_numeric(($product[13] ?? 0))) {
                        $resp[] = 'Az árhoz csak szám adható meg';
                        $status = 0;
                    }
                    if (! $realproduct) {
                        $resp[] = 'Nincs ilyen termék';
                        $status = 0;
                    } else {
                        //if ($realproduct->isbn != $product[1]) {
                        //    $resp[] = 'Nem egyezik meg az isbn';
                        //    $status = 0;
                        //}
                    }

                    $importableProducts[] = [
                        'isbn' => $product[0],
                        'title' => $product[1],
                        'alomgyar_list' => $product[4],
                        'alomgyar_sale' => $product[5],
                        'olcsokonyvek_list' => $product[8],
                        'olcsokonyvek_sale' => $product[9],
                        'nagyker_list' => $product[12],
                        'nagyker_sale' => $product[13],
                        'resp' => $resp,
                        'status' => $status,
                    ];
                    if ($status) {
                        $counts['good']++;
                    } else {
                        $counts['bad']++;
                    }
                }
            }
        }

        session()->flash('success', 'Az ellenőrzés lefutott!');

        return view('products::import', ['count' => $counts, 'products' => $importableProducts ?? []]);
    }

    public function runimport(Request $request)
    {
        $data = $request->all();
        $result = [
            '0' => 0,
            '1' => 0,
            '2' => 0,
            'total' => 0,
        ];
        foreach ($data['p'] as $isbn => $params) {
            if ($params['i'] ?? false) {
                $prices = explode('|', $params['prices']);
                $product = Product::where('isbn', $isbn)->first();
                if ($product) {
                    $priceArray = [
                        0 => [$prices[0], $prices[1]],
                        1 => [$prices[2], $prices[3]],
                        2 => [$prices[4], $prices[5]],
                    ];
                    $stores = [0, 1, 2];
                    foreach ($stores as $store) {
                        if ((isset($priceArray[$store][0]) || isset($priceArray[$store][1]))
                         && (is_numeric($priceArray[$store][0]) || is_numeric($priceArray[$store][1]))) {
                            if ($product->price($store) ?? false) {
                                if (isset($priceArray[$store][0]) && is_numeric($priceArray[$store][0])) {
                                    $product->price($store)->update([
                                        'price_list_original' => $priceArray[$store][0],
                                        'price_list' => $priceArray[$store][0],
                                    ]);
                                }
                                if (isset($priceArray[$store][1]) && is_numeric($priceArray[$store][1])) {
                                    $product->price($store)->update([
                                        'price_sale_original' => $priceArray[$store][1],
                                        'price_sale' => $priceArray[$store][1],
                                    ]);
                                }
                                $result[$store]++;
                            } else {
                                ProductPrice::create([
                                    'price_sale_original' => $priceArray[$store][1] ?? $priceArray[$store][0],
                                    'price_list_original' => $priceArray[$store][0] ?? $priceArray[$store][1],
                                    'discount_percent' => 0,
                                    'store' => $store,
                                    'price_sale' => $priceArray[$store][1] ?? $priceArray[$store][0],
                                    'price_list' => $priceArray[$store][0] ?? $priceArray[$store][1],
                                    'product_id' => $product->id,

                                ]);
                            }
                        }
                    }
                }
                $result['total']++;
            }
        }

        return view('products::import', ['result' => $result]);
    }

    public function fileupload(Request $request)
    {
        $uploadedFile = $request->file('file');
        $fileSize = $uploadedFile->getSize();

        if ($fileSize > 10485760) {
            return ['error' => 'A file mérete nem lehet nagyobb 10 MB-nál'];
        }
        $filename = Str::slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME).'_'.time(), '-');
        $ext = strtolower(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION));

        if (isset($request->path)) {
            $extraPath = $request->path;
        } else {
            $extraPath = date('Y').'/'.date('m').'/';
        }

        $uploader = Storage::disk('local')->putFileAs(
            'public/imports/'.$extraPath,
            $uploadedFile,
            $filename.'.'.$ext
        );
        $url = URL::to('/').Storage::url('imports/'.$extraPath.$filename.'.'.$ext);

        $response = [
            'rawurl' => $url,
            'url' => $extraPath.$filename.'.'.$ext,
        ];

        return $response;
    }

    public function flashPromotion()
    {
        return view('products::flash-promotion');
    }

    public function search(Request $request)
    {
        $term = trim($request->q);
        $onlyBooks = $request->onlyBooks;

        $products = Product::select('id', 'title', 'isbn')
                           ->search($term)
                           ->latest();

        if ($onlyBooks) {
            $products->book();
        }

        $products = $products->paginate(25);

        return response([
            'results' => ProductSelectResource::collection($products),
            'pagination' => [
                'more' => $products->currentPage() !== $products->lastPage(),
            ],
        ]);
    }
}
