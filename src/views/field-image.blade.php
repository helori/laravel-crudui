<?php $hasImage = isset($fieldData[$field['name']]) && is_array($fieldData[$field['name']]) ?>
<?php if($hasImage) $image = $fieldData[$field['name']] ?>


@if($hasImage)
    <div class="row narrow">
        <div class="col col-xs-4">
            <div class="image">
                <img src="<% $image['path'].'?'.@filemtime($image['path']) %>" alt="<% $field['title'] %>">
            </div>
        </div>
        <div class="col col-xs-8">
            <input <% $i == 0 ? 'autofocus' : '' %>
                type="file" 
                id="<% $field['name'] %>" 
                name="<% $field['name'] %>" 
                class="form-control">
            <div class="infos">
                <% $image['mime'] %>
                <% $image['width'].' x '.$image['height'].' px' %>
                <% floor(filesize($image['path'])/1000).' kb' %>
            </div>
            <!--a href="" class="btn btn-danger btn-block"><i class="fa fa-trash"></i></a-->
        </div>
    </div>
@else
    <input <% $i == 0 ? 'autofocus' : '' %>
        type="file" 
        id="<% $field['name'] %>" 
        name="<% $field['name'] %>" 
        class="form-control">
@endif