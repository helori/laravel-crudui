<div ng-controller="JsonController" ng-init="initItems('<% $field['name'] %>', '<% isset($fieldData) ? json_encode($fieldData->$field['name']) : '' %>')">
    <div class="row narrow" style="margin-bottom: 5px">
        <div class="col col-xs-9">
            <div class="row narrow">
                @foreach($field['columns'] as $column)
                    <div class="col col-xs-<% 12 / count($field['columns']) %>"><% $column['title'] %></div>
                @endforeach
            </div>
        </div>
        <div class="col col-xs-3"></div>
    </div>

    <div class="row narrow" style="margin-bottom: 5px" ng-repeat="item in items | orderBy:<% isset($field['orderBy']) ? $field['orderBy'] : $field['columns'][0]['name'] %>:<% isset($field['orderDir']) && $field['orderDir'] == 'desc' ? 'true' : 'false' %>">
        <div class="col col-xs-9">
            <div class="row narrow">
                @foreach($field['columns'] as $colIdx => $column)
                    <div class="col col-xs-<% 12 / count($field['columns']) %>">
                        <input <% ($i == 0 && $colIdx == 0) ? 'autofocus' : '' %>
                            type="text" 
                            ng-model="item.<% $column['name'] %>" 
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
    <input type="hidden" name="<% $field['name'] %>" id="<% $field['name'] %>">
</div>