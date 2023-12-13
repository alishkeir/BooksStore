<?php

namespace App\Http\Controllers;

use Alomgyar\Carts\Cart;
use Alomgyar\Customers\Customer;
use Alomgyar\Products\ApiProduct as Product;
use Alomgyar\Products\ProductPrice;
use App\Http\Resources\CartPageListResource;
use App\Http\Resources\CartPageResource;
use App\Http\Traits\ErrorMessages;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

class CartApiController extends Controller
{
    use ErrorMessages;

    protected array $validRefs = ['get', 'add', 'increment', 'decrement', 'cartPageLists'];

    private bool $guest = true;

    private Customer $customer;

    private $cart;

    private array $product;

    public function __invoke()
    {
        $this->refCheck();
        $this->isLoggedIn();
        $this->product = request()->body['product'] ?? [];
        $this->defineCart();

        if (request('ref') === 'add') {
            return $this->store();
        } elseif (request('ref') === 'increment') {
            return $this->increment();
        } elseif (request('ref') === 'decrement') {
            return $this->decrement();
        } elseif (request('ref') === 'remove') {
            return $this->destroy();
        } elseif (request('ref') === 'cartPageLists') {
            return $this->getCartPageLists();
        } else {
            return $this->getCart();
        }
    }

    private function refCheck()
    {
        if (! in_array(request('ref'), $this->validRefs) && request()->expectsJson()) {
            return $this->badRefMessage();
        }
    }

    public function store()
    {
        if (! isset($this->product)) {
            return $this->missingRequiredParameterMessage();
        }

        $productToAdd = Product::where('id', $this->product['id'])->where('status', 1)->select('id', 'status')->first();
        if ($productToAdd ?? false) {
            $this->cart->total_quantity = $this->calculateTotalQuantity();
            $this->cart->total_amount = $this->calculateTotalAmount();
            $this->cart->total_amount_full_price = $this->calculateTotalAmountFullPrice();
            $this->cart->store = request('store');
            $this->cart->reminded_at = null;
            $this->cart->save();
            $this->updateCartItems();

            $this->cart->refresh();
        }

        return $this->getCart();
    }

    private function isLoggedIn()
    {
        if ($token = request()->bearerToken()) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($token);
            if (! empty($accessToken)) {
                $this->guest = false;
                $this->customer = $accessToken->tokenable;
            }
        }
    }

    private function getGuestToken()
    {
        return isset(request()->body['guest_token']) ? request()->body['guest_token'] : Cart::generateGuestToken();
    }

    private function increment()
    {
        if (! $this->isEbook(request()->body['product_id'])) {
            $this->cart->items()->where('product_id', request()->body['product_id'])->increment('quantity');
            $this->cart->refresh();
            $this->cart->total_quantity = $this->calculateTotalQuantity();
            $this->cart->total_amount = $this->calculateTotalAmount();
            $this->cart->total_amount_full_price = $this->calculateTotalAmountFullPrice();
            $this->cart->reminded_at = null;
            $this->cart->save();
        }

        return $this->getCart();
    }

    private function decrement()
    {
        $this->cart->items()->where('product_id', request()->body['product_id'])->decrement('quantity');
        $this->cart->refresh();
        $this->checkZeroQuantity();
        $this->cart->total_quantity = $this->calculateTotalQuantity();
        $this->cart->total_amount = $this->calculateTotalAmount();
        $this->cart->total_amount_full_price = $this->calculateTotalAmountFullPrice();
        $this->cart->reminded_at = null;
        $this->cart->save();

        return $this->getCart();
    }

    private function destroy()
    {
        $this->cart->items()->where('product_id', request()->body['product_id'])->forceDelete();
        $this->cart->refresh();
        $this->cart->total_quantity = $this->calculateTotalQuantity();
        $this->cart->total_amount = $this->calculateTotalAmount();
        $this->cart->total_amount_full_price = $this->calculateTotalAmountFullPrice();
        $this->cart->reminded_at = null;
        $this->cart->save();

        return $this->getCart();
    }

    private function calculateTotalQuantity()
    {
        if (isset($this->product['id']) && isset($this->product['quantity'])) {
            return $this->cart->items()->sum('quantity') + $this->product['quantity'];
        }

        return $this->cart->items()->sum('quantity');
    }

    private function calculateTotalAmount()
    {
        $productIDs = $this->cart->items->pluck('product_id');
        if (isset($this->product['id']) && isset($this->product['quantity'])) {
            $productIDs->push($this->product['id']);
        }

        $returnSum = ProductPrice::select('product_id', 'price_sale', 'price_list', 'price_cart')
        ->whereStore(request('store'))
        ->whereIn('product_id', $productIDs)
        ->get()
        ->transform(function ($item) {
            $cartItem = $this->cart->items->where('product_id', $item->product_id)->first();
            if (! $cartItem && isset($this->product['id']) && $item->product_id === $this->product['id']) {
                $quantity = $this->product['quantity'] ?? 1;
                $price = $this->product['is_cart_price']
                    ? $item->price_cart * $quantity
                    : $item->price_sale * $quantity;
            } else {
                $quantity = $cartItem?->quantity ?? 1;
                $price = $cartItem?->is_cart_price
                    ? $item->price_cart * $quantity
                    : $item->price_sale * $quantity;
            }
            $itemsum = $price;

            if (request('store') == 2 && ($token = request()->bearerToken())) {
                $model = Sanctum::$personalAccessTokenModel;
                $accessToken = $model::findToken(request()->bearerToken());
                if (! empty($accessToken)) {
                    $this->customer = $accessToken->tokenable;

                    $product = Product::select('publisher_id')->where('id', $item->product_id)->first();
                    if ($product->publisher_id == 38) { //TODO
                        $discount = $this->customer->personal_discount_alomgyar;
                    } else {
                        $discount = $this->customer->personal_discount_all;
                    }
                    $priceList = $item->price_list * $quantity;

                    $itemsumPersonal = round($priceList - (($priceList / 100) * $discount));
                    if ($itemsumPersonal < $itemsum) {
                        $itemsum = $itemsumPersonal;
                    }
                }
            }

            return $itemsum;
        })
        ->sum();

        return $returnSum;
    }

    private function calculateTotalAmountFullPrice()
    {
        $productIDs = $this->cart->items->pluck('product_id');
        if (isset($this->product['id']) && isset($this->product['quantity'])) {
            $productIDs->push($this->product['id']);
        }
        $returnSum = ProductPrice::select('product_id', 'price_list')
                           ->whereStore(request('store'))
                           ->whereIn('product_id', $productIDs)
                           ->get()
                           ->transform(function ($item) {
                               return $item->price_list * (int) ($this->cart->items->where(
                                   'product_id',
                                   $item->product_id
                               )->first()->quantity ?? 1);
                           })
                           ->sum();

        return $returnSum;
    }

    private function updateCartItems()
    {
        $quantity = $this->isEbook($this->product['id']) ? 1 : $this->product['quantity'];

        $this->cart->items()->updateOrInsert(
            [
                'cart_id' => $this->cart->id,
                'product_id' => $this->product['id'],
            ],
            [
                'quantity' => $quantity,
                'is_cart_price' => $this->product['is_cart_price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function getCart()
    {
        return response([
            'data' => new CartPageResource($this->cart),
        ]);
    }

    private function getCartPageLists()
    {
        if ($this->cart->products->isEmpty()) {
            return response([
                'data' => [
                    'super_cheap_list' => [],
                    'others_bought_these' => [],
                ],
            ]);
        }

        $subcategories = $this->cart->products->map(function ($item) {
            return $item->subcategories;
        });

        $similarProductsIDs = collect([]);

        foreach ($subcategories as $subcategory) {
            foreach ($subcategory as $item) {
                $similarProductsIDs[] = $item->products->pluck('id');
            }
        }

        $productIDs = DB::table('product')
                        ->select('product.id')
                        ->join('product_price', 'product.id', '=', 'product_price.product_id')
                        ->where('product_price.store', request('store'))
                        ->where('product.state', Product::STATE_NORMAL)
                        ->whereNotIn('product.id', $this->cart->items->pluck('product_id'))
                        ->where('product_price.price_cart', '>', 0)
                        ->orderBy('product.orders_count_'.request('store'), 'DESC')
                        // ONLY ACTIVE
                        ->where('product.status', Product::STATUS_ACTIVE)
                        ->whereNull('product.deleted_at')
                        // ONLY ACTIVE
                        ->limit(3)
                        ->get();

        $superCheapList = DB::table('product')
                            ->select(
                                'product.id',
                                'product.slug',
                                'product.title',
                                'product.state',
                                'product.type',
                                'product.cover',
                                'product.published_at',
                                'product.authors',
                                'product_price.price_list',
                                'product_price.price_sale',
                                'product_price.price_cart',
                                'product.is_new'
                            )
                            ->selectRaw('ROUND((1 - (price_cart / price_list)) * 100, 0) as discount_percent')
                            ->join('product_price', 'product.id', '=', 'product_price.product_id')
                            ->where('product_price.store', request('store'))
                            ->where('product.state', Product::STATE_NORMAL)
                            ->whereNotIn('product.id', $this->cart->items->pluck('product_id'))
                            ->whereIn('product.id', $productIDs->pluck('id'))
                            ->where('product_price.price_cart', '>', 0)
                            ->orderBy('product.orders_count_'.request('store'), 'DESC')
                            // ONLY ACTIVE
                            ->where('product.status', Product::STATUS_ACTIVE)
                            ->whereNull('product.deleted_at')
                            // ONLY ACTIVE
                            ->limit(3)
                            ->get();

        $othersBoughtThese = DB::table('product')
                               ->select(
                                   'product.id',
                                   'product.slug',
                                   'product.title',
                                   'product.state',
                                   'product.type',
                                   'product.cover',
                                   'product.published_at',
                                   'product.authors',
                                   'product_price.price_list',
                                   'product_price.price_sale',
                                   'product_price.price_cart',
                                   'product.is_new'
                               )
                               ->selectRaw('ROUND((1 - (price_cart / price_list)) * 100, 0) as discount_percent')
                               ->join('product_price', 'product.id', '=', 'product_price.product_id')
                               ->where('product_price.store', request('store'))
                               ->where('product.state', Product::STATE_NORMAL)
                               ->when($similarProductsIDs->isNotEmpty(), function ($q) use ($similarProductsIDs) {
                                   $q->whereIn('product.id', $similarProductsIDs->flatten()->unique()->toArray());
                               })
                               ->whereNotIn(
                                   'product.id',
                                   $this->cart->items->pluck('product_id')
                                                     ->concat($productIDs->pluck('id'))
                                                     ->toArray()
                               )
                               ->where('product_price.price_cart', '>', 0)
                               ->orderBy('product.orders_count_'.request('store'), 'DESC')
                                // ONLY ACTIVE
                                ->where('product.status', Product::STATUS_ACTIVE)
                                ->whereNull('product.deleted_at')
                                // ONLY ACTIVE
                               ->limit(3)
                               ->get();

        return response([
            'data' => [
                'super_cheap_list' => CartPageListResource::collection($superCheapList),
                'others_bought_these' => CartPageListResource::collection($othersBoughtThese->union($superCheapList)->take(3)->values()),
            ],
        ]);
    }

    private function defineCart()
    {
        if ($this->guest) {
            $guestToken = $this->getGuestToken();
            $this->cart = Cart::where('guest_token', $guestToken)->firstOrNew();
            $this->cart->guest_token = $guestToken;
        } else {
            $this->cart = $this->customer->cart()->firstOrNew();
        }
    }

    private function checkZeroQuantity()
    {
        $this->cart->items()->whereQuantity(0)->forceDelete();
    }

    private function isEbook($productId)
    {
        return Product::find($productId)->type;
    }
}
