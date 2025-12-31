@php
$search_box_class = 'hide';
if ((Request::has('search') && count(Request::get('search')) > 0) || (Request::has('search_text') &&
Request::get('search_text') != '')){
$search_box_class = '';
}
@endphp
<div id="search-box" class="box-toggle card-box {{ $search_box_class }} search-box">
    @if (isset($moduleTitle))
    <h4 class="m-t-0 header-title">Search {!! $moduleTitle !!}</h4>
    @endif
    {!! Form::open(['method' => 'get']) !!}
    
    @if (Request::has('email_type') && Request::get('email_type') != '')
    {!! Form::hidden('email_type', Request::get('email_type')) !!}
    @endif
    
    @if (Request::has('email_for') && Request::get('email_for') != '')
    {!! Form::hidden('email_for', Request::get('email_for')) !!}
    @endif
    
    @if (Request::has('artwork_type') && Request::get('artwork_type') != '')
    {!! Form::hidden('artwork_type', Request::get('artwork_type')) !!}
    @endif

    @if (Request::has('domain_slug') && Request::get('domain_slug') != '')
    {!! Form::hidden('domain_slug', Request::get('domain_slug')) !!}
    @endif
    
    <div class="row">
        @if(isset($with_date) && $with_date == 1)
        <div class="col-md-2">
            <div class="form-group">
                <label for="exampleInputEmail1">Start Date</label>
                <div class="input-group">
                    <input type="text" class="form-control date_field" placeholder="dd/mm/yyyy" name="from_date"
                        value="{{ Request::get('from_date') }}">
                    <span class="input-group-addon bg-custom b-0"><i class="mdi mdi-calendar text-white"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="exampleInputEmail1">End Date</label>
                <div class="input-group">
                    <input type="text" class="form-control date_field" placeholder="dd/mm/yyyy" name="to_date"
                        value="{{ Request::get('to_date') }}">
                    <span class="input-group-addon bg-custom b-0"><i class="mdi mdi-calendar text-white"></i></span>
                </div>
            </div>
        </div>
        @endif

        @if (isset($search) && is_array($search) && count($search) > 0)

        @if (Request::has('search') && is_array(Request::get('search')))
        @php $search_qry_str = Request::get('search'); @endphp
        @endif

        @foreach ($search as $search_field_key => $search_field)
        <div class="col-md-3">
            <div class="form-group">
                <label for="search_field">{{ $search_field['title'] }}</label>
                {!! Form::select('search[' . $search_field_key . ']', !is_null($search_field['options']) ?
                $search_field['options'] : [],
                isset($search_qry_str[$search_field_key]) ? $search_qry_str[$search_field_key] : null, ['class' =>
                'form-control custom-select '.(isset($search_field['class'])
                ? $search_field['class'] : ''), 'placeholder' => 'Select ' . $search_field['title'], 'id' =>
                isset($search_field['id']) ? $search_field['id'] : '' ]) !!}
            </div>
        </div>
        @endforeach
        @endif

        @if (isset($searchColumns) && $searchColumns != null)
        <div class="col-md-3">
            <div class="form-group">
                <label for="search_field">Search By</label>
                {!! Form::select('search_field', $searchColumns, Request::get('search_field'), ['class' => 'form-control custom-select', 'id' => 'search_field']) !!}

            </div>
        </div>
        
        <div class="col-md-3" id="mile_range_filter" style="{{ ((Request::get('search_field') != 'companies.main_zipcode') ? 'display:none;' : '') }}">
            <div class="form-group">
                <label for="search_text">Mile range</label>
                {!! Form::select ('mile_range', config('config.mile_options'), Request::get('mile_range'), ['id' => 'mile_range' ,'class' => 'form-control custom-select', 'placeholder' => 'Select Zip radius'])!!}
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="search_text">Search Text</label>
                {!! Form::text('search_text', Request::get('search_text'), ['class' => 'form-control'] ) !!}
            </div>
        </div>
        @endif
        <div class="col-md-3">
            <div class="form-group">
                <label for="">&nbsp;</label>
                <div class="clearfix"></div>
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ $module_urls['list'] }}" class="btn btn-dark reset_button">Reset</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
