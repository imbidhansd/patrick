@php //dd($module_with_permissions); @endphp

<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li>
                    <a href="{{ url('admin/dashboard') }}" class="waves-effect waves-light">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-diamond-stone"></i>
                        <span>Lead System</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/leads') }}">Leads Dashboard</a></li>
                        <li><a href="{{ url('admin/affiliates') }}">Affiliates</a></li>

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

                        <?php /* <li><a href="{{ url('admin/networx_tasks') }}">Networx</a></li> */ ?>
                        <li><a href="{{ url('admin/service_categories/networx_task_list') }}">Networx Task List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-chart-bar"></i>
                        <span>Non Members</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/non_members') }}">Manage Members</a></li>
                        <li><a href="{{ url('admin/non_member_emails') }}">Follow Up Emails</a></li>
                        <li><a href="{{ url('admin/broadcast_emails?email_type=non_members') }}">Broadcast Emails</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-registered-trademark"></i>
                        <span>Registered Companies</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ route('company_by_membership_level', ['membership_level' => 'all-registered-members']) }}">Manage Companies</a></li>

                        <li><a href="{{ url('admin/registered_member_emails') }}">Follow Up Emails</a></li>
                        <li><a href="{{ url('admin/broadcast_emails?email_type=registered_members') }}">Broadcast Emails</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-shield-check-outline"></i>
                        <span>Official Members</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/companies/paid-pending') }}">Paid Pending</a></li>
                        <li><a href="{{ url('admin/companies/pending-approval') }}">Pending Approvals</a></li>
                        <li><a href="{{ route('company_by_membership_level', ['membership_level' => 'all-official-members']) }}">Manage Members</a></li>

                        <li>
                            <a href="{{ url('admin/companies/company-galleries') }}">
                                Company Gallery <br />
                                Photos Approvals
                            </a>
                        </li>
                        <?php /* <li><a href="{{ url('admin/company_users') }}">Company Owners</a></li> */ ?>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect waves-light">
                                <i class="mdi mdi-image"></i>
                                <span>Banners</span>
                                <span class="menu-arrow"></span>                            
                            </a>
                            <ul class="nav-third-level" aria-expanded="false">
                                <li><a href="{{ url('admin/site_logos?domain_slug=tp') }}">TrustPatrick.com</a></li>
                                <li><a href="{{ url('admin/site_logos?domain_slug=aad') }}">AllAboutDriveways.com</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ url('admin/artworks?artwork_type=social_media') }}">Social Media</a></li>
                        <li><a href="{{ url('admin/artworks?artwork_type=print_ready') }}">Artwork</a></li>
                        <li><a href="{{ url('admin/company_priority') }}">Company Priority</a></li>

                        <li><a href="{{ url('admin/new_emails?email_for=company') }}">Custom Emails</a></li>
                        <li><a href="{{ url('admin/broadcast_emails?email_type=official_members') }}">Broadcast Emails</a></li>
                        <li><a href="{{ url('admin/partner_links') }}">Partner Links</a></li>
                        <li><a href="{{ url('admin/verify_members') }}">Verify Members</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-account-group"></i>
                        <span>Manage Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/roles') }}">User Roles</a></li>
                        <li><a href="{{ url('admin/users') }}">Admin Users</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-email"></i>
                        <span>Feedback</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/feedback') }}">Reviews</a></li>
                        <li><a href="{{ url('admin/complaints') }}">Complaints</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-chart-pie"></i>
                        <span>Pages</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        @canany($module_with_permissions['pages'])
                        <li><a href="{{ url('admin/pages') }}">Pages</a></li>
                        @endcan

                        @canany($module_with_permissions['testimonials'])
                        <li><a href="{{ url('admin/testimonials') }}">Testimonials</a></li>
                        @endcan
                        
                        <li><a href="{{ url('admin/news') }}">News</a></li>
                        <li><a href="{{ url('admin/faqs') }}">FAQs</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ url('admin/video_podcasts') }}" class="waves-effect waves-light">
                        <i class="mdi mdi-television-play"></i>
                        <span>Video Podcasts</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-desktop-mac"></i>
                        <span>Consumer Emails</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/new_emails?email_for=consumer') }}">Consumer Emails</a></li>
                        @if (isset($sidebar_trades) && count($sidebar_trades) > 0)
                        <li>
                            <a href="javascript: void(0);" class="waves-effect waves-light">
                                <span>Follow Up Emails</span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">
                                @foreach ($sidebar_trades AS $key => $trade_item)
                                <li>
                                    <a href="{{ url('admin/follow_up_emails/?trade_id='.$key) }}">{{ $trade_item }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        
                        <li><a href="{{ url('admin/broadcast_emails') }}">Broadcast Emails</a></li>
                        <li><a href="{{ url('admin/manage_subscribers') }}">Manage Subscribers</a></li>
                    </ul>
                </li>



                <li>
                    <a href="{{ url('admin/new_emails?email_for=admin') }}" class="waves-effect waves-light">
                        <i class="mdi mdi-desktop-mac"></i>
                        <span>Admin Emails</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-shopping"></i>
                        <span>Shopping Cart</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/packages') }}">Packages</a></li>
                        <li><a href="{{ url('admin/products') }}">Products</a></li>
                        <?php //<li><a href="{{ url('admin/membership_types') }}">Membership Types</a></li> ?>
                        <li><a href="{{ url('admin/membership_levels') }}">Membership Levels</a></li>
                        <li><a href="{{ url('admin/membership_statuses') }}">Membership Statuses</a></li>
                        <li>
                            <a href="{{ url('admin/membership_level_statuses') }}">
                                Membership Level <br />
                                Videos
                            </a>
                        </li>
                        <li><a href="{{ url('admin/pre_screen_settings') }}">Pre-Screen Setting</a></li>
                        <li><a href="{{ url('admin/professional_affiliations') }}">Professional Affiliations</a></li>
                        <?php /* <li><a href="{{ url('admin/coupon_types') }}">Coupon Types</a></li>
                          <li><a href="{{ url('admin/coupons') }}">Coupons</a></li> */ ?>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-file-multiple"></i>
                        <span>PPL disputes</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/leads/open-disputes') }}">Open Disputes</a></li>
                        <li><a href="{{ url('admin/leads/closed-disputes') }}">Closed Disputes</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-cogs"></i>
                        <span>Global Settings</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/site-settings') }}">Settings</a></li>
                        <li><a href="{{ url('admin/default_email_header_footers') }}">Default Email Header footer</a></li>
                    </ul>
                </li>
                        
                <?php /* <li class="bg-primary">
                  <a href="{{ url('/') }}" target="_blank" class="waves-effect waves-light text-white">
                  <i class="mdi mdi-web"></i>
                  <span class="badge badge-success badge-pill float-right">New</span>
                  <span>Visit Website</span>
                  </a>
                  </li> */ ?>
            </ul>
        </div>
        <!-- End Sidebar -->
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->