<?php

namespace Alomgyar\Synchronizations;

use Illuminate\Database\Eloquent\Model;

class OldProduct extends Model
{
    protected $table = 'old_product';

    protected $fillable = [
        'title',
        'section',
        'details',
    ];

    protected static $logAttributes = ['*'];
}
