<?php

namespace Alomgyar\Product_movements;

use Alomgyar\Customers\Customer;
use Alomgyar\Products\Product;
use Alomgyar\Shops\Shop;
use Alomgyar\Suppliers\Supplier;
use Alomgyar\Warehouses\Warehouse;
use App\Order;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductMovement extends Model
{
    use LogsActivity;
    use SoftDeletes;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    //0 - raktárak közötti mozgatás, 1 - webshop eladásból fakadó, 2 - bolti eladásból fakadó, 3 - beszerzés, 4 - leltár, 5 - egyéb
    const DESTINATION_TYPE_BETWEEN_WAREHOUSES = 0;

    const DESTINATION_TYPE_WEBSHOP_ORDER = 1;

    const DESTINATION_TYPE_SHOP_ORDER = 2;

    const DESTINATION_TYPE_ACQUISITION = 3;

    const DESTINATION_TYPE_INVENTORY_CONTROL = 4;

    const DESTINATION_TYPE_VOID = 5;

    const DESTINATION_TYPE_MERCHANT = 6;

    protected $table = 'product_movements';

    protected $fillable = [
        'reference_nr',
        'causer_type',
        'causer_id',
        'source_type',
        'source_id',
        'destination_type',
        'destination_id',
        'comment_void',
        'comment_general',
        'comment_bottom',
        'is_canceled',
    ];

    protected $casts = [
        'is_canceled' => 'boolean',
    ];

    protected static $logAttributes = ['*'];

    public static $messages = [
        'required' => 'A :attribute kitöltése kötelező!',
        'email.required' => 'Az :attribute kitöltése kötelező!',
        'max' => 'Nem lehet hosszabb, mint :max karakter!',
        'alpha_num' => 'Csak az ABC betűit és számokat használhatod!',
        'alpha' => 'Csak az ABC betűit használhatod!',
        'string' => 'Csak az ABC betűit használhatod!',
        'min' => 'Nem lehet kevesebb, mint :min karakter!',
        'date' => 'Nem megfelelő dátum formátum!',
        'numeric' => 'Csak szám lehet!',
        'integer' => 'Csak szám lehet!',
        'email' => 'Nem megfelelő formátum!',
        'unique' => 'Ezzel az email címmel már regisztráltak!',
        'phone.regex' => 'Nem megfelelő formátum!',
    ];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
            : $query
                ->select('product_movements.id', 'product_movements.reference_nr', 'product_movements.causer_type', 'product_movements.causer_id', 'product_movements.source_id', 'product_movements.source_type', 'product_movements.destination_id', 'product_movements.destination_type', 'product_movements.comment_general', 'product_movements.is_canceled', 'product_movements.created_at'/*, 'product.title', 'product.isbn'*/)
                ->leftJoin('product_movements_items', function ($join) {
                    $join->on('product_movements.id', '=', 'product_movements_items.product_movements_id');
                })
                ->leftJoin('product', function ($join) {
                    $join->on('product.id', '=', 'product_movements_items.product_id');
                })
                ->leftJoin('suppliers', function ($join) {
                    $join->on('suppliers.id', '=', 'product_movements.source_id');
                })
                ->leftJoin('warehouse', function ($join) {
                    $join->on('warehouse.id', '=', 'product_movements.source_id');
                })
                ->leftJoin('shops', function ($join) {
                    $join->on('shops.id', '=', 'product_movements.source_id');
                })
                ->orWhere('destination_type', 'like', '%'.$term.'%')
                ->orWhere('suppliers.title', 'like', '%'.$term.'%')
                ->orWhere('warehouse.title', 'like', '%'.$term.'%')
                ->orWhere('shops.title', 'like', '%'.$term.'%')
                ->orWhere('product.title', 'like', '%'.$term.'%')
                ->orWhere('product.isbn', 'like', '%'.$term.'%')
                ->orWhere('product_movements.reference_nr', 'like', '%'.$term.'%')
                ->groupBy('product_movements.id');
    }

    public function getStatusHtmlAttribute()
    {
        return $this->status ?
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-success-600" title="Aktív"><i class="icon-switch"></i></span></a>' :
            '<a href="javascript:;" wire:click="changeStatus('.$this->id.')"><span class="badge badge-flat text-danger-600" title="Inaktív"><i class="icon-switch"></i></span></a>';
    }

    public function productItems()
    {
        return $this->hasMany(ProductMovementItems::class, 'product_movements_id', 'id');
    }

    public function source()
    {
        return match ($this->source_type) {
            'supplier' => $this->hasOne(Supplier::class, 'id', 'source_id'),
            'warehouse', 'other', 'merchant' => $this->hasOne(Warehouse::class, 'id', 'source_id'),
            'storno' => $this->stornoSourceType(),
            default => $this->hasOne(Shop::class, 'id', 'source_id'),
        };
    }

    private function stornoSourceType()
    {
        return match ($this->destination_type) {
            self::DESTINATION_TYPE_BETWEEN_WAREHOUSES,
            self::DESTINATION_TYPE_ACQUISITION,
            self::DESTINATION_TYPE_INVENTORY_CONTROL,
            self::DESTINATION_TYPE_VOID,
            self::DESTINATION_TYPE_MERCHANT => $this->hasOne(Warehouse::class, 'id', 'source_id'),
            default => $this->hasOne(Order::class, 'id', 'source_id')
        };
    }

    public function destination()
    {
        return match ($this->destination_type) {
            self::DESTINATION_TYPE_BETWEEN_WAREHOUSES,
            self::DESTINATION_TYPE_INVENTORY_CONTROL,
            self::DESTINATION_TYPE_VOID,
            self::DESTINATION_TYPE_MERCHANT => $this->hasOne(Warehouse::class, 'id', 'destination_id'),
            self::DESTINATION_TYPE_ACQUISITION => $this->source_type === 'storno' ?
                $this->hasOne(Supplier::class, 'id', 'destination_id') :
                $this->hasOne(Warehouse::class, 'id', 'destination_id'),
            default => $this->hasOne(Order::class, 'id', 'destination_id')
        };
    }

    // 0 - raktárak közötti mozgatás, 1 - webshop eladásból fakadó, 2 - bolti eladásból fakadó, 3 - beszerzés, 4 - leltár
    public function getDestinationTitleAttribute()
    {
        return match ($this->destination_type) {
            self::DESTINATION_TYPE_BETWEEN_WAREHOUSES, self::DESTINATION_TYPE_ACQUISITION, self::DESTINATION_TYPE_MERCHANT => Warehouse::where('id', $this->destination_id)->first(),
            self::DESTINATION_TYPE_WEBSHOP_ORDER => (object) ['title' => 'Webshop'],
            self::DESTINATION_TYPE_SHOP_ORDER => Shop::where('id', $this->destination_id)->first(),
            self::DESTINATION_TYPE_VOID => (object) ['title' => 'Egyéb'],
            default => (object) ['title' => 'Leltár'],
        };
    }

    public static function getPrefixAttribute($destination_type, $destination_id)
    {
        return match ($destination_type) {
            self::DESTINATION_TYPE_BETWEEN_WAREHOUSES, self::DESTINATION_TYPE_ACQUISITION => Warehouse::where('id', $destination_id)->first()->invoice_prefix ?? 'RA',
            self::DESTINATION_TYPE_WEBSHOP_ORDER, self::DESTINATION_TYPE_SHOP_ORDER => strtoupper(
                Order::$barionPrefix[Order::select('store')->where('id', $destination_id)->first()->store ?? 'NA'] ?? 'NA'),
            self::DESTINATION_TYPE_MERCHANT => 'PAM',
            default => 'NA',
        };
    }

    public function causer()
    {
        return match ($this->causer_type) {
            'App\User' => $this->hasOne(User::class, 'id', 'causer_id'),
            'Customer' => $this->hasOne(Customer::class, 'id', 'causer_id'),
        };
    }

    public function getTypeAttribute()
    {
        return match ($this->destination_type) {
            self::DESTINATION_TYPE_BETWEEN_WAREHOUSES => 'Raktárak közötti',
            self::DESTINATION_TYPE_WEBSHOP_ORDER => 'Eladás (webshop)',
            self::DESTINATION_TYPE_SHOP_ORDER => 'Eladás (bolt)',
            self::DESTINATION_TYPE_ACQUISITION => 'Beszerzés',
            self::DESTINATION_TYPE_VOID => 'Egyéb',
            self::DESTINATION_TYPE_MERCHANT => 'Kereskedői',
            default => 'Leltár',
        };
    }

    public function getQuantityAttribute()
    {
        if ($this->stock_in > 0) {
            return '<strong class="text-success">+ '.$this->stock_in.' db</strong>';
        } else {
            return '<strong class="text-danger">- '.$this->stock_out.' db</strong>';
        }
    }

    public static function generateReferenceNr()
    {
        $latestRefNr = ProductMovement::orderBy('id', 'DESC')->first()?->reference_nr;
        $lastNr = intval(Str::after(Str::before($latestRefNr, '/'), '-') ?? '000001');
        if (Str::after($latestRefNr, '/') != now()->format('Y')) {
            $lastNr = 0;
        }
        $newNr = $lastNr + 1;

        return str_pad($newNr, 6, '0', STR_PAD_LEFT).'/'.now()->format('Y');
    }

    public static function createByReferenceNumber($data)
    {
        $model = ProductMovement::firstOrNew(['reference_nr' => $data->reference_nr]);
        $model->reference_nr = self::getPrefixAttribute($data->destination_type, $data->destination_id).'-'.$model->reference_nr;
        $model->causer_type = $data->causer_type;
        $model->causer_id = $data->causer_id;
        $model->source_type = $data->source_type;
        $model->source_id = $data->source_id;
        $model->destination_type = $data->destination_type;
        $model->destination_id = $data->destination_id;
        $model->comment_void = $data->comment_void;
        $model->comment_general = $data->comment_general ?? null;
        $model->comment_bottom = $data->comment_bottom ?? null;
        $model->created_at = isset($data->created_at) ? Carbon::parse($data->created_at) : now();
        $model->save();

        return $model;
    }

    public static function addItems($model, $data = [])
    {
        foreach ($data as $k => $d) {
            if (! isset($d['created_at'])) {
                $d['created_at'] = now();
            } else {
                $d['created_at'] = Carbon::parse($d['created_at']);
            }

            $d['updated_at'] = now();
            $data[$k] = $d;
        }
        $model->productItems()->insert($data);
    }

    public static function getSelectedProductMovementsInThisPeriod($allTotalSalesInThisPeriod, $startDate, $endDate)
    {
        //        $pam = DB::select(DB::raw("select `id` from `suppliers` where `title` LIKE '%Publish and More%'"));
        //
        //        if ($onlyPam) {
        //            return DB::select(DB::raw("
        //                select `product_movements`.`id`
        //                from `product_movements` where exists (
        //                    select * from `product_movements_items` where `product_movements`.`id` = `product_movements_items`.`product_movements_id`
        //                    and `product_id` in (" . $allTotalSalesInThisPeriod->join(', ') . ")
        //                    and `product_movements_items`.`deleted_at` is null
        //                    and (`product_movements_items`.`remaining_quantity_from_report` is null OR `product_movements_items`.`remaining_quantity_from_report` > 0)
        //                )
        //                and `source_type` != 'storno' and `is_canceled` = 0
        //                and `destination_type` = 3
        //                and `source_id` = " . $pam[0]->id . "
        //                and `product_movements`.`deleted_at` is null;
        //            "));
        //        }

        return DB::select(DB::raw("
            select `product_movements`.`id`
            from `product_movements` where exists (
                select * from `product_movements_items` where `product_movements`.`id` = `product_movements_items`.`product_movements_id`
                and `created_at` between '".$startDate."' and '".$endDate."'
                and `product_id` in (".$allTotalSalesInThisPeriod->join(', ').")
                and (`remaining_quantity_from_report` is null or `remaining_quantity_from_report` > 0)
                and `product_movements_items`.`deleted_at` is null
            )
            and `source_type` != 'storno' and `is_canceled` = 0
            and `destination_type` = ".ProductMovement::DESTINATION_TYPE_ACQUISITION.'
            and `product_movements`.`deleted_at` is null;
        '));
    }

    public static function getAuthorSelectedProductMovementsInThisPeriod($allTotalSalesInThisPeriod, $startDate, $endDate): array
    {
        return DB::select(DB::raw("
            select `product_movements`.`id`
            from `product_movements` 
            where exists (
                select * from `product_movements_items` where `product_movements`.`id` = `product_movements_items`.`product_movements_id`
                and `created_at` between '".$startDate."' and '".$endDate."'
                and `product_id` in (".$allTotalSalesInThisPeriod->join(', ').')
                and `product_movements_items`.`deleted_at` is null
            )
            and `product_movements`.`deleted_at` is null;
        '));
    }
}
