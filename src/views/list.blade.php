@extends($layout_view)
@section('content')   

<div id="crud-table" ng-controller="CrudListController">
    <div class="container-fluid">

    	<div class="row">

            @if(count($filters) > 0)
            <div class="col-md-8">
            @else
            <div class="col-md-10">
            @endif

                <h1><% $list_title %></h1>

                <div class="items">
                    <table class="table table-striped table-bordered table-hover" <% $sortable ? 'sortable' : '' %>>
                        <thead>
                            <tr class="headers">
                                @foreach($list_fields as $field)
                                    <th>
                                        @if(!$sortable && isset($field['sortable']) && $field['sortable'])
                                            <a href="<% $route_url %>/items?<% $sort_query %>&sort_by=<% $field['name'] %>&sort_dir=<% $sort_dir == 'asc' ? 'desc' : 'asc' %>"><% $field['title'] %>
                                                @if($sort_by == $field['name'])
                                                    @if($sort_dir == 'asc')
                                                        <i class="fa fa-arrow-down"></i>
                                                    @else
                                                        <i class="fa fa-arrow-up"></i>
                                                    @endif
                                                @endif
                                            </a>
                                        @else
                                            <% $field['title'] %>
                                        @endif
                                    </th>
                                @endforeach
                                @if($sortable)
                                    <th></th>
                                @endif
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr class="item" id="<% $item->id %>" update-url="<% $route_url %>/update-position">
                                @foreach($list_fields as $field)
                                    <td>
                                        @if($field["type"] == "text")
                                            <% $item->$field["name"] %>
                                        @elseif($field["type"] == "date")
                                            <% $item->$field["name"]->format('Y-m-d h:i:s') %>
                                        @elseif($field["type"] == "checkbox")
                                            @if($item->$field["name"])
                                                <i class="fa fa-check-circle" style="font-size: 20px; color: green"></i>
                                            @else
                                                <i class="fa fa-minus-circle" style="font-size: 20px; color: red"></i>
                                            @endif
                                        @elseif($field["type"] == "select")
                                            <% isset($field["options"][$item->$field["name"]]) ? $field["options"][$item->$field["name"]] : '' %>
                                        @elseif($field["type"] == "textarea")
                                            <% $item->$field["name"] %>
                                        @elseif($field["type"] == "date")
                                            <% $item->$field["name"]->format('d/m/Y') %>
                                        @elseif($field["type"] == "datetime")
                                            <% $item->$field["name"]->format('d/m/Y H:i:s') %>
                                        @elseif($field["type"] == "url")
                                            <% $item->$field["name"] %>
                                        @elseif($field["type"] == "file")
                                            <a class="btn btn-default btn-block" target="_blank" href="<% $item->$field["name"] %>">Ouvrir</a>
                                        @elseif($field["type"] == "image")
                                            <div class="image">
                                                <img src="<% isset($item->{$field["name"]}['path']) ? $item->{$field["name"]}['path'] : '' %>">
                                            </div>
                                        @elseif($field["type"] == "image-advanced")
                                            <div class="image" style="background-image: url(<% $item->hasMedia($field["name"]) ? $item->getMedia($field["name"])->filepath.'?'.@filemtime($item->getMedia($field["name"])->filepath) : '' %>)"></div>
                                        @elseif($field["type"] == "images-advanced")
                                            <div class="image" style="background-image: url(<% $item->hasMedias($field["name"]) ? $item->getMedias($field["name"])[0]->filepath.'?'.@filemtime($item->getMedias($field["name"])[0]->filepath) : '' %>)"></div>
                                            <% count($item->getMedias($field["name"])) %> image(s)
                                        @endif
                                    </td>
                                @endforeach
                                @if($sortable)
                                    <td class="sortable">
                                        <i class="fa fa-arrows" ng-show="!savingPosition"></i>
                                        <i class="fa fa-cog fa-spin" ng-show="savingPosition"></i>
                                    </td>
                                @endif
                                <td class="actions">
                                    <a href="<% $route_url %>/edit-item/<% $item->id %>" class="btn btn-success icon-only">
                                        <i class="fa fa-edit"></i>
                                    </a>    
                                    <a href="<% $route_url %>/delete-item/<% $item->id %>" class="btn btn-danger icon-only" onclick="confirm('Êtes-vous sûr?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {!! $items->links() !!}

            </div>

            @if(count($filters) > 0)
            <div class="col-md-4">
            @else
            <div class="col-md-2">
            @endif
            
                <div id="crud-add">
                    <a ng-click="openCreateDialog($event)" href="<% $route_url %>/create-item" class="btn btn-primary btn-block">
                        <i class="fa fa-plus"></i> <% $add_text %>
                    </a>
                </div>

                @if(count($filters) > 0)
                <div id="crud-filters">
                    <h3>Filtrer les résultats</h3>
                    <form method="get" action="<% $route_url %>/items" class="form">
                        <input type="hidden" name="sort_by" value="<% $sort_by %>">
                        <input type="hidden" name="sort_dir" value="<% $sort_dir %>">
                        @foreach($filters as $i => $field)
                            @if(view()->exists('laravel-crudui::field-'.$field["type"]))
                                <div class="form-group">
                                    @include('laravel-crudui::field-'.$field["type"], ['fieldData' => $filters_data])
                                </div>
                            @endif
                        @endforeach
                        <div class="row narrow">
                            <div class="col col-xs-6">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-search"></i> Rechercher
                                </button>
                            </div>
                            <div class="col col-xs-6">
                                <a class="btn btn-default btn-block" href="<% $route_url %>/items">
                                    <i class="fa fa-refresh"></i> Ré-initialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

            </div>

        </div>

    </div>

    <div id="create-dialog" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><% $add_text %></h4>
                </div>
                <form method="post" action="<% $route_url %>/store-item" class="form-horizontal" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                         @foreach($create_fields as $i => $field)
                            @if($field['type'] == 'separator')
                                <hr>
                            @elseif($field['type'] != 'alias' && view()->exists('laravel-crudui::field-'.$field["type"]))
                                <div class="form-group">
                                    <label for="<% $field['name'] %>" class="control-label col-sm-4"><% $field['title'] %> :</label>
                                    <div class="col-sm-8">
                                        @include('laravel-crudui::field-'.$field["type"])
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="fa fa-close"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>



@endsection