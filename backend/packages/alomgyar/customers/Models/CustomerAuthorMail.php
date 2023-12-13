<?php

namespace Alomgyar\Customers\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAuthorMail extends Model
{
    protected $fillable = [
        'customer_id',
        'author_id',
        'product_id',
    ];
}
