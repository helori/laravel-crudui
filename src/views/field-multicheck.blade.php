<multicheck name="<% $field['name'] %>"
    options="<% json_encode($field['options']) %>" 
    value="<% isset($fieldData[$field['name']]) ? json_encode($fieldData[$field['name']]) : '' %>" >
</multicheck>
