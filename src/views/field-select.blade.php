<select {{ $i == 0 ? 'autofocus' : '' }}
	name="{{ $field['name'] }}" 
	id="{{ $field['name'] }}" 
	class="form-control"
	{{ isset($field['required']) && $field['required'] ? 'required' : '' }} >

	@if(isset($field['empty_value']))
		@if(is_array($field['empty_value']))
			@foreach($field['empty_value'] as $value => $text)
				<option value="{{ $value }}">== {{ $text }} ==</option>
			@endforeach
		@else
			<option value="{{ $field['empty_value'] }}">== {{ $field['title'] }} ==</option>
		@endif
	@else
		<option value="">== {{ $field['title'] }} ==</option>
	@endif
	
    @foreach($field['options'] as $value => $name)
        <option value="{{ $value }}" {{ (isset($fieldData[$field['name']]) && $fieldData[$field['name']] == $value) ? 'selected' : '' }}>{{ $name }}</option>
    @endforeach
</select>