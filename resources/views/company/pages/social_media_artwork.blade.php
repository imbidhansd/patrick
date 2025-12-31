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
        </ul>
        
        
        <div class="tab-content pt-0">
            @if ($company_item->is_founding_member == 'yes')
            <div class="tab-pane show active" id="founding_member" role="tabpanel" aria-labelledby="founding_member-tab">
                <div class="card mb-0 p-3">
                    @if (isset($founding_item_list) && count($founding_item_list) > 0)
                        @php
                            $founding_keys = array_keys($founding_item_list);
                        @endphp
                        
                        <ul class="nav nav-tabs tabs-bordered custom_tabs" role="tablist">
                        @foreach ($founding_keys AS $key => $founding_key_item)
                            <li class="nav-item">
                                <a class="nav-link {{ (($loop->first) ? 'active' : '') }}" id="founding_{{ $key }}-tab" data-toggle="tab" href="#founding_{{ $key }}" role="tab" aria-controls="founding_social" aria-selected="false">
                                    <span class="d-none d-sm-block">{{ $founding_key_item }}</span>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                        
                        <div class="tab-content pt-0">
                            @foreach ($founding_keys AS $key => $founding_key_item)
                            <div class="tab-pane {{ (($loop->first) ? 'show active' : '') }}" id="founding_{{ $key }}" role="tabpanel" aria-labelledby="founding_{{ $key }}-tab">
                                <div class="card mb-0 p-3">
                                    <div class="banners">
                                        <div class="row">
                                            @foreach ($founding_item_list[$founding_key_item] AS $banner_item)
                                            <div class="col-md-12 text-center">
                                                <div class="item">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <p class="caption_title text-left">{{ $banner_item->title }}</p>
                                                            @if (!is_null($banner_item->png_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->png_media->file_name) }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                                                <img src="{{ asset('/uploads/media/'.$banner_item->png_media->file_name) }}" class="img-thumbnail" />
                                                            </a>
                                                            @elseif (!is_null($banner_item->jpg_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->jpg_media->file_name) }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                                                <img src="{{ asset('/uploads/media/'.$banner_item->jpg_media->file_name) }}" class="img-thumbnail" />
                                                            </a>
                                                            @elseif (!is_null($banner_item->pdf_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->pdf_media->file_name) }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                                                <i class="far fa-file-pdf font-40"></i>
                                                            </a>
                                                            @endif
                                                        </div>

                                                        <?php /* <div class="col-md-3 align-self-center">
                                                            @if (!is_null($banner_item->png_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->png_media->file_name) }}" class="btn btn-theme_color btn-sm" download>Download PNG &nbsp; <i class="fas fa-download"></i></a>
                                                            @endif
                                                        </div> */ ?>

                                                        <div class="col-md-3 align-self-center">
                                                            @if (!is_null($banner_item->jpg_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->jpg_media->file_name) }}" class="btn btn-theme_color btn-sm" download><i class="fas fa-download mr-2"></i>Download</a>
                                                            @endif
                                                        </div>

                                                        <?php /* <div class="col-md-3 align-self-center">
                                                            @if (!is_null($banner_item->pdf_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->pdf_media->file_name) }}" class="btn btn-theme_color btn-sm" download>Download PDF &nbsp; <i class="fas fa-download"></i></a>
                                                            @endif
                                                        </div> */ ?>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif
            
            <div class="tab-pane {{ (($company_item->is_founding_member == 'no') ? 'show active' : '') }}" id="official_member" role="tabpanel" aria-labelledby="official_member-tab">
                <div class="card mb-0 p-3">
                    @if (isset($official_item_list) && count($official_item_list) > 0)
                        @php
                            $official_keys = array_keys($official_item_list);
                        @endphp
                        
                        <ul class="nav nav-tabs tabs-bordered custom_tabs" role="tablist">
                        @foreach ($official_keys AS $key => $official_key_item)
                            <li class="nav-item">
                                <a class="nav-link {{ (($loop->first) ? 'active' : '') }}" id="official_{{ $key }}-tab" data-toggle="tab" href="#official_{{ $key }}" role="tab" aria-controls="official_social" aria-selected="false">
                                    <span class="d-none d-sm-block">{{ $official_key_item }}</span>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                        
                        <div class="tab-content pt-0">
                            @foreach ($official_keys AS $key => $official_key_item)
                            <div class="tab-pane {{ (($loop->first) ? 'show active' : '') }}" id="official_{{ $key }}" role="tabpanel" aria-labelledby="official_{{ $key }}-tab">
                                <div class="card mb-0 p-3">
                                    <div class="banners">
                                        <div class="row">
                                            @foreach ($official_item_list[$official_key_item] AS $banner_item)
                                            <div class="col-md-12 text-center">
                                                <div class="item">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <p class="caption_title text-left">{{ $banner_item->title }}</p>
                                                            @if (!is_null($banner_item->png_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->png_media->file_name) }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                                                <img src="{{ asset('/uploads/media/'.$banner_item->png_media->file_name) }}" class="img-thumbnail" />
                                                            </a>
                                                            @elseif (!is_null($banner_item->jpg_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->jpg_media->file_name) }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                                                <img src="{{ asset('/uploads/media/'.$banner_item->jpg_media->file_name) }}" class="img-thumbnail" />
                                                            </a>
                                                            @elseif (!is_null($banner_item->pdf_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->pdf_media->file_name) }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                                                <i class="far fa-file-pdf font-40"></i>
                                                            </a>
                                                            @endif
                                                        </div>

                                                        <?php /* <div class="col-md-3 align-self-center">
                                                            @if (!is_null($banner_item->png_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->png_media->file_name) }}" class="btn btn-theme_color btn-sm" download>Download PNG &nbsp; <i class="fas fa-download"></i></a>
                                                            @endif
                                                        </div> */ ?>

                                                        <div class="col-md-3 align-self-center">
                                                            @if (!is_null($banner_item->jpg_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->jpg_media->file_name) }}" class="btn btn-theme_color btn-sm" download><i class="fas fa-download mr-2"></i>Download</a>
                                                            @endif
                                                        </div>

                                                        <?php /* <div class="col-md-3 align-self-center">
                                                            @if (!is_null($banner_item->pdf_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->pdf_media->file_name) }}" class="btn btn-theme_color btn-sm" download>Download PDF &nbsp; <i class="fas fa-download"></i></a>
                                                            @endif
                                                        </div> */ ?>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="tab-pane" id="recommended_company" role="tabpanel" aria-labelledby="recommended_company-tab">
                <div class="card mb-0 p-3">
                    @if (isset($recommended_item_list) && count($recommended_item_list) > 0)
                        @php
                            $recommended_keys = array_keys($recommended_item_list);
                        @endphp
                        
                        <ul class="nav nav-tabs tabs-bordered custom_tabs" role="tablist">
                        @foreach ($recommended_keys AS $key => $recommended_key_item)
                            <li class="nav-item">
                                <a class="nav-link {{ (($loop->first) ? 'active' : '') }}" id="recommended_{{ $key }}-tab" data-toggle="tab" href="#recommended_{{ $key }}" role="tab" aria-controls="recommended_social" aria-selected="false">
                                    <span class="d-none d-sm-block">{{ $recommended_key_item }}</span>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                        
                        <div class="tab-content pt-0">
                            @foreach ($recommended_keys AS $key => $recommended_key_item)
                            <div class="tab-pane {{ (($loop->first) ? 'show active' : '') }}" id="recommended_{{ $key }}" role="tabpanel" aria-labelledby="recommended_{{ $key }}-tab">
                                <div class="card mb-0 p-3">
                                    <div class="banners">
                                        <div class="row">
                                            @foreach ($recommended_item_list[$recommended_key_item] AS $banner_item)
                                            <div class="col-md-12 text-center">
                                                <div class="item">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <p class="caption_title text-left">{{ $banner_item->title }}</p>
                                                            @if (!is_null($banner_item->png_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->png_media->file_name) }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                                                <img src="{{ asset('/uploads/media/'.$banner_item->png_media->file_name) }}" class="img-thumbnail" />
                                                            </a>
                                                            @elseif (!is_null($banner_item->jpg_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->jpg_media->file_name) }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                                                <img src="{{ asset('/uploads/media/'.$banner_item->jpg_media->file_name) }}" class="img-thumbnail" />
                                                            </a>
                                                            @elseif (!is_null($banner_item->pdf_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->pdf_media->file_name) }}" data-fancybox="gallery_image_{{ $banner_item->id }}">
                                                                <i class="far fa-file-pdf font-40"></i>
                                                            </a>
                                                            @endif
                                                        </div>

                                                        <?php /* <div class="col-md-3 align-self-center">
                                                            @if (!is_null($banner_item->png_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->png_media->file_name) }}" class="btn btn-theme_color btn-sm" download>Download PNG &nbsp; <i class="fas fa-download"></i></a>
                                                            @endif
                                                        </div> */ ?>

                                                        <div class="col-md-3 align-self-center">
                                                            @if (!is_null($banner_item->jpg_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->jpg_media->file_name) }}" class="btn btn-theme_color btn-sm" download><i class="fas fa-download mr-2"></i>Download</a>
                                                            @endif
                                                        </div>

                                                        <?php /* <div class="col-md-3 align-self-center">
                                                            @if (!is_null($banner_item->pdf_media))
                                                            <a href="{{ asset('/uploads/media/'.$banner_item->pdf_media->file_name) }}" class="btn btn-theme_color btn-sm" download>Download PDF &nbsp; <i class="fas fa-download"></i></a>
                                                            @endif
                                                        </div> */ ?>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection

@section ('page_js')
@include('company.profile._js')
<?php /*<script type="text/javascript">
     $(function (){
        $(".download_artwork").attr("download", true);
    });
</script> */ ?>
@endsection
