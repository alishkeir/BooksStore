<?php

namespace Alomgyar\Products;

use Alomgyar\Products\Jobs\CreateFlashSalePromotionJob;
use Alomgyar\Promotions\Promotion;
use Alomgyar\Promotions\Scopes\NotShowFlashDealScope;
use App\Services\FlashDealToPromotionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FlashComponent extends Component
{
    //protected $listeners = ['runFlashPromotion'];
    public $source = false;

    public $target = false;

    public $store = false;

    public $done = false;

    public $fromDate;

    public $toDate;

    public $count = 0;

    public $stores = ['alom' => 0, 'olcso' => 1, 'nagyker' => 2];

    public $updateId = 0;

    public $newToDate;

    public $dateUpdated = false;

    public $cantUpdate = false;

    public $cantUpdateText = '';

    public function mount()
    {
        $this->fromDate = Carbon::now()->format(config('pamadmin.input-date-format'));
        $this->toDate = Carbon::now()->addDay()->format(config('pamadmin.input-date-format'));
    }

    protected $rules = [
        'source' => 'required',
        'target' => 'required',
        'stores' => 'required',
        'fromDate' => 'required',
        'toDate' => 'required',
    ];

    public function render()
    {
        if (($this->source ?? false) && ($this->store ?? false) && $this->store != 'Válassz') {
            $count = DB::select(DB::raw('
            SELECT COUNT(pp.id) as cnt
            FROM `product_price` as pp
            JOIN product as p ON p.id = pp.product_id
            WHERE pp.store='.$this->stores[$this->store].' AND p.status = 1 AND p.type = 0 AND p.state = 0 AND p.is_new != 1
            AND pp.discount_percent = '.$this->source.'
            GROUP BY pp.store
                '));
            $this->count = $count[0]->cnt ?? 0;
        }

        $currentFlashDeals = Promotion::query()
        ->withoutGlobalScope(NotShowFlashDealScope::class)
        ->flashDeals()
        ->active()
        ->get();

        return view('products::components.flashcomponent', [
            'currentFlashDeals' => $currentFlashDeals,
        ]);
    }

    public function runFlashPromotion()
    {
        $this->validate();

        // ORIGINAL QUERY

        // $sql = "UPDATE `product_price` pp
        // LEFT JOIN product as p ON p.id = pp.product_id
        // SET pp.discount_percent = ".$this->target.",
        // pp.price_sale = ROUND(pp.price_list / 100 * (100 - ".$this->target.")),
        // pp.price_sale_original = ROUND(pp.price_list / 100 * (100 - ".$this->target."))
        // WHERE pp.store=".$this->stores[$this->store]." AND p.status = 1 AND p.type = 0 AND p.state = 0 AND p.is_new != 1
        // AND pp.discount_percent = ".$this->source;
        // DB::statement($sql);

        // ADDED LOGIC
        CreateFlashSalePromotionJob::dispatch($this->source, $this->target, $this->stores[$this->store], $this->fromDate, $this->toDate, auth()->id());
        $this->reset();
        $this->fromDate = Carbon::now()->format(config('pamadmin.input-date-format'));
        $this->toDate = Carbon::now()->addDay()->format(config('pamadmin.input-date-format'));
        $this->done = true;
    }

    public function deleteFlashPromotion($promotionId)
    {
        $promotion = Promotion::withoutGlobalScopes()->where('id', $promotionId)->first();
        $promotion->status = Promotion::STATUS_INACTIVE;
        $promotion->save();
        $this->done = true;
    }

    public function updateFlashPromotionToDate()
    {
        $promotion = Promotion::withoutGlobalScopes()->where('id', $this->updateId)->first();
        if (! $this->newToDate) {
            $this->cantUpdate = true;
            $this->cantUpdateText = 'Adj meg a dátumot.';

            return;
        }

        if ($this->newToDate < Carbon::parse($promotion->active_from)->addMinutes(5)->format(config('pamadmin.input-date-format'))) {
            $this->cantUpdate = true;
            $this->cantUpdateText = 'Adj meg az kezdésnél távolabbi dátumot.';

            return;
        }

        $promotion = (new FlashDealToPromotionService)->updateEndDate($promotion, $this->newToDate);

        $this->dateUpdated = true;
    }

    public function setUpdateId($promotionId)
    {
        $this->updateId = $promotionId;
        $this->newToDate = Carbon::parse(Promotion::withoutGlobalScopes()->where('id', $promotionId)->first()->active_to)->format(config('pamadmin.input-date-format'));
    }
}
