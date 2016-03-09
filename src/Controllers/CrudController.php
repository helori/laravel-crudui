<?php

namespace Algoart\Crudui\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Route;

use Algoart\Crudui\Controllers\CrudBaseController;


class CrudController extends CrudBaseController
{
    public function __construct()
    {
        $routeParams = Route::current()->parameters();
        $section = config('crudui.sections.'.$routeParams['section']);

        parent::__construct($section['model_class']);

        $this->page_name = $section['page_name'];
        $this->route_url = $section['route_url'];
        $this->uploads_dir = $section['uploads_dir'];

        $this->list_title = $section['list_title'];
        $this->edit_title = $section['edit_title'];
        $this->add_text = $section['add_text'];

        $this->sort_by = $section['sort_by'];
        $this->sort_dir = $section['sort_dir'];
        $this->sortable = $section['sortable'];
        $this->limit = $section['limit'];

        $this->fields = $section['fields'];
        
        $this->initFields();
    }

    public function getEditItem(Request $request, $section, $id = null)
    {
        return parent::getEditItem($request, $id);
    }

    public function postUpdateItem(Request $request, $section, $id = null)
    {
        return parent::postUpdateItem($request, $id);
    }

    public function getDeleteItem(Request $request, $section, $id = null)
    {
        return parent::getDeleteItem($request, $id);
    }
}
