<?php

namespace Alomgyar\Products;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use function Livewire\str;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadImageComponent extends Component
{
    use WithFileUploads;

    public $image;

    public $images = [];

    public $path;

    public $acceptedFiles;

    public $multiple = false;

    public $fieldName;

    public $model;

    public $modelId;

    public $cover;

    public function mount()
    {
        $this->model = isset($this->modelId) ? Product::find($this->modelId) : null;

        if (isset($this->model)) {
            $image = $this->model->cover;

            $tmpFilename = $image && File::exists(storage_path('app/public/'.$image)) ? $this->prepareImage($image) : null;

            $this->image = $image && File::exists(storage_path('app/public/'.$image)) ? TemporaryUploadedFile::createFromLivewire($tmpFilename) : null;
            $this->cover = $this->model->cover;
            //dd( $this->image->getClientOriginalExtension() );
            //var_dump( $this->image?->getClientOriginalName() );
        }
    }

    public function finishUpload($name, $tmpPath, $isMultiple)
    {
        $this->cleanupOldUploads();

        if ($isMultiple) {
            $file = collect($tmpPath)->map(function ($i) {
                return TemporaryUploadedFile::createFromLivewire($i);
            })->toArray();
            $this->emitSelf('upload:finished', $name, collect($file)->map->getFilename()->toArray());

            $file = array_merge($this->getPropertyValue($name), $file);
        } else {
            $file = TemporaryUploadedFile::createFromLivewire($tmpPath[0]);
            $this->emit('upload:finished', $name, [$file->getFilename()])->self();

            // If the property is an array, but the upload ISNT set to "multiple"
            // then APPEND the upload to the array, rather than replacing it.
            if (is_array($value = $this->getPropertyValue($name))) {
                $file = array_merge($value, [$file]);
            }
        }

        $this->syncInput($name, $file);
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => [$this->acceptedFiles, 'max:10240'], // 10MB Max
        ]);

        $this->image->storeAs($this->path, $this->image->getClientOriginalName(), 'public');
    }

    /**
     * Most feltöltött kép eltávolítása
     */
    public function remove($index)
    {
        if ($this->multiple) {
            if (isset($this->model)) {
                $this->removeExisting($this->images[$index]->getClientOriginalName());
            }
            Storage::disk('public')->delete($this->path.$this->images[$index]->getClientOriginalName());
            unset($this->images[$index]);
        } else {
            if (isset($this->model)) {
                $this->removeExisting($this->image->getClientOriginalName());
            }
            Storage::disk('public')->delete($this->path.$this->image->getClientOriginalName());
            $this->image = null;
        }
    }

    /**
     * Már feltöltött kép eltávolítása
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeExisting($fileName)
    {
        return response()->json(['success' => true]);
    }

    /**
     * Képek sorrendjének módosítása
     */
    public function updateImageOrder($orderedImages)
    {
        $this->images = collect($orderedImages)->map(function ($image) {
            return collect($this->images)->first(function ($oldImage) use ($image) {
                if ($oldImage->getClientOriginalName() === $image['value']) {
                    return $oldImage;
                }
            });
        })->toArray();
    }

    public function render()
    {
        if ($this->multiple) {
            return view('products::components.uploadimages');
        }
        //var_dump($this->fieldName);
        return view('products::components.uploadimage');
    }

    /**
     * Generálunk livewire-ös temp fájl nevet
     *
     *
     * @return string
     */
    private function generateHashNameWithOriginalNameEmbedded($file)
    {
        $hash = str()->random(30);
        $file = Str::afterLast($file, '/');
        $filename = Str::before($file, '.');
        $meta = str('-meta'.base64_encode($filename).'-')->replace('/', '_');
        $extension = '.'.Str::afterLast($file, '.');

        return $hash.$meta.$extension;
    }

    /**
     * Szerkesztésnél a már feltöltött képeket előkészíti
     *
     *
     * @return string
     */
    private function prepareImage($image)
    {
        $tmpFilename = $this->generateHashNameWithOriginalNameEmbedded($image);

        File::copy(storage_path('app/public/'.$image), storage_path('app/livewire-tmp/'.$tmpFilename));

        return $tmpFilename;
    }
}
