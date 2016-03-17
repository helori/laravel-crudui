<?php

namespace Helori\LaravelCrudui\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Helori\LaravelCrudui\CrudUtilities;


class CrudSingleBaseController extends Controller
{
    protected $data = [];
    protected $class_name;

    public function __construct($class_name)
    {
        $this->class_name = $class_name;
        $this->item_id = 1;

        $this->page_name = "model";
        $this->route_url = "/model";
        $this->medias_url = "/medias";
        $this->uploads_dir = 'uploads';

        $this->edit_title = "Éditer l'élément";
        $this->fields = [];

        $this->layout_view = 'laravel-crudui::layout';
        $this->edit_view = 'laravel-crudui::edit';
    }

    public function getItems(Request $request)
    {
        $item = call_user_func(array($this->class_name, 'find'), $this->item_id);
        if(!$item){
            $item = new $this->class_name();
            $item->id = $this->item_id;
            $item->save();
        }
        
        $this->data['item'] = $item;
        $this->data['route_url'] = $this->route_url;
        $this->data['medias_url'] = $this->medias_url;
        $this->data['page_name'] = $this->page_name;
        $this->data['edit_title'] = $this->edit_title;
        $this->data['edit_fields'] = $this->fields;
        $this->data['layout_view'] = $this->layout_view;

        return view($this->edit_view, $this->data);
    }

    // Update item
    public function postUpdateItem(Request $request, $model, $id)
    {
        $item = call_user_func(array($this->class_name, 'find'), $id);
        if(!$item){
            $item = new $this->class_name();
            $item->id = $this->item_id;
        }
        CrudUtilities::fillItem($request, $item, $this->fields, $this->uploads_dir);
        return redirect($this->route_url.'/items');
    }
}
