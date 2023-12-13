<?php

namespace Alomgyar\Affiliates;

use Alomgyar\Customers\Customer;
use App\Traits\ActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory, ActiveScopeTrait;

    protected $fillable = [
        'name',
        'country',
        'zip',
        'city',
        'address',
        'vat',
        'code',
        'status',
        'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
