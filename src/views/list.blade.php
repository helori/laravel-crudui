@extends($layout_view)
@section('content')   

<div id="crud-table" ng-controller="CrudListController">

    <div id="saving" ng-class="{'active': savingPosition}">
        <i class="fa fa-cog fa-spin"></i> Saving...
    </div>

    <div class="container-fluid">

    	<div class="row">

            @if(count($filters) > 0)
            <div class="col-md-8">
            @else
            <div class="col-md-12">
            @endif

                <h1>{!! $list_title !!}</h1>

                @if($sortable)
                    <div class="alert alert-success">Aide : Glisser / Déposer les lignes pour trier les éléments</div>
                @endif

                @if($list_help)
                    <div class="alert alert-success">{{ $list_help }}</div>
                @endif

                <div class="items">
                    <table class="table table-striped table-bordered table-hover" {{ $sortable ? 'sortable' : '' }}>
                        <thead>
                            <tr class="headers">
                                @foreach($list_fields as $field)
                                    <th>
                                        @if(!$sortable && isset($field['sortable']) && $field['sortable'])
                                            <a href="{{ $route_url }}/items?{{ $sort_query }}&sort_by={{ $field['name'] }}&sort_dir={{ $sort_dir == 'asc' ? 'desc' : 'asc' }}">{{ $field['title'] }}
                                                @if($sort_by == $field['name'])
                                                    @if($sort_dir == 'asc')
                                                        <i class="fa fa-arrow-down"></i>
                                                    @else
                                                        <i class="fa fa-arrow-up"></i>
                                                    @endif
                                                @endif
                                            </a>
                                        @else
                                            {{ $field['title'] }}
                                        @endif
                                    </th>
                                @endforeach
                                @if($can_create || $can_delete || $can_update)
                                    <th>
                                        @if($can_create)
                                           <a ng-click="openCreateDialog($event)" href="{{ $route_url }}/create-item" class="btn btn-primary btn-block">
                                                <i class="fa fa-plus"></i> {{ $add_text }}
                                            </a>
                                        @endif
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr class="item" id="{{ $item->id }}" update-url="{{ $route_url }}/update-position">
                                @foreach($list_fields as $field)
                                    <td>
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- Text -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @if($field["type"] == "text")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                <div class="text-center">
                                                    <input list-input 
                                                        class="form-control"
                                                        type="text"
                                                        value="{{ $item->{$field['name']} }}" 
                                                        field-type="{{ $field['type'] }}"
                                                        field-name="{{ $field['name'] }}"
                                                        item-id="{{ $item->id }}"
                                                        update-url="{{ $route_url }}/update-field">
                                                    </div>
                                                </div>
                                            @else
                                                {{ $item->{$field['name']} }}
                                            @endif

                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- Password -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "password")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                <div class="text-center">
                                                    <input list-input 
                                                        class="form-control"
                                                        type="password"
                                                        field-type="{{ $field['type'] }}"
                                                        field-name="{{ $field['name'] }}"
                                                        item-id="{{ $item->id }}"
                                                        update-url="{{ $route_url }}/update-field">
                                                    </div>
                                                </div>
                                            @else
                                                (secret)
                                            @endif

                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- Textarea -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "textarea")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                <div class="text-center">
                                                    <textarea list-input 
                                                        class="form-control"
                                                        field-type="{{ $field['type'] }}"
                                                        field-name="{{ $field['name'] }}"
                                                        item-id="{{ $item->id }}"
                                                        update-url="{{ $route_url }}/update-field">
                                                        {{ $item->{$field['name']} }}
                                                    </textarea>
                                                </div>
                                            @else
                                                {!! nl2br($item->{$field['name']}) !!}
                                            @endif

                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- Number -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "number")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                <div class="text-center">
                                                    <input list-input 
                                                        class="form-control"
                                                        type="number"
                                                        value="{{ $item->{$field['name']} }}" 
                                                        field-type="{{ $field['type'] }}"
                                                        field-name="{{ $field['name'] }}"
                                                        item-id="{{ $item->id }}"
                                                        update-url="{{ $route_url }}/update-field">
                                                    </div>
                                                </div>
                                            @else
                                                {{ number_format($item->{$field['name']}, 2, ',', ' ') }}
                                            @endif

                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- Emails -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "email")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                <div class="text-center">
                                                    <input list-input 
                                                        class="form-control"
                                                        type="email"
                                                        value="{{ $item->{$field['name']} }}" 
                                                        field-type="{{ $field['type'] }}"
                                                        field-name="{{ $field['name'] }}"
                                                        item-id="{{ $item->id }}"
                                                        update-url="{{ $route_url }}/update-field">
                                                    </div>
                                                </div>
                                            @else
                                                {{ $item->{$field['name']} }}
                                            @endif

                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- URL -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "url")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                <div class="text-center">
                                                    <input list-input 
                                                        class="form-control"
                                                        type="url"
                                                        value="{{ $item->{$field['name']} }}" 
                                                        field-type="{{ $field['type'] }}"
                                                        field-name="{{ $field['name'] }}"
                                                        item-id="{{ $item->id }}"
                                                        update-url="{{ $route_url }}/update-field">
                                                    </div>
                                                </div>
                                            @else
                                                {{ $item->{$field['name']} }}
                                            @endif

                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- Date -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "date" || $field["type"] == "datetime")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                <div class="text-center">
                                                    <input list-input 
                                                        class="form-control"
                                                        type="date"
                                                        value="{{ $item->{$field['name']}->format('Y-m-d') }}"
                                                        field-type="{{ $field['type'] }}"
                                                        field-name="{{ $field['name'] }}"
                                                        item-id="{{ $item->id }}"
                                                        update-url="{{ $route_url }}/update-field">
                                                    </div>
                                                </div>
                                            @elseif($field["type"] == "date")
                                                {{ $item->{$field['name']}->format('d F Y') }}
                                            @elseif($field["type"] == "datetime")
                                                {!! $item->{$field['name']}->format('d F Y<\b\r>H:i:s') !!}
                                            @endif

                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- Checkbox -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "checkbox")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                <checkbox 
                                                    value="{{ $item->{$field['name']} ? 'true' : 'false' }}"
                                                    title=""
                                                    field-type="{{ $field['type'] }}"
                                                    field-name="{{ $field['name'] }}"
                                                    item-id="{{ $item->id }}"
                                                    update-url="{{ $route_url }}/update-field">
                                                </checkbox>
                                            @else
                                                @if($item->{$field['name']})
                                                    <div class="text-center"><i class="fa fa-check-circle" style="font-size: 20px; color: green"></i></div>
                                                @else
                                                    <div class="text-center"><i class="fa fa-minus-circle" style="font-size: 20px; color: red"></i></div>
                                                @endif
                                            @endif

                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- File -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "file")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                @include('laravel-crudui::field-'.$field["type"], ['fieldData' => $item])
                                            @else
                                                <div class="image-wrapper">
                                                    <div class="image" style="background-image: url({{ $item->mediaPath($field["name"]).'?'.@filemtime($item->mediaPath($field["name"])) }})"></div>
                                                </div>
                                            @endif

                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        <!-- Select -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "select")
                                            @if(isset($field['list-input']) && $field['list-input'])
                                                <select list-input 
                                                    class="form-control"
                                                    value="{{ $item->{$field['name']} }}" 
                                                    field-type="{{ $field['type'] }}"
                                                    field-name="{{ $field['name'] }}"
                                                    item-id="{{ $item->id }}"
                                                    update-url="{{ $route_url }}/update-field">
                                                    @if(isset($field['empty_value']))
                                                        <option value="{{ $field['empty_value'] }}">== {{ $field['title'] }} ==</option>
                                                    @endif
                                                    @foreach($field['options'] as $value => $name)
                                                        <option value="{{ $value }}" {{ (isset($item->{$field['name']}) && $item->{$field['name']} == $value) ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                {{ isset($field["options"][$item->{$field['name']}]) ? $field["options"][$item->{$field['name']}] : '' }}
                                            @endif

                                        @elseif($field["type"] == "currency")
                                            {{ number_format($item->{$field['name']}, 2, ',', ' ') }} €
                                        
                                        @elseif($field["type"] == "image")
                                            <div class="image-wrapper">
                                                <div class="image">
                                                    <img src="{{ isset($item->{$field["name"]}['path']) ? $item->{$field["name"]}['path'] : '' }}">
                                                </div>
                                            </div>
                                        @elseif($field["type"] == "image-advanced")
                                            <div class="image-wrapper">
                                                <div class="image" style="background-image: url({{ $item->hasMedia($field["name"]) ? $item->getMedia($field["name"])->filepath.'?'.@filemtime($item->getMedia($field["name"])->filepath) : '' }})"></div>
                                            </div>
                                        @elseif($field["type"] == "images-advanced")
                                            <div class="image-wrapper">
                                                <div class="image" style="background-image: url({{ $item->hasMedias($field["name"]) ? $item->getMedias($field["name"])[0]->filepath.'?'.@filemtime($item->getMedias($field["name"])[0]->filepath) : '' }})">
                                                    <div class="text">{{ count($item->getMedias($field["name"])) }} image(s)</div>
                                                </div>
                                            </div>
                                        @elseif($field["type"] == "link")
                                            <a href="{{ $route_url.'/'.$item->id.'/'.$field['model'].'/items' }}" class="btn btn-primary btn-block">
                                                <i class="fa fa-arrow-right"></i> {{ $field['title'] }}
                                            </a>
                                        @elseif($field["type"] == "relation")
                                            <?php
                                                $relations = explode('.', $field["relation"]);
                                                $related = $item;
                                                foreach($relations as $relation){
                                                    $related = $related->$relation;
                                                }
                                            ?>
                                            {{ $related->{$field["name"]} }}
                                        @endif
                                    </td>
                                @endforeach
                                @if($can_create || $can_delete || $can_update)
                                    <td class="actions">
                                        <div class="row narrow">
                                            @if($can_update)
                                            <div class="col col-xs-{{ $can_delete ? '6' : '12' }}">
                                                <a href="{{ $route_url }}/edit-item/{{ $item->id }}" class="btn btn-success icon-only btn-block">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </div>
                                            @endif
                                            @if($can_delete)
                                            <div class="col col-xs-{{ $can_update ? '6' : '12' }}">
                                                <a href="{{ $route_url }}/delete-item/{{ $item->id }}" class="btn btn-danger icon-only btn-block" onclick="confirm('Êtes-vous sûr?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {!! $items->links() !!}

            </div>

            @if(count($filters) > 0)
            <div class="col-md-4">
                <div id="crud-filters">
                    <h3>Filtrer les résultats</h3>
                    <form method="get" action="{{ $route_url }}/items" class="form">
                        <input type="hidden" name="sort_by" value="{{ $sort_by }}">
                        <input type="hidden" name="sort_dir" value="{{ $sort_dir }}">
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
                                <a class="btn btn-default btn-block" href="{{ $route_url }}/items">
                                    <i class="fa fa-refresh"></i> Ré-initialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </div>

    </div>

    <div id="create-dialog" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $add_text }}</h4>
                </div>
                <form method="post" action="{{ $route_url }}/store-item" class="form-horizontal" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                         @foreach($create_fields as $i => $field)
                            @if($field['type'] == 'separator')
                                <hr>
                            @elseif($field['type'] != 'alias' && view()->exists('laravel-crudui::field-'.$field["type"]))
                                <div class="form-group">
                                    <label for="{{ $field['name'] }}" class="control-label col-sm-4">{{ $field['title'] }} :</label>
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