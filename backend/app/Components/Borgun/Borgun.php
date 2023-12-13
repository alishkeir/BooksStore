<?php

namespace App\Components\Borgun;

use Alomgyar\Carts\Cart;
use Alomgyar\Customers\Customer;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use Alomgyar\Products\Product;

class Borgun
{
    private $merchantId;

    private $paymentGatewayId;

    private $orderId;

    protected string $currency = 'HUF';

    protected string $language = 'HU';

    protected array $borgunPrefix = ['ALOM', 'OLCSO', 'ALOMN'];

    private $cart;

    private array $borgun;

    private string $successUrl = '/order'; //TODO frontend url

    private string $cancelUrl = '/order'; //TODO frontend url

    private string $errorUrl = '/order/error'; //TODO frontend url

    private string $successServer;

    private $request;

    private array $borgunLogo = [
        0 => '/logo-alomgyar.png',
        1 => '/logo-olcsokonyvek.png',
        2 => '/logo-nagyker.png',
    ];

    public function __construct(Cart $cart, $request)
    {
        $this->borgun = [];
        $this->cart = $cart;
        $this->request = $request;
        $this->merchantId = env('BORGUN_'.$this->cart->store.'_MERCHANT', '9275444');
        $this->paymentGatewayId = env('BORGUN_'.$this->cart->store.'_PAYMENTGATEWAY', 16);
        $this->orderId = $cart->orderId;
        $this->successServer = route('checkout.callback', ['store' => $cart->store], false);
    }

    public function create()
    {
        $borgunUri = env('APP_ENV') == 'local' || env('APP_ENV') == 'dev' ? 'test' : 'securepay';

        $this->borgun = [
            'order' => [
                'order_id' => $this->cart->order_number,
                'payment_methods' => $this->cart->payment_methods,
            ],
            'options' => [
                'action' => 'https://'.$borgunUri.'.borgun.is/SecurePay/default.aspx',
                'method' => 'post',
            ],
            'post_data' => [
                'merchantid' => $this->merchantId,
                'paymentgatewayid' => $this->paymentGatewayId,
                'orderid' => $this->orderId,
                //'checkhash' => $this->createHash(),
                //'amount' => $this->total(),
                'currency' => $this->currency,
                'language' => $this->language,
                'buyername' => isset($this->cart->customer) ? $this->cart->customer->lastname.' '.$this->cart->customer->firstname : $this->request['billing']['inputs']['last_name'].' '.$this->request['billing']['inputs']['first_name'],
                'returnurlsuccess' => env('BACKEND_URL').'/api/v1/'.$this->cart->store.($this->successUrl.'/'.$this->orderId),
                'returnurlsuccessserver' => env('BACKEND_URL').'/api/v1/'.$this->cart->store.($this->successUrl.'/'.$this->orderId),
                'returnurlcancel' => env('BACKEND_URL').'/api/v1/'.$this->cart->store.($this->cancelUrl.'/'.$this->orderId),
                'returnurlerror' => env('BACKEND_URL').'/api/v1/'.$this->cart->store.($this->errorUrl.'/'.$this->orderId),
                'merchantlogo' => env('BACKEND_URL').$this->borgunLogo[$this->cart->store], // TODO
                'merchantemail' => 'webshop@alomgyar.hu', // TODO
                'buyeremail' => $this->cart->customer->email ?? $this->request['summary']['guest_email'] ?? 'aranytoth.tibor+noemail@gmail.com',
            ],

        ];

        $this->createItems();

        $this->borgun['post_data']['amount'] = $this->total() + $this->createFeeItems();
        $this->borgun['post_data']['checkhash'] = $this->createHash();

        return $this->borgun;
    }

    private function createItems()
    {
        $customer = Customer::find($this->cart->customer_id);

        foreach ($this->cart->items as $key => $item) {
            $price = $item->is_cart_price ? $item->product->price($this->cart->store)->price_cart : $item->product->price($this->cart->store)->price_sale;
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
            $this->borgun['post_data']['itemdescription_'.$key] = $item->product->title.($item->product->type == Product::EBOOK ? ' (ebook)' : '');
            $this->borgun['post_data']['itemcount_'.$key] = $item->quantity;
            $this->borgun['post_data']['itemunitamount_'.$key] = $price;
            $this->borgun['post_data']['itemamount_'.$key] = $price * $item->quantity;
        }
    }

    private function createFeeItems()
    {
        $totalFee = 0;

        $cartItems = count($this->cart->items);

        $shippingmethod = ShippingMethod::where('id', $this->cart->shipping_method_id)->first();

        if ($this->cart->order->shipping_fee > 0) {
            $this->borgun['post_data']['itemdescription_'.($cartItems)] = 'Szállítási költség';
            $this->borgun['post_data']['itemcount_'.($cartItems)] = 1;
            $this->borgun['post_data']['itemunitamount_'.($cartItems)] = $this->cart->order->shipping_fee;
            $this->borgun['post_data']['itemamount_'.($cartItems)] = $this->cart->order->shipping_fee;

            $totalFee += $this->cart->order->shipping_fee;
            $cartItems++;
        }

        $paymentMethod = PaymentMethod::where('id', $this->cart->order->payment_method_id)->first();

        if ($this->cart->order->payment_fee > 0) {
            $this->borgun['post_data']['itemdescription_'.($cartItems)] = 'Kényelmi díj ('.$paymentMethod->name.')';
            $this->borgun['post_data']['itemcount_'.($cartItems)] = 1;
            $this->borgun['post_data']['itemunitamount_'.($cartItems)] = $this->cart->order->payment_fee;
            $this->borgun['post_data']['itemamount_'.($cartItems)] = $this->cart->order->payment_fee;

            $totalFee += $this->cart->order->payment_fee;
        }

        return $totalFee;
    }

    private function createHash()
    {
        // MerchantId|ReturnUrlSuccess|ReturnUrlSuccessServer|OrderId|Amount|Currency
        $message = utf8_encode($this->merchantId.'|'.env('BACKEND_URL').'/api/v1/'.$this->cart->store.($this->successUrl.'/'.$this->orderId).'|'.env('BACKEND_URL').'/api/v1/'.$this->cart->store.($this->successUrl.'/'.$this->orderId).'|'.$this->orderId.'|'.$this->borgun['post_data']['amount'].'|'.$this->currency);
        $checkhash = hash_hmac('sha256', $message, env('BORGUN_'.$this->cart->store, '998877669988776699887766'));

        return $checkhash;
    }

    private function total()
    {
        $total = 0;
        foreach ($this->cart->items as $item) {
            $price = ($item->is_cart_price ? $item->product->price($this->cart->store)->price_cart : $item->product->price($this->cart->store)->price_sale) * $item->quantity;

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

            $total += round($price); // TODO aktuális árak implementálása
        }

        return $total;
    }
}
