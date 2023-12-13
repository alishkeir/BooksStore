<?php

namespace Alomgyar\Synchronizations;

use Illuminate\Database\Eloquent\Model;

class OldImage extends Model
{
    protected $table = 'old_image';

    protected $fillable = [
        'file',
    ];

    protected static $logAttributes = ['*'];
}
