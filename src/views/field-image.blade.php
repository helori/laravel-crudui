<!--div class="row narrow">
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
</div-->


@include('laravel-medias::image-manager-trigger', [])