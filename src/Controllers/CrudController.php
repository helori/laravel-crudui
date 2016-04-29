<?php

namespace Helori\LaravelCrudui\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Route;

use Helori\LaravelCrudui\Controllers\CrudBaseController;


class CrudController extends CrudBaseController
{
    public function __construct()
    {
        $routeParams = Route::current()->parameters();
        $model = config('laravel-crudui.models.'.$routeParams['model']);

        parent::__construct($model['model_class']);

        $this->page_name = $model['page_name'];
        $this->route_url = $model['route_url'];
        $this->medias_url = isset($model['medias_url']) ? $model['medias_url'] : 'models';
        $this->uploads_dir = $model['uploads_dir'];
        
        $this->can_create = isset($model['can_create']) ? $model['can_create'] : $this->can_create;
        $this->can_delete = isset($model['can_delete']) ? $model['can_delete'] : $this->can_delete;
        $this->can_update = isset($model['can_update']) ? $model['can_update'] : $this->can_update;

        $this->list_title = $model['list_title'];
        $this->edit_title = $model['edit_title'];
        $this->add_text = $model['add_text'];

        $this->sort_by = $model['sort_by'];
        $this->sort_dir = $model['sort_dir'];
        $this->sortable = $model['sortable'];
        $this->limit = $model['limit'];
        $this->where = $model['where'];
        $this->defaults = $model['defaults'];

        $this->fields = $model['fields'];

        $this->layout_view = isset($model['layout']) ? $model['layout'] : $this->layout_view;
        
        $this->initFields();
    }

    public function getEditItem(Request $request, $model, $id = null)
    {
        return parent::getEditItem($request, $id);
    }

    public function postUpdateItem(Request $request, $model, $id = null)
    {
        return parent::postUpdateItem($request, $id);
    }

    public function getDeleteItem(Request $request, $model, $id = null)
    {
        return parent::getDeleteItem($request, $id);
    }
}
