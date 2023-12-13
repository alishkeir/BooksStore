<?php

namespace Alomgyar\PackagePoints\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePointShop extends Model
{
    protected $table = 'package_points_shops';

    protected $fillable = [
        'id',
        'name',
        'address',
        'open',
        'email',
        'phone',
        'store_0',
        'store_1',
        'store_2',
    ];
}
