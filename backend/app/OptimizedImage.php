<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptimizedImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'file_name';

    protected $fillable = ['file_name'];
}
