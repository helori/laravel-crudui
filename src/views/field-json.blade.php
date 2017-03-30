<div ng-controller="JsonController" ng-init="initItems('{{ $field['name'] }}-{{ $fieldData->id }}', '{{ isset($fieldData) ? json_encode($fieldData->{$field['name']}, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) : '' }}')">
    <div class="row narrow" style="margin-bottom: 5px">
        <div class="col col-xs-9">
            <div class="row narrow">
                @foreach($field['columns'] as $column)
                    <div class="col col-xs-{{ 12 / count($field['columns']) }}">{{ $column['title'] }}</div>
                @endforeach
            </div>
        </div>
        <div class="col col-xs-3"></div>
    </div>

    <div class="row narrow" style="margin-bottom: 5px" ng-repeat="item in items | orderBy:{{ isset($field['orderBy']) ? $field['orderBy'] : $field['columns'][0]['name'] }}:{{ isset($field['orderDir']) && $field['orderDir'] == 'desc' ? 'true' : 'false' }}">
        <div class="col col-xs-9">
            <div class="row narrow">
                @foreach($field['columns'] as $colIdx => $column)
                    <div class="col col-xs-{{ 12 / count($field['columns']) }}">
                        <input type="text" 
                            ng-model="item.{{ $column['name'] }}" 
                            class="form-control" 
                            ng-change="updateItems()">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col col-xs-3">
            <button type="button" class="btn btn-block btn-danger" ng-click="removeItem(item)"><i class="fa fa-remove"></i> Supprimer</button>
        </div>
    </div>

    <button type="button" class="btn btn-default btn-block" ng-click="addItem()"><i class="fa fa-plus"></i> Ajouter</button>
    <input type="hidden" 
        @if(isset($jsonList) && $jsonList)
            list-input 
            field-type="{{ $field['type'] }}"
            field-name="{{ $field['name'] }}"
            item-id="{{ $fieldData->id }}"
            update-url="{{ $route_url }}/update-field"
        @endif
        name="{{ $field['name'] }}" 
        id="{{ $field['name'] }}-{{ $fieldData->id }}">
</div>