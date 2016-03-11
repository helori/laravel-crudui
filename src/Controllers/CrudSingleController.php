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
        $model = config('laravel-crudui.models.'.$routeParams['model']);

        parent::__construct($model['model_class']);

        $this->page_name = $model['page_name'];
        $this->route_url = $model['route_url'];
        $this->uploads_dir = $model['uploads_dir'];
        $this->edit_title = $model['edit_title'];
        $this->fields = $model['fields'];
        $this->layout_view = isset($model['layout']) ? $model['layout'] : $this->layout_view;
    }
}
