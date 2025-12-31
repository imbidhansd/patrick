<?php
$admin_page_title = 'Feedback';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">
        <div class="feedback_list">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="Reviews-tab" data-toggle="tab" href="#Reviews" role="tab" aria-controls="Reviews" aria-selected="true">
                        <span>Reviews</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="Complaints-tab" data-toggle="tab" href="#Complaints" role="tab" aria-controls="Complaints" aria-selected="false">
                        <span>Complaints</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane show active" id="Reviews" role="tabpanel" aria-labelledby="Reviews-tab">
                    @include('company.feedback._feedback_list')
                </div>
                <div class="tab-pane " id="Complaints" role="tabpanel" aria-labelledby="Complaints-tab">
                    @include('company.feedback._complaint_list')
                </div>
            </div>
        </div>
        
        <div class="clearfix">&nbsp;</div>
    </div>

    @include('company.profile._company_profile_sidebar')
</div>
@endsection


@section ('page_js')
@include('company.profile._js')
<!-- rating js -->
<script src="{{ asset('/themes/admin/assets/libs/ratings/jquery.raty-fa.js') }}"></script>
<script type="text/javascript">
    $(function (){
        @if(isset($feedback) && count($feedback) > 0)
        @foreach($feedback as $row)
        var row_id = '{{ $row->id }}';
        $("#detailModal_"+row_id+" #starHalf").raty({
            readOnly: !0,
            half: !0,
            starHalf: "fas fa-star-half text-success",
            starOff: "far fa-star text-muted",
            starOn: "fas fa-star text-success",
            score: "{{ $row->ratings }}",
        });
        @endforeach
        @endif
    });
</script>
@endsection
