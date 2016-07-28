<textarea tinymce 
	id="{{ $field['name'] }}" 
	name="{{ $field['name'] }}" 
	class="form-control"
	global-medias-url="{{ $global_medias_url }}"
	>{{ isset($fieldData[$field['name']]) ? $fieldData[$field['name']] : '' }}
</textarea>