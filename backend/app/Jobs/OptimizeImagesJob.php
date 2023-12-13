<?php

namespace App\Jobs;

use App\OptimizedImage;
use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OptimizeImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fileName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (OptimizedImage::where('file_name', $this->fileName)->exists()) {
            return;
        }

        // Generate Thumbnails
        $splitPath = explode('app//public/', $this->fileName);
        $pathAfterPublicDirectory = $splitPath[count($splitPath) - 1];
        $fileDirectory = pathinfo($pathAfterPublicDirectory, PATHINFO_DIRNAME);

        (new ImageService())->generateThumbnails($fileDirectory, pathinfo($this->fileName, PATHINFO_FILENAME), $this->fileName);
        (new ImageService())->saveAsWebp($this->fileName);
    }
}
