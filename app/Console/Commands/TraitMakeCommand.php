<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand as Command;

class TraitMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait';

    protected $type = 'Trait';

    protected function getStub()
    {
        return base_path('/stubs/trait.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Traits';
    }
}
