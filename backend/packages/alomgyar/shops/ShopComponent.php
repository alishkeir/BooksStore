<?php

namespace Alomgyar\Shops;

use Alomgyar\Countries\Country;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use Alomgyar\Products\Product;
use App\Order;
use App\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShopComponent extends Component
{
    public $address;

    public $countries;

    public $tab = 'tab-receipt';

    public $product_id;

    public $edit_item;

    public $addFromIsbn;

    public $status = 'new';

    public $newOrder;

    public $baseDiscount = null;

    public $fixedPrice = null;

    public $order = [
        'items' => [],
        'total' => 0,
        'quantity' => 0,
        'payment_method' => 'card',
        'address' => ['country_id' => 1, 'entity_type' => '1'],
    ];

    protected $listeners = ['setProductId', 'openItemForEdit', 'deleteItem', 'waitUntilAttachment'];

    protected function validateRequest()
    {
        $rules = [
            'order.items' => 'required|array|min:1',
            'order.items.*' => 'required|array',
            'order.items.*.id' => 'required|exists:product,id',
            'order.total' => 'required|integer',
            'order.quantity' => 'required|integer',
            'order.payment_method' => 'required|in:card,cash,transfer',
        ];
        if ($this->tab == 'tab-invoice') {
            $rules = $rules +
                [
                    'order.address.country_id' => 'required|exists:countries,id',
                    'order.address.entity_type' => 'required|in:1,2',
                    'order.address.last_name' => 'required_if:order.address.entity_type,1|string|min:2',
                    'order.address.first_name' => 'required_if:order.address.entity_type,1|string|min:2',
                    'order.address.address' => 'required|string|min:2',
                    'order.address.zip_code' => 'required|string|min:2',
                    'order.address.city' => 'required|string|min:2',
                    'order.address.business_name' => 'required_if:order.address.entity_type,2|string|min:2',
                    'order.address.vat_number' => 'required_if:order.address.entity_type,2',
                ];

            if (isset($this->order['address']['vat_number'])) {
                $rules['order.address.vat_number'] .= '|string|alpha_dash|min:8';
            }
        }

        return $this->validate($rules);
    }

    public function render()
    {
        $this->countries = Country::all();

        if ($this->addFromIsbn ?? false) {
            $productToAdd = Product::where('isbn', $this->addFromIsbn)->book()->first();
            if ($productToAdd) {
                $this->setProductId($productToAdd->id);
                $this->addFromIsbn = '';
            }
        }
        if (($this->edit_item['discount'] ?? false) > 0) {
            if (($this->edit_item['fixed_price'] ?? false) > 0) {
                $this->edit_item['price_sale'] = $this->edit_item['fixed_price'];
            } else {
                $this->edit_item['price_sale'] = round($this->edit_item['price_list'] - (($this->edit_item['price_list'] / 100) * $this->edit_item['discount']));
            }
        }
        $this->dispatchBrowserEvent('restartSelect2');

        return view('shops::components.shopcomponent');
    }

    public function setProductId($id)
    {
        $p = Product::find($id);
        $this->order['items'][$id] = [
            'id' => $id,
            'quantity' => 1,
            'title' => $p->title,
            'tax_rate' => $p->tax_rate,
            'price_sale' => $p->price(0)->price_list,
            'price_list' => $p->price(0)->price_list,
            'price_total' => $p->price(0)->price_list,
            'discount' => $this->baseDiscount ?? null,
            'fixed_price' => $this->fixedPrice ?? null,
        ];

        $priceList = $this->order['items'][$id]['price_list'] ?? 1;
        if (($this->fixedPrice ?? false) > 0) {
            $this->order['items'][$id]['price_sale'] = $this->fixedPrice;
            $this->order['items'][$id]['discount'] = round(($priceList - $this->fixedPrice) / $priceList * 100);
        } elseif (($this->baseDiscount ?? false) > 0) {
            $this->order['items'][$id]['price_sale'] = round($priceList - (($priceList / 100) * $this->baseDiscount));
        }
        $this->calculateOrder();
        $this->dispatchBrowserEvent('restartSelect2');
    }

    public function openItemForEdit($id)
    {
        $this->edit_item = $this->order['items'][$id];
    }

    public function saveItem($id)
    {
        $this->edit_item['quantity'] = round($this->edit_item['quantity']);
        $this->order['items'][$id] = $this->edit_item;
        $this->calculateOrder();
        $this->edit_item = false;
    }

    public function deleteItem($id)
    {
        unset($this->order['items'][$id]);
        $this->calculateOrder();
    }

    protected function calculateOrder()
    {
        $this->order['total'] = 0;
        $this->order['quantity'] = 0;
        foreach ($this->order['items'] as $item) {
            $this->order['items'][$item['id']]['price_total'] = $item['price_sale'] * $item['quantity'];
            $this->order['total'] += $this->order['items'][$item['id']]['price_total'];
            $this->order['quantity'] += $item['quantity'];
        }
    }

    public function setTab($value)
    {
        $this->tab = $value;
        $this->order['addressError'] = [];
        $this->dispatchBrowserEvent('restartSelect2');
    }

    public function save()
    {
        $this->validateRequest();

        $this->newOrder = new Order(); // The New Order...

        $this->newOrder->customer_id = null;
        $this->newOrder->guest_token = null;
        $this->newOrder->status = Order::STATUS_COMPLETED;
        $this->newOrder->payment_status = Order::STATUS_PAYMENT_PAID;
        $this->newOrder->shipping_fee = 0;
        $this->newOrder->payment_fee = 0;
        $this->newOrder->total_amount = $this->order['total'];
        $this->newOrder->total_quantity = $this->order['quantity'];
        $this->newOrder->has_ebook = 0;
        $this->newOrder->store = 3;
        $this->newOrder->order_number = strtoupper(Order::$barionPrefix[0].substr(uniqid(), -8)); // TODO csekkoljunk a db-t
        $this->newOrder->payment_token = hash_hmac('sha256', $this->newOrder->order_number, substr(uniqid(), -8));

        $country = Country::where('name', 'Magyarország')->first();
        $this->newOrder->country_id = $country->id;

        $selected_pmethod = PaymentMethod::where('method_id', $this->order['payment_method'])->first();
        $this->newOrder->payment_method_id = $selected_pmethod->id;

        $shippingmethod = ShippingMethod::where('method_id', 'shop')->first();
        $this->newOrder->shipping_method_id = $shippingmethod->id ?? 1;
        $this->newOrder->shipping_data = json_decode('{"type": "shop", "shop": {"selected_shop": {"id": '.Auth::user()->shop_id.'}}}');

        $this->newOrder->email = '';

        if ($this->newOrder->save()) {
            foreach ($this->order['items'] as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $this->newOrder->id;
                $orderItem->product_id = $item['id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['price_sale'];
                $orderItem->original_price = $item['price_list'] ?? $item['price_sale'];
                $orderItem->cart_price = 0;
                $orderItem->total = $item['price_sale'] * $item['quantity'];
                if ($orderItem->save()) {
                    //$orderItems[] = $orderItem;
                }
            }
            $this->newOrder->updateStock();
            if ($this->tab == 'tab-invoice') {
                $this->order['address']['type'] = 'billing';
                $this->order['address']['role'] = 'order';
                $this->order['address']['role_id'] = $this->newOrder->id;
                if (DB::table('addresses')->insert($this->order['address'])) {
                    $this->newOrder->createInvoice();
                }
            } else {
                $this->newOrder->createReceipt();
            }
        }

        $this->order = [
            'items' => [],
            'total' => 0,
            'quantity' => 0,
            'payment_method' => 'card',
            'address' => ['country_id' => 1],
        ];
        $this->status = 'done';
        // }
    }

    public function new()
    {
        $this->status = 'new';
        $this->dispatchBrowserEvent('restartSelect2');
    }
}
