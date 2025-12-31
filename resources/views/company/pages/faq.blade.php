<?php
$admin_page_title = 'Frequently Asked Questions';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header bg-secondary">
                <h3 class="card-title text-white mb-0">Frequently Asked Questions</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <p class="text-muted">We are in the process of compiling and posting the most frequently asked questions.<br/>Until then, please submit your question by clicking on the green button below. Thanks for your patience!</p>

                    <a href="javascript:;" data-toggle="modal" data-target="#emailUsYourQuestion" class="btn btn-success waves-effect waves-light mt-2">Email us your question</a>
                    <a href="tel: 720-445-4400" class="btn btn-primary waves-effect waves-light mt-2">Call Us at 720-445-4400</a>

                </div>

                @if(isset($faqs) && count($faqs) > 0)
                <div class="row mt-5">
                    <div class="col-xl-12">

                        <div class="accordion" id="accordionExample">
                            @foreach ($faqs as $faq_item)
                            <div class="card mb-2 shadow-none">
                                <div class="card-header">
                                    <h4 class="card-title m-0"><a href="#" data-toggle="collapse" data-target="#collapse_{{ $faq_item->id }}" aria-expanded="true" aria-controls="collapseOne">{{ $faq_item->title }}</a></h4>
                                </div>
                                <div id="collapse_{{ $faq_item->id }}"
                                     class="collapse "
                                     data-parent="#accordionExample">
                                    <div class="card-body">
                                        {!! $faq_item->content !!}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- end col -->
</div>

@include('company.pages._submit_faq_modal')
@endsection

@section ('page_js')
@include('company.profile._js')
@stack('_submit_faq_js')
@endsection
