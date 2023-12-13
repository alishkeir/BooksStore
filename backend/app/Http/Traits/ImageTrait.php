<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    public function getOptimizedImage(string $image, string $size = ''): array
    {
        // HANDLE IF IMAGE IS NULL
        if (! $image) {
            return [];
        }

        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $name = rtrim($image, '.'.$ext);

        if (file_exists(storage_path('app/public/'.$name.$size.'.webp'))) {
            return [$this->addUrl($name.$size.'.webp'), $this->addUrl($image)];
        }

        return [$this->addUrl($image)];
    }

    /**
     * @return mixed|string
     */
    public function addUrl($image): mixed
    {
        return strpos($image, '://') !== false ? $image : env('BACKEND_URL').Storage::url($image);
    }
}
