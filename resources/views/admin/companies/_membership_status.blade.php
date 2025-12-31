{!! Form::open(['url' => url('admin/companies/update-company-profile'), 'class' => 'module_form']) !!}

{!! Form::hidden('update_type', 'company_member_status_info') !!}
{!! Form::hidden('company_id', $company_item->id) !!}

@php
    $bg_check_date = null;
    if (!is_null($company_item->bg_check_date)){
        $bg_check_date = \Carbon\Carbon::createFromFormat(env('DB_DATE_FORMAT', 'Y-m-d'), $company_item->bg_check_date)->format(env('DATE_FORMAT', 'm/d/Y'));
    } else{
        $recent_bg_check = \App\Models\CompanyUser::where('company_id', $company_item->id)
                ->whereNotNull('bg_check_date')
                ->orderBy('bg_check_date', 'DESC')
                ->first();
                
        if (!is_null($recent_bg_check)){
            $bg_check_date = \Carbon\Carbon::createFromFormat(env('DB_DATE_FORMAT', 'Y-m-d'), $recent_bg_check->bg_check_date)->format(env('DATE_FORMAT', 'm/d/Y'));
        }
    }
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Level') !!}
            {!! Form::select('membership_level_id', $membership_levels, $company_item->membership_level_id, ['class' =>
            'form-control custom-select', 'id' => 'membership_level_id', 'placeholder' => 'Select Level', 'required' =>
            true]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Member Status') !!}
            {!! Form::select('status', $membership_status, $company_item->status, ['class' => 'form-control
            custom-select', 'id' => 'membership_status', 'placeholder' => 'Select Member Status', 'required' => true])
            !!}
        </div>

        <div class="form-group">
            {!! Form::label('Awards') !!}
            {!! Form::text('awards', $company_item->awards, ['class' => 'form-control', 'placeholder' => 'Enter Awards',
            'required' => false]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Allow to Edit') !!}
            {!! Form::select('allow_to_edit', ['yes' => 'Yes', 'no' => 'No'], $company_item->allow_to_edit, ['class' =>
            'form-control custom-select', 'required' => true]) !!}
        </div>
        
        <div class="form-group">
            {!! Form::label('In Home Service') !!}
            {!! Form::select('in_home_service', ['yes' => 'Yes', 'no' => 'No'], $company_item->in_home_service, ['class' => 'form-control custom-select', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Approval Date') !!}
            <div class="input-group">
                {!! Form::text('approval_date', $company_item->approval_date, ['class' => 'form-control date_field', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'data-date'=> \Carbon\Carbon::today()->format('m/d/Y') , 'id' => 'approval_date' , 'required' => false]) !!}
                <div class="input-group-append">
                    <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('Renewal Date') !!}
            <div class="input-group">
                {!! Form::text('renewal_date', $company_item->renewal_date, ['class' => 'form-control date_field', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'data-date'=> \Carbon\Carbon::today()->addYear()->format('m/d/Y'), 'id' => 'renewal_date' ,'required' => false]) !!}
                <div class="input-group-append">
                    <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div>

        <?php /* <div class="form-group">
            {!! Form::label('Registered Date') !!}
            <div class="input-group">
                {!! Form::text('registered_date', $company_item->registered_date, ['class' => 'form-control
                month_field', 'autocomplete' => 'off', 'placeholder' => 'MM/YYYY', 'required' => false]) !!}
                <div class="input-group-append">
                    <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div> */ ?>


        <div class="form-group">
            {!! Form::label('Most Recent BG check') !!} 
            {!! Form::text('bg_check_date', $bg_check_date, ['class' => 'form-control', 'placeholder' => 'MM/DD/YYYY', 'data-toggle' => 'input-mask', 'data-mask-format' => '00/00/0000', 'autocomplete' => 'off']) !!}
        </div>
        
        <div class="form-group">
            {!! Form::label('Company Registration Date (MAP)') !!}
            <div class="input-group">
                {!! Form::text('created_at', $company_item->created_at->format('m/d/Y'), ['class' => 'form-control date_field', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'id' => 'created_at' ,'disabled' => true]) !!}
                <div class="input-group-append">
                    <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div>


        @if (isset($admin_form) && $admin_form == true)
        <div class="form-group">
            {!! Form::label('Founding Member?') !!}
            {!! Form::select('is_founding_member', ['no' => 'No', 'yes' => 'Yes'],  $company_item->is_founding_member, ['class' => 'custom-select',
            'placeholder' => 'Select', 'required' => false]) !!}
        </div>
        @endif


    </div>
</div>

<div class="text-left">
    <button type="submit" class="btn btn-sm btn-primary btn-rounded width-sm waves-effect waves-light">Update</button>
</div>
{!! Form::close() !!}


@push('_edit_company_profile_js')
<link href="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<script src="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
    $(function () {

        $('#membership_status').change(function(){

            if ($(this).val() == 'Approved'){

                if ($('#approval_date').val() == ''){
                    $('#approval_date').val($('#approval_date').data('date'));
                }
                if ($('#renewal_date').val() == ''){
                    $('#renewal_date').val($('#renewal_date').data('date'));
                }
            }
        });


        $('.month_field').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'mm/yyyy',
            startView: "months",
            minViewMode: "months"
        });

        $("#membership_level_id").on("change", function (){
            var membership_level_id = $(this).val();

            $.ajax({
                url: '{{ url("admin/companies/get-membership-status-from-level") }}',
                type: 'POST',
                data: {'membership_level_id': membership_level_id, '_token': '{{ csrf_token() }}'},
                success: function (data){
                    if (typeof data.success !== 'undefined'){
                        Swal.fire({
                            title: "Error",
                            text: "No status found with selected level.",
                            type: "warning",
                        });
                    } else {
                        $("#membership_status").html(data);
                    }
                }
            });
        });
    });
</script>
@endpush
