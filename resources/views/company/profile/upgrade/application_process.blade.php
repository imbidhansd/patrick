<?php
$admin_page_title = $page->title;
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')
@include('flash::message')

<!-- Basic Form Wizard -->

<div class="card-box application_process_content">
    <h1 class="text-theme_color text-center">{{ $page->title }}</h1>
    <h4 class="text-theme_color text-center">What Are We Checking?</h4>
    
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
    
    @include('admin.includes.formErrors')

    {!! $page->content !!}

    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
    {!! Form::open(['class' => 'module_form', 'onsubmit' => 'return false;']) !!}
    <div class="form-group">
        <div class="checkbox checkbox-primary checkbox-circle">
            <input type="checkbox" name="terms" id="terms" value="1" required />
            <label for="terms">I have read, understand and agree with the requirements to apply for listing with TrustPatrick.com</label>
        </div>
    </div>

    <button type="submit" id="upgrade_btn" class="btn btn-sm btn-primary">Looks Good.. Let's Go <i class="fas fa-angle-double-right"></i></button>
    {!! Form::close() !!}
</div>
@endsection

@section ('page_js')
<script type="text/javascript">
    $(function () {
        $("#terms").on("change", function () {
            $.ajax({
                url: '{{ url("account/upgrade/terms-check") }}',
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}'},
                success: function (data) {

                }
            });
        });

        $("#upgrade_btn").on("click", function () {
            if ($("#terms").is(":checked")) {
                window.location.href = '{{ url("account/upgrade") }}';
                return false;
            }
        });
    });
</script>
@endsection
