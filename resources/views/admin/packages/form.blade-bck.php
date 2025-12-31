<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Package Name') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' =>
            'Enter Package Name', 'required' => true]) !!}
        </div>
    </div>
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
            {!! Form::label('BG Check/Pre-Screen Fee Amount') !!}
            {!! Form::text('bg_pre_screen_fee', null, ['class' => 'form-control', 'id' => 'bg_pre_screen_fee', 'placeholder' => 'Enter BG Check/Pre-Screen Fee Amount', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Setup fee amount') !!}
            {!! Form::text('setup_fee', ((isset($setup_fee) && !is_null($setup_fee->amount)) ? number_format($setup_fee->amount, 2) : null), ['class' => 'form-control', 'id' => 'setup_fee', 'placeholder' => 'Enter Setup fee amount', 'required' => true]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Trade') !!}
            {!! Form::select('trade_id', $trades, null, ['class' => 'form-control custom-select', 'id' => 'trade_id', 'placeholder' => 'Select Trade', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-12 top_level_categories_container"></div>

    <div class="col-md-6">
	    <div class="form-group">
	        <label for="main_category">Main Category</label>
	        {!! Form::select('main_category_id', [], null, ['id' => 'main_category_id','class' =>
	        'form-control custom-select', 'required' => true, 'placeholder' => 'Select']) !!}
	    </div>
    </div>

    <div class="col-md-12 main_service_category_container"></div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="main_category">Secondary Category</label>
            {!! Form::select('secondary_main_category_id', [], null, ['id' => 'secondary_main_category_id','class' => 'form-control custom_select', 'required' => false, 'placeholder' => 'Select']) !!}
        </div>
    </div>

    <div class="col-md-12 secondary_service_category_container"></div>


    <div class="col-md-12">
    	<label>Would you like to see additional service category listings?</label>

    	<div class="checkbox checkbox-danger">
		    <input type="checkbox" name="include_rest_categories" class="include_rest_categories last_input"
		        id="include_rest_categories_no" value="no" />
		    <label for="include_rest_categories_no">No</label>
		</div>
    </div>

    <div class="col-md-12 rest_service_category_container"></div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Todays Charges') !!}
            {!! Form::text('todays_total_fee', null, ['class' => 'form-control', 'id' => 'todays_total_fee', 'placeholder' => 'Todays Charges', 'readonly' => true]) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
	<div class="col-md-12">
		<h4>Charges on Approval</h4>
		
		<div class="form-group">
            {!! Form::label('Annual Membership Fee') !!}
            {!! Form::text('membership_total_fee', null, ['class' => 'form-control',  'id' => 'membership_total_fee', 'placeholder' => 'Annual Membership Fee', 'readonly' => true]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Leads') !!}
            {!! Form::text('leads_total_fee', null, ['class' => 'form-control', 'id' => 'leads_total_fee', 'placeholder' => 'Leads', 'readonly' => true]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('(Suggested Products)') !!}
            {!! Form::text('suggested_product_total_fee', null, ['class' => 'form-control', 'id' => 'suggested_product_total_fee', 'placeholder' => '(Suggested Products)', 'readonly' => true]) !!}
        </div>
	</div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>


@push('page_scripts')

<script type="text/javascript">
	$(function (){
		/* Membership change start */
		$("#membership_level_id").on("change", function (){
			var level_id = $(this).val();
			var type = 'membership';
			
			$.ajax({
				url: '{{ url("admin/packages/get-fees") }}',
				type: 'POST',
				data: {'type': type, 'level_id': level_id, '_token': '{{ csrf_token() }}'},
				success: function (data){
					if (data.success){
						$("#membership_total_fee").val(data.fees);
					}
				}
			});
		});
		/* Membership change end */


		/* Owner selection change start */
		$("#qty_of_owners").on("change", function (){
			var owners_qty = $(this).val();
			var type = 'owner_selection';
			
			$.ajax({
				url: '{{ url("admin/packages/get-fees") }}',
				type: 'POST',
				data: {'type': type, 'owners_qty': owners_qty, '_token': '{{ csrf_token() }}'},
				success: function (data){
					if (data.success){
						$("#bg_pre_screen_fee").val(data.fees);

						changeTodaysCharges();
					}
				}
			});
		});
		/* Owner selection change end */
	});


	/* Todays charge value change */
	function changeTodaysCharges(){
		var bg_pre_screen_fee = $("#bg_pre_screen_fee").val();
		console.log ($("#setup_fee").val());
		var setup_fee = $("#setup_fee").val();

		var todays_charge = parseFloat(bg_pre_screen_fee) + parseFloat(setup_fee);
		$("#todays_total_fee").val(todays_charge.toFixed(2));
	}
</script>
@include('admin.packages._service_categories_js')
@endpush