@extends($layout_view)
@section('content')   

<div id="crud-table" ng-controller="CrudListController">

    <div id="saving" ng-class="{'active': savingPosition}">    
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
        </svg>
        Saving...
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                                                    </svg>                                                      
                                                    @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                                                    </svg>                                                      
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
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                {{ $add_text }}
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
                                    <td class="cell-{{ $field['type'] }}">
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
                                                        placeholder="{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}"
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
                                                        placeholder="{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}"
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
                                                        placeholder="{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}"
                                                        update-url="{{ $route_url }}/update-field">{{ $item->{$field['name']} }}</textarea>
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
                                                        placeholder="{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}"
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
                                                        placeholder="{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}"
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
                                                        placeholder="{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}"
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
                                                        value="{{ $item->{$field['name']} ? $item->{$field['name']}->format('Y-m-d') : null }}"
                                                        placeholder="{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}"
                                                        field-type="{{ $field['type'] }}"
                                                        field-name="{{ $field['name'] }}"
                                                        item-id="{{ $item->id }}"
                                                        update-url="{{ $route_url }}/update-field">
                                                    </div>
                                                </div>
                                            @elseif($field["type"] == "date")
                                                {{ $item->{$field['name']} ? $item->{$field['name']}->format('d F Y') : 'pas de date' }}
                                            @elseif($field["type"] == "datetime")
                                                {!! $item->{$field['name']} ? $item->{$field['name']}->format('d F Y<\b\r>H:i:s') : 'pas de date' !!}
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
                                                    <div class="text-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; color: green">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; color: red">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>                                                          
                                                    </div>
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
                                        <!-- Files -->
                                        <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
                                        @elseif($field["type"] == "files")
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
                                                        @if(is_array($field['empty_value']))
                                                            @foreach($field['empty_value'] as $value => $text)
                                                                <option value="{{ $value }}">== {{ $text }} ==</option>
                                                            @endforeach
                                                        @else
                                                            <option value="{{ $field['empty_value'] }}">== {{ $field['title'] }} ==</option>
                                                        @endif
                                                    @else
                                                        <option value="">== {{ $field['title'] }} ==</option>
                                                    @endif
                                                    
                                                    @foreach($field['options'] as $value => $name)
                                                        <option value="{{ $value }}" {{ (isset($item[$field['name']]) && $item[$field['name']] == $value) ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach

                                                </select>
                                            @else
                                                {{ isset($field["options"][$item->{$field['name']}]) ? $field["options"][$item->{$field['name']}] : '' }}
                                            @endif

                                        @elseif($field["type"] == "currency")
                                            {{ number_format($item->{$field['name']}, 2, ',', ' ') }} €
                                        
                                        @elseif($field["type"] == "json")
                                            
                                            @include('laravel-crudui::field-'.$field["type"], ['fieldData' => $item, 'jsonList' => true])

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
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                </svg>                                                  
                                                {{ $field['title'] }}
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
                                        @else
                                            {{ $item->{$field['name']} }}
                                        @endif
                                    </td>
                                @endforeach
                                @if($can_create || $can_delete || $can_update)
                                    <td class="actions">
                                        <div class="row narrow">
                                            @if($can_update)
                                            <div class="col col-xs-{{ $can_delete ? '6' : '12' }}">
                                                <a href="{{ $route_url }}/edit-item/{{ $item->id }}" class="btn btn-success icon-only btn-block">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>                                                      
                                                </a>
                                            </div>
                                            @endif
                                            @if($can_delete)
                                            <div class="col col-xs-{{ $can_update ? '6' : '12' }}">
                                                <a class="btn btn-danger icon-only btn-block" ng-click="destroy('{{ $route_url }}', '{{ $item->id }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>                                                      
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
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>                                      
                                    Rechercher
                                </button>
                            </div>
                            <div class="col col-xs-6">
                                <a class="btn btn-secondary btn-block" href="{{ $route_url }}/items">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>                                      
                                    Ré-initialiser
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa fa-times"></i> Annuler
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