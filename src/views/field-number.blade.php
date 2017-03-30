<input {{ $i == 0 ? 'autofocus' : '' }}
	type="number" 
	id="{{ $field['name'] }}" 
	name="{{ $field['name'] }}" 
	class="form-control" 
	placeholder="{{ isset($field['placeholder']) ? $field['placeholder'] : '' }}"
	value="{{ isset($fieldData[$field['name']]) ? $fieldData[$field['name']] : '' }}"
	step="0.001"
	{{ isset($field['required']) && $field['required'] ? 'required' : '' }} >