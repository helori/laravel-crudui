<input <% $i == 0 ? 'autofocus' : '' %>
	type="text" 
	id="<% $field['name'] %>" 
	name="<% $field['name'] %>" 
	class="form-control" 
	placeholder="<% $field['title'] %>"
	value="<% isset($fieldData[$field['name']]) ? $fieldData[$field['name']] : '' %>"
	<% isset($field['required']) && $field['required'] ? 'required' : '' %> >