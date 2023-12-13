<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = Storage::disk('local')->path('/public');

        $files = [];
        $this->get_files_recursive($path, $files);

        $this->info('Found '.count($files).' images to optimize');
        $this->warn('Optimizing images, please wait (it takes time)...');

        foreach ($files as $file) {
            dispatch(new \App\Jobs\OptimizeImagesJob($file));
        }

        $this->info('Done!');
    }

    /**
     * @param  array  $files
     *
     * Manipulate the $files array by reference to add all files to it recursively from the given path
     */
    private function get_files_recursive(string $path, array &$files)
    {
        $dir = opendir($path);
        while (($file = readdir($dir)) !== false) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $file_path = $path.DIRECTORY_SEPARATOR.$file;
            if (is_dir($file_path)) {
                $this->get_files_recursive($file_path, $files);
            } else {
                if (preg_match('/\.(jpg|jpeg|png|gif|jfif)$/i', $file)) {
                    $files[] = $file_path;
                }
            }
        }
        closedir($dir);
    }
}
