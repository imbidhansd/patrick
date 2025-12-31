@if (count($company_users) > 0)
    @foreach ($company_users AS $company_user_item)
    @if ($company_user_item->user_bio_status == 'in process')
    <div class="col-md-12">
        <div class="card card-border card-primary">
            <div class="card-header border-primary bg-transparent">
                <h3 class="card-title text-primary mb-0">{{ $company_user_item->first_name.' '.$company_user_item->last_name }} Bio</h3>
            </div>

            <div class="card-body">
                {!! $company_user_item->user_bio !!}
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group btn-group-solid">
                            <a href="javascript:;" class="btn btn-warning btn-sm accept_user_bio" data-user_id="{{ $company_user_item->id }}">Accept</a>

                            <a href="javascript:;" class="btn btn-danger btn-sm reject_user_bio" data-toggle="modal" data-target="#rejectUserInfoModal" data-user_id="{{ $company_user_item->id }}">Reject</a>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:;" class="btn btn-danger btn-sm remove_user_bio" data-user_id="{{ $company_user_item->id }}">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach


    @foreach ($company_users AS $company_user_item)
    @if ($company_user_item->user_image_status == 'in process')
    <div class="col-md-4">
        <div class="card card-border card-primary">
            <div class="card-header border-primary bg-transparent">
                <h3 class="card-title text-primary mb-0">{{ $company_user_item->first_name.' '.$company_user_item->last_name }} Profile Picture</h3>
            </div>

            <div class="card-body">
                @if (!is_null($company_user_item->media))
                <a href="{{ asset('/') }}uploads/media/{{ $company_user_item->media->file_name }}"
                    data-fancybox="gallery">
                    <img src="{{ asset('/') }}/uploads/media/fit_thumbs/40x40/{{ $company_user_item->media->file_name }}"
                        class='img-thumbnail' />
                </a>
                @endif
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group btn-group-solid">
                            <a href="javascript:;" class="btn btn-success btn-sm accept_user_profile_picture" data-user_id="{{ $company_user_item->id }}"><i data-toggle="tooltip" data-placement="left" title="Accept" class="far fa-thumbs-up"></i></a>

                            <a href="javascript:;" class="btn btn-warning btn-sm reject_user_profile_picture" data-toggle="modal" data-target="#rejectUserInfoModal" data-user_id="{{ $company_user_item->id }}"><span data-toggle="tooltip" data-placement="right" title="Reject"><i class="far fa-thumbs-down"></i></span></a>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:;" class="btn btn-danger btn-sm remove_user_profile_picture" data-user_id="{{ $company_user_item->id }}">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach


    <div class="modal fade" id="rejectUserInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-left"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {!! Form::open(['url' => '', 'class' => 'module_form', 'id' => 'user_bio_reject_form']) !!}
                {!! Form::hidden('approval_status', 'pending') !!}
                {!! Form::hidden('approval_status_type', null, ['id' => 'approval_status_type']) !!}
                {!! Form::hidden('company_id', $company_item->id) !!}
                {!! Form::hidden('company_user_id', null, ['id' => 'company_user_id']) !!}

                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('Note') !!}
                        {!! Form::textarea('reject_note', null, ['class' => 'form-control', 'required' => true]) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endif


@push('manage_request_js')
<script type="text/javascript">
    $(function (){
        /* User Bio approval status start */
        $(".accept_user_bio").on("click", function (){
            var company_user_id = $(this).data("user_id");
            user_swal_types('user_bio', company_user_id);
        });

        $(".reject_user_bio").on("click", function (){
            var company_user_id = $(this).data("user_id");

            $("#rejectUserInfoModal .modal-title").text("Reason for Reject User Bio");
            $("#rejectUserInfoModal #approval_status_type").val("user_bio");
            $("#rejectUserInfoModal #company_user_id").val(company_user_id);
        });

        $(".remove_user_bio").on("click", function (){
            var company_user_id = $(this).data("user_id");
            user_swal_remove_types('user_bio', company_user_id);
        });

        $("#user_bio_reject_form").on("submit", function (){
            var sendData = $(this).serialize();
            approvalStatusUserAjax (sendData);
            return false;
        });
        /* User Bio approval status end */


        /* User Profile Picture approval status start */
        $(".accept_user_profile_picture").on("click", function (){
            var company_user_id = $(this).data("user_id");
            user_swal_types('user_profile_picture', company_user_id);
        });

        $(".reject_user_profile_picture").on("click", function (){
            var company_user_id = $(this).data("user_id");
            
            $("#rejectUserInfoModal .modal-title").text("Reason for Reject User Profile Picture");
            $("#rejectUserInfoModal #approval_status_type").val("user_profile_picture");
            $("#rejectUserInfoModal #company_user_id").val(company_user_id);
        });

        $(".remove_user_profile_picture").on("click", function (){
            var company_user_id = $(this).data("user_id");
            user_swal_remove_types('user_profile_picture', company_user_id);
        });

        /*$("#user_bio_reject_form").on("submit", function (){
            var sendData = $(this).serialize();
            approvalStatusUserAjax (sendData);
            return false;
        });*/
        /* User Bio approval status end */
    });

    function user_swal_types (status_type, company_user_id){
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#53479a",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, Accept it!"
        }).then(function (t) {
            if (typeof t.value !== 'undefined'){
                var sendData = {
                    'company_id': '{{ $company_item->id }}',
                    'approval_status_type': status_type,
                    'company_user_id': company_user_id,
                    'approval_status': 'completed',
                    '_token': '{{ csrf_token() }}'
                };

                approvalStatusUserAjax(sendData);
            }
        });
    }

    function user_swal_remove_types (status_type, company_user_id){
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#ff0000",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, Remove it!"
        }).then(function (t) {
            if (typeof t.value !== 'undefined'){
                var sendData = {
                    'company_id': '{{ $company_item->id }}',
                    'approval_status_type': status_type,
                    'company_user_id': company_user_id,
                    'approval_status': 'remove',
                    '_token': '{{ csrf_token() }}'
                };

                approvalStatusUserAjax(sendData);
            }
        });
    }


    function approvalStatusUserAjax(sendData){
        $.ajax({
            url: '{{ url("admin/companies/change-company-user-approval-status") }}',
            type: 'POST',
            data: sendData,
            success: function (data){
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    type: data.type,
                }).then(function (t) {
                    window.location.reload();
                });
            }
        });
    }
</script>
@endpush