<?php
    $admin_page_title = 'Company Owners';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">

        @if (isset($company_item) && !is_null($company_item) && $company_item->number_of_owners > 1)
        <div class="row">
            @for($num = 2; $num <= $company_item->number_of_owners; $num++)

                <?php $name_field = 'company_owner_' . $num . '_full_name' ?>
                <?php $email_field = 'company_owner_' . $num . '_email' ?>
                <?php $invitation_key_field = 'company_owner_' . $num . '_invitation_key' ?>
                <?php $status_field = 'company_owner_' . $num . '_status' ?>


                @if (isset($company_information->$email_field) && $company_information->$email_field != '')
                <div class="col-sm-6 col-md-4">
                    <div class="card">

                        <?php
                        $card_class = 'bg-danger';
                        if ($company_information->$status_field == 'invited'){
                            $card_class = 'bg-info';
                        }elseif ($company_information->$status_field == 'registered'){
                            $card_class = 'bg-success';
                        }
                        ?>
                        <div class="card-header {{ $card_class }}">
                            <h5 class="card-title text-white mb-0">Owner {{ $num }}</h5>
                        </div>
                        <div class="card-body text-center">
                            <i class="fas fa-user font-50 text-dark"></i>
                            <br />

                            <h5 class="text-dark">{{ $company_information->$name_field }}</h5>
                            <h6 class="text-dark">{{ $company_information->$email_field }}</h6>
                        </div>

                        <div class="card-footer">
                            <div class="btn-group btn-group-solid">
                                @if ($company_information->$status_field == 'pending')

                                {!! Form::open(['url' => url('invite-company-owner'), 'class' =>
                                'invite_company_user_form'])
                                !!}
                                {!! Form::hidden('owner_num', $num) !!}
                                <button type="submit" class="btn btn-sm btn-primary invitation_btn">Send
                                    Invitation</button>
                                {!! Form::close() !!}
                                @elseif ($company_information->$status_field == 'invited')
                                <button class="btn btn-sm btn-info">Invited</button>
                                @elseif ($company_information->$status_field == 'registered')
                                <button class="btn btn-sm btn-success">Registered</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif


            @endfor
        </div>
        @endif


    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')


<script type="text/javascript">
    $(function(){
        $('.invitation_btn').click(function(){

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
                if (typeof t.value !== 'undefined'){
                    btn.closest('.invite_company_user_form').submit();
                }
            });

            return false;


        });
    });
</script>

@endsection
