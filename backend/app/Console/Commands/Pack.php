<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Package;

class Pack extends Command
{
    protected $name;

    protected $folder;

    protected $resource;

    protected $fields;

    protected $path;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:create
                            {name : The name of the package}
                            {--folder= : Provide the folder within packages}
                            {--resource : Do you need CRUD controller and Views}
                            {--fields=* : Provide the fields of the table in array}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new package for Skvadmin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Composer $composer)
    {
        parent::__construct();
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name = Str::snake(trim($this->argument('name')));
        $this->folder = $this->option('folder') ?? 'skvadcom';
        //$this->resource = $this->option('resource');
        $this->resource = 1;
        $this->fields = $this->option('fields');
        $this->path = $this->folder.'/'.Str::plural($this->name);

        if (count($this->fields) === 1) {
            $this->fields = explode(',', $this->fields[0]);
        }

        $this->info("Building {$this->name} package!");
        $bar = $this->output->createProgressBar(10);
        $bar->start();
        $this->line('');

        // 1. Create migration file according to fields
        $this->createMigrationFile(); // TODO: Itt menő lenne, ha a field-ek bekerülnének a migrációs fájlba
        $bar->advance();

        // 2. Create composer.json file
        $this->info($this->createComposerFile());
        $bar->advance();

        // 3. Create model file
        $this->createModelFile();
        $bar->advance();

        // 4. Create routes.php according to resource
        $this->createRoutesFile();
        $bar->advance();

        // 5. Create service provider file
        $this->createServiceProviderFile();
        $bar->advance();

        // 6. Create controller file
        $this->createControllerFile();
        $bar->advance();

        // 7. Create component file
        $this->createComponentFile();
        $bar->advance();

        // 8. Create views file
        $this->createViewsFile();
        $bar->advance();

        // Finishing
        // 9. Put a line into composer.json at the root
        $this->addLineToComposer();
        $bar->advance();

        // 10. Register service provider file at config/app.php
        $this->addServiceProvider();
        $bar->advance();

        $this->info("\n{$this->name} package has been built!");
        $this->info($this->savePackage());
        $this->info($this->dumpAutoload());
        $bar->advance();
        $this->line('');
        $this->info('Ne felejtsd el a migration file-t módosítani és futtatni, majd hozzáadni a package-t a sidebar-hoz!');
        $bar->finish();

        return 1;
    }

    protected function createMigrationFile()
    {
        $migrationName = 'create_'.Str::plural($this->name).'_table';
        $path = 'packages/'.$this->path.'/migrations';

        if (Storage::disk('package')->missing($this->path.'/migrations')) {
            // Create folders
            Storage::disk('package')->makeDirectory($this->path.'/migrations');
        }
        try {
            // Call migration command php artisan make:migration $migrationName --path=packages/$name/migrations
            $this->call('make:migration', [
                'name' => $migrationName, '--path' => $path, '--create' => Str::plural($this->name), '-n', '-v',
            ]);
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function createComposerFile()
    {
        $content = Storage::disk('package')->get('skvadcom/items/src/composer.json');
        $newContent = $this->changeContent($content);

        try {
            Storage::disk('package')->put($this->path.'/composer.json', $newContent);
            $this->line('');

            return 'Composer.json has been created!';
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function createModelFile()
    {
        $content = Storage::disk('package')->get('skvadcom/items/src/Item.php');
        // $fillableOriginal = <<< EOD
        //                 protected \$fillable = [
        //                     'name',
        //                     'description'
        //                 ];
        //             EOD;
        // $fillableNew = <<< EOD
        //                         protected \$fillable = [
        //                     EOD.PHP_EOL."\t".implode(','.PHP_EOL."\t",$this->fields).PHP_EOL.<<< EOD
        //                 ];
        //             EOD;

        // $newContent = str_replace(['Skvadcom', 'item', 'Item', $fillableOriginal], [Str::ucfirst($this->folder), $this->name, Str::ucfirst($this->name), $fillableNew], $content);
        $newContent = $this->changeContent($content);

        try {
            Storage::disk('package')->put($this->path.'/'.Str::ucfirst($this->name).'.php', $newContent);
            $this->line('');
            $this->info('Model has been created!');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function createRoutesFile()
    {
        $content = Storage::disk('package')->get('skvadcom/items/src/routes.php');
        $newContent = $this->changeContent($content);

        try {
            Storage::disk('package')->put($this->path.'/routes.php', $newContent);
            $this->line('');
            $this->info('routes.php has been created!');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function createServiceProviderFile()
    {
        $content = Storage::disk('package')->get('skvadcom/items/src/ItemServiceProvider.php');
        $newContent = $this->changeContent($content);

        try {
            Storage::disk('package')->put($this->path.'/'.Str::ucfirst($this->name).'ServiceProvider.php', $newContent);
            $this->line('');
            $this->info(Str::ucfirst($this->name).'ServiceProvider.php has been created!');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function createControllerFile()
    {
        $content = Storage::disk('package')->get('skvadcom/items/src/ItemController.php');
        $newContent = $this->changeContent($content);

        try {
            Storage::disk('package')->put($this->path.'/'.Str::ucfirst($this->name).'Controller.php', $newContent);
            $this->line('');
            $this->info('Controller has been created!');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function createComponentFile()
    {
        $files = Storage::disk('package')->allFiles('skvadcom/items/src/');
        array_walk($files, function ($file) {
            if (Str::contains($file, 'Component')) {
                $path = explode('/', $file);
                $fileName = $path[count($path) - 1];
                $content = Storage::disk('package')->get($file);
                $newContent = $this->changeContent($content);

                try {
                    Storage::disk('package')->put($this->path.'/'.$fileName, $newContent);
                    $this->line('');
                    $this->info($fileName.' has been created!');
                } catch (\Throwable $th) {
                    $this->error($th->getMessage());
                }
            }
        });
//        $content    = Storage::disk('package')->get('skvadcom/items/src/ListComponent.php');

//        try {
//            Storage::disk('package')->put($this->path . '/ListComponent.php', $newContent);
//            $this->line('');
//            $this->info('Component has been created!');
//        } catch (\Throwable $th) {
//            $this->error($th->getMessage());
//        }
    }

    protected function createViewsFile()
    {
        $files = Storage::disk('package')->allFiles('skvadcom/items/src/views/');
        array_walk($files, function ($file) {
            $path = explode('/', $file);
            $fileName = $path[count($path) - 1];
            $subFolder = $path[count($path) - 2] !== 'views' ? $path[count($path) - 2] : null;
            $content = Storage::disk('package')->get($file);
            $newContent = $this->changeContent($content);

            try {
                Storage::disk('package')->put($this->path.'/views/'.$subFolder.'/'.$fileName, $newContent);
                $this->line('');
                $this->info($fileName.' has been created!');
            } catch (\Throwable $th) {
                $this->error($th->getMessage());
            }
        });
    }

    protected function addLineToComposer()
    {
        $composerFile = base_path('composer.json');
        $content = file_get_contents($composerFile);
        $search = '"Modules\\\": "Modules/",';
        $replace = PHP_EOL."\t\t".'"'.Str::ucfirst($this->folder).'\\\\'.Str::plural(Str::ucfirst($this->name)).'\\\": "packages/'.$this->folder.'/'.Str::plural($this->name).'/",';
        $pos = strpos($content, $search);
        $newContent = substr_replace($content, $replace, $pos + 24, 0);

        try {
            file_put_contents($composerFile, $newContent, LOCK_EX);
            $this->line('');
            $this->info($composerFile.' has been created!');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function addServiceProvider()
    {
        $file = config_path('app.php');
        $content = file_get_contents($file);
        $search = 'Skvadcom\Items\ItemServiceProvider::class,';
        $replace = PHP_EOL."\t\t".Str::ucfirst($this->folder).'\\'.Str::plural(Str::ucfirst($this->name)).'\\'.Str::ucfirst($this->name).'ServiceProvider::class,';
        $pos = strpos($content, $search);
        $newContent = substr_replace($content, $replace, $pos + 42, 0);

        try {
            file_put_contents($file, $newContent, LOCK_EX);
            $this->line('');
            $this->info($file.' has been created!');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function dumpAutoload()
    {
        try {
            $this->composer->dumpAutoloads();
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    protected function savePackage()
    {
        $model = new Package();
        $model->name = $this->name;
        $model->folder = $this->path;
        $model->fields = $this->fields;
        $model->resource = $this->resource;

        if ($model->save()) {
            return 'Package has been saved to database.';
        }
    }

    private function changeContent($content)
    {
        return str_replace(
            [
                'Skvadcom', 'items', 'Items', 'item', 'Item',
            ],
            [
                Str::ucfirst($this->folder), Str::plural($this->name), Str::ucfirst(Str::plural($this->name)), $this->name, Str::ucfirst($this->name),
            ],
            $content);
    }
}
