<textarea <% $i == 0 ? 'autofocus' : '' %>
	id="<% $field['name'] %>" 
	name="<% $field['name'] %>" 
	class="form-control" 
	rows="5"
	placeholder="<% $field['title'] %>"
	style="resize:vertical"><% isset($fieldData[$field['name']]) ? $fieldData[$field['name']] : '' %>
</textarea>