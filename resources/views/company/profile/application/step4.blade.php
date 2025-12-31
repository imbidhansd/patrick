{!! Form::model($company_customer_reference, ['url' => 'account/application/customer-references','id' =>
'company_customer_references_form', 'class' => 'module_form', 'files' => true])
!!}

{!! Form::hidden('ref_type', null, ['id' => 'ref_type']) !!}

<h4 class="mb-3">Customer References And Professional Affiliations</h4>
<h3 class="card-title text-primary mb-0">Customer References</h3>

<p>Please list 5 past references. 1 or 2 for each year for the past 3 to 5 years.</p>
<div class="form-group">
    <label for="">Would you like to submit your customer references now or email them upon on completion? <span class="required">*</span></label>

    <div class="radio radio-primary radio-circle">
        {!! Form::radio("ref_type",
        'Customer References', null, ['id' => 'submit_your_customer_references_now_or_email_customer_references', 'class' => 'submit_your_customer_references_now_or_email', 'required' => true, 'data-parsley-errors-container' => '#submit_your_customer_references_now_or_email_error']) !!}
        <label for="submit_your_customer_references_now_or_email_customer_references">Complete and submit Customer References form now</label>
    </div>

    <div class="radio radio-primary radio-circle">
        {!! Form::radio("ref_type", 'Professional Affiliations', null, ['id' => 'submit_your_customer_references_now_or_email_professional_affiliations', 'class' => 'submit_your_customer_references_now_or_email', 'required' => true, 'data-parsley-errors-container' => '#submit_your_customer_references_now_or_email_error']) !!}
        <label for="submit_your_customer_references_now_or_email_professional_affiliations">Email Customer References form upon completing online application</label>
    </div>
</div>
<div id="submit_your_customer_references_now_or_email_error"></div>

<div class="customer_reference_title_text {{ !is_null($company_customer_reference) && $company_customer_reference->ref_type == 'Professional Affiliations' ? '' : 'hide' }}">
    <h5 class="mt-0">Customer Reference Form will be emailed upon completion of application</h5>
</div>
<div class="clearfix">&nbsp;</div>

<div class="card card-border card-primary {{ !is_null($company_customer_reference) && $company_customer_reference->ref_type == 'Customer References' ? '' : 'hide' }}"
     id="customer_reference_form">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">Customer Reference Form</h3>
        <p class="text-black-50 mb-0">Please include customer name, phone number, and date work was
            performed.</p>
    </div>

    <div class="card-body">
        <div class="row">
            <?php
            $customer_required = false;
            $customers = null;
            if (!is_null($company_customer_reference) && $company_customer_reference->customers != '') {

                if ($company_customer_reference->ref_type == 'Professional Affiliations') {
                    $customer_required = false;
                } elseif ($company_customer_reference->ref_type == 'Customer References') {
                    $customer_required = true;
                }
                $customers = json_decode($company_customer_reference->customers, true);
            }
            ?>

            @foreach(range(1,5) as $i)

            <?php
            $customer_item = [
                'name' => null,
                'phone' => null,
                'date' => null,
            ];

            if (isset($customers[$i]) && is_array($customers[$i])) {
                $customer_item = $customers[$i];
            }
            ?>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Customer Name #{{ $i }}: <span class="required">*</span></label>
                    {!! Form::text("customers[$i][name]", $customer_item['name'], ['class' =>
                    'form-control', 'required'
                    => $customer_required]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Customer Phone Number: <span class="required">*</span></label>
                    {!! Form::text("customers[$i][phone]", $customer_item['phone'], ['class' =>
                    'form-control', 'required'
                    => $customer_required,
                    'data-toggle' => 'input-mask',
                    'data-mask-format' => '(000) 000-0000'
                    ]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Date work performed: <span class="required">*</span> (mm/yyyy)</label>
                    {!! Form::text("customers[$i][date]", $customer_item['date'], ['class' => 'form-control
                    month_field',
                    'required' => $customer_required])
                    !!}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card card-border card-primary {{ !is_null($company_customer_reference) && $company_customer_reference->ref_type == 'Professional Affiliations' ? '' : 'hide' }}"
     id="professional_affiliations_form">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">Professional Affiliations</h3>
        <p class="text-black-50 mb-0">Are you a member or is your company a member of any professional
            affilations?</p>
    </div>

    <div class="card-body">

        <div class="form-group mb-0">
            <label>Please choose all that apply <span class="required">*</span> (You must check at least 1 option.Check "None" if you
                have no professional affiliations.)</label>

            <?php
            $a_none = $a_boma = $a_napa = $a_aci = $a_interlocking = $a_bbb = $a_angies = $a_home_advisor = $a_networx = $a_others = false;

            if (!is_null($company_customer_reference) && $company_customer_reference->professional_affiliations != '') {
                $professional_affiliations_arr = json_decode($company_customer_reference->professional_affiliations);

                if (is_array($professional_affiliations_arr)) {
                    if (in_array('None', $professional_affiliations_arr)) {
                        $a_none = true;
                    }

                    if (in_array('Other: (Please List)', $professional_affiliations_arr)) {
                        $a_others = true;
                    }
                }
            }
            ?>


            <div class="checkbox checkbox-primary">
                {!! Form::checkbox('professional_affiliations[]', 'None', $a_none, ['id' => 'a_none',
                'class'
                =>
                'cust_ref_opt_none', 'data-parsley-errors-container' => '#professional_affiliations_error']) !!}
                <label for="a_none">None</label>
            </div>

            @if (isset($professional_affiliations) && count($professional_affiliations) > 0)
            @foreach ($professional_affiliations AS $professional_affiliation_item)
            <div class="checkbox checkbox-primary">
                @php $checked = false; @endphp
                @if (isset($professional_affiliations_arr) && is_array($professional_affiliations_arr) && in_array($professional_affiliation_item, $professional_affiliations_arr))
                    @php $checked = true; @endphp
                @endif
                {!! Form::checkbox('professional_affiliations[]', $professional_affiliation_item, $checked, ['id' => $professional_affiliation_item, 'class' => 'cust_ref_opt_other', 'data-parsley-errors-container' => '#professional_affiliations_error']) !!}
                <label for="{{ $professional_affiliation_item }}">{{ $professional_affiliation_item }}</label>
            </div>
            @endforeach
            @endif

            <div class="checkbox checkbox-primary">
                {!! Form::checkbox('professional_affiliations[]',
                'Other: (Please List)', $a_others,
                ['id' => 'a_others', 'class' =>
                'cust_ref_opt_other', 'data-parsley-errors-container' => '#professional_affiliations_error']) !!}
                <label for="a_others">Other: (Please List)</label>
            </div>
        </div>
        <div id="professional_affiliations_error"></div>

        <div class="form-group {{ $a_others ? '' : 'hide' }} other_professional_affiliations">
            {!! Form::textarea('other_professional_affiliations', null, [
            'placeholder' => 'Explain Other Professional Affiliations Here',
            'class' => 'form-control',
            'id' => 'other_professional_affiliations',
            'required' => $a_others ? true : false,
            ]) !!}
        </div>


    </div>
</div>


<button type="button" class="btn btn-dark back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input step4_submit">Save & Next</button>


{!! Form::close() !!}

@push('page_scripts')

<link href="{{ asset('/') }}themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet"
      type="text/css" />
<script src="{{ asset('/') }}themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
$(function () {

    $('.month_field').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'mm/yyyy',
        startView: "months",
        minViewMode: "months"
    });

    $('#submit_your_customer_references_now_or_email_customer_references').change(function () {
        $('#professional_affiliations_form').hide().find('input').removeAttr('required').removeAttr('checked');
        $('#customer_reference_form').show().find('input').attr('required', true);
        $(".customer_reference_title_text").hide();
        refresh_slick_content();
    });

    $('#submit_your_customer_references_now_or_email_professional_affiliations').change(function () {
        $('#customer_reference_form').hide().find('input').removeAttr('required').val('');
        $('#professional_affiliations_form').show().find('input').attr('required', true);
        $(".customer_reference_title_text").show();
        refresh_slick_content();
    });

    $('.cust_ref_opt_other').click(function () {
        $('.cust_ref_opt_none').prop('checked', false);
    });
    $('.cust_ref_opt_none').click(function () {
        $('.cust_ref_opt_other').prop('checked', false);
    });

    $('#a_others').change(function () {
        if ($(this).is(':checked') == true) {
            $('.other_professional_affiliations').show();
            $('#other_professional_affiliations').attr('required', true);
        } else {
            $('.other_professional_affiliations').hide();
            $('#other_professional_affiliations').removeAttr('required');
        }
        refresh_slick_content();
    });

    // Submit Step

    $('#company_customer_references_form').submit(function () {
        // Ajax call of step 1 [Start]

        var form = $('#company_customer_references_form')[0]; // You need to use standard javascript object here
        var formData = new FormData(form);

        $(".step4_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: $('#company_customer_references_form').attr('action'),
            type: 'POST',
            data: formData,
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false, // NEEDED, DON'T OMIT THIS
            success: function (data) {
                slick_next();
                $(".step4_submit").removeAttr('disabled').html('Save & Next');
            },
            error: function (e) {
                alert('error');
                $(".step4_submit").removeAttr('disabled').html('Save & Next');
            },
        });
        // Ajax call of step 1 [End]
        return false;
    });
});
</script>
@endpush
