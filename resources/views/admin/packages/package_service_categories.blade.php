@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'], $admin_page_title => '']])

@include('flash::message')



<div class="card">
    <div class="card-header">Package Information</div>
    <div class="card-body p-0">
        <?php 
            /*echo '<pre>';
                print_r ($package->toArray());
            echo '</pre>'; */
        ?>    

        <table class="table table-striped">
            <tr>
                <td>Package Title</td>
                <td>{{ $package->title }}</td>
            </tr>
            <tr>
                <td>Company Name</td>
                <td>{{ $package->company->company_name }}</td>
            </tr>
            @if ($package->package_code != '')
            <tr>
                <td>Package Code</td>
                <td>
                    <span data-toggle="tooltip" data-placement="top" data-clipboard-action="copy" id="var_{{ $package->id }}" data-clipboard-target="#var_{{ $package->id }}" class="badge badge-info badge-label variable">{{ $package->package_code }}</span>
                </td>
            </tr>
            @endif
            <tr>
                <td>Membership Level</td>
                <td>{{ $package->membership_level->title }}</td>
            </tr>
            <tr>
                <td>Number Of Owners</td>
                <td>{{ $package->qty_of_owners }}</td>
            </tr>

            <tr>
                <td>BG Check/Pre-Screen First Owner Fee Amount</td>
                <td>${{ number_format($package->bg_pre_screen_first_owner_fee, 2) }}</td>
            </tr>

            <tr>
                <td>BG Check/Pre-Screen Other Owner Fee Amount</td>
                <td>${{ number_format($package->bg_pre_screen_other_owner_fee, 2) }}</td>
            </tr>

            <tr>
                <td>Setup Fee</td>
                <td>${{ $package->setup_fee }}</td>
            </tr>

            <tr>
                <td>Today's Total Fee</td>
                <td>${{ $package->todays_total_fee }}</td>
            </tr>
            <tr>
                <td>Membership Total Fee</td>
                <td>${{ $package->membership_total_fee }}</td>
            </tr>
            @if (count($package->package_products) > 0)
            <tr>
                <td>Products</td>
                <td>
                    @foreach ($package->package_products AS $product_item)
                    <b>{{ $product_item->product->title }}:</b>
                    <span>{{ number_format($product_item->product_price, 2) }}</span> <br />
                    @endforeach
                </td>
            </tr>
            @endif
        </table>
    </div>
</div>

<div class="card-box">

    @include('admin.includes.formErrors')

    {!! Form::open(['url' => url('admin/packages/service-categories'), 'class' => 'module_form', 'files' => true]) !!}
    {!! Form::hidden('package_id', $package->id) !!}

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('Select Trade') !!}
                {!! Form::select('trade_id', $trades, ((!is_null($package->trade_id)) ? $package->trade_id : null) , ['class' => 'form-control custom-select', 'id' => 'trade_id', 'placeholder' => 'Select Trade', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-md-12 top_level_categories_container"></div>

        <div class="col-md-6">
            <div class="clearfix">&nbsp;</div>
            <div class="form-group">
                <label for="main_category">Main Category</label>
                {!! Form::select('main_category_id', [], null, ['id' => 'main_category_id','class' =>
                'form-control custom-select', 'required' => true, 'placeholder' => 'Select']) !!}
            </div>
            <div class="clearfix">&nbsp;</div>
        </div>

        <div class="col-md-12 main_service_category_container"></div>

        <div class="col-md-6">
            <div class="clearfix">&nbsp;</div>
            <div class="form-group">
                <label for="main_category">Secondary Category</label>
                {!! Form::select('secondary_main_category_id', [], null, ['id' => 'secondary_main_category_id','class' => 'form-control custom-select', 'required' => false, 'placeholder' => 'Select']) !!}
            </div>
            <div class="clearfix">&nbsp;</div>
        </div>

        <div class="col-md-12 secondary_service_category_container"></div>

        <div class="col-md-12">
            <div class="clearfix">&nbsp;</div>
            <label>Would you like to see additional service category listings?</label>

            <div class="checkbox checkbox-danger">
                <input type="checkbox" name="include_rest_categories" class="include_rest_categories last_input" id="include_rest_categories_no" value="no" {{ ((!is_null($package->include_rest_categories) && $package->include_rest_categories == 'no') ? 'checked="checked"' : '') }} />
                <label for="include_rest_categories_no">No</label>
            </div>
            <div class="clearfix">&nbsp;</div>
        </div>

        <div class="col-md-12 rest_service_category_container"></div>
    </div>

    <hr />
    <button type="submit" class="btn btn-info float-right waves-effect waves-light submit_package_categories">Submit</button>
    {!! Form::close() !!}
</div>
@stop


@section('page_js')
@include('admin.packages._service_categories_js')
@stop

