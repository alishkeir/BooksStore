<?php

namespace Alomgyar\Promotions;

use Alomgyar\Products\Product;
use Alomgyar\Products\ProductPrice;
use Livewire\Component;

class PromotionComponent extends Component
{
    public $promotion_id;

    public $promotion;

    public $showSelectedProducts = false;

    public $setProductId;

    public $alomgyarPrice = 0;

    public $olcsokonyvekPrice = 0;

    public $nagykerPrice = 0;

    public $selectedProduct;

    public $errorMessage = '';

    protected $listeners = ['show', 'setProductId'];

    public function render()
    {
        //parent promotion
        $this->promotion = Promotion::find($this->promotion_id);

        //get the actual products to selection
        $selected = PromotionPrice::where('promotion_id', $this->promotion_id)->get();
        $count = $selected->count();

        return view('promotions::components.products', [
            'selected' => $selected,
            'count' => $count,

        ]);
    }

    public function show()
    {
        $this->showSelectedProducts = true;
    }

    public function removeProduct($productId)
    {
        $deletePromotionPrice = PromotionPrice::where('promotion_id', $this->promotion_id)->where('product_id', $productId)->delete();
    }

    public function setProductId($id)
    {
        $productPrice = ProductPrice::where('product_id', $id)->get();

        $this->selectedProduct = Product::query()
            ->select('id', 'title', 'isbn')
            ->where('id', $id)
            ->first();

        $this->alomgyarPrice = $productPrice->where('store', 0)->first()->price_list ?? 10000;
        $this->olcsokonyvekPrice = $productPrice->where('store', 1)->first()->price_list ?? 10000;
        $this->nagykerPrice = $productPrice->where('store', 2)->first()->price_list ?? 10000;

        $this->dispatchBrowserEvent('restartSelect2');
    }

    public function addNewProductToList()
    {
        $validation = true;
        if ($this->alomgyarPrice == 0 && $this->olcsokonyvekPrice == 0 && $this->nagykerPrice == 0) {
            $this->errorMessage = 'Nincs megadva ár!';
            $validation = false;
        }
        if (! $this->selectedProduct) {
            $this->errorMessage = 'Nincs kiválasztva termék!';
            $validation = false;
        }

        if ($validation) {
            $addPromotionPrice = PromotionPrice::updateOrCreate([
                'promotion_id' => $this->promotion_id,
                'product_id' => $this->selectedProduct->id,
            ], [
                'price_sale_0' => $this->alomgyarPrice,
                'price_sale_1' => $this->olcsokonyvekPrice,
                'price_sale_2' => $this->nagykerPrice,
            ]);
            $this->resetFields();
        }

        $this->dispatchBrowserEvent('restartSelect2');
    }

    public function resetFields()
    {
        $this->errorMessage = '';
        $this->selectedProduct = null;
        $this->setProductId = null;
        $this->alomgyarPrice = 0;
        $this->olcsokonyvekPrice = 0;
        $this->nagykerPrice = 0;
    }
}
