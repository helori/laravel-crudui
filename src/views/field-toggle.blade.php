<input <% (isset($i) && ($i == 0)) ? 'autofocus' : '' %>
	type="checkbox" 
	id="<% $field['name'] %>" 
	name="<% $field['name'] %>" 
	value="true" 
	class="toogle"
	<% isset($fieldData[$field['name']]) && $fieldData[$field['name']] ? 'checked' : '' %>>

	<% $fieldData[$field['name']] %>
	
<label for="<% $field['name'] %>"><span class="ui"></span></label>