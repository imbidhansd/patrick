<option value="">Select Membership Status</option>
@if (isset($membership_status) && count($membership_status) > 0)
@foreach ($membership_status AS $membership_status_item)
<option value="{{ $membership_status_item }}">{{ $membership_status_item }}</option>
@endforeach
@endif