<?php

namespace Algoart\Crudui;

use Illuminate\Support\ServiceProvider;

class CruduiServiceProvider extends ServiceProvider
{
    public function register()
    {
        
    }
    
    public function boot()
	{
		$this->loadViewsFrom(__DIR__.'/views', 'laravel-crudui');
	}
}
