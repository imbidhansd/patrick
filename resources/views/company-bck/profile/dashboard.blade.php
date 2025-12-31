<?php
    $admin_page_title = 'Dashboard';
?>
@extends('company.layout')


@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-sm-9">
        @if ($company_detail->company_subscribe_status == 'unsubscribed')
        <div class="row" id="welcome_block">
            <div class="col-sm-12">
                <div class="card-box text-center">
                    <div class="float-right">
                        <a href="javascript:;" class="welcom_sec_close_btn" id="close_btn"><i class="far fa-window-close"></i></a>
                    </div>
                    <div class="row">
                        <div class="col-md-2">&nbsp;</div>
                        
                        <div class="col-md-8">
                            <h3>Welcome Back!</h3>

                            <h5>You are currently {{ $company_detail->company_subscribe_status }} and not receiving preview leads or free leads submitted from your company page Resubscribe to continue receiving preview leads and free leads.</h5>

                            <a href="javascript:;" data-type="subscribe"
                                class="btn btn-sm btn-primary btn-rounded width-sm change_subscription">Resubscribe</a>

                            <h5>or upgrade now and unlock all preview leads</h5>
                            <a href="javascript:;" class="btn btn-sm btn-primary btn-rounded width-sm">Upgrade Now</a>
                            
                            <h5>or call Member Support at 720-445-4400</h5>
                            <a href="javascript:;" class="btn btn-sm btn-primary btn-rounded width-sm"><i class="fas fa-exclamation-circle"></i> Learn More</a>
                        </div>
                        <div class="col-md-2">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="header-title mb-4">Latest News</h4>

                    
                    <div class="card">
                        <div class="card-header bg-purple text-white">
                            <div class="card-widgets">
                                <i class="far fa-calendar-alt"></i> Tue, Nov, 26 2019 10:35 AM
                            </div>

                            <h3 class="card-title text-white mb-0">
                                <i class="far fa-newspaper"></i> New 1
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="text-left">
                                <div class="short_content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor felis ut
                                        diam vehicula cursus. Morbi aliquet rutrum quam, quis finibus ex tempus ac. Integer
                                        commodo porttitor sapien, sed facilisis sem luctus ut. Duis tristiquligula quis
                                        purus sagittis, non tristique mauris hendrerit. Suspendisse at dapibus tortor, nec
                                        commodo leo. <a href="javascript:;" class="read_full_content">Read More</a>
                                    </p>

                                </div>

                                <div class="full_content" style="display: none;">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor felis ut
                                        diam vehicula cursus. Morbi aliquet rutrum quam, quis finibus ex tempus ac. Integer
                                        commodo porttitor sapien, sed facilisis sem luctus ut. Duis tristiquligula quis
                                        purus sagittis, non tristique mauris hendrerit. Suspendisse at dapibus tortor, nec
                                        commodo leo.
                                    </p>
                                    <ul>
                                        <li>
                                            <span>Morbi non lorem molestie, aliquam enim nec, placerat enim.</span>
                                        </li>
                                        <li>
                                            <span>Donec quis metus nec orci aliquam luctus eget a velit.</span>
                                        </li>
                                        <li>
                                            <span>Maecenas ac est iaculis, interdum velit eu, dictum nisi.</span>
                                        </li>
                                        <li>
                                            <span>Quisque consequat dignissim efficitur.</span>
                                        </li>
                                    </ul>
                                    <p>Nam eget metus sed ligula imperdiet ullamcorper. Cras semper lectus in nulla sodales
                                        fringilla. Proin luctus lobortis pretium. In ante nibh, dictum at purus vel, gravida
                                        congue enim. Vivamus vestibulum ligula sem, in mollis leo pretium quis. Nam molestie
                                        arcu id ultricies vulputate. Quisque nibh nulla, imperdiet a lacinia vitae,
                                        efficitur eget sapien.</p>

                                    <a href="javascript:;" class="read_less_content">Read Less</a>
                                </div>

                                <div class="clearfix">&nbsp;</div>

                                <div class="text-left">
                                    <a href="" class="badge badge-primary"><i class="far fa-comments"></i> Comments</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-purple text-white">
                            <div class="card-widgets">
                                <i class="far fa-calendar-alt"></i> Tue, Nov, 26 2019 10:35 AM
                            </div>

                            <h3 class="card-title text-white mb-0">
                                <i class="far fa-newspaper"></i> New 2
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="text-left">
                                <div class="short_content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor felis ut
                                        diam vehicula cursus. Morbi aliquet rutrum quam, quis finibus ex tempus ac. Integer
                                        commodo porttitor sapien, sed facilisis sem luctus ut. Duis tristiquligula quis
                                        purus sagittis, non tristique mauris hendrerit. Suspendisse at dapibus tortor, nec
                                        commodo leo. <a href="javascript:;" class="read_full_content">Read More</a>
                                    </p>

                                </div>

                                <div class="full_content" style="display: none;">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor felis ut
                                        diam vehicula cursus. Morbi aliquet rutrum quam, quis finibus ex tempus ac. Integer
                                        commodo porttitor sapien, sed facilisis sem luctus ut. Duis tristiquligula quis
                                        purus sagittis, non tristique mauris hendrerit. Suspendisse at dapibus tortor, nec
                                        commodo leo.
                                    </p>
                                    <ul>
                                        <li>
                                            <span>Morbi non lorem molestie, aliquam enim nec, placerat enim.</span>
                                        </li>
                                        <li>
                                            <span>Donec quis metus nec orci aliquam luctus eget a velit.</span>
                                        </li>
                                        <li>
                                            <span>Maecenas ac est iaculis, interdum velit eu, dictum nisi.</span>
                                        </li>
                                        <li>
                                            <span>Quisque consequat dignissim efficitur.</span>
                                        </li>
                                    </ul>
                                    <p>Nam eget metus sed ligula imperdiet ullamcorper. Cras semper lectus in nulla sodales
                                        fringilla. Proin luctus lobortis pretium. In ante nibh, dictum at purus vel, gravida
                                        congue enim. Vivamus vestibulum ligula sem, in mollis leo pretium quis. Nam molestie
                                        arcu id ultricies vulputate. Quisque nibh nulla, imperdiet a lacinia vitae,
                                        efficitur eget sapien.</p>

                                    <a href="javascript:;" class="read_less_content">Read Less</a>
                                </div>

                                <div class="clearfix">&nbsp;</div>

                                <div class="text-left">
                                    <a href="" class="badge badge-primary"><i class="far fa-comments"></i> Comments</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-purple text-white">
                            <div class="card-widgets">
                                <i class="far fa-calendar-alt"></i> Tue, Nov, 26 2019 10:35 AM
                            </div>

                            <h3 class="card-title text-white mb-0">
                                <i class="far fa-newspaper"></i> New 3
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="text-left">
                                <div class="short_content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor felis ut
                                        diam vehicula cursus. Morbi aliquet rutrum quam, quis finibus ex tempus ac. Integer
                                        commodo porttitor sapien, sed facilisis sem luctus ut. Duis tristiquligula quis
                                        purus sagittis, non tristique mauris hendrerit. Suspendisse at dapibus tortor, nec
                                        commodo leo. <a href="javascript:;" class="read_full_content">Read More</a>
                                    </p>

                                </div>

                                <div class="full_content" style="display: none;">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor felis ut
                                        diam vehicula cursus. Morbi aliquet rutrum quam, quis finibus ex tempus ac. Integer
                                        commodo porttitor sapien, sed facilisis sem luctus ut. Duis tristiquligula quis
                                        purus sagittis, non tristique mauris hendrerit. Suspendisse at dapibus tortor, nec
                                        commodo leo.
                                    </p>
                                    <ul>
                                        <li>
                                            <span>Morbi non lorem molestie, aliquam enim nec, placerat enim.</span>
                                        </li>
                                        <li>
                                            <span>Donec quis metus nec orci aliquam luctus eget a velit.</span>
                                        </li>
                                        <li>
                                            <span>Maecenas ac est iaculis, interdum velit eu, dictum nisi.</span>
                                        </li>
                                        <li>
                                            <span>Quisque consequat dignissim efficitur.</span>
                                        </li>
                                    </ul>
                                    <p>Nam eget metus sed ligula imperdiet ullamcorper. Cras semper lectus in nulla sodales
                                        fringilla. Proin luctus lobortis pretium. In ante nibh, dictum at purus vel, gravida
                                        congue enim. Vivamus vestibulum ligula sem, in mollis leo pretium quis. Nam molestie
                                        arcu id ultricies vulputate. Quisque nibh nulla, imperdiet a lacinia vitae,
                                        efficitur eget sapien.</p>

                                    <a href="javascript:;" class="read_less_content">Read Less</a>
                                </div>

                                <div class="clearfix">&nbsp;</div>

                                <div class="text-left">
                                    <a href="" class="badge badge-primary"><i class="far fa-comments"></i> Comments</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-purple text-white">
                            <div class="card-widgets">
                                <i class="far fa-calendar-alt"></i> Tue, Nov, 26 2019 10:35 AM
                            </div>

                            <h3 class="card-title text-white mb-0">
                                <i class="far fa-newspaper"></i> New 4
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="text-left">
                                <div class="short_content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor felis ut
                                        diam vehicula cursus. Morbi aliquet rutrum quam, quis finibus ex tempus ac. Integer
                                        commodo porttitor sapien, sed facilisis sem luctus ut. Duis tristiquligula quis
                                        purus sagittis, non tristique mauris hendrerit. Suspendisse at dapibus tortor, nec
                                        commodo leo. <a href="javascript:;" class="read_full_content">Read More</a>
                                    </p>

                                </div>

                                <div class="full_content" style="display: none;">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur auctor felis ut
                                        diam vehicula cursus. Morbi aliquet rutrum quam, quis finibus ex tempus ac. Integer
                                        commodo porttitor sapien, sed facilisis sem luctus ut. Duis tristiquligula quis
                                        purus sagittis, non tristique mauris hendrerit. Suspendisse at dapibus tortor, nec
                                        commodo leo.
                                    </p>
                                    <ul>
                                        <li>
                                            <span>Morbi non lorem molestie, aliquam enim nec, placerat enim.</span>
                                        </li>
                                        <li>
                                            <span>Donec quis metus nec orci aliquam luctus eget a velit.</span>
                                        </li>
                                        <li>
                                            <span>Maecenas ac est iaculis, interdum velit eu, dictum nisi.</span>
                                        </li>
                                        <li>
                                            <span>Quisque consequat dignissim efficitur.</span>
                                        </li>
                                    </ul>
                                    <p>Nam eget metus sed ligula imperdiet ullamcorper. Cras semper lectus in nulla sodales
                                        fringilla. Proin luctus lobortis pretium. In ante nibh, dictum at purus vel, gravida
                                        congue enim. Vivamus vestibulum ligula sem, in mollis leo pretium quis. Nam molestie
                                        arcu id ultricies vulputate. Quisque nibh nulla, imperdiet a lacinia vitae,
                                        efficitur eget sapien.</p>

                                    <a href="javascript:;" class="read_less_content">Read Less</a>
                                </div>

                                <div class="clearfix">&nbsp;</div>

                                <div class="text-left">
                                    <a href="" class="badge badge-primary"><i class="far fa-comments"></i> Comments</a>
                                </div>
                            </div>
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
<script type="text/javascript">
    $(function (){
        $(".read_full_content").on("click", function (){
            $(this).parents(".text-left").find(".short_content").hide();
            $(this).parents(".text-left").find(".full_content").show();
        });

        $(".read_less_content").on("click", function (){
            $(this).parents(".text-left").find(".short_content").show();
            $(this).parents(".text-left").find(".full_content").hide();
        });


        $("#close_btn").on("click", function (){
            $(this).parents("#welcome_block").remove();
        });
    });
</script>
@endsection
