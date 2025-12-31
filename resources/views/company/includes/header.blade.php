
<!-- Navigation Bar-->
<header id="topnav">

    @if (Session::has('company_mask') && Session::get('company_mask') == true && isset($company_item))
    <div class="admin-bar">
        <div class="container-fluid">
            <p class="m-0 p-0 text-center white-font">Masquerading as ({{ $company_item->company_name }})<a href="{{ url('logout') }}" class="btn btn-danger btn-xs ml-3">Logout From Company</a></p>
        </div>
    </div>
    @endif


    <!-- Topbar Start -->
    <div class="navbar-custom">
        <div class="container-fluid">

            @if (Auth::guard('company_user')->check())
            <ul class="list-unstyled topnav-menu float-right mb-0">

                <li class="dropdown notification-list">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle nav-link">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </li>


                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="mdi mdi-bell noti-icon"></i>
                        <span class="badge badge-success rounded-circle noti-icon-badge">4</span>
                        <div class="noti-dot">
                            <span class="dot"></span>
                            <span class="pulse"></span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="font-16 m-0">
                                <span class="float-right">
                                    <a href="" class="text-dark">
                                        <small>Clear All</small>
                                    </a>
                                </span>Notification
                            </h5>
                        </div>

                        <div class="slimscroll noti-scroll">

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon bg-success">
                                    <i class="mdi mdi-settings-outline"></i>
                                </div>
                                <p class="notify-details">New settings
                                    <small class="text-muted">There are new settings available</small>
                                </p>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon bg-info">
                                    <i class="mdi mdi-bell-outline"></i>
                                </div>
                                <p class="notify-details">Updates
                                    <small class="text-muted">There are 2 new updates available</small>
                                </p>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon bg-danger">
                                    <i class="mdi mdi-account-plus"></i>
                                </div>
                                <p class="notify-details">New user
                                    <small class="text-muted">You have 10 unread messages</small>
                                </p>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon bg-info">
                                    <i class="mdi mdi-comment-account-outline"></i>
                                </div>
                                <p class="notify-details">Caleb Flakelar commented on Admin
                                    <small class="text-muted">4 days ago</small>
                                </p>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon bg-secondary">
                                    <i class="mdi mdi-heart"></i>
                                </div>
                                <p class="notify-details">Carlos Crouch liked
                                    <b>Admin</b>
                                    <small class="text-muted">13 days ago</small>
                                </p>
                            </a>
                        </div>

                        <!-- All-->
                        <a href="javascript:void(0);"
                            class="dropdown-item text-center text-primary notify-item notify-all">
                            See all Notification
                            <i class="fi-arrow-right"></i>
                        </a>

                    </div>
                </li>

                @if(isset($company_messages_notification) && count($company_messages_notification) > 0)
                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="mdi mdi-email noti-icon"></i>
                        <span
                            class="badge badge-danger rounded-circle noti-icon-badge">{{ count($company_messages_notification) }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="font-16 m-0">
                                <?php /* <span class="float-right">
                                     <a href="" class="text-dark">
                                        <small>Clear All</small>
                                    </a>
                                </span> */ ?>
                                Messages
                            </h5>
                        </div>

                        <div class="slimscroll noti-scroll">

                            <div class="inbox-widget">

                                @forelse ($company_messages_notification AS $message_item)
                                <a href="{{ url('company_messages?id='.$message_item->id) }}">
                                    <div class="inbox-item">
                                        <?php /* <div class="inbox-item-img"><img src="{{ asset('themes/front/assets/images/users/avatar-1.jpg') }}" class="rounded-circle" alt=""></div> */ ?>
                                        <p class="inbox-item-author">{{ $message_item->title }}</p>
                                        <p class="inbox-item-text text-truncate">
                                            {{ $message_item->created_at->format(env('DATE_FORMAT')) }}</p>
                                    </div>
                                </a>
                                @empty
                                @endforelse

                                <?php /* <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img
                                                src="{{ asset('themes/admin/assets/images/users/avatar-5.jpg') }}"
                                                class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Shahedk</p>
                                        <p class="inbox-item-text text-truncate">Hey! there I am available...</p>

                                    </div>
                                </a> */ ?>
                            </div> <!-- end inbox-widget -->

                        </div>

                        <!-- All-->
                        <a href="{{ url('company_messages') }}"
                            class="dropdown-item ztext-center text-primary notify-item notify-all">
                            See all Messages
                            <i class="fi-arrow-right"></i>
                        </a>

                    </div>
                </li>
                @endif


                @php
                    $company_owner = Auth::guard('company_user')->user();
                    $last_login_activity_log = \Spatie\Activitylog\Models\Activity::where('subject_id', $company_owner->id)->where('description', 'Logged In')->orderBy('id', 'desc')->skip(1)->take(1)->first();


                    /*echo '<pre>';
                        print_r ($last_login_activity_log->toArray());
                    echo '</pre>';
                    exit();*/
                @endphp
                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fas fa-user-circle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0 text-info">Welcome {{ $company_owner->first_name }}</h6>
                            @if (!is_null($last_login_activity_log))
                            <p class="mb-0 pb-0">
                                <small>
                                <span class="text-muted">Last Login</span><br/>
                                <span class="text-danger">{{ $last_login_activity_log->created_at->format('m/d/Y H:i a') }}</span>
                                </small>
                            </p>
                            @else
                            <p class="mb-0 pb-0 text-success">You are new user</p>
                            @endif
                        </div>

                        <!-- item-->
                        <a href="{{ url('profile') }}" class="dropdown-item notify-item paid_pending_cls">
                            <i class="mdi mdi-account-outline"></i>
                            <span>My Profile</span>
                        </a>

                        <a href="{{ url('company-profile') }}" class="dropdown-item notify-item paid_pending_cls">
                            <i class="mdi mdi-office-building"></i>
                            <span>Company Profile</span>
                        </a>




                        @if (Auth::guard('company_user')->user()->company_user_type == 'company_super_admin' && isset($company_item) && $company_item->number_of_owners > 1 && $company_item->membership_level->paid_members == 'yes')
                        <a href="{{ url('company-owners') }}" class="dropdown-item notify-item paid_pending_cls">
                            <i class="fas fa-users"></i>
                            <span>Company Owners</span>
                        </a>
                        @endif

                        <a href="{{ url('service-category') }}" class="dropdown-item notify-item paid_pending_cls">
                            <i class="mdi mdi-server"></i>
                            <span>Service Categories</span>
                        </a>

                        <a href="{{ url('zip-codes') }}" class="dropdown-item notify-item paid_pending_cls">
                            <i class="mdi mdi-map-plus"></i>
                            <span>Zipcodes</span>
                        </a>

                        <a href="{{ url('lead-management') }}" class="dropdown-item notify-item paid_pending_cls">
                            <i class="mdi mdi-dice-multiple-outline"></i>
                            <span>Lead Management</span>
                        </a>

                        @if ($company_owner->company->membership_level->paid_members == 'yes')
                        <a href="{{ url('billing') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-credit-card-outline"></i>
                            <span>Billing</span>
                        </a>

                        

                        <a href="{{ url('company-documents') }}" class="dropdown-item notify-item paid_pending_cls">
                            <i class="mdi mdi-file-document-box-check"></i>
                            <span>Company Documents</span>
                        </a>
                        @endif
                        
                        
                        <!-- item-->
                        <a href="{{ url('company_galleries') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-folder-image"></i>
                            <span>Company Gallery</span>
                        </a>

                        <!-- item-->
                        <a href="{{ url('change-password') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-key-change"></i>
                            <span>Change Password</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <a href="{{ url('logout') }}" class="dropdown-item notify-item text-danger">
                            <i class="mdi mdi-logout-variant"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>

                <?php
                /*****************************/
                /*      Settings Button      */
                /*****************************/
                /*
                <li class="dropdown notification-list">
                    <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                        <i class="mdi mdi-settings-outline noti-icon"></i>
                    </a>
                </li>
                */
                ?>

            </ul>
            @endif

            <!-- LOGO -->
            <div class="logo-box">
                <a href="{{ url('/') }}" class="logo text-center">
                    <span class="logo-lg">
                        <img src="{{ asset('/images/header-logo.png') }}" alt="">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('/images/header-logo.png') }}" alt="">
                    </span>
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end Topbar -->


    @if (Auth::guard('company_user')->check())
    <div class="topbar-menu">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">


                    <li>
                        <a href="{{ url('dashboard') }}"> <i class="mdi mdi-view-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('company_messages') }}"><i class="mdi mdi-bell-outline"></i> Messages</a>
                    </li>
                    <li>
                        <a href="{{ url('leads-archive-inbox') }}" class="paid_pending_cls"><i class="mdi mdi-email-outline"></i> Leads Archive Inbox</a>
                    </li>
                    <li>
                        <a href="{{ url('feedback') }}" class="paid_pending_cls"><i class="mdi mdi-email-multiple"></i> Feedback</a>
                    </li>

                    


                    @if (isset($company_item) && ($company_item->status == 'Application Declined' || $company_item->status == 'Declined Payment'))
                    @else
                    <li>
                        <a href="{{ url('member-resources') }}" id="member_resources_link" class="paid_pending_cls"> <i class="mdi mdi-library-books"></i> Member Resources</a>
                    </li>
                    <li>
                        <a href="{{ url('partner-links') }}" id="partner_links_link" class="paid_pending_cls"><i class="mdi mdi-link-variant"></i> Partner Links</a>
                    </li>
                    @endif

                    <li>
                        <a href="{{ url('news') }}"> <i class="mdi mdi-newspaper-variant-multiple-outline"></i> Latest News</a>
                    </li>


                    <li>
                        <a href="{{ url('contact-us') }}"> <i class="mdi mdi-phone-outline"></i> Contact Us</a>
                    </li>

                    <li>
                        <a href="{{ url('faq') }}"> <i class="mdi mdi-comment-question-outline"></i> FAQ</a>
                    </li>


                </ul>
                <!-- End navigation menu -->

                <div class="clearfix"></div>
            </div>
            <!-- end #navigation -->
        </div>
        <!-- end container -->
    </div>
    @endif
    <!-- end navbar-custom -->
</header>


<?php /*



<!-- Navigation Bar -->
<header id="topnav">
    <div class="topbar-main navbar p-0">
        <div class="container-fluid">
            <div class="topbar-left">
                <a href="{{ url('admin') }}" class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="" style="max-height 50px;" />
                </a>
            </div>


            <div class="menu-extras">

                <ul class="nav navbar-right float-right">

                    <li><a data-toggle="tooltip" data-placement="bottom" data-original-title="View Website"
                            href="{{ url('/') }}" target="_blank"><i class="fas fa-home"></i></a></li>

                    <li class="dropdown navbar-c-items">
                        <a href="#" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown"
                            aria-expanded="true"><i class="fas fa-user"></i></a>
                        <ul
                            class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                            <li class="text-center">
                                <h5>Hi, {{ Auth::user()->first_name }}</h5>
                            </li>
                            <li><a href="{{ url('admin/profile') }}"><i class="ti-user m-r-5"></i> Profile</a></li>
                            <li><a href="{{ url('admin/change-password') }}"><i class="ti-eraser m-r-5"></i> Change
                                    Password</a></li>

                            <li><a href="{{ url('admin/logout') }}"><i class="ti-power-off m-r-5"></i> Logout</a></li>

                        </ul>

                    </li>
                </ul>
                <div class="menu-item">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </div>
            </div>
            <!-- end menu-extras -->

        </div> <!-- end container-fluid -->
    </div>
    <!-- end topbar-main -->

    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">

                    <li><a href="{{ url('admin/dashboard') }}"><i class="mdi mdi-view-dashboard"></i>Dashboard</a></li>

                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-list"></i>Leads</a>
                        <ul class="submenu">
                            <li><a href="{{ url('admin/trades') }}">Trades</a></li>
                            <li><a href="{{ url('admin/top_level_categories') }}">Top Level Category</a></li>
                            <li><a href="{{ url('admin/main_categories') }}">Main Category</a></li>
                            <li><a href="{{ url('admin/service_category_types') }}">Service Category Types</a></li>
                            <li><a href="{{ url('admin/service_categories') }}">Service Category</a></li>
                        </ul>
                    </li>


                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-layers"></i>UI Kit</a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    <li>
                                        <span>Components</span>
                                    </li>
                                    <li><a href="ui-buttons.html">Buttons</a></li>
                                    <li><a href="ui-typography.html">Typography</a></li>
                                    <li><a href="ui-card.html">Card</a></li>
                                    <li><a href="ui-portlets.html">Portlets</a></li>
                                    <li><a href="ui-modals.html">Modals</a></li>
                                    <li><a href="ui-checkbox-radio.html">Checkboxs-Radios</a></li>
                                    <li><a href="ui-tabs.html">Tabs</a></li>
                                    <li><a href="ui-progressbars.html">Progress Bars</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li>
                                        <span>Components</span>
                                    </li>
                                    <li><a href="ui-notifications.html">Notification</a></li>
                                    <li><a href="ui-alerts.html">Alerts</a>
                                    <li><a href="ui-carousel.html">Carousel</a>
                                    <li><a href="ui-video.html">Video</a>
                                    <li><a href="ui-tooltips-popovers.html">Tooltips & Popovers</a></li>
                                    <li><a href="ui-images.html">Images</a></li>
                                    <li><a href="ui-bootstrap.html">Bootstrap UI</a></li>
                                    <li><a href="ui-grid.html">Grid</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li>
                                        <span>Admin UI</span>
                                    </li>
                                    <li><a href="admin-sweet-alert2.html">Sweet Alert 2</a></li>
                                    <li><a href="admin-widgets.html">Widgets</a></li>
                                    <li><a href="admin-nestable.html">Nestable List</a></li>
                                    <li><a href="admin-rangeslider.html">Range Slider</a></li>
                                    <li><a href="admin-ratings.html">Ratings</a></li>
                                    <li><a href="admin-animation.html">Animation</a></li>
                                    <li><a href="admin-calendar.html">Calendar</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-diamond-stone"></i>Components</a>
                        <ul class="submenu">
                            <li class="has-submenu">
                                <a href="#">Forms</a>
                                <ul class="submenu">
                                    <li><a href="form-elements.html">Form Elements</a></li>
                                    <li><a href="form-advanced.html">Form Advanced</a></li>
                                    <li><a href="form-validation.html">Form Validation</a></li>
                                    <li><a href="form-pickers.html">Form Pickers</a></li>
                                    <li><a href="form-wizard.html">Form Wizard</a></li>
                                    <li><a href="form-mask.html">Form Masks</a></li>
                                    <li><a href="form-summernote.html">Summernote</a></li>
                                    <li><a href="form-wysiwig.html">Wysiwig Editors</a></li>
                                    <li><a href="form-uploads.html">Multiple File Upload</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Charts</a>
                                <ul class="submenu">
                                    <li><a href="chart-flot.html">Flot Chart</a></li>
                                    <li><a href="chart-morris.html">Morris Chart</a></li>
                                    <li><a href="chart-google.html">Google Chart</a></li>
                                    <li><a href="chart-chartist.html">Chartist Charts</a></li>
                                    <li><a href="chart-chartjs.html">Chartjs Chart</a></li>
                                    <li><a href="chart-c3.html">C3 Chart</a></li>
                                    <li><a href="chart-sparkline.html">Sparkline Chart</a></li>
                                    <li><a href="chart-knob.html">Jquery Knob</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Email</a>
                                <ul class="submenu">
                                    <li><a href="email-inbox.html"> Inbox</a></li>
                                    <li><a href="email-read.html"> Read Mail</a></li>
                                    <li><a href="email-compose.html"> Compose Mail</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Icons</a>
                                <ul class="submenu">
                                    <li><a href="icons-colored.html">Colored Icons</a></li>
                                    <li><a href="icons-materialdesign.html">Material Design</a></li>
                                    <li><a href="icons-ionicons.html">Ion Icons</a></li>
                                    <li><a href="icons-fontawesome.html">Font awesome</a></li>
                                    <li><a href="icons-themifyicon.html">Themify Icons</a></li>
                                    <li><a href="icons-typicons.html">Typicons</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Tables</a>
                                <ul class="submenu">
                                    <li><a href="tables-basic.html">Basic Tables</a></li>
                                    <li><a href="tables-layouts.html">Tables Layouts</a></li>
                                    <li><a href="tables-datatable.html">Data Table</a></li>
                                    <li><a href="tables-responsive.html">Responsive Table</a></li>
                                    <li><a href="tables-tablesaw.html">Tablesaw Table</a></li>
                                    <li><a href="tables-editable.html">Editable Table</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Maps</a>
                                <ul class="submenu">
                                    <li><a href="maps-google.html">Google Maps</a></li>
                                    <li><a href="maps-vector.html">Vector Maps</a></li>
                                    <li><a href="maps-mapael.html">Mapael Maps</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-google-pages"></i>Pages</a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    <li><a href="page-starter.html">Starter Page</a></li>
                                    <li><a href="page-login.html">Login</a></li>
                                    <li><a href="page-register.html">Register</a></li>
                                    <li><a href="page-logout.html">Logout</a></li>
                                    <li><a href="page-recoverpw.html">Recover Password</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li><a href="page-lock-screen.html">Lock Screen</a></li>
                                    <li><a href="page-confirm-mail.html">Confirm Mail</a></li>
                                    <li><a href="page-404.html">Error 404</a></li>
                                    <li><a href="page-404-alt.html">Error 404-alt</a></li>
                                    <li><a href="page-500.html">Error 500</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-book-multiple"></i>Extras</a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    <li><a href="extras-profile.html">Profile</a></li>
                                    <li><a href="extras-sitemap.html">Sitemap</a></li>
                                    <li><a href="extras-about.html">About Us</a></li>
                                    <li><a href="extras-contact.html">Contact</a></li>
                                    <li><a href="extras-members.html">Members</a></li>
                                    <li><a href="extras-timeline.html">Timeline</a></li>
                                    <li><a href="extras-invoice.html">Invoice</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li><a href="extras-search-result.html">Search Result</a></li>
                                    <li><a href="extras-emailtemplate.html">Email Template</a></li>
                                    <li><a href="extras-maintenance.html">Maintenance</a></li>
                                    <li><a href="extras-coming-soon.html">Coming Soon</a></li>
                                    <li><a href="extras-faq.html">FAQ</a></li>
                                    <li><a href="extras-gallery.html">Gallery</a></li>
                                    <li><a href="extras-pricing.html">Pricing</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-comment-text"></i>Blog</a>
                        <ul class="submenu">
                            <li><a href="blogs-dashboard.html">Dashboard</a></li>
                            <li><a href="blogs-blog-list.html">Blog List</a></li>
                            <li><a href="blogs-blog-column.html">Blog Column</a></li>
                            <li><a href="blogs-blog-post.html">Blog Post</a></li>
                            <li><a href="blogs-blog-add.html">Add Blog</a></li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="mdi mdi-home-map-marker"></i>Real Estate</a>
                        <ul class="submenu">
                            <li><a href="real-estate-dashboard.html">Dashboard</a></li>
                            <li><a href="real-estate-list.html">Property List</a></li>
                            <li><a href="real-estate-column.html">Property Column</a></li>
                            <li><a href="real-estate-detail.html">Property Detail</a></li>
                            <li><a href="real-estate-agents.html">Agents</a></li>
                            <li><a href="real-estate-profile.html">Agent Details</a></li>
                            <li><a href="real-estate-add.html">Add Property</a></li>
                        </ul>
                    </li>


</ul>
<!-- End navigation menu -->
</div> <!-- end #navigation -->
</div> <!-- end container-fluid -->
</div> <!-- end navbar-custom -->
</header>
<!-- End Navigation Bar-->
*/ ?>
