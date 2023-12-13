<?php

namespace Alomgyar\Synchronizations;

use Illuminate\Database\Eloquent\Model;

class OldPublisher extends Model
{
    protected $table = 'old_publisher';

    protected $fillable = [
        'name',
    ];

    protected static $logAttributes = ['*'];
}
