@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-sm-9">

        <ul class="nav nav-tabs tabs-bordered custom_tabs" role="tablist">
            @if ($company_item->is_founding_member == 'yes')
            <li class="nav-item">
                <a class="nav-link active" id="founding_member-tab" data-toggle="tab" href="#founding_member" role="tab" aria-controls="founding_member" aria-selected="false">
                    <span class="d-none d-sm-block">Founding Member</span>
                </a>
            </li>
            @endif
            
            <li class="nav-item">
                <a class="nav-link {{ (($company_item->is_founding_member == 'no') ? 'active' : '') }}" id="official_member-tab" data-toggle="tab" href="#official_member" role="tab" aria-controls="official_member" aria-selected="false">
                    <span class="d-none d-sm-block">Official Member</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="recommended_company-tab" data-toggle="tab" href="#recommended_company" role="tab" aria-controls="recommended_company" aria-selected="false">
                    <span class="d-none d-sm-block">Recommended Company</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="certifiedpro-tab" data-toggle="tab" href="#certifiedpro" role="tab" aria-controls="certifiedpro" aria-selected="false">
                    <span class="d-none d-sm-block">Certified Pro</span>
                </a>
            </li>
        </ul>

        <div class="tab-content pt-0">
            @if ($company_item->is_founding_member == 'yes')
            <div class="tab-pane show active" id="founding_member" role="tabpanel" aria-labelledby="founding_member-tab">
                <div class="card mb-0 p-3">
                    @if (isset($founding_item_list) && !is_null($founding_item_list) && count($founding_item_list) > 0)
                    <div class="banners">
                        <div class="row">
                            @foreach ($founding_item_list AS $banner_item)
                            <div class="col-md-4 text-center">
                                <div class="item">
                                    @if ($banner_item->banner_url != '')
                                    <a href="{{ $banner_item->banner_url }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                        <img src="{{ $banner_item->banner_url }}" class="img-thumbnail" />
                                    </a>
                                    
                                    {!! Form::hidden('banner_url', $banner_item->banner_url, ['class' => 'banner_url']) !!}
                                    @endif
                                    
                                    {!! Form::hidden('banner_title', $banner_item->title, ['class' => 'banner_title']) !!}
                                    {!! Form::hidden('banner_size', $banner_item->size, ['class' => 'banner_size']) !!}
                                    {!! Form::hidden('member_url', $banner_item->member_url, ['class' => 'member_url']) !!}
                                    {!! Form::hidden('banner_alt', $banner_item->banner_alt, ['class' => 'banner_alt']) !!}

                                    <p class="caption_title text-center">{{ $banner_item->title }}
                                        <br />
                                        <i class="text-info">Size: {{ $banner_item->size }}</i>
                                    </p>

                                    <div class="btn-group text-center">
                                        <a href="{{  $banner_item->banner_url }}" data-fancybox="gallery_{{ $banner_item->id }}" class="btn btn-dark btn-xs">View</a>
                                        <a href="javascript:;" data-banner_id="{{ $banner_item->id }}" class="btn btn-info select_banner_btn btn-xs">Select</a>
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
                    @endif
                </div>
            </div>
            @endif
            
            <div class="tab-pane {{ (($company_item->is_founding_member == 'no') ? 'show active' : '') }}" id="official_member" role="tabpanel" aria-labelledby="official_member-tab">
                <div class="card mb-0 p-3">
                    @if (isset($official_item_list) && !is_null($official_item_list) && count($official_item_list) > 0)
                    <div class="banners">
                        <div class="row">
                            @foreach ($official_item_list AS $banner_item)
                            <div class="col-md-4 text-center">
                                <div class="item">
                                    @if ($banner_item->banner_url != '')
                                    <a href="{{ $banner_item->banner_url }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                        <img src="{{ $banner_item->banner_url }}" class="img-thumbnail" />
                                    </a>
                                    
                                    {!! Form::hidden('banner_url', $banner_item->banner_url, ['class' => 'banner_url']) !!}
                                    @endif
                                    
                                    {!! Form::hidden('banner_title', $banner_item->title, ['class' => 'banner_title']) !!}
                                    {!! Form::hidden('banner_size', $banner_item->size, ['class' => 'banner_size']) !!}
                                    {!! Form::hidden('member_url', $banner_item->member_url, ['class' => 'member_url']) !!}
                                    {!! Form::hidden('banner_alt', $banner_item->banner_alt, ['class' => 'banner_alt']) !!}

                                    <p class="caption_title text-center">{{ $banner_item->title }}
                                        <br />
                                        <i class="text-info">Size: {{ $banner_item->size }}</i>
                                    </p>

                                    <div class="btn-group text-center">
                                        <a href="{{  $banner_item->banner_url }}" data-fancybox="gallery_{{ $banner_item->id }}" class="btn btn-dark btn-xs">View</a>
                                        <a href="javascript:;" data-banner_id="{{ $banner_item->id }}" class="btn btn-info select_banner_btn btn-xs">Select</a>
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
                    @endif
                </div>
            </div>
            
            <div class="tab-pane" id="recommended_company" role="tabpanel" aria-labelledby="recommended_company-tab">
                <div class="card mb-0 p-3">
                    @if (isset($recommended_item_list) && !is_null($recommended_item_list) && count($recommended_item_list) > 0)
                    <div class="banners">
                        <div class="row">
                            @foreach ($recommended_item_list AS $banner_item)
                            <div class="col-md-4 text-center">
                                <div class="item">
                                    @if ($banner_item->banner_url != '')
                                    <a href="{{ $banner_item->banner_url }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                        <img src="{{ $banner_item->banner_url }}" class="img-thumbnail" />
                                    </a>
                                    
                                    {!! Form::hidden('banner_url', $banner_item->banner_url, ['class' => 'banner_url']) !!}
                                    @endif
                                    
                                    {!! Form::hidden('banner_title', $banner_item->title, ['class' => 'banner_title']) !!}
                                    {!! Form::hidden('banner_size', $banner_item->size, ['class' => 'banner_size']) !!}
                                    {!! Form::hidden('member_url', $banner_item->member_url, ['class' => 'member_url']) !!}
                                    {!! Form::hidden('banner_alt', $banner_item->banner_alt, ['class' => 'banner_alt']) !!}

                                    <p class="caption_title text-center">{{ $banner_item->title }}
                                        <br />
                                        <i class="text-info">Size: {{ $banner_item->size }}</i>
                                    </p>

                                    <div class="btn-group text-center">
                                        <a href="{{  $banner_item->banner_url }}" data-fancybox="gallery_{{ $banner_item->id }}" class="btn btn-dark btn-xs">View</a>
                                        <a href="javascript:;" data-banner_id="{{ $banner_item->id }}" class="btn btn-info select_banner_btn btn-xs">Select</a>
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
                    @endif
                </div>
            </div>

            <div class="tab-pane" id="certifiedpro" role="tabpanel" aria-labelledby="certifiedpro-tab">
                <div class="card mb-0 p-3">
                    @if (isset($certifiedpro_list) && !is_null($certifiedpro_list) && count($certifiedpro_list) > 0)
                    <div class="banners">
                        <div class="row">
                            @foreach ($certifiedpro_list AS $banner_item)
                            <div class="col-md-4 text-center">
                                <div class="item">
                                    @if ($banner_item->banner_url != '')
                                    <a href="{{ $banner_item->banner_url }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                        <img src="{{ $banner_item->banner_url }}" class="img-thumbnail" />
                                    </a>
                                    
                                    {!! Form::hidden('banner_url', $banner_item->banner_url, ['class' => 'banner_url']) !!}
                                    @endif
                                    
                                    {!! Form::hidden('banner_title', $banner_item->title, ['class' => 'banner_title']) !!}
                                    {!! Form::hidden('banner_size', $banner_item->size, ['class' => 'banner_size']) !!}
                                    {!! Form::hidden('member_url', $banner_item->member_url, ['class' => 'member_url']) !!}
                                    {!! Form::hidden('banner_alt', $banner_item->banner_alt, ['class' => 'banner_alt']) !!}

                                    <p class="caption_title text-center">{{ $banner_item->title }}
                                        <br />
                                        <i class="text-info">Size: {{ $banner_item->size }}</i>
                                    </p>

                                    <div class="btn-group text-center">
                                        <a href="{{  $banner_item->banner_url }}" data-fancybox="gallery_{{ $banner_item->id }}" class="btn btn-dark btn-xs">View</a>
                                        <a href="javascript:;" data-banner_id="{{ $banner_item->id }}" class="btn btn-info select_banner_btn btn-xs">Select</a>
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
                    @endif
                </div>
            </div>
        </div>
        
        
        <!-- Modal -->
        <div class="modal fade" id="memberResourceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Code to show logo/banner</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="item text-center">
                            <a href="" data-fancybox="gallery_image" id="banner_thumbnail_link">
                                <img src="" class="img-thumbnail" id="banner_thumbnail" />
                            </a>

                            <p class="caption_title text-center">
                                <span id="banner_title"></span>
                                <br />
                                <i class="text-info">Size: <span id="banner_size"></span></i>
                            </p>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        
                        <div class="form-group">
                            <?php /* <label>Put the following code where you want to show logo/banner &lt;body&gt;... ....
                                ....&lt;/body&gt; tag</label> */ ?>
                            <label>Copy the code below and paste where you want to display the banner on your website. Or send to your web developer.</label>
                            <textarea id="copy_code" readonly class="form-control"></textarea>
                        </div>

                        <a href="javascript:;" class="btn btn-sm btn-info copy_code" data-clipboard-action="copy" data-clipboard-target="#copy_code">Click To Copy</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
<script type="text/javascript">
$(function () {
    var clipboard = new ClipboardJS('.copy_code');
    clipboard.on('success', function (e) {
        $.toast({
            text: 'Copied to clipboard!',
            icon: 'info',
        })
        e.clearSelection();
    });
    
    $(".select_banner_btn").on("click", function (){
        var banner_url = $(this).parents(".item").find(".banner_url").val();
        var banner_title = $(this).parents(".item").find(".banner_title").val();
        var banner_size = $(this).parents(".item").find(".banner_size").val();
        var member_url = $(this).parents(".item").find(".member_url").val();
        var banner_alt = $(this).parents(".item").find(".banner_alt").val();

        if (typeof banner_url !== 'undefined'){
            var copy_text = '<a href="'+member_url+'" target="_blank"><img alt="'+banner_alt+'" src="'+banner_url+'" /></a>';
            $("#memberResourceModal #banner_thumbnail_link").attr("href", banner_url);
            $("#memberResourceModal #banner_thumbnail").attr("src", banner_url);
            $("#memberResourceModal #banner_title").html(banner_title);
            $("#memberResourceModal #banner_size").html(banner_size);
            $("#memberResourceModal #copy_code").val(copy_text);

            $("#memberResourceModal").modal("show");
        } else {
            Swal.fire({
                title: 'Error',
                type: 'error',
                text: 'Banner link not found.'
            });
        }
    });
});
</script>
@endsection
