<?php

namespace Alomgyar\Posts;

class ApiPost extends Post
{
    const PER_PAGE = 12;

    public function scopeByStore($query)
    {
        return $query->where('store_'.request('store'), 1);
    }

    public function scopeSearch($query, $term)
    {
        return empty($query) ? $query
            : $query->where('title', 'like', '%'.$term.'%')
                    ->orWhere('lead', 'like', '%'.$term.'%')
                    ->orWhere('body', 'like', '%'.$term.'%');
    }
}
