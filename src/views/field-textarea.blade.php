<textarea {{ $i == 0 ? 'autofocus' : '' }}
	id="{{ $field['name'] }}" 
	name="{{ $field['name'] }}" 
	class="form-control" 
	rows="5"
	placeholder="{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}"
	{{ isset($field['required']) && $field['required'] ? 'required' : '' }}
	style="resize:vertical">{{ isset($fieldData[$field['name']]) ? $fieldData[$field['name']] : '' }}</textarea>