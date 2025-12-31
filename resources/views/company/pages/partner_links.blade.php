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
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-white mb-0">{{ $admin_page_title }}</h3>
                    </div>
                    <div class="card-body">
                        @if (isset($partner_links) && count($partner_links) > 0)
                        <div class="banners">
                            <div class="row">
                                @foreach ($partner_links AS $partner_link_item)
                                <div class="col-md-3 text-center">
                                    <div class="item mb-4 active">
                                        @if (!is_null($partner_link_item->link != ''))
                                        <a href="{{ asset('/uploads/media/'.$partner_link_item->media->file_name) }}" data-fancybox="gallery_image">
                                            <img src="{{ asset('/uploads/media/fit_thumbs/100x100/'.$partner_link_item->media->file_name) }}" class="img-thumbnail" />
                                        </a>
                                        @endif
                                        
                                        <p class="caption_title text-center font-15">
                                            {{ $partner_link_item->title }}
                                        </p>
                                        <div class="btn-group text-center">
                                            <a href="{{ $partner_link_item->link }}" target="_blank" class="btn btn-dark btn-xs">View</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="text-left">
                            <p class="text-muted">
                                We are in the process of securing new agreements with our partners under our new name of trustpatrick.com. We'll notify you of our new agreements and partners soon!
                            </p>
                        </div>
                        @endif
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
@endsection
