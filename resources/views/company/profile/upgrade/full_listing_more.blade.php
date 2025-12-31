<?php
$admin_page_title = $page->title;
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->
<div class="card-box">
    <div class="text-center">
        <h1>Membership Options</h1>
        <!-- <h4 class="text-primary">Limited To Just 6 companies Per Service Category/Zip Code</h4> -->

        <!-- <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>

        <b>
            <p class="text-theme_color">Secure your spot immediately and save!</p>
            <p>Join today with a full listing and we’ll create a special discounted package designed just for your company.</p>
        </b> -->
    </div>

    <div class="clearfix">&nbsp;</div>
    @if (isset($membership_levels) && count($membership_levels) > 0)
    <ul class="nav nav-tabs tabs-bordered custom_tabs" role="tablist">
        @foreach ($membership_levels AS $membership_level_item)
        <li class="nav-item">
            <a class="nav-link {{ (($loop->first) ? 'active': '') }}" id="{{ $membership_level_item->slug }}-tab" data-toggle="tab" href="#{{ $membership_level_item->slug }}" role="tab" aria-controls="{{ $membership_level_item->slug }}" aria-selected="false">
                <span>{{ $membership_level_item->title }}</span>
            </a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content pt-0 membership_level_tab_content">
        @foreach ($membership_levels AS $membership_level_item)
        <div class="tab-pane {{ (($loop->first) ? 'show active': '') }}" id="{{ $membership_level_item->slug }}" role="tabpanel" aria-labelledby="{{ $membership_level_item->slug }}-tab">
            <div class="mb-0 p-3">
             {!! str_replace('XX', $membership_fee->price, $membership_level_item->content) !!}
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@section ('page_js')
@endsection
