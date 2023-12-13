<?php

namespace Alomgyar\Categories;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use function Livewire\str;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    public function mount()
    {
        $this->model = isset($this->modelId) ? Category::find($this->modelId) : null;

        if ($this->multiple) {
            if (isset($this->model)) {
                foreach ($this->model->getMedia($this->fieldName) as $image) {
                    $tmpFilename = $this->prepareImage($image);

                    $temporaryUploadedFile = TemporaryUploadedFile::createFromLivewire($tmpFilename);

                    array_push($this->images, $temporaryUploadedFile);
                }
            }
        } else {
            if (isset($this->model)) {
                $image = $this->model->getFirstMedia($this->fieldName);

                $tmpFilename = $image ? $this->prepareImage($image) : null;

                $this->image = $image ? TemporaryUploadedFile::createFromLivewire($tmpFilename) : null;
            }
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

    public function updatedImages($images)
    {
        $tooBig = false;
        foreach ($images as $key => $image) {
            if ($image->getSize() >= 1024 * 1024 * 10) {
                unset($images[$key]);
                unset($this->images[$key]);
                $tooBig = true;
            }
        }

        $this->validate([
            'images.*' => [$this->acceptedFiles], // 10MB Max
        ]);

        if ($tooBig) {
            $this->addError('size', 'A fájl mérete nem lehet nagyobb, mint 10MB');
        }

        foreach ($images as $image) {
            if (! Storage::exists($this->path.$image->getClientOriginalName())) {
                $image->storeAs($this->path, $image->getClientOriginalName(), 'public');
            }
        }
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
        $work = $this->model;
        $collection = $this->fieldName;
        $mediaId = Media::where('file_name', $fileName)
                           ->where('collection_name', $collection)
                           ->where('model_type', get_class($work))
                           ->first()
            ->id;

        foreach ($work->getMedia($collection) as $image) {
            if ($image->id === $mediaId) {
                $image->delete();
            }
        }

        return response()->json(['success' => true, 'image' => $image]);
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
            return view('categories::components.uploadimages');
        }

        return view('categories::components.uploadimage');
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
        $meta = str('-meta'.base64_encode($file->file_name).'-')->replace('/', '_');
        $extension = '.'.$file->getExtensionAttribute();

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
        if (! File::exists('public/'.$this->path)) {
            Storage::makeDirectory('public/'.$this->path);
        }
        File::copy($image->getPath(), storage_path('app/livewire-tmp/'.$tmpFilename));
        File::copy($image->getPath(), storage_path('app/public/'.$this->path.$image->file_name));

        return $tmpFilename;
    }
}
