<?php /*
{!! Form::open(['onsubmit' => 'return false;']) !!}
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::text('zipcode_search', null, ['class' => 'form-control', 'id' => 'search_zipcode', 'placeholder' => 'Search Zipcode', 'autocomplete' => 'off']) !!}
        </div>
    </div>
</div>

{!! Form::close() !!}
*/ ?>

<div class="service_area_list">

    <div class="row">
    @foreach ($company_service_areas AS $service_area_item)

    <div class="col-md-4 col-sm-6">
        <div class="service_area_item">
            {{ $service_area_item->zip_code }}{{ (!is_null($service_area_item->city)) ? ', '.$service_area_item->city : '' }}
        </div>
    </div>

    @endforeach
    </div>
</div>


