<?php

namespace Alomgyar\Customers;

use Alomgyar\Authors\Author;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomerAuthor extends Model
{
    use LogsActivity, HasFactory;

    protected $table = 'customer_authors';

    protected $fillable = [
        'customer_id',
        'author_id',
    ];

    protected static $logAttributes = ['*'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
