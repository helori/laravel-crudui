<div class="row narrow">
    @if(isset($fieldData[$field['name']]) && is_array($fieldData[$field['name']]))
        <div class="col col-xs-4">
            <a href="{{ isset($fieldData[$field['name']]['path']) ? $fieldData[$field['name']]['path'] : '' }}" target="_blank" class="btn btn-default btn-block">
                <i class="fa fa-file-o"></i>
                {{ isset($fieldData[$field['name']]['mime']) ? $fieldData[$field['name']]['mime'] : '' }}
                | {{ isset($fieldData[$field['name']]['path']) && is_file($fieldData[$field['name']]['path']) ? floor(filesize($fieldData[$field['name']]['path'])/1000).' ko' : '' }}
            </a>
        </div>
        <div class="col col-xs-8">
    @else
        <div class="col col-xs-12">
    @endif
    <input {{ $i == 0 ? 'autofocus' : '' }}
        type="file" 
        id="{{ $field['name'] }}" 
        name="{{ $field['name'] }}" 
        class="form-control">
    </div>
</div>