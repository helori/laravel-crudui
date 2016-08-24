<input {{ $i == 0 ? 'autofocus' : '' }}
	type="password" 
	id="{{ $field['name'] }}" 
	name="{{ $field['name'] }}" 
	class="form-control" 
	placeholder="{{ $field['title'] }}"
	{{ isset($field['required']) && $field['required'] ? 'required' : '' }} >