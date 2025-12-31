@if ($company_approval_status->company_logo != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->company_logo) }}">

    @if ($company_approval_status->company_logo == 'pending')
        <a href="javascript:;" data-toggle="modal" data-target="#uploadCompanyLogo">Company Logo</a>
    @elseif ($company_approval_status->company_logo == 'completed')
        <a href="javascript:;" data-toggle="modal" data-target="#companyLogoModal">Company Logo</a>

        <div class="modal fade" id="companyLogoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content ">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold text-left">Company Logo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <h4>Thank you for uploading your Company Logo!</h4>
                        <div class="clearfix">&nbsp;</div>
                        <h5>Thank You!</h5>
                    </div>
                </div>
            </div>
        </div>
    @else
        Company Logo
    @endif

    {!! $company_approval_status->showStatusIcon($company_approval_status->company_logo) !!}
</li>


@if ($company_approval_status->company_logo == 'pending')

<div class="modal fade" id="uploadCompanyLogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Company Logo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('update-company-profile'), 'class' => 'module_form', 'files' => true]) !!}
            {!! Form::hidden('update_type', 'company_logo') !!}
            <div class="modal-body text-left">

                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle text-danger alert-icon"></i>
                    As per our policy, logos should not contain <strong>phone numbers</strong>, <strong>website
                        addresses</strong>, <strong>custom text numbers</strong>,
                    or
                    any other <strong>contact information</strong>.
                </div>


                <div class="form-group">
                    {!! Form::label('Company Logo') !!}
                    {!! Form::file('company_logo', ['class' => 'filestyle', 'accept' => 'image/*', 'required' => true])
                    !!}
                </div>

                <div class="text-center text-danger"> Need help? Call Member Support at <a href="tel: 720-445-4400"
                        class="text-info"><strong>720-445-4400</strong></a></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Upload Logo</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>



@endif
@endif
