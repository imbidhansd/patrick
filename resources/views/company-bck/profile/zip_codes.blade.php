<?php
    $admin_page_title = 'Zip Code/Zip Code Radius';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-12">

                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white mb-0">Zip and Miles</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-left">
                            {!! Form::open(['url' => url('update-company-zipcode-list'), 'class' => 'module_form']) !!}
                            <p class="text-muted font-13">
                                <strong>Please enter the main zip code of your working territory. *</strong>
                                <br />
                                <span>A Region is defined by a 50 Mile radius of your center Zip Code. To purchase
                                    additional regions, please contact us at 720-445-4400.</span>
                            </p>

                            <div class="row">
                                <div class="col-sm-4">
                                    {!! Form::text('main_zipcode', $company_detail->main_zipcode, ['id' => 'zipcode',
                                    'class' => 'form-control', 'data-toggle' => 'input-mask', 'data-mask-format' =>
                                    '00000', 'readonly' => true]) !!}
                                </div>
                            </div>

                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <select name="mile_range" id="mile_range" class="form-control">
                                            <option value="">Select Zip radius</option>
                                            <?php
                                            for ($i=5;$i<55;$i = $i+5){
                                                if ($company_detail->mile_range == $i){
                                                    echo '<option value="'.$i.'" selected="selected">'.$i.' Miles</option>';
                                                } else {
                                                    echo '<option value="'.$i.'">'.$i.' Miles</option>';
                                                }
                                            }
                                            ?>

                                            <?php /* <option value="5">5 Miles</option>
                                            <option value="10">10 Miles</option>
                                            <option value="15">15 Miles</option>
                                            <option value="20">20 Miles</option>
                                            <option value="25">25 Miles</option>
                                            <option value="30">30 Miles</option>
                                            <option value="35">35 Miles</option>
                                            <option value="40">40 Miles</option>
                                            <option value="45">45 Miles</option>
                                            <option value="50">50 Miles</option> */ ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="googlemapborder">
                                <div id="map-canvas" style="height:300px;"></div>
                            </div>

                            <div class="clearfix">&nbsp;</div>
                            <p class="text-muted font-13">
                                <strong>All zip codes below fall within the ZIP code radius for the main zip code you
                                    have chosen. Please deselect zip codes you do not service or edit your zip code and
                                    increase/decrease your zip code radius. If you would like to unsubscribe, you can do
                                    so by clicking on the Unsubscribe button in the upper-right hand corner of this
                                    page.</strong>
                            </p>

                            <div id="zip_code_list">
                                @if (isset($company_zip_codes) && count($company_zip_codes) > 0)
                                <div class="row">
                                    @foreach ($company_zip_codes AS $zip_code_item)
                                    <div class="col-sm-4">
                                        <ul class="pl20">
                                            <li>
                                                <div class="checkbox checkbox-primary">
                                                    <input name="zipcode_item[]" value="{{ $zip_code_item->zip_code }}"
                                                        type="checkbox" checked />
                                                    <label for="">
                                                        {{ $zip_code_item->zip_code.', '.$zip_code_item->city.', ('.$zip_code_item->distance.' miles)' }}
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <div class="text-left">
                                <button type="submit"
                                    class="btn btn-sm btn-primary btn-rounded width-sm waves-effect waves-light">Update</button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')
@include('company.profile._js')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}"></script>
<script src="{{ asset('js/zipcode-radius.js') }}"></script>
<script type="text/javascript">
    $(function (){
        $('#mile_range').change(function(){
            if ($(this).val() > 0){
                getGoogleMaps($(this).val());
            }else{
                getGoogleMaps(1);
            }

            var zipcode = '{{ $company_detail->main_zipcode }}';
            var mile_range = $(this).val();

            $.ajax({
                url: '{{ url("zipcode-list-display") }}',
                type: 'POST',
                data: {'zipcode': zipcode, 'mile_range': mile_range, '_token': '{{ csrf_token() }}'},
                success: function (data){
                    if (typeof data.status !== 'undefined'){
                        alert (data.message);
                    } else {
                        $("#zip_code_list").html(data);
                    }
                }
            });
        });
    });
</script>
@endsection
