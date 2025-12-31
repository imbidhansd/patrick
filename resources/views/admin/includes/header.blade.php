<!-- Navigation Bar-->
<header id="topnav">
    <!-- Topbar Start -->
    <div class="navbar-custom">
        <div class="container-fluid">
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

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="mdi mdi-email noti-icon"></i>
                        <span class="badge badge-danger rounded-circle noti-icon-badge">8</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="font-16 m-0">
                                <span class="float-right">
                                    <a href="" class="text-dark">
                                        <small>Clear All</small>
                                    </a>
                                </span>Messages
                            </h5>
                        </div>

                        <div class="slimscroll noti-scroll">

                            <div class="inbox-widget">
                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img
                                                src="{{ asset('themes/admin/assets/images/users/avatar-1.jpg') }}"
                                                class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Chadengle</p>
                                        <p class="inbox-item-text text-truncate">Hey! there I'm available...</p>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img
                                                src="{{ asset('themes/admin/assets/images/users/avatar-2.jpg') }}"
                                                class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Tomaslau</p>
                                        <p class="inbox-item-text text-truncate">I've finished it! See you so...</p>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img
                                                src="{{ asset('themes/admin/assets/images/users/avatar-3.jpg') }}"
                                                class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Stillnotdavid</p>
                                        <p class="inbox-item-text text-truncate">This theme is awesome!</p>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img
                                                src="{{ asset('themes/admin/assets/images/users/avatar-4.jpg') }}"
                                                class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Kurafire</p>
                                        <p class="inbox-item-text text-truncate">Nice to meet you</p>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="inbox-item">
                                        <div class="inbox-item-img"><img
                                                src="{{ asset('themes/admin/assets/images/users/avatar-5.jpg') }}"
                                                class="rounded-circle" alt=""></div>
                                        <p class="inbox-item-author">Shahedk</p>
                                        <p class="inbox-item-text text-truncate">Hey! there I am available...</p>

                                    </div>
                                </a>
                            </div> <!-- end inbox-widget -->

                        </div>

                        <!-- All-->
                        <a href="javascript:void(0);"
                            class="dropdown-item text-center text-primary notify-item notify-all">
                            See all Messages
                            <i class="fi-arrow-right"></i>
                        </a>



                    </div>
                </li>

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fas fa-user-circle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome {{ Auth::user()->first_name }}!</h6>
                        </div>

                        <!-- item-->
                        <a href="{{ url('admin/profile') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-outline"></i>
                            <span>Profile</span>
                        </a>

                        <!-- item-->
                        <a href="{{ url('admin/change-password') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-key-change"></i>
                            <span>Change Password</span>
                        </a>

                        <!-- item-->
                        <a href="{{ url('admin/site-settings') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-settings-outline"></i>
                            <span>Settings</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <a href="{{ url('admin/logout') }}" class="dropdown-item notify-item text-danger">
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

            <!-- LOGO -->
            <div class="logo-box">
                <a href="{{ url('admin') }}" class="logo text-center">
                    <span class="logo-lg">
                        <img src="{{ asset('/images/header-logo.png') }}" alt="{{ env('APP_NAME') }}">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('/images/header-logo.png') }}" alt="{{ env('APP_NAME') }}">
                    </span>
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end Topbar -->

    <div class="topbar-menu">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">

                    <li><a href="{{ url('admin/dashboard') }}"> <i class="mdi mdi-view-dashboard"></i>Dashboard</a></li>


                    <li class="has-submenu">
                        <a href="javascript:;"><i class="mdi mdi-layers"></i>Site Content</a>
                        <ul class="submenu megamenu">

                            @canany(array_merge($module_with_permissions['pages'],
                            $module_with_permissions['post_categories'], $module_with_permissions['posts'],
                            $module_with_permissions['testimonials']))
                            <li>
                                <ul>
                                    <li>
                                        <span>Post & Pages</span>
                                    </li>
                                    @canany($module_with_permissions['pages'])
                                    <li><a href="{{ url('admin/pages') }}">Pages</a></li>
                                    @endcan
                                    
                                    @canany($module_with_permissions['post_categories'])
                                    <li><a href="{{ url('admin/post_categories') }}">Post Categories</a></li>
                                    @endcan

                                    @canany($module_with_permissions['posts'])
                                    <li><a href="{{ url('admin/posts') }}">Posts</a></li>
                                    @endcan
                                </ul>
                            </li>
                            @endcan

                            <li>
                                <ul>
                                    <li>
                                        <span>Other Content</span>
                                    </li>

                                    <li><a href="{{ url('admin/news') }}">News</a></li>

                                    @canany($module_with_permissions['testimonials'])
                                    <li><a href="{{ url('admin/testimonials') }}">Testimonials</a></li>
                                    @endcan

                                    <li><a href="{{ url('admin/faqs') }}">Faqs</a></li>
                                    <li><a href="{{ url('admin/company_faq_questions') }}">Company Questions</a></li>
                                </ul>
                            </li>

                            <li>
                                <ul>
                                    <li><span>Emails</span></li>
                                    
                                    <?php /* <li><a href="{{ url('admin/default_emails') }}">Default Emails</a>
                                    </li> */ ?>
                                    <li><a href="{{ url('admin/emails') }}">Emails</a></li>
                                    <li><a href="{{ url('admin/follow_up_mail_categories') }}">Follow Up Mail Categories</a></li>
                                    <li><a href="{{ url('admin/follow_up_emails') }}">Follow Up Emails</a></li>
                                    <li><a href="{{ url('admin/new_emails') }}">New Emails</a></li>
                                    <li><a href="{{ url('admin/broadcast_emails') }}">Broadcast Emails</a></li>
                                </ul>
                            </li>

                        </ul>
                    </li>


                    <li class="has-submenu">
                        <a href="javascript:;"><i class="mdi mdi-chart-bar"></i> Members</a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    <li><span>Members</span></li>

                                    @if (isset($top_menu_membership_levels) && count($top_menu_membership_levels) > 0)
                                    @foreach ($top_menu_membership_levels as $membership_level_item)
                                    <li><a
                                            href="{{ route('company_by_membership_level', ['membership_level' => $membership_level_item->slug]) }}">{{ $membership_level_item->title }}
                                            <div class="badge badge-primary">
                                                {{  $membership_level_item->companies_count }}</div>
                                        </a></li>
                                    @endforeach
                                    @endif
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li><span>Other</span></li>
                                    <li><a href="{{ url('admin/companies/pending-approval') }}">Pending Approvals</a></li>
                                    <li><a href="{{ url('admin/companies/company-galleries') }}">Company Gallery Approvals</a></li>
                                    <li><a href="{{ url('admin/company_users') }}">Company Owners</a></li>
                                    <li><a href="{{ url('admin/site_logos') }}">Site Logo</a></li>
                                    <li><a href="{{ url('admin/company_priority') }}">Company Priority</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="javascript:;"><i class="mdi mdi-diamond-stone"></i>Lead Categories</a>
                        <ul class="submenu">

                            @canany($module_with_permissions['trades'])
                            <li><a href="{{ url('admin/trades') }}">Trades</a></li>
                            @endcan

                            @canany($module_with_permissions['top_level_categories'])
                            <li><a href="{{ url('admin/top_level_categories') }}">Top Level Categories</a></li>
                            @endcan

                            @canany($module_with_permissions['main_categories'])
                            <li><a href="{{ url('admin/main_categories') }}">Main Categories</a></li>
                            @endcan

                            @canany($module_with_permissions['service_category_types'])
                            <li><a href="{{ url('admin/service_category_types') }}">Service Category Types</a></li>
                            @endcan

                            @canany($module_with_permissions['service_categories'])
                            <li><a href="{{ url('admin/service_categories') }}">Service Categories</a></li>
                            @endcan
                        </ul>
                    </li>

                    <li>
                        <a href="{{ url('admin/leads') }}">
                            <i class="fas fa-handshake"></i>
                            Leads
                        </a>
                    </li>

                    <li class="has-submenu">
                        <a href="javascript:;"><i class="fas fa-users"></i>Users</a>
                        <ul class="submenu">
                            <li><a href="{{ url('admin/roles') }}">User Roles</a></li>
                            <li><a href="{{ url('admin/users') }}">Admin Users</a></li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="javascript:;"><i class="fas fa-envelope"></i>Feedback</a>
                        <ul class="submenu">
                            <li><a href="{{ url('admin/feedback') }}">Feedback</a></li>
                            <li><a href="{{ url('admin/complaints') }}">Complaints</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ url('/admin/video_podcasts') }}">
                            <i class="mdi mdi-television-play"></i>
                            Video Podcasts
                        </a>
                    </li>

                    <li class="has-submenu">
                        <a href="javascript:;"><i class="fas fa-shopping-cart"></i>Shopping Cart</a>
                        <ul class="submenu">
                            <li><a href="{{ url('admin/packages') }}">Packages</a></li>
                            <li><a href="{{ url('admin/products') }}">Products</a></li>
                            <?php //<li><a href="{{ url('admin/membership_types') }}">Membership Types</a></li> ?>
                            <li><a href="{{ url('admin/membership_levels') }}">Membership Levels</a></li>
                            <li><a href="{{ url('admin/membership_statuses') }}">Membership Statuses</a></li>
                            <li><a href="{{ url('admin/membership_level_statuses') }}">Membership Level Videos</a></li>
                            <li><a href="{{ url('admin/pre_screen_settings') }}">Pre-Screen Setting</a></li>
                            <li><a href="{{ url('admin/professional_affiliations') }}">Professional Affiliations</a></li>
                            <?php /* <li><a href="{{ url('admin/coupon_types') }}">Coupon Types</a></li>
                            <li><a href="{{ url('admin/coupons') }}">Coupons</a></li> */ ?>
                        </ul>
                    </li>

                    <?php /*
                    <li class="has-submenu">
                        <a href="javascript:;"><i class="far fa-copy"></i>Affiliate Program</a>
                        <ul class="submenu">
                            <li><a href="{{ url('admin/affiliates') }}">Affiliates</a></li>
                        </ul>
                    </li> */ ?>

                </ul>
                <!-- End navigation menu -->

                <div class="clearfix"></div>
            </div>
            <!-- end #navigation -->
        </div>
        <!-- end container -->
    </div>
    <!-- end navbar-custom -->
</header>
