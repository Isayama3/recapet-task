<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateAdminModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-admin-modules {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle()
    {
        $name = $this->argument('name');

        $this->generateRequest($name);
        $this->generateRepository($name);
        $this->generateService($name);
        $this->generateController($name);
        $this->generateViews($name);
        $this->info("Modules for {$name} generated successfully.");
    }

    protected function generateRequest($name)
    {
        $stub = $this->getStub('AdminRequest');
        $this->createFile("Http/Requests/Admin//{$name}Request.php", $stub, $name);
    }

    protected function generateRepository($name)
    {
        $stub = $this->getStub('AdminRepository');
        $this->createFile("Repositories/{$name}Repository.php", $stub, $name);
    }

    protected function generateService($name)
    {
        $stub = $this->getStub('Service');
        $this->createFile("Services//{$name}Service.php", $stub, $name);
    }

    protected function generateController($name)
    {
        $stub = $this->getStub('AdminController');
        $this->createFile("Http/Controllers/Admin/{$name}/{$name}Controller.php", $stub, $name);
    }

    protected function generateViews($name)
    {
        $name = strtolower($name . 's');
        $this->createViewFiles("resources/views/admin/{$name}/index.blade.php", $this->getStub('AdminIndex'), $name);
        $this->createViewFiles("resources/views/admin/{$name}/create.blade.php", $this->getStub('AdminCreate'), $name);
        $this->createViewFiles("resources/views/admin/{$name}/edit.blade.php", $this->getStub('AdminEdit'), $name);
        $this->createViewFiles("resources/views/admin/{$name}/filter.blade.php", $this->getStub('AdminFilter'), $name);
        $this->createViewFiles("resources/views/admin/{$name}/show.blade.php", $this->getStub('AdminShow'), $name);
    }

    protected function createFile($path, $stub, $name)
    {
        $path = app_path($path);
        $stub = str_replace('{{name}}', $name, $stub);
        if (!File::exists($path)) {
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $stub);
        }
    }

    protected function createViewFiles($path, $stub, $name)
    {
        $path = base_path($path);
        $stub = str_replace('{{name}}', $name, $stub);
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $stub);
    }

    protected function getStub($type)
    {
        return File::get(base_path("stubs/{$type}.stub"));
    }
}
