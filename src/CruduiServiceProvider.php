<?php

namespace Helori\LaravelCrudui;

use Illuminate\Support\ServiceProvider;

class CruduiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/laravel-crudui.php', 'laravel-crudui'
        );
    }
    
    public function boot()
	{
		$this->loadViewsFrom(__DIR__.'/views', 'laravel-crudui');

		$this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/laravel-crudui'),
        ], 'views');

		$this->publishes([
            __DIR__.'/config/laravel-crudui.php' => config_path('laravel-crudui.php')
        ], 'config');

        if(!class_exists('CreateMediasTable'))
        {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/migrations/create_medias_table.php' => database_path('migrations/'.$timestamp.'_create_medias_table.php'),
            ], 'migrations');
        }
	}

    public static function routes()
    {
        require(__DIR__.'/routes.php');
    }
}
