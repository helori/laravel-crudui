<multi-check name="{{ $field['name'] }}"
    options="{{ json_encode($field['options']) }}" 
    values="{{ isset($fieldData[$field['name']]) ? json_encode(\Illuminate\Support\Arr::pluck($fieldData[$field['name']], 'id')) : '[]' }}">
</multi-check>
