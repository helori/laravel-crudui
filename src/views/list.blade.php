@extends($layout_view)

@section('content')    
<div id="crud-table">
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
                    <a href="<% $route_url %>/create-item" class="btn btn-primary btn-block">
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
                            @if(view()->exists('crud.field-'.$field["type"]))
                                <div class="form-group">
                                    @include('crud.field-'.$field["type"], ['fieldData' => $filters_data])
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
</div>
@endsection