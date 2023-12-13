<?php

namespace Alomgyar\Synchronizations;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Synchronization extends Model
{
    use LogsActivity;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    protected $table = 'synchronizations';

    protected $fillable = [
        'title',
        'section',
        'details',
    ];

    protected static $logAttributes = ['*'];

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
        : $query->where('id', 'like', '%'.$term.'%')
            ->orWhere('title', 'like', '%'.$term.'%');
    }
}
