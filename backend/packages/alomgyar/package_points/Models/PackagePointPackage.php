<?php

namespace Alomgyar\PackagePoints\Models;

use Alomgyar\PackagePoints\Filter\PackagePointFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PackagePointPackage extends Model
{
    use HasFactory;

    protected $table = 'package_points_packages';

    protected $fillable = [
        'code',
        'partner_id',
        'shop_id',
        'customer',
        'email',
        'mail_sent_at',
        'collected',
        'status',
    ];

    public function shop()
    {
        return $this->hasOne(PackagePointShop::class, 'id', 'shop_id');
    }

    public function partner()
    {
        return $this->hasOne(PackagePointPartner::class, 'id', 'partner_id');
    }

    public function scopeFilter(Builder $builder, Request $request)
    {
        return (new PackagePointFilter($request))->filter($builder);
    }
}
