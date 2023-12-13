<?php

namespace Alomgyar\Products;

use Livewire\Component;

class PriceComponent extends Component
{
    public $init = true;

    protected $listeners = ['calculate'];

    public $store;

    public $productid;

    public $prices;

    public function render()
    {
        $this->init = false;
        $productstore = Product::find($this->productid);
        $this->prices = [
            'price_sale' => $productstore->price($this->store)->price_sale ?? false,
            'price_list' => $productstore->price($this->store)->price_list ?? false,
            'discount_percent' => $productstore->price($this->store)->discount_percent ?? false,
        ];

        return view('products::components.price');
    }

    public function calculate()
    {
        $product = Product::find($this->productid);

        $price_sale = $product->price($this->store)->price_sale_original;
        if ($price_sale == 0 || $price_sale > $product->price($this->store)->price_list_original) {
            $price_sale = $product->price($this->store)->price_list_original;
        }
        foreach ($product->promotions as $promotion) {
            if ($promotion->{'store_'.$this->store} == 1) {
                $prom_sale_price = $promotion->price($this->productid)->{'price_sale_'.$this->store};
                if (($prom_sale_price ?? false) && $prom_sale_price != 0 && $prom_sale_price < $price_sale) {
                    $price_sale = $prom_sale_price;
                }
            }
        }

        $this->prices = [
            'price_list' => $product->price($this->store)->price_list_original,
            'price_sale' => $price_sale,
            'discount_percent' => 100 - (($price_sale / $product->price($this->store)->price_list_original) * 100),
        ];
        $product->price($this->store)->update($this->prices);
    }
}
