@if (Session::has('company_mask') && Session::get('company_mask') == true && isset($company_item))
<div class="admin-bar">
    <div class="container-fluid">
        <p class="m-0 p-0 text-center white-font">Masquerading as ({{ $company_item->company_name }})<a href="{{ url('logout') }}" class="btn btn-danger btn-xs ml-3">Logout From Company</a></p>
    </div>
</div>
@endif


<!-- Topbar Start -->
<div class="navbar-custom {{ Session::has('company_mask') && Session::get('company_mask') == true && isset($company_item) ? 'masquerade_mode' : '' }}">
    @if (Auth::guard('company_user')->check())
    <ul class="list-unstyled topnav-menu float-right mb-0">
        @php
        $company_owner = Auth::guard('company_user')->user();
        $last_login_activity_log = \Spatie\Activitylog\Models\Activity::where('subject_id', $company_owner->id)->where('description', 'Logged In')->orderBy('id', 'desc')->skip(1)->take(1)->first();
        @endphp

        <?php /* <li class="dropdown notification-list">
          <!-- Mobile menu toggle-->
          <a class="navbar-toggle nav-link">
          <div class="lines">
          <span></span>
          <span></span>
          <span></span>
          </div>
          </a>
          <!-- End mobile menu toggle-->
          </li> */ ?>
        <li class="dropdown notification-list d-none d-sm-none d-md-block">
            @if (!is_null($last_login_activity_log))
            <p class="mb-0 pb-0 nav-link">
                <span class="text-muted">Last Login:</span>
                <span class="text-danger">{{ $last_login_activity_log->created_at->format('m/d/Y H:i a') }}</span>
            </p>
            @else
            <p class="mb-0 pb-0 nav-link text-success">You are new user</p>
            @endif
        </li>


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
                        Messages
                    </h5>
                </div>

                @if(isset($company_messages_notification) && count($company_messages_notification) > 0)
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
                    </div> <!-- end inbox-widget -->

                </div>

                <!-- All-->
                <a href="{{ url('company_messages') }}"
                   class="dropdown-item ztext-center text-primary notify-item notify-all">
                    See all Messages
                    <i class="fi-arrow-right"></i>
                </a>
                @else

                <a href="{{ url('company_messages') }}"
                   class="dropdown-item ztext-center text-primary notify-item notify-all">
                    <small class="text-danger">No New Messages</small>
                </a>
                @endif

            </div>
        </li>

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light font-30" data-toggle="dropdown"
               href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="fas fa-user-circle"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0 text-info">Welcome {{ $company_owner->first_name }}</h6>
                    @if (!is_null($last_login_activity_log))
                    <p class="mb-0 pb-0 d-block d-sm-block d-md-none">
                        <small>
                            <span class="text-muted">Last Login</span><br/>
                            <span class="text-danger">{{ $last_login_activity_log->created_at->format('m/d/Y H:i a') }}</span>
                        </small>
                    </p>
                    @else
                    <p class="mb-0 pb-0 d-block d-sm-block d-md-none text-success">You are new user</p>
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

                @if ($company_item->membership_level->paid_members == 'yes')
                <a href="{{ url('billing') }}" class="dropdown-item notify-item">
                    <i class="mdi mdi-credit-card-outline"></i>
                    <span>Billing</span>
                </a>

                <a href="{{ url('company-documents') }}" class="dropdown-item notify-item paid_pending_cls">
                    <i class="mdi mdi-file-document-box-check"></i>
                    <span>Company Documents</span>
                </a>
                @endif


                @if ($company_item->status == 'Active')
                <!-- item-->
                <a href="{{ url('company_galleries') }}" class="dropdown-item notify-item">
                    <i class="mdi mdi-folder-image"></i>
                    <span>Photo Gallery</span>
                </a>
                @endif

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
    </ul>
    @endif

    <!-- LOGO -->
    <div class="logo-box">
        <a href="{{ url('dashboard') }}" class="logo text-center">
            <span class="logo-lg">
                <img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png"  alt="{{ env('APP_NAME') }}" />
            </span>
            <span class="logo-sm">
                <img src="{{ asset('images/small-logo.png') }}" alt="{{ env('APP_NAME') }}" />
            </span>
        </a>
    </div>

    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button class="button-menu-mobile waves-effect">
                <i class="mdi mdi-menu"></i>
            </button>
        </li>
    </ul>
</div>
<!-- end Topbar -->