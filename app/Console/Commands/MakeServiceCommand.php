<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

/**
 * Artisan command to generate a new service class.
 *
 * Extends Laravel's GeneratorCommand to scaffold a service class
 * from a stub file into the App\Services namespace.
 *
 * Usage:
 *   php artisan make:service UserService
 *
 * This will create: app/Services/UserService.php
 */
class MakeServiceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The type of class being generated.
     *
     * Used by GeneratorCommand to display success/error messages,
     * e.g. "Service created successfully."
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     *
     * Points to the custom service stub at stubs/service.stub,
     * which acts as the template for the generated class.
     *
     * @return string Absolute path to the stub file.
     */
    protected function getStub()
    {
        return base_path('stubs/service.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * Places all generated service classes under App\Services
     * unless a sub-namespace is specified by the caller.
     *
     * @param  string $rootNamespace The application's root
     * namespace (typically "App").
     *
     * @return string The fully resolved namespace for the new service.
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }
}
