@php
$company_lead_notification = $company->company_lead_notification;

$conditionArr = ['owner_2', 'owner_3', 'owner_4', 'office_manager', 'sales_manager', 'estimators_sales_1', 'estimators_sales_2'];

$nameArr = ['owner_2_name', 'owner_3_name', 'owner_4_name', 'office_manager_name', 'sales_manager_name', 'estimators_sales_1_name', 'estimators_sales_2_name'];

$emailArr = ['owner_2_email', 'owner_3_email', 'owner_4_email', 'office_manager_email', 'sales_manager_email', 'estimators_sales_1_email', 'estimators_sales_2_email'];
@endphp

<table class="table table-bordered" border="0">
    <tr>
        <th colspan="2" class="text-center"><h3>Company Page And Lead Notifications</h3></th>
    </tr>
    <tr>
        <th>Main Email Address</th>
        <td>{{ $company_lead_notification->main_email_address }}</td>
    </tr>

    @if ($company_lead_notification->receive_a_copy == 'yes')
        @foreach ($conditionArr AS $key => $value)
            @php
                $name = $nameArr[$key];
                $email = $emailArr[$key];
            @endphp

            @if ($company_lead_notification->$value == 'yes')
            <tr>
                <th colspan="2">{{ ucwords(str_replace('_', ' ', $value)) }}</th>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $company_lead_notification->$name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $company_lead_notification->$email }}</td>
            </tr>
            @endif
        @endforeach
    @endif
</table>
