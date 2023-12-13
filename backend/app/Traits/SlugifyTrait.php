<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SlugifyTrait
{
    public function slugify($text)
    {
        // remove ? mark from string
        $text = Str::slug($text);
        $slug = preg_replace('/\?/u', ' ', trim($text));
        $slug = preg_replace('/\s+/u', '-', trim($slug));

        // slug repeat check
        $latest = $this->whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$'")
                       ->latest('id')
                       ->value('slug');

        if ($latest) {
            $pieces = explode('-', $latest);
            $number = intval(end($pieces));
            $slug .= '-'.($number + 1);
        }

        return mb_strtolower($slug);
    }
}

/*
How to use:
Place it in model:

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->slug = $model->slugify($model->name);
        });
        static::updating(function($model) {
            $model->slug = $model->slugify($model->name);
        });
   }
*/
