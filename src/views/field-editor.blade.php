<textarea tinymce 
	id="<% $field['name'] %>" 
	name="<% $field['name'] %>" 
	class="form-control"><% isset($fieldData[$field['name']]) ? $fieldData[$field['name']] : '' %>
</textarea>