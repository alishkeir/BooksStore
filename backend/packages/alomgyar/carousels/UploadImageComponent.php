<?php

namespace Alomgyar\Carousels;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadImageComponent extends Component
{
    use WithFileUploads;

    public $image;

    public $path;

    public $file;

    public $images;

    public $fieldName;

    public $acceptedFiles;

    public $storedImage;

    public function finishUpload($name, $tmpPath)
    {
        $this->cleanupOldUploads();

        $file = TemporaryUploadedFile::createFromLivewire($tmpPath[0]);
        $this->emit('upload:finished', $name, [$file->getFilename()])->self();

        if (is_array($value = $this->getPropertyValue($name))) {
            $file = array_merge($value, [$file]);
        }

        $this->storeFile($file);
        $this->syncInput($name, $file);
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => [$this->acceptedFiles, 'max:10240'], // 10MB Max
        ]);
    }

    public function remove()
    {
        Storage::disk('public')->delete($this->path.$this->image->getClientOriginalName());
        $this->image = null;
    }

    public function render()
    {
        return view('carousels::components.uploadimage');
    }

    private function storeFile(TemporaryUploadedFile|array $file): void
    {
        if (! File::exists('public/'.$this->path)) {
            Storage::makeDirectory('public/'.$this->path);
        }

        $this->storedImage = Storage::disk('public')->put($this->path, $file);
    }
}
