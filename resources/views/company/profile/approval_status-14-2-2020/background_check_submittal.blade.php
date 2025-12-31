@if ($company_approval_status->background_check_submittal != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->background_check_submittal) }}">
    Background Check Submittal
    {!! $company_approval_status->showStatusIcon($company_approval_status->background_check_submittal) !!}
</li>
@endif