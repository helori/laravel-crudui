<select <% $i == 0 ? 'autofocus' : '' %>
	name="<% $field['name'] %>" 
	id="<% $field['name'] %>" 
	class="form-control">
	<option value="<% isset($field['empty_value']) ? $field['empty_value'] : '' %>">== <% $field['title'] %> ==</option>
    @foreach($field['options'] as $value => $name)
        <option value="<% $value %>" <% (isset($fieldData[$field['name']]) && $fieldData[$field['name']] == $value) ? 'selected' : '' %>><% $name %></option>
    @endforeach
</select>