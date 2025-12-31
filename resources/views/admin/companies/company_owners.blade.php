@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])

<div class="card-box">
    <div class="row">
        @for($num = 1; $num <= $formObj->number_of_owners; $num++)
            @php
                
                $name_field = 'company_owner_' . $num . '_full_name';
                $email_field = 'company_owner_' . $num . '_email';
                $invitation_key_field = 'company_owner_' . $num . '_invitation_key';
                $status_field = 'company_owner_' . $num . '_status';

                $company_owner = 'company_owner'.$num;

                $company_information = $formObj->company_information;
                $company_user_information = $company_information->$company_owner;
            @endphp

            @if (isset($company_information->$email_field) && $company_information->$email_field != '')
            <div class="col-md-4">
                <div class="text-center card-box">
                    <div class="member-card">
                        <div class="avatar-xl member-thumb mx-auto">
                            @if (!is_null($company_user_information) && !is_null($company_user_information->media_id))
                            <img src="{{ asset('/uploads/media/fit_thumbs/80x80/'.$company_user_information->media->file_name) }}" class="rounded-circle img-thumbnail" alt="profile-image" />
                            @else
                            <i class="fas fa-user-circle font-80 text-dark rounded-circle img-thumbnail"></i>
                            @endif

                            @if (!is_null($company_user_information) && $company_user_information->company_user_type == 'company_super_admin')
                            <i class="mdi mdi-star-circle member-star text-success" title="Company Super Admin"></i>
                            @endif
                        </div>

                        <div class="mt-3">
                            <h4 class="font-18 mb-1">{{ $company_information->$name_field }}</h4>
                            <p class="text-muted">
                                @if (!is_null($company_user_information))
                                {{ '@'.$company_user_information->username }}
                                <span> | </span>
                                @endif
                                <span class="text-pink">{{ $company_information->$email_field }} </span>
                            </p>
                        </div>

                        @if (!is_null($company_user_information))
                        <p class="text-muted">
                            {!! substr($company_user_information->user_bio, 0, 200) !!}
                        </p>
                        @endif


                        @if ($company_information->$status_field == 'pending')
                        <button class="btn btn-danger mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round">Not Invited Yet</button>
                        @elseif ($company_information->$status_field == 'invited')
                        <button class="btn btn-info mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round">Invited</button>
                        @elseif ($company_information->$status_field == 'registered')
                        <button class="btn btn-success mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round">Registered</button>
                            @if (!is_null($company_user_information) && $company_user_information->company_user_type == 'company_admin')
                            <a href="javascript:;" data-user_id="{{ $company_user_information->id }}" class="btn btn-primary mt-3 btn-rounded btn-bordered waves-effect width-md waves-light f_btn_round make_super_admin">Make Super Admin</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @endfor
    </div>
</div>

@stop

@section('page_js')
<script type="text/javascript">
    $(function (){
        $(".make_super_admin").on("click", function (){
            var company_id = '{{ $formObj->id }}';
            var user_id = $(this).data("user_id");

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#188ae2",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes!"
            }).then(function (t) {
                if (typeof t.value != 'undefined') {
                    $.ajax({
                        url: '{{ url("admin/companies/make-owner-super-admin") }}',
                        type: 'POST',
                        data: {'company_id': company_id, 'user_id': user_id, '_token': '{{ csrf_token() }}'},
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
            });
        });
    });
</script>
@stop