<?php

namespace App\Services;

use App\OptimizedImage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class ImageService
{
    /**
     * @param  UploadedFile  $file
     */
    public function generateThumbnails($folder, string $name, string $path, $thumbSizes = []): void
    {
        // imageSize => imageQuality
        $sizeList = ['256' => 85];
        $image = Image::make($path);

        foreach ($sizeList as $imageSize => $quality) {
            if ($image->width() < $imageSize) {
                continue;
            }

            if (! file_exists(storage_path().'/app/public/'.$folder)) {
                mkdir(storage_path().'/app/public/'.$folder, 0777, true);
            }
            $folder = rtrim($folder, '/');
            $image->resize((int) $imageSize, null, function ($constraint) {
                $constraint->aspectRatio();
            })
                ->save(storage_path().'/app/public/'.$folder.'/'.$name.'_'.$imageSize.'.webp', $quality, 'webp');
        }

        OptimizedImage::create(['file_name' => $path]); // Log image to avoid optimizing in the future when optimized successfully
    }

    public function saveAsWebp(string $path): void
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $name = rtrim($path, $ext);
        $img = Image::make($path);

        if (! file_exists($name.'webp')) {
            $img->encode('webp', 80)->save($name.'webp');
        }
    }
}
