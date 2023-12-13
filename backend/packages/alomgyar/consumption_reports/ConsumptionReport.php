<?php

namespace Alomgyar\Consumption_reports;

use Alomgyar\Products\Product;
use Alomgyar\Suppliers\Supplier;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Traits\LogsActivity;

class ConsumptionReport extends Model
{
    use LogsActivity;

    protected static ?Collection $report;

    protected $table = 'consumption_reports';

    protected $fillable = [
        'period',
        'number_of_books',
        'number_of_suppliers',
        'link_to_report',
        'link_to_author_report',
        'link_to_copyright_report',
    ];

    protected $casts = [
        'link_to_report' => AsCollection::class,
        'link_to_author_report' => AsCollection::class,
        'link_to_copyright_report' => AsCollection::class,
    ];

    protected static $logAttributes = ['*'];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
            : $query->where('id', 'like', '%'.$term.'%')
                    ->orWhere('title', 'like', '%'.$term.'%');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
