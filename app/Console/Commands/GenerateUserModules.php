<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateUserModules extends Command
{
    protected $signature = 'app:generate-user-modules {name}';
    protected $description = 'Generate Request, Resource, Repository, Service, and Controller files';

    public function handle()
    {
        $name = $this->argument('name');

        $this->generateRequest($name);
        $this->generateResource($name);
        $this->generateRepository($name);
        $this->generateService($name);
        $this->generateController($name);

        $this->info("Modules for {$name} generated successfully.");
    }

    protected function generateRequest($name)
    {
        $stub = $this->getStub('UserRequest');
        $this->createFile("Http/Requests/User/{$name}Request.php", $stub, $name);
    }

    protected function generateResource($name)
    {
        $stub = $this->getStub('Resource');
        $this->createFile("Http/Resources/{$name}Resource.php", $stub, $name);
    }

    protected function generateRepository($name)
    {
        $stub = $this->getStub('UserRepository');
        $this->createFile("Repositories/{$name}Repository.php", $stub, $name);
    }

    protected function generateService($name)
    {
        $stub = $this->getStub('Service');
        $this->createFile("Services/{$name}Service.php", $stub, $name);
    }

    protected function generateController($name)
    {
        $stub = $this->getStub('UserController');
        $this->createFile("Http/Controllers/User/{$name}/{$name}Controller.php", $stub, $name);
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

    protected function getStub($type)
    {
        return File::get(base_path("stubs/{$type}.stub"));
    }
}
