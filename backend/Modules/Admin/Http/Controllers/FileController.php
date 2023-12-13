<?php

namespace Modules\Admin\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class FileController
{
    /**
     * Entry image upload handler
     *
     * @return json
     */
    public function upload(Request $request)
    {
        try {
                //code...

            $params = $request->all();
            $extraPath = $params['type'].'/';

            $messages = [
                'file.mimes' => 'Nem megfelelő fájltípus',
            ];

            $attributes = ['file' => 'fájl'];

            $validatedData = $request->validate([
                'file' => 'file|mimes:pdf,csv,txt,xls,xlsx,bmp,gif,jpeg,jpg,jpe,png,svg,tiff,tif,webp,JPG',
            ], $messages, $attributes);

            $uploadedFile = $request->file('file');

            $fileSize = $uploadedFile->getSize();

            if ($fileSize > 10485760) {
                return ['error' => 'A file mérete nem lehet nagyobb 10 MB-nál'];
            }
            $data = getimagesize($request->file('file'));
            $filename = Str::slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME), '-').'_'.$data[0].'-'.$data[1];
            $ext = strtolower(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION));

            $width = $data[0];
            $height = $data[1];

            $uploader = Storage::disk('local')->putFileAs(
                'public/'.$extraPath,
                $uploadedFile,
                $filename.'.'.$ext
            );

            $url = URL::to('/').Storage::url($extraPath.$filename.'.'.$ext);

            $path = Storage::disk('local')->path('public/'.$extraPath.$filename.'.'.$ext);

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'jfif'])) {
                (new ImageService())->generateThumbnails($extraPath, $filename, $path);

                if ($ext != 'webp') {
                    (new ImageService())->saveAsWebp($path);
                }
            }

            $response = [
                'rawurl' => $url,
                'url' => $extraPath.$filename.'.'.$ext,
                'message' => $filename.'  feltöltése sikeres!',
            ];
        } catch (\Throwable $th) {

            $response = [
                'rawurl' => '',
                'url' => '',
                'message' => 'Képfeltöltés sikertelen, próbáld más néven, vagy jelezd részünkre.',
            ];
        }

        return $response;
    }
}
