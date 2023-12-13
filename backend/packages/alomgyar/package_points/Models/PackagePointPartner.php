<?php

namespace Alomgyar\PackagePoints\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePointPartner extends Model
{
    protected $table = 'package_points_partners';

    protected $fillable = [
        'id',
        'name',
        'link',
        'email',
        'phone',
    ];
}
