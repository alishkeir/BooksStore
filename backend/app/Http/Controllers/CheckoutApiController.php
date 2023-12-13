<?php

namespace App\Http\Controllers;

use Alomgyar\Carts\Cart;
use Alomgyar\Countries\Country;
use Alomgyar\Customers\Address;
use Alomgyar\Customers\Customer;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use Alomgyar\Orders\Mail\OrderMail;
use App\Components\Borgun\Borgun;
use App\Helpers\SettingsHelper;
use App\Helpers\StoreHelper;
use App\Http\Traits\ErrorMessages;
use App\Order;
use App\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use SzamlaAgent\Buyer;
use SzamlaAgent\Currency;
use SzamlaAgent\Document\Invoice\Invoice;
use SzamlaAgent\Item\InvoiceItem;
use SzamlaAgent\Language;
use SzamlaAgent\SzamlaAgentAPI;

/**
 * Barion fizetés
 */
class CheckoutApiController extends Controller
{
    use ErrorMessages;

    protected array $validRefs = ['create', 'check', 'get', 'success', 'shipping_methods'];

    private bool $guest = true;

    private Customer $customer;

    private ?Cart $cart;

    private $barion;

    private $request;

    private $order;

    private $guestToken;

    public function __invoke()
    {
        $this->request = request()->body['steps'] ?? request()->body;
        $this->refCheck();
        $this->isLoggedIn();

        if (request('ref') == 'create') {
            return $this->create();
        } elseif (request('ref') == 'check') {
            return $this->checkOrder();
        } elseif (request('ref') == 'get') {
            return $this->checkPayment();
        } elseif (request('ref') == 'success') {
            return $this->paymentSuccess();
        } elseif (request('ref') == 'shipping_methods') {
            return $this->getShippinMethods();
        }
    }

    public function create()
    {
        $payment_type = $this->request['summary']['payment_type'] ?? 'borgun';

        // rendelt könyvek feldolgozása
        if (isset($this->customer->id)) {
            $this->cart = Cart::with('items.product', 'customer.billingAddresses',
                'customer.shippingAddresses')->where([
                    [
                        'customer_id', $this->customer->id,
                    ],
                ])->whereNull('deleted_at')->first();
        } else {
            $this->cart = Cart::with('items.product', 'customer.billingAddresses',
                'customer.shippingAddresses')->where([
                    [
                        'guest_token', request()->body['guest_token'],
                    ],
                ])->whereNull('deleted_at')->first();
        }

        // Order init

        $this->order = new Order(); // The New Order...

        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
        $i = 0;
        $pass = '';

        while ($i < 6) {
            $num = rand(0, strlen($chars) - 1);
            $pass .= $chars[$num];
            $i++;
        }

        $orderIdStr = str_shuffle($pass);

        // WITH THIS, THIS WILL ONLY RUN ONCE
        $checkedOrder = $this->checkOrder(true);

        $this->order->customer_id = $this->customer->id ?? null;
        $this->order->guest_token = ! empty($this->cart->guest_token) ? $this->cart->guest_token : null;
        $this->order->status = $this->request['summary']['payment_method'] == 'card' ? Order::STATUS_DRAFT : Order::STATUS_NEW;
        $this->order->payment_status = Order::STATUS_PAYMENT_WAITING;
        $this->order->shipping_fee = $checkedOrder['shipping_fee'];
        $this->order->payment_fee = $checkedOrder['payment_fee'];
        $this->order->total_amount = $this->getTotal() + $this->order->shipping_fee + $this->order->payment_fee;
        $this->order->total_quantity = $this->cart->items->sum('quantity');
        $this->order->has_ebook = $this->cart->hasEbook() ? 1 : 0;
        $this->order->store = $this->cart->store;
        $this->order->message = $this->request['summary']['comment'] ?? '';
        //$this->order->phone          = $this->request['summary']['phone'] ?? '';
        $this->order->order_number = strtoupper(($this->order::$barionPrefix[$this->cart->store]).date('ym').$orderIdStr); // TODO csekkoljunk a db-t
        $this->order->payment_token = hash_hmac('sha256', $this->order->order_number, $orderIdStr);

        // set affiliate data
        if (isset(request()->body['affiliate']) && request()->body['affiliate']) {
            $this->order->affiliate_code = request()->body['affiliate']['affiliate_code'];
            $this->order->affiliate_commission_percentage = request()->body['affiliate']['affiliate_commission_percentage'];
            $this->order->valid_for_affiliate_since = Carbon::now()->addDays(config('pamadmin.default-affiliate-waiting-period-in-days'));
        }

        $getCountry = $this->request['shipping']['home']['inputs']['country_id'] ?? false;

        if ($getCountry) {
            $country = Country::find($getCountry);
        } else {
            $country = Country::where('name', Country::HUNGARY_STRING)->first();
        }

        $this->order->country_id = $country->id;

        $getPaymentMethod = PaymentMethod::where('method_id', $this->request['summary']['payment_method'])->first();

        $this->order->payment_method_id = $getPaymentMethod->id ?? null;

        if ($country->name == Country::HUNGARY_STRING) {
            //
        }
        $shippingmethod = ShippingMethod::where('method_id', $this->request['shipping']['type'] ?? 'none')->first();

        $this->order->shipping_method_id = $shippingmethod->id ?? 1;

        $this->order->email = ! empty($this->request['summary']['email']) ? $this->request['summary']['email'] : ($this->cart->customer->email ?? '');

        if ($this->order->save()) {
            $this->cart->order_id = $this->order->id;

            $this->cart->save();
            $orderItems = $this->createOrderItems();
            if ($this->request['summary']['phone'] ?? false) {
                $this->customer->phone = $this->request['summary']['phone'] ?? false;
                $this->customer->save();
            }
            /*return [
                'orderId' => $this->order->id,
                'orderItems' => $orderItems
            ];*/
        }

        // számlázási cím feldolgozása - TODO validáció
        $this->processBillingAddress();

        // szállítási cím feldolgozása - TODO validáció
        $this->processShippingAddress();

        if (count($this->cart->items) > 0) {
            if ($this->request['summary']['payment_method'] == 'card') {
                // barion request összellítása és küldése
                if ($payment_type == 'barion') {
                    return $this->createBarion();
                } else {
                    return $this->createBorgun();
                }
            } elseif ($this->request['summary']['payment_method'] == 'transfer') {
                // előre utalás
                $this->cart->deleted_at = Carbon::now();
                $this->cart->save();

                $this->sendOrderMail();

                return [
                    'order' => [
                        'email' => $this->cart->customer->email,
                        'order_id' => $this->order->order_number,
                        'status' => 'success',
                        'payment_method' => $this->request['summary']['payment_method'],
                    ],
                ];
            } elseif ($this->request['summary']['payment_method'] == 'cash') {
                //fizetés átvételkor
                $this->cart->deleted_at = Carbon::now();
                $this->cart->save();
                $this->sendOrderMail();

                return [
                    'order' => [
                        'email' => $this->cart->customer->email,
                        'order_id' => $this->order->order_number,
                        'status' => 'success',
                        'payment_method' => $this->request['summary']['payment_method'],
                    ],
                ];
            } elseif ($this->request['summary']['payment_method'] == ShippingMethod::CASH_ON_DELIVERY) {
                //fizetés átvételkor
                $this->cart->deleted_at = Carbon::now();
                $this->cart->save();
                $this->sendOrderMail();

                return [
                    'order' => [
                        'email' => $this->cart->customer->email,
                        'order_id' => $this->order->order_number,
                        'status' => 'success',
                        'payment_method' => $this->request['summary']['payment_method'],
                    ],
                ];
            }
        }
    }

    private function getCart()
    {
        if (isset($this->customer->id)) {
            $cart = Cart::with('items.product', 'customer.billingAddresses',
                'customer.shippingAddresses')->where([
                    [
                        'customer_id', $this->customer->id,
                    ],
                ])->whereNull('deleted_at')->first();
            if ($cart ?? false) {
                $this->cart = $cart;
            }
        } else {
            $this->cart = Cart::with('items.product', 'customer.billingAddresses',
                'customer.shippingAddresses')->where([
                    [
                        'guest_token', request()->body['guest_token'],
                    ],
                ])->whereNull('deleted_at')->first();
        }
    }

    private function createOrderItems()
    {
        $orderItems = [];

        foreach ($this->cart->items as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $this->order->id;
            $orderItem->product_id = $item->product_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->is_cart_price
                ? $item->product->price($this->cart->store)->price_cart
                : ((! empty($item->product->price($this->cart->store)->price_sale)
                    ? $item->product->price($this->cart->store)->price_sale
                    : $item->product->price($this->cart->store)->price_list));
            if (request('store') == 2 && ($token = request()->bearerToken())) {
                $model = Sanctum::$personalAccessTokenModel;
                $accessToken = $model::findToken($token);
                if (! empty($accessToken)) {
                    $this->customer = $accessToken->tokenable;

                    $priceList = $item->product->price($this->cart->store)->price_list;
                    if ($item->product->publisher_id == 38) { //TODO
                        $discount = $this->customer->personal_discount_alomgyar;
                    } else {
                        $discount = $this->customer->personal_discount_all;
                    }
                    $pricePersonal = round($priceList - (($priceList / 100) * $discount));
                    if ($orderItem->price > $pricePersonal) {
                        $orderItem->price = $pricePersonal;
                    }
                }
            }
            $orderItem->original_price = $item->product->price($this->cart->store)->price_list;
            $orderItem->cart_price = $item->product->price($this->cart->store)->is_cart_price ?? 0;
            $orderItem->total = $orderItem->price * $orderItem->quantity;
            if ($orderItem->save()) {
                $orderItems[] = $orderItem;
            }
        }

        return $orderItems;
    }

    private function processBillingAddress()
    {
        if (! empty($this->request['billing']['user_selected_address'])) {
            $getAddress = Address::where([
                ['id', $this->request['billing']['user_selected_address']['id']],
                ['role', 'customer'],
            ])->first();

            if (! empty($getAddress)) {
                $getAddress = $getAddress->toArray();
                $orderAddress = new Address($getAddress);
                $orderAddress->role = 'order';
                $orderAddress->role_id = $this->order->id;
                if ($orderAddress->save()) {
                    //$this->order->save();
                }
            }
        } else {
            $orderAddress = new Address($this->request['billing']['inputs']);
            $orderAddress->role = 'order';
            $orderAddress->role_id = $this->order->id;
            if ($orderAddress->save()) {
                //$this->order->save();
            }
        }
    }

    private function processShippingAddress()
    {
        $shippingType = $this->request['shipping']['type'];

        if ($shippingType == ShippingMethod::HOME || $shippingType == ShippingMethod::DPD || $shippingType == ShippingMethod::SAMEDAY) {
            $address = $this->request['shipping']['types']['home'];
            if (! empty($address['user_selected_address']['id'])) {
                $getAddress = Address::where([
                    ['id', $address['user_selected_address']['id']],
                    ['role', 'customer'],
                    ['role_id', $this->customer->id],
                ])->first();

                if (! empty($getAddress)) {
                    $getAddress = $getAddress->toArray();
                    $shippingAddress = new Address($getAddress);
                } else {
                    return false;
                    /*$shippingAddress = new Address();
                    $shippingAddress->last_name = $address['inputs']['last_name'];
                    $shippingAddress->first_name = $address['inputs']['first_name'];
                    $shippingAddress->business_name = $address['inputs']['business_name'];
                    $shippingAddress->vat_number = $address['inputs']['vat_number'];
                    $shippingAddress->city = $address['inputs']['city'];
                    $shippingAddress->zip_code = $address['inputs']['zip_code'];
                    $shippingAddress->address = $address['inputs']['address'];
                    $shippingAddress->comment = $address['inputs']['comment'];
                    $shippingAddress->country_id = $address['inputs']['country_id'];
                    $shippingAddress->entity_type = 1;
                    $shippingAddress->type = 'shipping';*/
                }
            } else {
                return false;
                /*$shippingAddress = new Address();
                $shippingAddress->last_name = $address['inputs']['last_name'];
                $shippingAddress->first_name = $address['inputs']['first_name'];
                $shippingAddress->business_name = $address['inputs']['business_name'];
                $shippingAddress->vat_number = $address['inputs']['vat_number'];
                $shippingAddress->city = $address['inputs']['city'];
                $shippingAddress->zip_code = $address['inputs']['zip_code'];
                $shippingAddress->address = $address['inputs']['address'];
                $shippingAddress->comment = $address['inputs']['comment'];
                $shippingAddress->country_id = $address['inputs']['country_id'];
                $shippingAddress->entity_type = 1;
                $shippingAddress->type = 'shipping';*/
            }

            $shippingAddress->role = 'order';
            $shippingAddress->role_id = $this->order->id;

            if ($shippingAddress->save()) {
                //$this->order->shipping_address_id = $shippingAddress->id;
                // $this->order->save();
            }
        } else {
            $this->order->boxprovider = isset($this->request['shipping']['types'][$shippingType]['selected_box']) ? $this->request['shipping']['types'][$shippingType]['selected_box']['provider'] : null;
        }
        $this->order->shipping_data = [
            'type' => $shippingType,
            "$shippingType" => $this->request['shipping']['types'][$shippingType] ?? null,
        ];
        $this->order->save();
    }

    private function createBorgun()
    {
        $this->cart->order_number = $this->order->order_number;
        $this->cart->orderId = $this->order->order_number;
        $this->cart->country_id = $this->order->country_id;
        $this->cart->shipping_method_id = $this->order->shipping_method_id;
        $this->cart->payment_methods = $this->getPaymentMethods($this->order->country);
        $borgun = new Borgun($this->cart, $this->request);

        return $borgun->create();
    }

    private function createBarion()
    {
    }

    private function refCheck()
    {
        if (! in_array(request('ref'), $this->validRefs) && request()->expectsJson()) {
            return $this->badRefMessage();
        }
    }

    private function getGuestToken()
    {
        return isset(request()->body['guest_token']) ? request()->body['guest_token'] : Cart::generateGuestToken();
    }

    private function isLoggedIn()
    {
        if ($token = request()->bearerToken()) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($token);
            if (! empty($accessToken)) {
                $this->guest = false;
                $this->customer = $accessToken->tokenable;
            } else {
                $this->guestToken = request()->body['guest_token'] ?? null;
            }
        }
    }

    private function getTotal()
    {
        $total = 0;
        if ($this->cart ?? false) {
            foreach ($this->cart->items as $item) {
                $price = ($item->is_cart_price ? $item->product->price($this->cart->store)->price_cart : $item->product->price($this->cart->store)->price_sale) * $item->quantity;
                if (request('store') == 2) {
                    $customer = Customer::find($this->cart->customer_id);
                    if ($customer) {
                        $priceListTotal = ($item->product->price($this->cart->store)->price_list) * $item->quantity;
                        if ($item->product->publisher_id == 38) { //TODO
                            $discount = $customer->personal_discount_alomgyar;
                        } else {
                            $discount = $customer->personal_discount_all;
                        }
                        $pricePersonal = round($priceListTotal - (($priceListTotal / 100) * $discount));
                        if ($price > $pricePersonal) {
                            $price = $pricePersonal;
                        }
                    }
                }
                $total += round($price);
            }
        }

        return $total;
    }

    public function getOrder(Request $request)
    {
        if (! isset($request->orderid)) {
            abort(400);
        }

        $order = Order::with('orderItems.product', 'customer')->where('order_number', $request->orderid)->first();

        $this->order = $order;

        if (empty($order)) {
            abort(400);
        }

        switch ($request->status) {
            case 'CANCEL':
                $order->payment_status = Order::STATUS_PAYMENT_CANCELLED;
                break;
            case 'ERROR':
                $order->payment_status = Order::STATUS_PAYMENT_ERROR;
                break;

            case 'OK':
                $paidFlag = true;
                if ($order->onlyEbook()) {
                    $order->status = Order::STATUS_COMPLETED;
                    $order->payment_status = Order::STATUS_PAYMENT_PAID;
                } else {
                    $order->status = Order::STATUS_NEW;
                }

                $cart = Cart::where('order_id', $order->id)->first();
                if (! empty($cart)) {
                    $cart->deleted_at = Carbon::now();
                    $cart->save();
                    $order->payment_status = Order::STATUS_PAYMENT_PAID;
                    $this->createInvoice($request->orderid);

                    Log::info('order mail sent:'.time());
                    $this->sendOrderMail();
                }
                break;
        }

        // ezt akkor, amikor a user visszatér
        if ($order->save()) {
            header('Location: '.StoreHelper::currentStore().'/penztar/fizetes/'.$order->order_number.($paidFlag ?? false ? '?i=true' : ''));
            exit();
        }
    }

    private function getPaymentMethods($selectedCountry)
    {
        $hasEbook = false;
        $onlyPrepay = false;

        $items = isset($this->cart) ? $this->cart->items : $this->order->orderItems;
        $store = isset($this->cart) ? $this->cart->store : $this->order->store;


        foreach ($items as $item) {
            if ($item->product->type == 1) {
                $hasEbook = true;
            }
            if ($item->product->only_prepay) {
                $onlyPrepay = true;
            }
        }

        $paymentMethods = PaymentMethod::select(DB::raw('method_id as "key"'), 'fee_'.$store,
            DB::raw('status_'.$store.' as active'))->get();
        $paymentMethodsObject = [];
        if ($selectedCountry->name == Country::HUNGARY_STRING) {
            // $shippingFee = ShippingMethod::where('method_id', $this->request['shipping']['type'] )->first();
            foreach ($paymentMethods as $method) {
                $paymentFree = false;
                $method->active = $method->active == 1;
                if ($method->key !== 'card' and $hasEbook) {
                    $method->active = false;
                }
                if ($method->key !== 'card' and $method->key !== 'transfer' and $onlyPrepay) {
                    $method->active = false;
                }

                if (!$hasEbook and isset($this->request['shipping']['type']) and $this->request['shipping']['type'] == ShippingMethod::SHOP) {
                    if ($method->key == ShippingMethod::CASH_ON_DELIVERY) {
                        //  if ($this->cart->store == 0){
                        $paymentFree = true;
                        // }
                    }else if(!$onlyPrepay){
                        $method->active = false;
                    }
                }

                $paymentMethodsObject[$method->key] = [
                    'fee' => $paymentFree ? 0 : $method->fee($store),
                    'active' => $method->active,
                ];
            }
        } else {
            foreach ($paymentMethods as $method) {
                $method->active = $method->active == 1 ? true : false;
                if ($method->key !== 'card') {
                    $method->active = false;
                }

                $paymentMethodsObject[$method->key] = [
                    'fee' => $method->fee($store),
                    'active' => $method->active,
                ];
            }
        }

        return $paymentMethodsObject;
    }

    private function getShippinMethods()
    {
        $store = $this->customer->store;
        // THIS LINE IS IMPORTANT, TO SET $this->cart VARIABLE
        $cart = $this->getCart();
        $total = $this->getTotal();

        $onlyFreeDelivery = true;
        $orderOnlyShop = false;

        $items = isset($this->cart) ? $this->cart->items : ($this->order?->orderItems ?? []);

        foreach ($items as $item) {
            if (empty($item->product->free_delivery)) {
                $onlyFreeDelivery = false;
            }
            if ($item->product->order_only_shop) {
                $orderOnlyShop = true;
            }
        }

        $defaultLimit = $onlyFreeDelivery
            ? 0
            : (StoreHelper::freeShippingLimit($this->customer->store) ?? 10000);

        //$payments = PaymentMethod::findAll();'fee_'.$store, DB::raw('status_'.$store.' as active')
        $shipping = ShippingMethod::select([
            DB::raw('method_id as "key"'),
            $total >= $defaultLimit
                ? DB::raw('discounted_fee_'.$store.' as fee')
                : DB::raw('fee_'.$store.' as fee'),
            DB::raw('status_'.$store.' as active')
        ])->get();

        foreach ($shipping as $ship) {
            $ship->active = $ship->active == 1;

            if($orderOnlyShop and $ship->key !== ShippingMethod::SHOP){
                $ship->active = false;
            }

            if ($store == 2) { // nagykeres vásárlás logika
                $amp = ceil($total / (int) option('shipping_method_home_multiplier_nagyker', 0, false));
                $ship->fee = in_array($ship->key, [ShippingMethod::HOME, ShippingMethod::BOX]) ? ($ship->fee * $amp) : $ship->fee;
            }
        }

        return ['shipping_methods' => $shipping];
    }

    private function checkPayment()
    {
        if (isset(request()->body['order_number'])) {
            $order = Order::with('orderItems.product')->where('order_number',
                request()->body['order_number'])->first();
            $this->order = $order;

            return $this->buildOrderObject($order);

            /*return [
                'order' => $order,
                'payment_status' => $order->payment_status == Order::STATUS_PAYMENT_PAID ? 'success' : ($order->payment_status == Order::STATUS_PAYMENT_CANCELLED ? 'cancelled' : 'error'),
                'payment_methods' => $this->getPaymentMethods($this->order->country)
            ];*/
        }
    }

    public function checkOrder($onlyNewData = false)
    {
        if (isset(request()->body['order_id'])) {
            return request()->body['order_id'];
        }

        $cart = $this->getCart();
        $total = $this->getTotal();


        $onlyFreeDelivery = true;
        $items = isset($this->cart) ? $this->cart->items : $this->order->orderItems;

        foreach ($items as $item) {
            if (empty($item->product->free_delivery)) {
                $onlyFreeDelivery = false;
            }
        }

        // INITS
        $defaultLimit = $onlyFreeDelivery
            ? 0
            : (StoreHelper::freeShippingLimit($this->cart->store) ?? 10000);
        $getPaymentMethod = PaymentMethod::where('method_id', $this->request['summary']['payment_method'])->first() ?? null;
        $getCountry = $this->request['shipping']['types']['home']['user_selected_address']['country']['id'] ?? $this->request['shipping']['types']['home']['inputs']['country_id'] ?? false;
        $country = Country::HUNGARY_STRING;
        $freeShipping = false;
        $shippingType = $this->request['shipping']['type'];
        $finalShippingFee = $this->request['summary']['shipping_fee'] ?? 0;

        // GET THE SELECTED COUNTRY
        if ($getCountry) {
            $selectedCountry = Country::find($getCountry);
        } else {
            $selectedCountry = Country::where('name', $country)->first();
        }

        // WHAT HAPPEND WHEN NOT SET ??
        if (isset($shippingType)) {
            $localFee = ShippingMethod::where('method_id', $shippingType)->first();
        }
        // WHY IS THIS NECESSARY ?
        if (! $this->cart->onlyEbook()) {
            $finalShippingFee = $selectedCountry->fee;
            if ($selectedCountry->name == Country::HUNGARY_STRING) {
                if (isset($shippingType)) {
                    $finalShippingFee = $defaultLimit <= $total
                        ? $localFee->discountedFee($this->cart->store)
                        : $localFee->fee($this->cart->store);
                } else {
                    // FALLBACK
                    $finalShippingFee = 0;
                }
            }
        }

        if ($this->cart->store == 2) { // nagykeres vásárlás logika
            $amp = ceil($this->getTotal() / (int) option('shipping_method_home_multiplier_nagyker', 0, false));

            $shippingFeeFornagyker = $localFee->fee_2;
            if (in_array($localFee->method_id, [ShippingMethod::DPD, ShippingMethod::SAMEDAY, ShippingMethod::HOME, ShippingMethod::BOX])) {
                $shippingFeeFornagyker = $localFee->fee_2 * $amp;
            }
            $finalShippingFee = $shippingFeeFornagyker;
        } else { // minden más
            if ($shippingType == ShippingMethod::SHOP && $this->request['summary']['payment_method'] == ShippingMethod::CASH_ON_DELIVERY) {
                if ($selectedCountry->name == Country::HUNGARY_STRING && $defaultLimit <= $this->getTotal()) {
                    // if ($this->cart->store == 0){
                    $freeShipping = true;
                    // }
                }
            }
        }

        // // THESE ARE NEEDED FOR DEFAULT STEPS AS I THINK
        if ($this->request['summary']['payment_method'] == ShippingMethod::CASH_ON_DELIVERY && $this->request['shipping']['type'] == ShippingMethod::SHOP) {
            $this->request['summary']['payment_fee'] = 0;
        } else {
            $this->request['summary']['payment_fee'] = $freeShipping ? 0 : ($getPaymentMethod->fee($this->cart->store) ?? 0);
        }

        $this->request['summary']['payment_methods'] = $this->getPaymentMethods($selectedCountry);
        $this->request['summary']['shipping_fee'] = $finalShippingFee ?? 0;
        $this->request['summary']['total'] = intval($this->getTotal()) + intval($this->request['summary']['payment_fee']) + intval($this->request['summary']['shipping_fee']);

        if ($onlyNewData) {
            return [
                'shipping_fee' => $finalShippingFee ?? 0,
                'payment_fee' => $this->request['summary']['payment_fee'],
                'payment_methods' => $this->getPaymentMethods($selectedCountry),
                'total' => $this->request['summary']['total'] + $this->request['summary']['payment_fee'] + $finalShippingFee,
            ];
        }

        return ['steps' => $this->request];
    }

    public function callback(Request $request)
    {
        if (! isset($request->paymentId)) {
            abort(400);
        }
    }

    public function barionCheck(Request $request)
    {
        $order = Order::with('orderItems.product', 'customer')->where('order_number', $request->orderid)->first();

        $this->order = $order;

        if (empty($order)) {
            abort(400);
        }

        switch ($request->status) {
            case 'CANCEL':
                $order->payment_status = Order::STATUS_PAYMENT_CANCELLED;
                break;
            case 'ERROR':
                $order->payment_status = Order::STATUS_PAYMENT_ERROR;
                break;

            case 'OK':
                if ($order->onlyEbook()) {
                    $order->status = Order::STATUS_COMPLETED;
                    $order->payment_status = Order::STATUS_PAYMENT_PAID;
                } else {
                    $order->status = Order::STATUS_NEW;
                }

                $cart = Cart::where('order_id', $order->id)->first();
                if (! empty($cart)) {
                    $cart->deleted_at = Carbon::now();
                    $cart->save();
                }
                $order->save();
                $this->createInvoice($request->orderid);

                Log::info('order mail sent:'.time());
                $this->sendOrderMail();
                break;
        }

        // ezt akkor, amikor a user visszatér
        if ($order->save()) {
            return true;
        }
    }

    // számlakészítés
    public function createInvoice($orderId)
    {
        $afaNum = 5; // ÁFA
        $afaString = '5';

        $order = Order::with(
            'orderItems.product',
            'country',
            'customer',
            'billingAddress',
            'shippingMethod'
        )->where('order_number', $orderId)->first();

        if (empty($order)) {
            return false;
        }

        foreach ($order->orderItems as $item) {
            if ($item->product->type == 0) {
                //ha a rendelésben nemcsak ebook van, akkor nem készítünk számlát
                return true;
            }
        }

        $billingAddress = $order->billingAddress;
        if (empty($billingAddress)) {
            $billingAddress = Address::where([['role_id', $order->customer_id], ['role', 'customer']])->first();
        }

        require_once app_path().'/Components/Szamlazz/autoload.php';

        $szamlaKey = env('SZAMLAZZ_'.$order->store, 'qwd4jiyr796mzq9jcbybc67fpsezrzw4imwe32ta4v');

        $agent = SzamlaAgentAPI::create($szamlaKey, true, 0);

        $invoice = new Invoice(Invoice::INVOICE_TYPE_P_INVOICE);
        $header = $invoice->getHeader();
        $header->setOrderNumber($order->order_number);
        $header->setPaymentMethod(Invoice::PAYMENT_METHOD_BANKCARD);
        $header->setCurrency(Currency::CURRENCY_HUF);
        $header->setLanguage(Language::LANGUAGE_HU);
        if ($order->onlyEbook()) {
            if (env('APP_ENV') == 'live') {
                $header->setPrefix('EWEB');
            } else {
                $header->setComment('Éles környezetben EWEB előtaggal generálódna a számla');
            }
        }
        $header->setPaid(true);
        // Számla teljesítés dátuma
        $header->setFulfillment(date('Y-m-d'));
        // Számla fizetési határideje
        $header->setPaymentDue(date('Y-m-d'));

        $buyerName = ! empty($billingAddress->business_name) ? $billingAddress->business_name : $billingAddress->last_name.' '.$billingAddress->first_name;
        // vevő létrehozása
        $buyer = new Buyer(
            $buyerName,
            $billingAddress->zip_code,
            $billingAddress->city,
            $billingAddress->address
        );
        $buyer->setCountry($order->country->name);
        if (! empty($billingAddress->vat_number)) {
            $buyer->setTaxNumber($billingAddress->vat_number);
        }

        $buyer->setEmail($order->customer->email);

        $invoice->setBuyer($buyer);

        foreach ($order->orderItems as $item) {
            $afa = ! empty($item->product->tax_rate) ? $item->product->tax_rate : $afaNum;

            $netto = round($item->price / (1 + ($afa / 100)));

            $invoiceItem = new InvoiceItem($item->product->title, $netto, $item->quantity, 'db', strval($afa));
            // Tétel nettó értéke
            $invoiceItem->setNetPrice($netto * $item->quantity);
            // Tétel ÁFA értéke
            $invoiceItem->setVatAmount($item->price - $netto);
            // Tétel bruttó értéke
            $invoiceItem->setGrossAmount($item->price * $item->quantity);
            // Tétel hozzáadása a számlához
            $invoice->addItem($invoiceItem);
        }
        /* szállítási költség hozzáadása */
        if ($order->shipping_fee > 0) {
            $sNetto = round($order->shipping_fee / (1 + $afaNum / 100));
            $shippingItem = new InvoiceItem('Kényelmi költség ('.$order->shippingMethod->name.')', $sNetto, 1, 'db',
                strval($afaNum));
            // Tétel nettó értéke
            $shippingItem->setNetPrice($sNetto);
            // Tétel ÁFA értéke
            $shippingItem->setVatAmount($order->shipping_fee - $sNetto);
            // Tétel bruttó értéke
            $shippingItem->setGrossAmount($order->shipping_fee);
            // Tétel hozzáadása a számlához
            $invoice->addItem($shippingItem);
        }

        $result = $agent->generateInvoice($invoice);
        if ($result->isSuccess()) {
            $order->invoice_url = $result->getDocumentNumber();
            $order->attachments = [$result->getDocumentNumber()];
            $order->save();

            return true;
        }
    }

    private function buildOrderObject($order)
    {
        $errors = [
            'id' => '',
            'last_name' => '',
            'first_name' => '',
            'business_name' => '',
            'vat_number' => '',
            'city' => '',
            'zip_code' => '',
            'address' => '',
            'comment' => '',
            'coutry_id' => '',
            'entity_type' => '',
        ];

        $billing = Address::select(
            'id',
            'last_name',
            'first_name',
            'business_name',
            'vat_number',
            'city',
            'zip_code',
            'address',
            'comment',
            'country_id'
        )
            ->where([['type', 'billing'], ['role', 'order'], ['role_id', $order->id]])
            ->first();

        $shipping_data = [
            'type' => '',
            'valid' => true,
            'types' => [
                'shop' => [
                    'selected_shop' => null,
                ],
                'box' => [
                    'selected_box' => null,
                ],
                'home' => [
                    'user_selected_address' => null,
                    'inputs' => [
                        'address' => '',
                        'business_name' => '',
                        'city' => '',
                        'comment' => '',
                        'country_id' => '',
                        'first_name' => '',
                        'last_name' => '',
                        'vat_number' => '',
                        'zip_code' => '',
                    ],
                    'errors' => $errors,
                ],
            ],
        ];

        if (empty($order->shipping_data)) {
            $shipping = Address::select('id', 'last_name', 'first_name', 'business_name',
                'vat_number', 'city', 'zip_code', 'address', 'comment', 'country_id')->where([
                    ['type', 'shipping'], ['role', 'order'], ['role_id', $order->id],
                ])->first();
            $shipping_data['types']['home'] = $shipping;
            $shipping_data['type'] = ShippingMethod::HOME;
        } else {
            $shipping_data['type'] = $order->shipping_data['type'];
            $shipping_data['types'][$order->shipping_data['type']] = $order->shipping_data[$order->shipping_data['type']];
        }

        $response = [
            //'guest_token' => $order->guest_token ?? '',
            /*'steps' => [
                'billing' => [
                    'type' => 'private',
                    'user_selected_address' => null,
                    'valid' => true,
                    'inputs' => $billing ? $billing->toArray() : [] ,
                    'errors' => $errors
                ],
                'shipping' => [
                    'inputs' => $shipping_data
                ],
                'summary' => [
                    'valid' => true,
                    'done' => $order->payment_status == 10 ? 'success' : 'error',
                    'payment_method' => $order->paymentMethod->method_id,
                    'payment_methods' => $this->getPaymentMethods($order->country)
                ]
            ],*/
            'order' => [
                'status' => $order->payment_status == Order::STATUS_PAYMENT_PAID || $order->status == Order::STATUS_NEW ? 'success' : 'error',
                'order_id' => $order->order_number,
                'email' => $order->customer->email ?? '',
                'payment_method' => $order->paymentMethod->method_id ?? 'card',
            ],
        ];

        return $response;
    }

    private function sendOrderMail()
    {
        // NEED A FRESH ONE
        $this->order = Order::find($this->order->id);

        $orderMail = Mail::to(trim($this->order->customer->email));
        // ONLY SEND BCC ON LIVE
        if(app()->environment('live'))
        {
            $orderMail->bcc($this->getBcc());
        }
        $orderMail->send(new OrderMail($this->order));
    }

    private function getBcc()
    {
        $slug = [
            0 => 'alomgyar',
            1 => 'olcsokonyvek',
            2 => 'nagyker',
        ];

        $option = option(sprintf('order_mail_bcc_%s', $slug[$this->order->store]), null, false);

        return ! empty($option) ? $option : [];
    }
}
