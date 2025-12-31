<!-- Topbar Start -->
<div class="navbar-custom">
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
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light font-30" data-toggle="dropdown"
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
    </ul>

    <!-- LOGO -->
    <div class="logo-box">
        <a href="{{ url('admin') }}" class="logo text-center">
            <span class="logo-lg">
                <img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png" alt="{{ env('APP_NAME') }}" />
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