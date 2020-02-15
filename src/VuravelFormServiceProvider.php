<?php

namespace Vuravel\Form;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class VuravelFormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang');

        //$this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'vuravel-form');

        if (file_exists($file = __DIR__.'/helpers.php'))
            require_once $file;

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\MakeForm::class,
                Commands\MakeModel::class,
                Commands\MakeMigration::class
            ]);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
