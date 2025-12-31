<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Coupon type') !!}
            {!! Form::select('coupon_type_id', $coupon_types, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Coupon Type', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Product') !!}
            {!! Form::select('product_id', $products, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Product', 'required' => false]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Coupon Code') !!}
            {!! Form::text('coupon_code', null, ['class' => 'form-control', 'placeholder' => 'Enter Coupon Code', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Coupon Amount') !!}
            {!! Form::text('coupon_amount', null, ['class' => 'form-control', 'placeholder' => 'Enter Coupon Amount', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Usage Limit') !!}
            {!! Form::text('usage_limit', null, ['class' => 'form-control', 'placeholder' => 'Enter Usage Limit', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Expiration Date') !!}
            {!! Form::text('expiration_date', null, ['class' => 'form-control', 'data-toggle' => 'input-mask', 'data-mask-format' => '00/00/0000', 'placeholder' => 'MM/DD/YYYY', 'required' => true]) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Description') !!}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'required' => false]) !!}
            
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}
            </div>
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>