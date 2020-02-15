<?php

namespace Vuravel\Form\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeMigration extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vuravel:migration {name} {pivot?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new vuravel migration class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $text = parent::replaceClass($stub, $name);
        $text = str_replace('{Model}', ucfirst($this->argument('name')),
                str_replace('{model}', strtolower($this->argument('name')), $text));
        if($this->argument('pivot'))
            $text = str_replace('{pivot}', $this->argument('pivot'), $text);
        return $text;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return database_path('migrations/'.$this->getDatePrefix().'_create_'.strtolower($this->argument('name')).'s_table.php');
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__ . '/stubs/vuravel-migration.stub';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The class name of the model.'],
            ['pivot', InputArgument::OPTIONAL, 'The class name of the pivot model.']
        ];
    }

}
