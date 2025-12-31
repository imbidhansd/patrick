<div class="card">
    <div class="card-header bg-secondary">
        <h3 class="card-title text-white mb-0">Find A Pro Notifications:</h3>
    </div>
    <div class="card-body">
        <div class="text-left">
            <p class="text-muted font-14">All requests/inquiries are sent to the following email addresses</p>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-border card-primary">
                        <div class="card-header border-primary bg-transparent">
                            <h3 class="card-title text-primary mb-0">
                                Leads Destination Email:
                            </h3>
                        </div>
                        <div class="card-body">
                            @if (!is_null($company_lead_notifications) &&
                            !is_null($company_lead_notifications->main_email_address))
                            <span>{{ $company_lead_notifications->main_email_address }}</span>
                            @else
                            <span>Not set</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-border card-primary">
                        <div class="card-header border-primary bg-transparent">
                            <h3 class="card-title text-primary mb-0">
                                Additional Notifications:
                            </h3>
                        </div>

                        <div class="card-body">

                            @if ($company_item->membership_level->paid_members == 'yes')

                            @if (
                            !is_null($company_lead_notifications) &&
                            ($company_lead_notifications->owner_2 == 'yes' ||
                            $company_lead_notifications->owner_3 == 'yes' ||
                            $company_lead_notifications->owner_4 == 'yes' ||
                            $company_lead_notifications->office_manager == 'yes' ||
                            $company_lead_notifications->sales_manager == 'yes' ||
                            $company_lead_notifications->estimators_sales_1 == 'yes' ||
                            $company_lead_notifications->estimators_sales_2 == 'yes')
                            )
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                    </tr>
                                    @if ($company_lead_notifications->owner_2 == 'yes')
                                    <tr>
                                        <th>Applicant/Owner 2</th>
                                        <td>{{ $company_lead_notifications->owner_2_name }}</td>
                                        <td>{{ $company_lead_notifications->owner_2_email }}</td>
                                    </tr>
                                    @endif

                                    @if ($company_lead_notifications->office_manager == 'yes')
                                    <tr>
                                        <th>Office Manager</th>
                                        <td>{{ $company_lead_notifications->office_manager_name }}</td>
                                        <td>{{ $company_lead_notifications->office_manager_email }}</td>
                                    </tr>
                                    @endif

                                    @if ($company_lead_notifications->sales_manager == 'yes')
                                    <tr>
                                        <th>Sales Manager</th>
                                        <td>{{ $company_lead_notifications->sales_manager_name }}</td>
                                        <td>{{ $company_lead_notifications->sales_manager_email }}</td>
                                    </tr>
                                    @endif

                                    @if ($company_lead_notifications->estimators_sales_1 == 'yes')
                                    <tr>
                                        <th>Estimator/Sales</th>
                                        <td>{{ $company_lead_notifications->estimators_sales_1_name }}</td>
                                        <td>{{ $company_lead_notifications->estimators_sales_1_email }}</td>
                                    </tr>
                                    @endif

                                    @if ($company_lead_notifications->estimators_sales_2 == 'yes')
                                    <tr>
                                        <th>Estimator/Sales 2</th>
                                        <td>{{ $company_lead_notifications->estimators_sales_2_name }}</td>
                                        <td>{{ $company_lead_notifications->estimators_sales_2_email }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            @else
                            <span>None Assigned</span>
                            @endif


                            @else
                            <p class="text-danger m-0 p-0">Additional notifications feature only available for members.</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            @if ($company_item->membership_level->paid_members == 'yes')
            <p class="text-muted font-14">
                Add/Update lead notifications?
                <a href="javascript:;" data-toggle="modal" data-target="#leadsNotification">Click Here</a>
            </p>
            @elseif (isset($admin_form) && $admin_form)
            <p class="text-muted font-14">
                Add/Update lead notifications?
                <a href="javascript:;" data-toggle="modal" data-target="#leadsNotification">Click Here</a>
            </p>
            @endif
        </div>
    </div>
</div>



<div class="modal fade" id="leadsNotification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Additional Notifications</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @php
            $form_url = "update-company-application-leads-notification";
            if (isset($admin_form) && $admin_form){
            $form_url = "admin/companies/update-company-application-leads-notification";
            }
            @endphp

            {!! Form::open(['url' => url($form_url), 'class' => 'module_form']) !!}

            @if (isset($admin_form) && $admin_form)
            {!! Form::hidden('company_id', $company_item->id) !!}
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Please enter MAIN email address:*</label>
                            {!! Form::email('main_email_address', ((!is_null($company_lead_notifications)) ?
                            $company_lead_notifications->main_email_address : null), ['class' => 'form-control',
                            'required' => true]) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        @if ($company_item->membership_level->paid_members == 'yes')

                        <p class="text-danger">Attention: Leads cannot be sent to anyone outside of your company. For
                            obvious reasons, we do not allow companies to sub-contract work or pass leads to companies
                            that are not approved by TrustPatrick.com.</p>
                        <div class="form-group">
                            <label>Please select all that apply:</label>

                            <div class="checkbox checkbox-primary ">
                                {!! Form::checkbox('owner_2', 'yes', ((!is_null($company_lead_notifications) &&
                                $company_lead_notifications->owner_2 == 'yes') ? true : null), ['id' =>
                                'another_email_ids_applicant',
                                'class' => 'other_email_option']) !!}
                                <label for="another_email_ids_applicant">Applicant/Owner 2</label>
                            </div>

                            <div class="checkbox checkbox-primary ">
                                {!! Form::checkbox('office_manager', 'yes', ((!is_null($company_lead_notifications) &&
                                $company_lead_notifications->office_manager == 'yes') ? true : null), ['id' =>
                                'another_email_ids_manager',
                                'class' => 'other_email_option']) !!}
                                <label for="another_email_ids_manager">Office Manager</label>
                            </div>

                            <div class="checkbox checkbox-primary">
                                {!! Form::checkbox('sales_manager', 'yes', ((!is_null($company_lead_notifications) &&
                                $company_lead_notifications->sales_manager == 'yes') ? true : null), ['id' =>
                                'another_email_ids_sales_manager', 'class' => 'other_email_option']) !!}
                                <label for="another_email_ids_sales_manager">Sales Manager</label>
                            </div>

                            <div class="checkbox checkbox-primary">
                                {!! Form::checkbox('estimators_sales_1', 'yes', ((!is_null($company_lead_notifications)
                                && $company_lead_notifications->estimators_sales_1 == 'yes') ? true : null), ['id' =>
                                'another_email_ids_sales', 'class' => 'other_email_option']) !!}
                                <label for="another_email_ids_sales">Estimator/sales</label>
                            </div>



                            <div class="checkbox checkbox-primary ">
                                {!! Form::checkbox('estimators_sales_2', 'yes', ((!is_null($company_lead_notifications)
                                && $company_lead_notifications->estimators_sales_2 == 'yes') ? true : null), ['id' =>
                                'another_email_ids_sales2', 'class' => 'other_email_option']) !!}
                                <label for="another_email_ids_sales2">Estimator/sales 2</label>
                            </div>
                        </div>



                        <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->owner_2 == 'yes' ? : 'hide' }}"
                             id="another_email_ids_applicant_div">
                            <div class="card-header border-primary bg-transparent">
                                <h3 class="card-title text-primary mb-0">
                                    Applicant/Owner2 Info
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Name</label>
                                        {!! Form::text('owner_2_name', ((!is_null($company_lead_notifications)) ?
                                        $company_lead_notifications->owner_2_name : null), ['class' => 'form-control'])
                                        !!}
                                    </div>

                                    <div class="col-md-6">
                                        <label>Email</label>
                                        {!! Form::email('owner_2_email', ((!is_null($company_lead_notifications)) ?
                                        $company_lead_notifications->owner_2_email : null), ['class' => 'form-control'])
                                        !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->office_manager == 'yes' ? : 'hide' }}"
                             id="another_email_ids_manager_div">
                            <div class="card-header border-primary bg-transparent">
                                <h3 class="card-title text-primary mb-0">
                                    Office Manager Info
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Name</label>
                                        {!! Form::text('office_manager_name', ((!is_null($company_lead_notifications)) ?
                                        $company_lead_notifications->office_manager_name : null), ['class' =>
                                        'form-control']) !!}
                                    </div>

                                    <div class="col-md-6">
                                        <label>Email</label>
                                        {!! Form::email('office_manager_email', ((!is_null($company_lead_notifications))
                                        ? $company_lead_notifications->office_manager_email : null), ['class' =>
                                        'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->sales_manager == 'yes' ? : 'hide' }}"
                             id="another_email_ids_sales_manager_div">
                            <div class="card-header border-primary bg-transparent">
                                <h3 class="card-title text-primary mb-0">
                                    Sales Manager Info
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Name</label>
                                        {!! Form::text('sales_manager_name', ((!is_null($company_lead_notifications)) ?
                                        $company_lead_notifications->sales_manager_name : null), ['class' =>
                                        'form-control']) !!}
                                    </div>

                                    <div class="col-md-6">
                                        <label>Email</label>
                                        {!! Form::email('sales_manager_email', ((!is_null($company_lead_notifications))
                                        ? $company_lead_notifications->sales_manager_email : null), ['class' =>
                                        'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->estimators_sales_1 == 'yes' ? : 'hide' }}"
                             id="another_email_ids_sales_div">
                            <div class="card-header border-primary bg-transparent">
                                <h3 class="card-title text-primary mb-0">
                                    Estimator/sales Info
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Name</label>
                                        {!! Form::text('estimators_sales_1_name',
                                        ((!is_null($company_lead_notifications)) ?
                                        $company_lead_notifications->estimators_sales_1_name : null), ['class' =>
                                        'form-control']) !!}
                                    </div>

                                    <div class="col-md-6">
                                        <label>Email</label>
                                        {!! Form::email('estimators_sales_1_email',
                                        ((!is_null($company_lead_notifications)) ?
                                        $company_lead_notifications->estimators_sales_1_email : null), ['class' =>
                                        'form-control'])
                                        !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-border card-primary {{ !is_null($company_lead_notifications) && $company_lead_notifications->estimators_sales_2 == 'yes' ? : 'hide' }}"
                             id="another_email_ids_sales2_div">
                            <div class="card-header border-primary bg-transparent">
                                <h3 class="card-title text-primary mb-0">
                                    Estimator/sales 2 Info
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Name</label>
                                        {!! Form::text('estimators_sales_2_name',
                                        ((!is_null($company_lead_notifications)) ?
                                        $company_lead_notifications->estimators_sales_2_name : null), ['class' =>
                                        'form-control']) !!}
                                    </div>

                                    <div class="col-md-6">
                                        <label>Email</label>
                                        {!! Form::email('estimators_sales_2_email',
                                        ((!is_null($company_lead_notifications)) ?
                                        $company_lead_notifications->estimators_sales_2_email : null), ['class' =>
                                        'form-control'])
                                        !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @else
                        <h6 class="text-danger">Additional lead notification available for only paid members.</h6>
                        @endif


                        <div class="card widget-box-three">
                            <div class="card-body">
                                <div class="float-left mt-2 mr-3">
                                    <i class="fas fa-exclamation-triangle display-4 m-0 text-danger"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-danger font-14">
                                        ONLY people within your company can be added to receive copies of leads from
                                        your listing. Leads CANNOT be sent to anyone outside of your company. For
                                        obvious reasons, we do not allow companies to sub-contract work or pass leads to
                                        companies that are not approved by TrustPatrick.com. Sending leads to someone
                                        outside of your company is strictly against our terms and conditions and doing
                                        so will result in an immediate termination of your listing.
                                    </p>

                                    <div class="checkbox checkbox-danger">
                                        <input type="checkbox" name="i_understand" id="i_understand" value="yes"
                                               required="true" />
                                        <label for="i_understand">I understand and agree.</label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


@push('_edit_company_profile_js')
<script type="text/javascript">
    $(function () {
        $('.other_email_option').change(function () {
            var id = '#' + $(this).attr('id') + '_div';
            if ($(this).is(':checked') == true) {
                $(id).show();
                $(id).find('input').attr('required', true);
            } else {
                $(id).hide();
                $(id).find('input').removeAttr('required');
            }
        });
    });
</script>
@endpush
