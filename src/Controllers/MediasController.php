<?php

namespace Helori\LaravelCrudui\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Route;
use Helori\LaravelCrudui\Controllers\MediasBaseController;


class MediasController extends MediasBaseController
{
    public function __construct()
    {
        $routeParams = Route::current()->parameters();
        $model = config('laravel-crudui.models.'.$routeParams['model']);
        parent::__construct($model['model_class']);
    }

    public function getMedia(Request $request, $model, $id, $collection = null)
    {
        return parent::getMedia($request, $id, $collection);
    }

    public function getMedias(Request $request, $model, $id, $collection = null)
    {
        return parent::getMedias($request, $id, $collection);
    }
}
