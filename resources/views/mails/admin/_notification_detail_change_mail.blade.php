<table class="table table-striped">
    <tr>
        <th></th>
        <th>Name</th>
        <th>Email</th>
    </tr>

    @if (isset($updateArr['owner_2']) && $updateArr['owner_2'] == 'yes')
    <tr>
        <th>Applicant/Owner 2</th>
        <td>{{ $updateArr['owner_2_name'] }}</td>
        <td>{{ $updateArr['owner_2_email'] }}</td>
    </tr>
    @endif

    @if (isset($updateArr['office_manager']) && $updateArr['office_manager'] == 'yse')
    <tr>
        <th>Office Manager</th>
        <td>{{ $updateArr['office_manager_name'] }}</td>
        <td>{{ $updateArr['office_manager_email'] }}</td>
    </tr>
    @endif
    
    @if (isset($updateArr['sales_manager']) && $updateArr['sales_manager'] == 'yes')
    <tr>
        <th>Sales Manager</th>
        <td>{{ $updateArr['sales_manager_name'] }}</td>
        <td>{{ $updateArr['sales_manager_email'] }}</td>
    </tr>
    @endif
    
    @if (isset($updateArr['estimators_sales_1']) && $updateArr['estimators_sales_1'] == 'yes')
    <tr>
        <th>Estimator/Sales</th>
        <td>{{ $updateArr['estimators_sales_1_name'] }}</td>
        <td>{{ $updateArr['estimators_sales_1_email'] }}</td>
    </tr>
    @endif
    
    @if (isset($updateArr['estimators_sales_2']) && $updateArr['estimators_sales_2'] == 'yes')
    <tr>
        <th>Estimator/Sales 2</th>
        <td>{{ $updateArr['estimators_sales_2_name'] }}</td>
        <td>{{ $updateArr['estimators_sales_2_email'] }}</td>
    </tr>
    @endif
</table>