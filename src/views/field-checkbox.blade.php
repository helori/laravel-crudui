<div class="checkbox">
	<label for="<% $field['name'] %>">
		<input <% (isset($i) && ($i == 0)) ? 'autofocus' : '' %>
			type="checkbox" 
			id="<% $field['name'] %>" 
			name="<% $field['name'] %>" 
			value="true" 
			<% isset($fieldData[$field['name']]) && $fieldData[$field['name']] ? 'checked' : '' %>>
	</label>
</div>