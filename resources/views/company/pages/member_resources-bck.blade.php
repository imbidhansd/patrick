@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-sm-9">

        @if ($company_item ->is_founding_member == 'yes' && isset($founding_item_list) && !is_null($founding_item_list) && count($founding_item_list) > 0)
        <div class="card">
            <div class="card-header bg-secondary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#founding_member" role="button" aria-expanded="false"
                        aria-controls="founding_member"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-white" href="#founding_member" role="button" aria-expanded="false"
                        aria-controls="founding_member">Founding Member</a>
                </h5>
            </div>

            <div id="founding_member" class="collapse show">
                <div class="card-body">
                    <div class="banners">

                        <div class="row">
                            @foreach ($founding_item_list AS $banner_item)
                            <div class="col-md-4 text-center">
                                <div class="item {{ isset($company_logo_item) && $company_logo_item->site_logo_id == $banner_item->id ? 'active' : '' }}">
                                    @if ($banner_item->banner_url != '')
                                    <a href="{{ $banner_item->banner_url }}"
                                        data-fancybox="gallery_image_{{ $banner_item->id }}"><img src="{{ $banner_item->banner_url }}" class="img-thumbnail" />
                                    </a>
                                    @endif

                                    <p class="caption_title text-center">{{ $banner_item->title }}
                                        <br />
                                        <i class="text-info">Size: {{ $banner_item->size }}</i>
                                    </p>

                                    <div class="btn-group text-center">
                                        <a href="{{  $banner_item->banner_url }}"
                                            data-fancybox="gallery_{{ $banner_item->id }}"
                                            class="btn btn-dark btn-xs">View</a>
                                        <a href="javascript:;" data-banner_id="{{ $banner_item->id }}"
                                            class="btn {{ isset($company_logo_item) && $company_logo_item->site_logo_id == $banner_item->id ? 'btn-success' : 'btn-info' }} select_banner_btn btn-xs">
                                            @if (isset($company_logo_item) && $company_logo_item->site_logo_id == $banner_item->id)
                                            <i class="fas fa-check"></i> Selected
                                            @else
                                            Select
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>


                            @if ($loop->iteration % 3 == 0)
                        </div>
                        <hr />
                        <div class="row">
                            @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif



        @if (isset($official_item_list) && !is_null($official_item_list) && count($official_item_list) > 0)
        <div class="card">
            <div class="card-header bg-secondary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#official_member" role="button" aria-expanded="false"
                        aria-controls="official_member"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-white" href="#official_member" role="button" aria-expanded="false"
                        aria-controls="official_member">Official Member</a>
                </h5>
            </div>

            <div id="official_member" class="collapse show">
                <div class="card-body">
                    <div class="banners">

                        <div class="row">
                            @foreach ($official_item_list AS $banner_item)
                            <div class="col-md-4 text-center">
                                <div class="item {{ isset($company_logo_item) && $company_logo_item->site_logo_id == $banner_item->id ? 'active' : '' }}">
                                    @if ($banner_item->banner_url != '')
                                    <a href="{{ $banner_item->banner_url }}"
                                        data-fancybox="gallery_image_{{ $banner_item->id }}"><img src="{{ $banner_item->banner_url }}" class="img-thumbnail" />
                                    </a>
                                    @endif

                                    <p class="caption_title text-center">{{ $banner_item->title }}
                                        <br />
                                        <i class="text-info">Size: {{ $banner_item->size }}</i>
                                    </p>

                                    <div class="btn-group text-center">
                                        <a href="{{  $banner_item->banner_url }}"
                                            data-fancybox="gallery_{{ $banner_item->id }}"
                                            class="btn btn-dark btn-xs">View</a>
                                        <a href="javascript:;" data-banner_id="{{ $banner_item->id }}"
                                            class="btn {{ isset($company_logo_item) && $company_logo_item->site_logo_id == $banner_item->id ? 'btn-success' : 'btn-info' }} select_banner_btn btn-xs">
                                            @if (isset($company_logo_item) && $company_logo_item->site_logo_id == $banner_item->id)
                                            <i class="fas fa-check"></i> Selected
                                            @else
                                            Select
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>


                            @if ($loop->iteration % 3 == 0)
                        </div>
                        <hr />
                        <div class="row">
                            @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif


        @if (isset($recommended_item_list) && !is_null($recommended_item_list) && count($recommended_item_list) > 0)
        <div class="card">
            <div class="card-header bg-secondary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#recommended_member" role="button" aria-expanded="false"
                        aria-controls="recommended_member"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-white" href="#recommended_member" role="button" aria-expanded="false"
                        aria-controls="recommended_member">Recommended Member</a>
                </h5>
            </div>

            <div id="recommended_member" class="collapse show">
                <div class="card-body">
                    <div class="banners">

                        <div class="row">
                            @foreach ($recommended_item_list AS $banner_item)
                            <div class="col-md-4 text-center">
                                <div class="item {{ isset($company_logo_item) && $company_logo_item->site_logo_id == $banner_item->id ? 'active' : '' }}">
                                    @if ($banner_item->banner_url != '')
                                    <a href="{{ $banner_item->banner_url }}"
                                        data-fancybox="gallery_image_{{ $banner_item->id }}"><img src="{{ $banner_item->banner_url }}" class="img-thumbnail" />
                                    </a>
                                    @endif

                                    <p class="caption_title text-center">{{ $banner_item->title }}
                                        <br />
                                        <i class="text-info">Size: {{ $banner_item->size }}</i>
                                    </p>

                                    <div class="btn-group text-center">
                                        <a href="{{  $banner_item->banner_url }}"
                                            data-fancybox="gallery_{{ $banner_item->id }}"
                                            class="btn btn-dark btn-xs">View</a>
                                        <a href="javascript:;" data-banner_id="{{ $banner_item->id }}"
                                            class="btn {{ isset($company_logo_item) && $company_logo_item->site_logo_id == $banner_item->id ? 'btn-success' : 'btn-info' }} select_banner_btn btn-xs">
                                            @if (isset($company_logo_item) && $company_logo_item->site_logo_id == $banner_item->id)
                                            <i class="fas fa-check"></i> Selected
                                            @else
                                            Select
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>


                            @if ($loop->iteration % 3 == 0)
                        </div>
                        <hr />
                        <div class="row">
                            @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif




        <div class="card" id="set_form_card">
            <div class="card-header bg-secondary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#set_form" role="button" aria-expanded="false"
                        aria-controls="set_form"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-white" href="#set_form" role="button" aria-expanded="false"
                        aria-controls="set_form">Website URL</a>
                </h5>
            </div>

            <div id="set_form" class="collapse show">
                <div class="card-body">
                    {!! Form::model($company_logo_item, ['url' => 'member-resources/set-company-logo-banner', 'class' => 'module_form', 'id' => 'member_resource_form']) !!}
                    {!! Form::hidden('company_id', $company_item->id) !!}
                    {!! Form::hidden('site_logo_id', null, ['id' => 'set_site_logo_id']) !!}

                    <div class="form-group">
                        {!! Form::label('Your company website url') !!}
                        {!! Form::text('url', !is_null($company_logo_item) ? $company_logo_item->url : null, ['class'
                        => 'form-control', 'placeholder' => 'URL', 'required' => true]) !!}
                        <small class="text-danger">Please use full address including http or https like https://trustpatrick.com </small>
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary waves-effect waves-light member_resource_submit_form">Submit</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>



        @if (isset($company_logo_item) && !is_null($company_logo_item))
        <div class="card">
            <div class="card-header">Code to show logo/banner</div>
            <div class="card-body">
                <div class="form-group">
                    <label>Put the following code where you want to show logo/banner &lt;body&gt;... ....
                        ....&lt;/body&gt; tag</label>
                    <textarea id="copy_code" readonly class="form-control"><div id="map-container"></div><script type="text/javascript" id="map-script" data-url="{{ url('/') }}" data-key="{{ $company_logo_item->unique_key }}" src="{{ rtrim(url('/'), '/index.php') }}/js/script.js"></script></textarea>
                </div>

                <a href="javascript:;" class="btn btn-sm btn-info copy_code" data-clipboard-action="copy"
                    data-clipboard-target="#copy_code">Click To Copy</a>

            </div>
        </div>
        @endif


    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')
@include('company.profile._js')
<script type="text/javascript">
    $(function () {

        $('.select_banner_btn').click(function(){

            $('.select_banner_btn').removeClass('btn-success').addClass('btn-info').html('Select');

            $(this).removeClass('btn-info').addClass('btn-success').html('<i class="fas fa-check"></i> Selected');
            $("#set_site_logo_id").val($(this).data('banner_id'));

            var target = $("#set_form_card");
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - 200
                }, 1000);
            }
        })


        $(".set_logo_banner").on("click", function () {
            var site_logo_id = $(this).data('logo_id');

            $(".banners .item").removeClass("active");
            $(this).parent(".item").addClass("active");
            $("#set_form #set_site_logo_id").val(site_logo_id);
        });
        
        $("#member_resource_form").on("submit", function (){
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".member_resource_submit_form").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".member_resource_submit_form").attr('disabled', true);
            } else {
                $(".member_resource_submit_form").html('Submit Question');
                $(".member_resource_submit_form").attr('disabled', false);
            }
        });
    });
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
<script type="text/javascript">
    $(function (){
        var clipboard = new ClipboardJS('.copy_code');
        clipboard.on('success', function(e) {
            $.toast({
                text: 'Copied to clipboard!',
                icon: 'info',
            })
            e.clearSelection();
        });
    });
</script>
@endsection
