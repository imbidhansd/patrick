@if (Auth::guard('company_user')->check())
<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li>
                    <a href="{{ url('dashboard') }}" class="waves-effect waves-light">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('company_messages') }}" class="waves-effect waves-light paid_pending_cls">
                        <i class="mdi mdi-bell-outline"></i>
                        <span>Messages</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('leads-archive-inbox') }}" class="waves-effect waves-light paid_pending_cls">
                        <i class="mdi mdi-email-outline"></i>
                        <span>Leads Archive Inbox</span>
                        <span class="badge badge-primary badge-pill float-right">{{ $total_leads }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('feedback') }}" class="waves-effect waves-light paid_pending_cls">
                        <i class="mdi mdi-email-multiple"></i>
                        <span>Feedback</span>
                    </a>
                </li>

                @if (isset($company_item) && ($company_item->status == 'Application Declined' || $company_item->status == 'Declined Payment'))
                @else
                
                <li>
                    <a href="javascript: void(0);" id="member_resources_link" class="waves-effect waves-light paid_pending_cls">
                        <i class="mdi mdi-image-album"></i>
                        <span>Member Resources</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <!-- <a href="{{ url('member-resources') }}">Website Banners</a> -->
                            <a href="javascript: void(0);" class="waves-effect waves-light">
                                <i class="mdi mdi-image"></i>
                                <span>Banners</span>
                                <span class="menu-arrow"></span>                            
                            </a>
                            <ul class="nav-third-level" aria-expanded="false">
                                <li><a href="{{ url('member-resources?domain_slug=tp') }}">TrustPatrick.com</a></li>
                                @if ($show_aad_banner)
                                    <li><a href="{{ url('member-resources?domain_slug=aad') }}">AllAboutDriveways.com</a></li>
                                @endif
                            </ul>
                        </li>                        
                        <li><a href="{{ url('social-media-artwork') }}">Social Media Artwork</a></li>
                        <li><a href="{{ url('print-ready-artwork') }}">Print Ready Artwork</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="{{ url('partner-links') }}" id="partner_links_link" class="waves-effect waves-light paid_pending_cls">
                        <i class="mdi mdi-link-variant"></i>
                        <span>Partner Links</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ url('news') }}" class="waves-effect waves-light">
                        <i class="mdi mdi-newspaper-variant-multiple-outline"></i>
                        <span>Latest News</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('contact-us') }}" class="waves-effect waves-light">
                        <i class="mdi mdi-phone-outline"></i>
                        <span>Contact Us</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('faq') }}" class="waves-effect waves-light">
                        <i class="mdi mdi-comment-question-outline"></i>
                        <span>FAQ</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- End Sidebar -->
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->
@endif