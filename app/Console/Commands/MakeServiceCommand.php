<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\GeneratorCommand;

#[Signature('make:service')]
#[Description('Create a new service class')]
class MakeServiceCommand extends GeneratorCommand
{
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
     * Places all generated service classes under app\Services
     * unless a sub-namespace is specified by the caller.
     *
     * @param  string $rootNamespace The application's root
     * namespace (typically "app").
     *
     * @return string The fully resolved namespace for the new service.
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }
}
