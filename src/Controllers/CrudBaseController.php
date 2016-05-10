<?php

namespace Helori\LaravelCrudui\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Helori\LaravelCrudui\CrudUtilities;


class CrudBaseController extends Controller
{
    protected $data = [];
    protected $class_name;

    public function __construct($class_name)
    {
        $this->class_name = $class_name;

        $this->page_name = "model";
        $this->route_url = "/model";
        $this->medias_url = "/medias";
        $this->global_medias_url = config('laravel-crudui.global_medias.route_url');

        $this->can_create = true;
        $this->can_delete = true;
        $this->can_update = true;

        $this->list_title = "Titre de la liste";
        $this->edit_title = "Éditer l'élément";
        $this->add_text = "Ajouter un élément";

        $this->sort_by = "name";
        $this->sort_dir = "asc";
        $this->sortable = false;
        $this->limit = 5;
        $this->where = [];
        $this->defaults = [];

        $this->fields = [];

        $this->layout_view = 'laravel-crudui::layout';
        $this->list_view = 'laravel-crudui::list';
        $this->edit_view = 'laravel-crudui::edit';
    }

    protected function initFields()
    {
        foreach($this->fields as &$field)
        {
            if($field['type'] == 'select' && isset($field['options_model']))
            {
                $model = $field['options_model']['model'];
                $items = call_user_func(array($model, 'orderBy'), $field['options_model']['field'])->get();
                $options = array_pluck($items, $field['options_model']['field'], 'id');
                $field['options'] = $options;
            }
        }

        $this->list_fields = array_where($this->fields, function ($key, $field){
            return isset($field['list']) && $field['list'];
        });

        $this->edit_fields = array_where($this->fields, function ($key, $field){
            return (isset($field['edit']) && $field['edit']) || $field['type'] == 'separator';
        });

        $this->create_fields = array_where($this->fields, function ($key, $field){
            return (isset($field['create']) && $field['create']);
        });

        $this->filters = array_where($this->fields, function ($key, $field){
            return isset($field['filter']) && $field['filter'];
        });

        foreach($this->filters as &$filter){
            if(isset($filter['required']))
                unset($filter['required']);
        }

        if($this->sortable)
            $this->limit = 10000;
    }

    protected function onGetItems(Request $request){}
    protected function onUpdateItem(Request $request, &$item){}

    public function getItems(Request $request)
    {
        if($this->sortable){
            $this->sort_by = 'position';
            $this->sort_dir = 'asc';
        }else{
            $this->sort_by = $request->has('sort_by') ? $request->input('sort_by') : $this->sort_by;
            $this->sort_dir = $request->has('sort_dir') ? $request->input('sort_dir') : $this->sort_dir;
        }
        $items = call_user_func(array($this->class_name, 'orderBy'), $this->sort_by, $this->sort_dir);

        foreach($this->where as $column => $value)
        {
            $items->where($column, '=', $value);
        }

        foreach($this->filters as $filter)
        {
            if($request->has($filter['name']))
            {
                if(in_array($filter['type'], ['text', 'email', 'url', 'textarea'])){
                    $items->where($filter['name'], 'like', '%'.$request->input($filter['name']).'%');
                }
                if(in_array($filter['type'], ['select', 'checkbox'])){
                    $items->where($filter['name'], '=', $request->input($filter['name']));
                }
            }
        }
        $items = $items->paginate($this->limit)->appends($request->except('page'));

        $this->data['items'] = $items;
        $this->data['filters_data'] = $request->all();
        $this->data['sort_query'] = http_build_query($request->except(['page', 'sort_by', 'sort_dir']));

        $this->data['page_name'] = $this->page_name;
        $this->data['route_url'] = $this->route_url;
        $this->data['medias_url'] = $this->medias_url;
        $this->data['global_medias_url'] = $this->global_medias_url;

        $this->data['can_create'] = $this->can_create;
        $this->data['can_delete'] = $this->can_delete;
        $this->data['can_update'] = $this->can_update;

        $this->data['list_title'] = $this->list_title.' ('.(($items->currentPage()-1) * $items->perPage() + 1).' à '.min($items->currentPage() * $items->perPage(), $items->total()).' sur '.$items->total().')';
        $this->data['add_text'] = $this->add_text;

        $this->data['sort_by'] = $this->sort_by;
        $this->data['sort_dir'] = $this->sort_dir;
        $this->data['sortable'] = $this->sortable;
        
        $this->data['list_fields'] = $this->list_fields;
        $this->data['create_fields'] = $this->create_fields;
        $this->data['filters'] = $this->filters;

        $this->data['layout_view'] = $this->layout_view;

        $this->onGetItems($request);

        return view($this->list_view, $this->data);
    }

    // Show the form
    public function getCreateItem(Request $request)
    {
        $this->data['item'] = null;

        $this->data['route_url'] = $this->route_url;
        $this->data['page_name'] = $this->page_name;
        $this->data['edit_title'] = $this->add_text;
        $this->data['edit_fields'] = $this->edit_fields;
        $this->data['layout_view'] = $this->layout_view;

        return view($this->edit_view, $this->data);
    }

    // Store in DB
    public function postStoreItem(Request $request)
    {
        $item = new $this->class_name();
        if($this->sortable){
            $item->position = 0;
            $items = call_user_func(array($this->class_name, 'increment'), 'position');
        }
        foreach($this->defaults as $column => $value)
        {
            $item->$column = $value;
        }
        CrudUtilities::fillItem($request, $item, $this->create_fields);
        return redirect($this->route_url.'/items');
    }

    // Show the form
    public function getEditItem(Request $request, $id, $model = null)
    {
        $this->data['item'] = call_user_func(array($this->class_name, 'findOrFail'), $id);
        
        $this->data['route_url'] = $this->route_url;
        $this->data['medias_url'] = $this->medias_url;
        $this->data['global_medias_url'] = $this->global_medias_url;
        $this->data['page_name'] = $this->page_name;
        $this->data['edit_title'] = $this->edit_title;
        $this->data['edit_fields'] = $this->edit_fields;
        $this->data['layout_view'] = $this->layout_view;

        return view($this->edit_view, $this->data);
    }

    // Update item
    public function postUpdateItem(Request $request, $id, $model = null)
    {
        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
        CrudUtilities::fillItem($request, $item, $this->edit_fields);
        $this->onUpdateItem($request, $item);
        return redirect($this->route_url.'/items');
    }

    public function postUpdateField(Request $request)
    {
        $id = intVal($request->input('id'));
        $fieldType = $request->input('type');
        $fieldName = $request->input('name');
        $fieldValue = $request->input('value');
        
        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
        
        if($fieldType == 'checkbox'){
            $item->$fieldName = ($fieldValue == 'true');
        }
        $item->save();
    }

    public function getDeleteItem(Request $request, $id, $model = null)
    {
        if($this->sortable)
        {
            $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
            $items = call_user_func(array($this->class_name, 'where'), 'position', '>', $item->position);
            $items->decrement('position');
        }
        call_user_func(array($this->class_name, 'destroy'), $id);
        return redirect($this->route_url.'/items');
    }

    public function postUpdatePosition(Request $request)
    {
        $id = intVal($request->input('id'));
        $item = call_user_func(array($this->class_name, 'findOrFail'), $id);
        
        $oldPos = $item->position;
        $newPos = intVal($request->input('position'));

        if($oldPos != $newPos)
        {
            if($oldPos < $newPos)
                $range = [$oldPos + 1, $newPos];
            else
                $range = [$newPos, $oldPos - 1];

            //$items = DB::table('projects')->whereBetween('position', $range)

            $items = call_user_func(array($this->class_name, 'whereBetween'), 'position', $range);
            //$items = call_user_func(array($items, 'get'));
            
            if($oldPos < $newPos){
                $items->decrement('position');
            }else{
                $items->increment('position');
            }
            $item->position = $newPos;
            $item->save();
        }
    }
}
