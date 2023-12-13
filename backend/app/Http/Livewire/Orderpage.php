<?php

namespace App\Http\Livewire;

use Alomgyar\Countries\Country;
use Alomgyar\Customers\Address;
use Alomgyar\Methods\PaymentMethod;
use Alomgyar\Methods\ShippingMethod;
use Alomgyar\PickUpPoints\Model\PickUpPoint;
use Alomgyar\Products\Product;
use Alomgyar\Shops\Shop;
use App\Order;
use App\OrderItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Orderpage extends Component
{
    public $orderNumber;

    public $model;

    public $onlyEbook;

    public $editInProgress = false;

    public $correctiveInProgress = false;

    public $editShipping = false;

    public $editPayment = false;

    public $editItem;

    public $shippingMethods;

    public $paymentMethods;
    public $editBillingAddress = false;
    public $businessName;
    public $BAfirstName;
    public $BAlastName;
    public $BAvatNumber;
    public $BAcountryID;
    public $BAzipCode;
    public $BAcity;
    public $BAaddress;
    public $countries;
    public $editDeliveryAddress = false;
    public $SAfirstName;
    public $SAlastName;
    public $SAvatNumber;
    public $SAcountryID;
    public $SAzipCode;
    public $SAcity;
    public $SAaddress;
    public $SAcomment;
    public $shops;
    public $SAshopID;
    public $pickupPoints;
    public $SAboxID;


    protected $rules = [
        'editItem.price' => ['required', 'integer'],
        'editItem.quantity' => ['required', 'integer'],
        'model.shipping_method_id' => ['required', 'integer'],
        'model.payment_method_id'  => ['required', 'integer'],
        'model.shipping_fee'  => ['required', 'integer'],
    ];

    protected $listeners = ['addNewProduct', 'setBoxID'];

    public function mount()
    {

        $this->shippingMethods = Cache::remember('shipping-methods-for-order-page', config('cache.admin_default_cache_time'), function () {
            return ShippingMethod::all();
        });
        $this->paymentMethods = Cache::remember('payment-methods-for-order-page', config('cache.admin_default_cache_time'), function () {
            return PaymentMethod::all();
        });
        $this->countries = Country::all();

    }

    public function dehydrate()
    {
        $this->dispatchBrowserEvent('restartSelect2');
    }

    public function render()
    {

        $this->model = Order::query()
            ->where('order_number', $this->orderNumber)
            ->with('shippingMethod', 'paymentMethod', 'billingAddress', 'shippingAddress')
            ->with('orderItems', function ($query) {
                $query->with('product', function ($query2) {
                    $query2->without(['author', 'prices']);
                });
            })
            ->first();

        //$this->model = Order::with('billingAddress')->where('order_number', $this->orderNumber)->first();

        $this->businessName = $this->model->billingAddress?->business_name;
        $this->BAfirstName = $this->model->billingAddress?->first_name;
        $this->BAlastName = $this->model->billingAddress?->last_name;
        $this->BAvatNumber = $this->model->billingAddress?->vat_number;
        $this->BAcountryID = $this->model->billingAddress?->country_id;
        $this->BAzipCode = $this->model->billingAddress?->zip_code;
        $this->BAcity = $this->model->billingAddress?->city;
        $this->BAaddress = $this->model->billingAddress?->address;
        if(!in_array($this->model->store,[0,1,2])){
            $this->shops = [];
        }else {
            $this->shops = Shop::query()->where('store_' . $this->model->store, Shop::STATUS_ACTIVE)->orderBy('title')->get();
        }
        if (($this->model->shipping_data['type'] ?? false) == 'shop')
            $this->SAshopID = $this->model->shipping_data['shop']['selected_shop']['id'];

        if ($this->model->shippingAddress) {
            $this->SAfirstName = $this->model->shippingAddress->first_name;
            $this->SAlastName = $this->model->shippingAddress->last_name;
            $this->SAvatNumber = $this->model->shippingAddress->vat_number;
            $this->SAcountryID = $this->model->shippingAddress->country_id;
            $this->SAzipCode = $this->model->shippingAddress->zip_code;
            $this->SAcity = $this->model->shippingAddress->city;
            $this->SAaddress = $this->model->shippingAddress->address;
            $this->SAcomment = $this->model->shippingAddress->comment;
        }



        if ($this->model->temp ?? false) {
            $this->editInProgress = true;
            $this->correctiveInProgress = true;
        }


        $productMovement = DB::table('product_movements')->whereIn('destination_type', [1,2])
            ->where(function ($query) {
                $query->where('destination_id', $this->model->id)->orWhere('comment_general', 'LIKE', '%'.$this->model->order_number.'%');
            })
            ->get();



        return view('livewire.orderpage', compact('productMovement'));
    }

    public function setOrderToEdit()
    {
        $this->model->status = Order::STATUS_NEW;
        $this->model->save();
    }

    public function openItemForEdit($id)
    {
        $this->editItem = OrderItem::find($id);
    }

    public function saveItem($id)
    {
        $this->editItem->total = $this->editItem->price * $this->editItem->quantity;
        OrderItem::find($id)->update($this->editItem->toArray());
        $this->calculateOrder();
        $this->editItem = null;
    }

    public function deleteItem($item_id)
    {
        $item = OrderItem::find($item_id);
        $item->delete();

        $this->calculateOrder();
    }

    public function calculateOrder()
    {
        $this->model->refresh();
        foreach ($this->model->orderItems as $item) {
            $total_price = ($total_price ?? 0) + $item->price;
            $total_original_price = ($total_original_price ?? 0) + $item->original_price;
            $total_quantity = ($total_quantity ?? 0) + $item->quantity;
            $total = ($total ?? 0) + $item->total;
        }
        $this->model->total_amount = $total + $this->model->shipping_fee + $this->model->payment_fee;
        $this->model->total_quantity = $total_quantity;
        $this->model->save();
    }

    public function createReceipt()
    {
        $this->model->refresh();
        $this->model->createReceipt();
    }

    public function updatedModelShippingMethodId($id)
    {
        $method = $this->shippingMethods->where('id', $id)->first();
        if(in_array($method->method_id,["home","sameday","dpd"])) {
            Address::firstOrCreate([
                'type' => 'shipping',
                'role' => 'order',
                'role_id' => $this->model->id,
                'entity_type' => 1,
            ]);
        }

        $this->model->shipping_method_id = $id;
        $this->model->shipping_fee = $method->{'fee_'.$this->model->store};
        $this->model->save();
        $this->editShipping = false;
        $this->calculateOrder();
    }

    public function updatedModelPaymentMethodId($id)
    {
        $this->model->payment_method_id = $id;
        $this->model->payment_fee = $this->paymentMethods->where('id', $id)->first()->{'fee_'.$this->model->store};
        $this->model->save();
        $this->editPayment = false;
        $this->calculateOrder();
    }

    public function updatedModelShippingFee($fee)
    {
        $this->model->shipping_fee = $fee;
        $this->model->save();
        $this->editShipping = false;
        $this->calculateOrder();
    }

    public function updatedBusinessName($name)
    {
        $this->model->billingAddress->business_name = $name;
        $this->model->billingAddress->save();
    }

    public function updatedBAfirstName($name)
    {
        $this->model->billingAddress->first_name = $name;
        $this->model->billingAddress->save();
    }

    public function updatedBAlastName($name)
    {
        $this->model->billingAddress->last_name = $name;
        $this->model->billingAddress->save();
    }

    public function updatedBAvatNumber($vat)
    {
        $this->model->billingAddress->vat_number = $vat;
        $this->model->billingAddress->save();
    }

    public function updatedBAcountryID($id)
    {
        $this->model->billingAddress->country_id = $id;
        $this->model->billingAddress->save();
    }

    public function updatedBAzipCode($id)
    {
        $this->model->billingAddress->zip_code = $id;
        $this->model->billingAddress->save();
    }
    public function updatedBAcity($id)
    {
        $this->model->billingAddress->city = $id;
        $this->model->billingAddress->save();
    }
    public function updatedBAaddress($id)
    {
        $this->model->billingAddress->address = $id;
        $this->model->billingAddress->save();
    }

    public function updatedSAfirstName($name)
    {
        $this->model->shippingAddress->first_name = $name;
        $this->model->shippingAddress->save();
    }

    public function updatedSAlastName($name)
    {
        $this->model->shippingAddress->last_name = $name;
        $this->model->shippingAddress->save();
    }

    public function updatedSAvatNumber($vat)
    {
        $this->model->shippingAddress->vat_number = $vat;
        $this->model->shippingAddress->save();
    }

    public function updatedSAcountryID($id)
    {
        $this->model->shippingAddress->country_id = $id;
        $this->model->shippingAddress->save();
    }

    public function updatedSAzipCode($id)
    {
        $this->model->shippingAddress->zip_code = $id;
        $this->model->shippingAddress->save();
    }
    public function updatedSAcity($id)
    {
        $this->model->shippingAddress->city = $id;
        $this->model->shippingAddress->save();
    }
    public function updatedSAaddress($id)
    {
        $this->model->shippingAddress->address = $id;
        $this->model->shippingAddress->save();
    }

    public function updatedSAcomment($id)
    {
        $this->model->shippingAddress->comment = $id;
        $this->model->shippingAddress->save();
    }

    public function updatedSAshopID($shop)
    {
        $this->model->shipping_data = [
            "type" => "shop",
            "shop" => [
                "selected_shop" => Shop::find($shop)
            ]
        ];
        $this->model->save();
        $this->editBillingAddress = false;
    }

    public function setBoxID($box)
    {
        logger($box);
        $point = PickUpPoint::query()->where('provider_id', $box)->first();
        $this->model->shipping_data = [
            "type" => "box",
            "box" => [
                "selected_box" => $point
            ]
        ];
        $this->model->boxprovider = $point->provider;
        $this->model->save();
        $this->editBillingAddress = false;
    }



    public function addNewProduct($id)
    {
        $product = Product::with('prices')->find($id);
        $this->model->orderItems()->create([
            'product_id' => $id,
            'price' => $product->price($this->model->store)?->price_list ?? 9999999,
            'original_price' => $product->price($this->model->store)?->price_list_original ?? 9999999,
            'quantity' => 1,
            'total' => $product->price($this->model->store)?->price_list ?? 9999999,
        ]);
        $this->calculateOrder();
    }

    public function startCorrective()
    {
        $this->editInProgress = true;
        $this->correctiveInProgress = true;

        foreach ($this->model->orderItems as $item) {
            $temp[$item->id]['quantity'] = $item->quantity;
            $temp[$item->id]['product_id'] = $item->product->id;
            $temp[$item->id]['price'] = $item->price;
        }

        $this->model->temp = json_encode($temp);
        $this->model->save();
    }

    public function createCorrective()
    {
        $this->editInProgress = false;
        $this->correctiveInProgress = false;

        foreach ($this->model->orderItems as $item) {
            $temp[$item->id]['quantity'] = $item->quantity;
            $temp[$item->id]['product_id'] = $item->product->id;
            $temp[$item->id]['price'] = $item->price;
        }

        $all['before'] = json_decode($this->model->temp, true);
        $all['now'] = json_decode(json_encode($temp), true);

        foreach ($all['now'] as $iId => $item) {
            if (isset($all['before'][$iId])) {
                if ($item['quantity'] != $all['before'][$iId]['quantity']) {
                    $item['quantity'] = $item['quantity'] - $all['before'][$iId]['quantity'];
                    $all['new'][$iId] = $item;
                }
            } else {
                $all['new'][$iId] = $item;
            }
        }
        foreach ($all['before'] as $iId => $item) {
            if (!isset($all['now'][$iId])) {
                $item['quantity'] = $item['quantity'] - (2 * $item['quantity']);
                $all['new'][$iId] = $item;
            }
        }
        if (!($all['new'] ?? false)) {
            dd('Nem észlelhető módosítás');
        }

        //helyesbito szamla
        $this->model->createCorrectiveInvoice($all['new']);
        //raktárkészlet kezelés
        $this->model->updateStock(false, $all['new']);

        $this->model->temp = '';
        $this->model->save();
    }

    public function hydrate()
    {
        $this->emit('select2');
    }
}
