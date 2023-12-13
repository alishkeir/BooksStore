<?php

namespace Alomgyar\Orders;

use Alomgyar\Countries\Country;
use Alomgyar\Customers\Address;
use Alomgyar\Customers\Customer;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use Alomgyar\Products\Product;
use App\Imports\OrderItemImport;
use App\Order;
use App\OrderItem;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class CreateComponent extends Component
{
    public $customerID;

    public $order = [
        'items' => [],
        'total' => 0,
        'quantity' => 0,
        'payment_method' => 'card',
        'address' => ['country_id' => 1],
        'email' => '',
        'phone' => '',
    ];

    public $importfile;

    public $product_id;

    public $edit_item;

    protected $listeners = ['setProductId', 'openItemForEdit', 'deleteItem', 'setImportFile', 'setCustomerID'];

    protected $rules = [
        'order.address.first_name' => 'required',
        'order.address.last_name' => 'required',
        'order.phone' => 'required',
        'order.email' => 'required|email',
        'order.items.*.id' => 'required|exists:product,id',
        'order.items.*.quantity' => 'required',
        'order.items.*.title' => 'required',
        'order.items.*.tax_rate' => 'required|integer',
        'order.items.*.price_sale' => 'required|integer',
        'order.items.*.price_list' => 'required|integer',
        'order.items.*.price_total' => 'required',
    ];

    public function render()
    {
        $this->countries = Country::all();

        $this->dispatchBrowserEvent('restartSelect2');

        return view('orders::components.createcomponent');
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
        ];
        $this->calculateOrder();
        $this->dispatchBrowserEvent('restartSelect2');
    }

    public function setCustomerID($id)
    {
        $this->customerID = $id;
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

    public function setImportFile($value)
    {
        $this->importfile = $value;
    }

    public function loadFromFile()
    {
        $products = Excel::toCollection(
            new OrderItemImport,
            Storage::disk('local')->path('public/imports/'.$this->importfile)
        );

        foreach ($products as $tabs) {
            foreach ($tabs as $product) {
                $p = Product::where('isbn', $product[0])->first();
                $this->order['items'][$p->id] = [
                    'id' => $p->id,
                    'quantity' => $product[1],
                    'title' => $p->title,
                    'tax_rate' => $product[2],
                    'price_sale' => $product[3],
                    'price_list' => $product[4],
                    'price_total' => $product[1] * $product[3],
                ];
            }
        }
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

    public function save()
    {
        // check customer
        $this->validate();
        if (! $this->customerID) {
            $customer = Customer::create([
                'firstname' => $this->order['address']['first_name'],
                'lastname' => $this->order['address']['last_name'],
                'email' => $this->order['email'],
                'phone' => $this->order['phone'],
            ]);
            $this->customerID = $customer->id;
        } else {
            $customer = Customer::find($this->customerID);
        }
        // save order
        $newOrder = new Order();

        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
        $i = 0;
        $pass = '';

        while ($i < 6) {
            $num = rand(0, strlen($chars) - 1);
            $pass .= $chars[$num];
            $i++;
        }

        $orderIdStr = str_shuffle($pass);

        $newOrder->customer_id = $this->customerID;
        $newOrder->status = Order::STATUS_NEW;
        $newOrder->payment_status = Order::STATUS_PAYMENT_WAITING;
        $newOrder->total_amount = $this->order['total'];
        $newOrder->total_quantity = $this->order['quantity'];
        $newOrder->store = 0;
        $newOrder->order_number = strtoupper(($newOrder::$barionPrefix[0]).date('ym').$orderIdStr);
        $newOrder->payment_token = hash_hmac('sha256', $newOrder->order_number, $orderIdStr);


        $newOrder->country_id = $this->order['address']['country_id'];

        $getPaymentMethod = PaymentMethod::where('method_id', $this->order['payment_method'])->first();

        $newOrder->payment_method_id = $getPaymentMethod->id ?? null;

        $shippingmethod = ShippingMethod::where('method_id', $this->order['shipping_method'] ?? 'none')->first();

        $newOrder->shipping_method_id = $shippingmethod->id ?? 1;

        $newOrder->email = ! empty($this->order['email']) ? $this->order['email'] : $customer->email;

        if ($newOrder->save()) {
            $orderItems = $this->createOrderItems($newOrder->id);
        }

        $orderAddress = new Address($this->order['address']);
        $orderAddress->type = 'billing';
        $orderAddress->role = 'order';
        $orderAddress->role_id = $newOrder->id;
        $orderAddress->save();

        $orderAddress = new Address($this->order['address']);
        $orderAddress->type = 'shipping';
        $orderAddress->role = 'order';
        $orderAddress->role_id = $newOrder->id;
        $orderAddress->save();

        // redirect orders
        return redirect()->route('orders.index')->with('success', 'Új rendelés létrehozva');
    }

    private function createOrderItems($newOrderID)
    {
        $orderItems = [];

        foreach ($this->order['items'] as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $newOrderID;
            $orderItem->product_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price_list'] ?? $item['price_sale'];
            $orderItem->original_price = $item['price_list'] ?? $item['price_sale'];
            $orderItem->total = $item['price_total'];
            if ($orderItem->save()) {
                $orderItems[] = $orderItem;
            }
        }

        return $orderItems;
    }
}
