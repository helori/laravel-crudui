<div class="checkbox">
	<label for="<% $field['name'] %>">
		<input <% $i == 0 ? 'autofocus' : '' %>
			type="checkbox" 
			id="<% $field['name'] %>" 
			name="<% $field['name'] %>" 
			value="true" 
			<% isset($fieldData[$field['name']]) && $fieldData[$field['name']] ? 'checked' : '' %>>
		<% $field['title'] %>
	</label>
</div>