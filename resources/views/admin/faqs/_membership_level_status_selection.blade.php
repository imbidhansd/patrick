@if (isset($membership_status) && count($membership_status) > 0)
	@foreach ($membership_status AS $key => $membership_status_item)
	<option value="{{ $key }}">{{ $membership_status_item }}</option>
	@endforeach
@endif