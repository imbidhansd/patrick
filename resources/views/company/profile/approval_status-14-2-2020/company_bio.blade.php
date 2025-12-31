@if ($company_approval_status->company_bio != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->company_bio) }}">

    @if ($company_approval_status->company_bio == 'pending')
        <a href="javascript:;" data-toggle="modal" data-target="#updateStatusCompanyBioModal">Company Bio</a>
    @elseif ($company_approval_status->company_bio == 'completed')
        <a href="javascript:;" data-toggle="modal" data-target="#companyBioModal">Company Bio</a>

        <div class="modal fade" id="companyBioModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content ">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold text-left">Company Bio</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <h4>Thank you for submitting your Company Bio!</h4>
                        <div class="clearfix">&nbsp;</div>
                        <h5>Thank You!</h5>
                    </div>
                </div>
            </div>
        </div>
    @else
        Company Bio
    @endif

    {!! $company_approval_status->showStatusIcon($company_approval_status->company_bio) !!}
</li>

@if ($company_approval_status->company_bio == 'pending')

<div class="modal fade" id="updateStatusCompanyBioModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Contact Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('update-company-profile'), 'class' => 'module_form ']) !!}
            {!! Form::hidden('update_type', 'company_bio') !!}
            <div class="modal-body text-left">
                <p class="text-center">Your company bio is the first exposure people visiting our websites have to your
                    company so make it stand out! Take the time to write a great bio or copy and paste from your company
                    website.</p>

                <div class="form-group">
                    {!! Form::label('Company Bio') !!}
                    {!! Form::textarea('company_bio', $company_item->company_bio, ['class' => 'form-control
                    summernote', 'required' => false]) !!}
                </div>

                <p>Per our policy, company bio cannot contain phone numbers, website addresses, custom text numbers, or
                    any other contact information.</p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>

            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>



@endif
@endif
