@for ($num = 2; $num <= $company_item->number_of_owners; $num++)
@php
$name_field = 'company_owner_'.$num.'_full_name';
$status_field = 'company_owner_'.$num.'_status';

if ($company_information->$status_field == 'pending'){
    $status = "pending";
} else if ($company_information->$status_field == 'invited'){
    $status = "in process";
} else if ($company_information->$status_field == 'registered'){
    $status = "completed";
}
@endphp

<li class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($status) }}">
    <a href="javascript:;" data-toggle="modal" data-target="#additionaOwnerModal{{ $num }}">Additional Owner ({{ $company_information->$name_field }}) Registration</a>
    
    {!! $company_approval_status->showStatusIcon($status) !!}
    
    <div class="modal fade" id="additionaOwnerModal{{ $num }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Additional Owner ({{ $company_information->$name_field }}) Registration</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                @if ($status == 'pending')
                <p class="text-center font-15">{{ $company_information->$name_field }} has not been invited Yet</p>
                <div class="clearfix"></div>
                <p class="text-center font-15">
                    <?php /* {!! Form::open(['url' => url('invite-company-owner'), 'class' => 'invite_company_user_form']) !!}
                    {!! Form::hidden('owner_num', $num) !!}
                    <button type="submit" id="invitation_btn_{{ $num }}" class="btn btn-primary mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round">Send Invitation</button>
                    {!! Form::close() !!} */ ?>
                    
                    <a href="{{ url('company-owners') }}" class="btn btn-primary mt-3 btn-rounded btn-bordered waves-effect width-md waves-light">Send Invitation</a>

                    @push('company_document_approval_status_js')
                    <script type="text/javascript">
                        $(function () {
                            $('#invitation_btn_{{ $num }}').click(function () {
                                var btn = $(this);
                                Swal.fire({
                                //title: "Are you sure?",
                                text: "Are you sure to invite this Owner?",
                                type: "warning",
                                showCancelButton: !0,
                                confirmButtonColor: "#53479a",
                                cancelButtonColor: "#6c757d",
                                confirmButtonText: "Yes, Invite it!"
                            }).then(function (t) {
                                if (typeof t.value !== 'undefined') {
                                    btn.closest('.invite_company_user_form').submit();
                                }
                            });

                            return false;
                        });
                        });
                    </script>
                    @endpush


                </p>

                <div class="clearfix"></div>
                @elseif ($status == 'in process')
                <p class="text-center font-15"><strong class="text-primary">{{ $company_information->$name_field }}</strong> has been invited but has not yet completed the registration.</p>
                <p class="text-center font-15">Please remind Owner Name to complete the registration to keep the approval process moving swiftly.</p>

                <a href="{{ url('company-owners') }}"  class="btn btn-primary mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round">Resend Invitation</a>


                <?php /*

                {!! Form::open(['url' => url('invite-company-owner'), 'class' => 'invite_company_user_form']) !!}
                {!! Form::hidden('owner_num', $num) !!}
                <button type="submit" id="invitation_btn_{{ $num }}" class="btn btn-primary mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round">Resend Invitation</button>
                {!! Form::close() !!}

                @push('company_document_approval_status_js')
                <script type="text/javascript">
                    $(function () {
                        $('#invitation_btn_{{ $num }}').click(function () {
                            var btn = $(this);
                            Swal.fire({
                                //title: "Are you sure?",
                                text: "Are you sure to invite this Owner?",
                                type: "warning",
                                showCancelButton: !0,
                                confirmButtonColor: "#53479a",
                                cancelButtonColor: "#6c757d",
                                confirmButtonText: "Yes, Invite it!"
                            }).then(function (t) {
                                if (typeof t.value !== 'undefined') {
                                    btn.closest('.invite_company_user_form').submit();
                                }
                            });

                            return false;
                        });
                    });
                </script>
                @endpush


                */ ?>


                @elseif ($status == 'completed')
                <p class="text-center font-15 font-bold">{{ $company_information->$name_field }} has been registered</p>
                @endif

                <h5>Thank You!</h5>
                <div class="text-center text-danger"> Need help? Call Member Support at <a href="tel: 720-445-4400" class="text-info"><strong>720-445-4400</strong></a></div>
            </div>
        </div>
    </div>
</div>
</li>
@endfor



