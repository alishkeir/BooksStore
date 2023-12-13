<?php

namespace Alomgyar\Synchronizations;

use Illuminate\Database\Eloquent\Model;

class OldAuthor extends Model
{
    protected $table = 'old_author';

    protected $fillable = [
        'name',
    ];

    protected static $logAttributes = ['*'];
}
