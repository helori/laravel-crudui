<input {{ $i == 0 ? 'autofocus' : '' }}
	type="text" 
	id="{{ $field['name'] }}" 
	name="{{ $field['name'] }}" 
	class="form-control" 
	placeholder="YYYY-MM-DD"
	pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
	value="{{ isset($fieldData[$field['name']]) ? $fieldData[$field['name']]->format('Y-m-d') : '' }}">
<p class="help-block">La date doit être au format YYYY-MM-DD</p>