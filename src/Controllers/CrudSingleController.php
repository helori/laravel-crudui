<?php

namespace Helori\LaravelCrudui\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Route;

use Helori\LaravelCrudui\Controllers\CrudSingleBaseController;


class CrudSingleController extends CrudSingleBaseController
{
    public function __construct()
    {
        $routeParams = Route::current()->parameters();
        $section = config('laravel-crudui.sections.'.$routeParams['section']);

        parent::__construct($section['model_class']);

        $this->page_name = $section['page_name'];
        $this->route_url = $section['route_url'];
        $this->uploads_dir = $section['uploads_dir'];
        $this->edit_title = $section['edit_title'];
        $this->fields = $section['fields'];
    }
}
