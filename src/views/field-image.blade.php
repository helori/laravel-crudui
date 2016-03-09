<div class="row narrow">
    @if(isset($fieldData) && $fieldData->image && is_file($fieldData->image->filepath))
        <div class="col col-xs-4">
            <div class="image">
                <img src="<% $fieldData->image->filepath.'?'.filemtime($fieldData->image->filepath) %>" alt="<% $fieldData->image->title %>">
            </div>
            <% $fieldData->image->mime %>
            | <% $fieldData->image->width.'x'.$fieldData->image->height %>
            | <% floor(filesize($fieldData->image->filepath)/1000).' ko' %>
        </div>
        <div class="col col-xs-8">
    @else
        <div class="col col-xs-12">
    @endif
    <input <% $i == 0 ? 'autofocus' : '' %>
        type="file" 
        id="<% $field['name'] %>" 
        name="<% $field['name'] %>" 
        class="form-control">
    </div>
</div>

<!--uploader>
    <div class="row narrow">
        <div class="col col-xs-8">
            <input type="file" 
                multiple
                item-id="<% isset($fieldData['id']) ? $fieldData['id'] : '' %>"
                class="form-control file-input" 
                ng-disabled="status == 'loading'">
        </div>
        <div class="col col-xs-4">
            <button type="button" 
                ng-click="upload(doc)" 
                class="btn btn-block btn-default" 
                ng-disabled="status == 'loading'">
                <i class="fa fa-upload"></i> Envoyer
            </button>
        </div>
        {{status}} {{file}}
        <div ng-show="status == 'loading'">
            <div class="progress" ng-repeat="file in files">
                <div class="progress-bar" role="progressbar" style="width: {{100 * file.loaded / file.size}}%;">
                    <span class="sr-only">{{100 * file.loaded / file.size}}% Complete</span>
                </div>
            </div>
        </div>
    </div>
</uploader-->
