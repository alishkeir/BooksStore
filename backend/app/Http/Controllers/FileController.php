<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

//use Intervention\Image\ImageManagerStatic as Image;

class FileController
{
    /**
     * Entry image upload handler
     *
     * @return json
     */
    public function upload(Request $request)
    {
        $params = $request->all();
        $extraPath = $request->path;

        if (isset($params['base64'])) {
            $image = $params['base64'];
            $imageInfo = explode(';base64,', $image);
            $imgExt = str_replace('data:image/', '', $imageInfo[0]);
            $image = str_replace(' ', '+', $imageInfo[1]);
            $imageName = 'image-'.time().'.'.$imgExt;

            Storage::disk('local')->put('public/'.$extraPath.$imageName, base64_decode($image));

            $data = getimagesize(Storage::disk('local')->path('public/'.$extraPath.$imageName));
            $width = $data[0];
            $height = $data[1];

            $response = [
                'rawurl' => $imageName,
                'url' => url('image/'.$width.'/'.$height.'/'.$extraPath.$imageName),
                'message' => 'AAASikeres fájl feltöltés!',
            ];

            return $response;
        }

        if (is_array($params['file'])) {
            $messages = [
                'file.*.mimes' => 'Nem megfelelő fájltípus',
            ];

            $attributes = [
                'file.*' => 'fájl',
            ];

            $validatedData = $request->validate([
                'file.*' => 'file|mimes:pdf,csv,txt,xls,xlsx,bmp,gif,jpeg,jpg,jpe,png,svg,tiff,tif,webp,JPG',
            ], $messages, $attributes);

            foreach ($params['file'] as $file) {
                $uploadedFile = $file;

                $fileSize = $uploadedFile->getSize();

                if ($fileSize > 10485760) {
                    return ['error' => 'A file mérete nem lehet nagyobb 10 MB-nál'];
                }
                $filename = Str::slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME), '-');
                $ext = strtolower(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION));
                if ($ext == 'jpeg') {
                    $ext = 'jpg';
                }

                $uploader = Storage::disk('local')->putFileAs(
                    'public/'.$extraPath,
                    $uploadedFile,
                    $filename.'.'.$ext
                );
                $url = URL::to('/').Storage::url($extraPath.$filename.'.'.$ext);
                $path = Storage::disk('local')->path('public/'.$extraPath.$filename.'.'.$ext);

                $response[] = [
                    'rawurl' => $url,
                    'url' => $extraPath.$filename.'.'.$ext,
                    'message' => $filename.' AAA feltöltése sikeres!!',
                ];
            }
        } else {
            $messages = [
                'file.mimes' => 'Nem megfelelő fájltípus',
            ];

            $attributes = [
                'file' => 'fájl',
            ];

            $validatedData = $request->validate([
                'file' => 'file|mimes:pdf,csv,txt,xls,xlsx,bmp,gif,jpeg,jpg,jpe,png,svg,tiff,tif,webp,JPG',
            ], $messages, $attributes);

            $uploadedFile = $request->file('file');

            $fileSize = $uploadedFile->getSize();

            if ($fileSize > 10485760) {
                return ['error' => 'A file mérete nem lehet nagyobb 10 MB-nál'];
            }
            $filename = Str::slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME), '-');
            $ext = strtolower(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION));
            if ($ext == 'jpeg') {
                $ext = 'jpg';
            }

            $uploader = Storage::disk('local')->putFileAs(
                'public/'.$extraPath,
                $uploadedFile,
                $filename.'.'.$ext
            );
            $url = URL::to('/').Storage::url($extraPath.$filename.'.'.$ext);
            $path = Storage::disk('local')->path('public/'.$extraPath.$filename.'.'.$ext);

            $response = [
                'rawurl' => $url,
                'url' => $extraPath.$filename.'.'.$ext,
                'message' => $filename.' feltöltése AAA sikeres!--',
            ];
        }

        return $response;
    }
}
