<?php

namespace Skvadcom\Logs;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'activity_log';

    protected $fillable = [
        'log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'properties',
    ];

    // public function getCreatedAttribute()
    // {
    //     return User::find($this->causer_id)->name ?? 'Vendég';
    // }

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
        : $query->where('id', 'like', '%'.$term.'%')
            ->orWhere('log_name', 'like', '%'.$term.'%')
            ->orWhere('description', 'like', '%'.$term.'%')
            ->orWhere('subject_id', 'like', '%'.$term.'%')
            ->orWhere('subject_type', 'like', '%'.$term.'%')
            ->orWhere('causer_id', 'like', '%'.$term.'%')
            ->orWhere('causer_type', 'like', '%'.$term.'%')
            ->orWhere('properties', 'like', '%'.$term.'%');
    }
}
