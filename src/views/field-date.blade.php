<input {{ $i == 0 ? 'autofocus' : '' }}
	type="date" 
	id="{{ $field['name'] }}" 
	name="{{ $field['name'] }}" 
	class="form-control" 
	value="{{ isset($fieldData[$field['name']]) ? $fieldData[$field['name']]->format('Y-m-d') : '' }}">