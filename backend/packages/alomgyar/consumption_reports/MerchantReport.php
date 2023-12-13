<?php

namespace Alomgyar\Consumption_reports;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class MerchantReport extends Model
{
    use LogsActivity;

    protected $table = 'merchant_reports';

    protected $fillable = [
        'warehouse_id',
        'merchant_name',
        'merchant_email',
        'quantity',
        'total_amount',
        'invoice_url',
        'comment',
        'created_by',
    ];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
        : $query->where('id', 'like', '%'.$term.'%')
            ->orWhere('merchant_name', 'like', '%'.$term.'%')
            ->orWhere('merchant_email', 'like', '%'.$term.'%')
            ->orWhere('invoice_url', 'like', '%'.$term.'%')
            ->orWhere('comment', 'like', '%'.$term.'%');
    }
}
