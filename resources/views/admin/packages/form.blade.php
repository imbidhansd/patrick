<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Company') !!}
            {!! Form::select('company_id', $company_list, null, ['class' => 'form-control select2', 'placeholder' => 'Select Company', 'id' => 'company_id', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Company Email') !!}
            {!! Form::email('company_email', null, ['class' => 'form-control', 'id' => 'company_email', 'placeholder' => 'Enter Company Email', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Package Name') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Package Name', 'required' => true]) !!}
        </div>
    </div>

    @if (isset($formObj))
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Package Code') !!}
            {!! Form::text('package_code', null, ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
    </div>
    @endif

</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Membership Level') !!}
            {!! Form::select('membership_level_id', $membership_levels, null, ['class' => 'form-control custom-select', 'id' => 'membership_level_id', 'placeholder' => 'Select Membership Level', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Qty of Owners') !!}
            {!! Form::select('qty_of_owners', $owner_qty, null, ['class' => 'form-control custom-select', 'id' => 'qty_of_owners', 'placeholder' => 'Select Qty of Owners', 'required' => true]) !!}
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('BG Check/Pre-Screen First Owner Fee Amount') !!}
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                {!! Form::text('bg_pre_screen_first_owner_fee', null, ['class' => 'form-control text-right', 'id' => 'bg_pre_screen_first_owner_fee', 'placeholder' => 'Enter Amount', 'required' => true]) !!}
            </div>

        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('BG Check/Pre-Screen Other Owner Fee Amount') !!}
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                {!! Form::text('bg_pre_screen_other_owner_fee', null, ['class' => 'form-control text-right', 'id' => 'bg_pre_screen_other_owner_fee', 'placeholder' => 'Enter Amount', 'required' => true]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Setup fee amount') !!}
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                
                @if (isset($formObj))
                    @php
                        $setup_fee = $formObj->setup_fee;
                    @endphp
                @else
                    @php
                        $setup_fee = ((isset($setup_fee) && !is_null($setup_fee->price)) ? number_format($setup_fee->price, 2, '.', '') : null);
                    @endphp
                @endif
                {!! Form::text('setup_fee', $setup_fee, ['class' => 'form-control text-right', 'id' => 'setup_fee', 'placeholder' => 'Enter Amount', 'required' => true]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Todays Charges') !!}
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                {!! Form::text('todays_total_fee', null, ['class' => 'form-control text-right', 'id' =>
                'todays_total_fee', 'placeholder' => 'Todays Charges', 'readonly' => true]) !!}
            </div>
        </div>
    </div>
</div>

<div class="row">

</div>

<hr />

@if (isset($products) && count($products) > 0)
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Products') !!}
            <select name="products[]" class="form-control select2" id="products" multiple>
                @foreach ($products AS $product_item)
                @php
                $selected = "";
                @endphp
                @if (isset($selected_products) && count($selected_products) > 0 && in_array($product_item->id,
                $selected_products))
                @php
                $selected = "selected";
                @endphp
                @endif

                <option value="{{ $product_item->id }}" data-price="{{ $product_item->price }}" {{ $selected }}>
                    {{ $product_item->title }}</option>
                @endforeach
            </select>

            <table class="table" id="selected_product_table">
                @if (isset($formObj) && count($formObj->package_products) > 0)
                @foreach ($formObj->package_products As $package_product_item)
                <tr class="{{ $package_product_item->product_id }}">
                    <th>{{ $package_product_item->product->title }}</th>
                    <td>
                        {!! Form::text('product_price['.$package_product_item->product_id.']',
                        $package_product_item->product_price, ['class' => 'form-control product_price']) !!}
                    </td>
                </tr>
                @endforeach
                @endif
            </table>
        </div>
    </div>
</div>
<hr />
@endif


<h4>Charges on Approval</h4>
<div class="row">
    <div class="col-md-6">

        <div class="form-group">
            {!! Form::label('Membership Fee') !!}
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                @php
                    $membership_price = null;
                    if (isset($formObj) && !is_null($formObj->membership_total_fee)){
                        $membership_price = $formObj->membership_total_fee;
                    } else {
                        if (isset($membership_fee) && !is_null($membership_fee->price)){
                            $membership_price = $membership_fee->price;
                        }
                    }
                @endphp
                
                {!! Form::text('membership_fee', $membership_price, ['class' => 'form-control text-right', 'id' => 'membership_fee', 'placeholder' => 'Annual Membership Fee', 'readonly' => false]) !!}
                <?php /* {!! Form::text('membership_fee', ((isset($membership_fee) && !is_null($membership_fee->price)) ? number_format($membership_fee->price, 2, '.', '') : null), ['class' => 'form-control text-right', 'id' => 'membership_fee', 'placeholder' => 'Annual Membership Fee', 'readonly' => false]) !!} */ ?>

            </div>
            {!! Form::hidden('membership_total_fee', $membership_price, ['id' => 'membership_total_fee']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('(Suggested Products)') !!}
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                </div>
                {!! Form::text('suggested_product_total_fee', null, ['class' => 'form-control text-right', 'id' =>
                'suggested_product_total_fee', 'placeholder' => '(Suggested Products)', 'readonly' => true]) !!}
            </div>
        </div>
    </div>


</div>
<div class="clearfix"></div>
<hr/>
<div class="row">
    <div class="col-md-12">
        <h6>Addendum</h6>
        {!! Form::textarea('addendum', null, ['class' => 'form-control ckeditor']) !!}
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>


@push('page_scripts')
<link href="{{ asset('/') }}/themes/admin/assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />

<script src="{{ asset('/') }}/themes/admin/assets/libs/select2/select2.min.js"></script>

<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>

<script type="text/javascript">
$(function () {
    $('.select2').select2();

    /* Get first owner company email start */
    $("#company_id").on("change", function () {
        var company_id = $(this).val();

        $.ajax({
            url: '{{ url("admin/packages/get-company-owner-email") }}',
            type: 'POST',
            data: {'company_id': company_id, '_token': '{{ csrf_token() }}'},
            success: function (data) {
                if (data.success) {
                    $("#company_email").val(data.email);
                }
            }
        });
    });
    /* Get first owner company email end */


    $('#membership_level_id').change(function () {
        if ($(this).val() == '6') { // 6 = PPL
            $('#ppl_monthly_budget').removeAttr('readonly');
        } else {
            $('#ppl_monthly_budget').attr('readonly', true);
            $('#ppl_monthly_budget').val('');
        }
    });

    $('#membership_level_id').trigger('change');

    /* Owner selection change start */
    $("#qty_of_owners").on("change", function () {
        var owners_qty = $(this).val();
        var type = 'owner_selection';

        $.ajax({
            url: '{{ url("admin/packages/get-fees") }}',
            type: 'POST',
            data: {'type': type, 'owners_qty': owners_qty, '_token': '{{ csrf_token() }}'},
            success: function (data) {
                if (data.success) {
                    /*$("#bg_pre_screen_fee").val(data.fees);*/
                    $("#bg_pre_screen_first_owner_fee").val(data.first_owner_fee);
                    $("#bg_pre_screen_other_owner_fee").val(data.other_owner_fee);

                    changeTodaysCharges();
                }
            }
        });
    });
    /* Owner selection change end */


    /* Products selection change start */
    $("#products").on("change", function () {
        $("#products option").each(function () {
            var product_name = $(this).text();
            var product_price = $(this).data("price");
            var product_id = $(this).attr("value");

            if ($(this).is(":selected")) {
                if (!$("#selected_product_table tr").hasClass(product_id)) {
                    var appendTr = '<tr class="' + product_id + '"><th>' + product_name + '</th><td><input type="text" name="product_price[' + product_id + ']" value="' + product_price + '" class="form-control product_price" /></td></tr>';

                    $("#selected_product_table").append(appendTr);
                }
            } else {
                $("#selected_product_table tr." + product_id).remove();

                if ($("#selected_product_table tr").length == 0) {
                    $("#suggested_product_total_fee").val('0.00');
                }
            }

            $(".product_price").trigger("blur");
        });
    });

    $(document).on("blur", ".product_price", function () {
        var product_total_price = 0;
        $(".product_price").each(function () {
            if ($(this).val() != '') {
                product_total_price += parseFloat($(this).val());
            }
        });

        $("#suggested_product_total_fee").val(product_total_price.toFixed(2));
    });
    /* Products selection change end */



    /* Price change start */
    $("#bg_pre_screen_fee, #setup_fee").on("blur", function () {
        changeTodaysCharges();
    });
    /* Price change end */


    /* Membership fee change event start */
    $("#membership_fee").on("blur", function () {
        $("#membership_total_fee").val($(this).val());
    });
    /* Membership fee change event end */
});


/* Todays charge value change */
function changeTodaysCharges() {
    var bg_pre_screen_first_owner_fee = $("#bg_pre_screen_first_owner_fee").val();
    var bg_pre_screen_other_owner_fee = $("#bg_pre_screen_other_owner_fee").val();
    var setup_fee = $("#setup_fee").val();

    if (typeof bg_pre_screen_first_owner_fee !== 'undefined' && bg_pre_screen_first_owner_fee != '' && typeof setup_fee !== 'undefined' && setup_fee != '') {
        var todays_charge = parseFloat(bg_pre_screen_first_owner_fee) + parseFloat(setup_fee);
        if (typeof bg_pre_screen_other_owner_fee !== 'undefined' && bg_pre_screen_other_owner_fee != '') {
            todays_charge += parseFloat(bg_pre_screen_other_owner_fee);
        }

        $("#todays_total_fee").val(todays_charge.toFixed(2));
    }
}

CKEDITOR.replace('addendum', {
    filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
    filebrowserUploadMethod: 'form'
});
</script>
@endpush
