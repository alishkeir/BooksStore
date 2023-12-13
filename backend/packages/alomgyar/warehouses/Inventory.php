<?php

namespace Alomgyar\Warehouses;

use Alomgyar\Products\Events\ProductStateChangedEvent;
use Alomgyar\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Inventory extends Model
{
    use LogsActivity;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    protected $table = 'inventories';

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'status',
        'stock',
    ];

    protected static $logAttributes = ['*'];

    /**
     * Default value of status
     *
     * @var int[]
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
            : $query->where('id', 'like', '%'.$term.'%')
                    ->orWhere('stock', 'like', '%'.$term.'%');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public static function updateInventory($data = [], $storno = false)
    {
        if (empty($data)) {
            return false;
        }
        $inventory = $fromInventory = null;
        foreach ($data as $item) {
            if (isset($item['source_id'])) {
                $fromInventory = Inventory::firstOrNew(
                    [
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $item['source_id'],
                    ],
                );
                $fromInventory->stock = $fromInventory->stock - ($item['stock_in'] == 0 ? $item['stock_out'] : $item['stock_in']);
                $fromInventory->save();
            }

            if (isset($item['destination_id'])) {
                // ADDING NEW QUANTITY TO THE STOCK
                $inventory = Inventory::firstOrNew(
                    [
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $item['destination_id'],
                    ],
                );
                $inventory->stock = $inventory->stock + $item['stock_in'];

                // // IF ITS NOT STORNO
                // if (!$storno) {
                //     $inventory->stock = $inventory->stock + $item['stock_in'];
                // }

                // // IF ITS STORNO
                // if ($storno) {
                //     // CHECKING QUANTITIES, NOT TO MOVE UNDER 0
                //     if ($inventory->stock >= $item['stock_in']) {
                //         $inventory->stock = $inventory->stock - $item['stock_in'];
                //     }

                //     if ($inventory->stock < $item['stock_in']) {
                //         $inventory->stock = 0;
                //     }
                // }

                $inventory->save();
            }
        }

        ProductStateChangedEvent::dispatch($data);

        return compact('fromInventory', 'inventory');
    }
}
