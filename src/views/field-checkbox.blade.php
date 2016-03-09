@if(isset($field['toggle']) && $field['toggle'])
	
<input <% $i == 0 ? 'autofocus' : '' %>
	type="checkbox" 
	id="<% $field['name'] %>" 
	name="<% $field['name'] %>" 
	value="true" 
	class="toogle"
	<% isset($fieldData[$field['name']]) && $fieldData[$field['name']] ? 'checked' : '' %>>
	
<label for="<% $field['name'] %>"><span class="ui"></span></label>

@else

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

@endif