@if (isset($company_status_history) && count($company_status_history) > 0)
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Admin</th>
                <th>IP Address</th>
                <th>From</th>
                <th>To</th>
                <th>DateTime</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($company_status_history AS $i => $company_status_history_item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $company_status_history_item->admin_user->first_name }} {{ $company_status_history_item->admin_user->last_name }}</td>
                <td>{{ $company_status_history_item->ip_address }}</td>
                <td>
                    <b>Level:</b> {{ $company_status_history_item->from_membership_level->title }} <br />
                    <b>Status:</b> {{ $company_status_history_item->from_membership_status->title }}
                </td>
                <td>
                    <b>Level:</b> {{ $company_status_history_item->membership_level->title }} <br />
                    <b>Status:</b> {{ $company_status_history_item->membership_status->title }}
                </td>
                <td>{{ $company_status_history_item->created_at->format(env('DATETIME_FORMAT')) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif