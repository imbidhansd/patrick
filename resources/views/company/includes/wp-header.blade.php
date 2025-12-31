<!-- Header -->
<header>
    <div class="nav_sec">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light">
                <a class="navbar-brand" href="https://trustpatrick.com/">
                @if (isset($web_settings['header_logo']) && !is_null($web_settings['header_logo']))
                <?php  
                    $logo_data = json_decode($web_settings['header_logo']);                   
                ?>
                    <img src="{{ asset('/') }}/uploads/media/{{ $logo_data->filename }}" style="max-width: 280px" alt="{{env('APP_URL')}}">
                @else
                    <img src="{{ asset('/images/header-logo.png') }}" style="max-width: 280px" alt="{{env('APP_URL')}}">
                @endif  
                </a>                  
                <button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#Navigation" aria-controls="Navigation" aria-expanded="false" aria-label="Toggle navigation"><span></span><span></span><span></span></button>

                <div class="collapse navbar-collapse" id="Navigation">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active"><a class="nav-link" href="https://trustpatrick.com/">Home</a></li>
                        <li class="nav-item active"><a class="nav-link" href="https://trustpatrick.com/#about">About</a></li>
                        <li class="nav-item active"><a class="nav-link" href="https://trustpatrick.com/#works">How It Works</a></li>
                        <li class="nav-item active"><a class="nav-link" href="https://trustpatrick.com/#team">Team</a></li>
                        <li class="nav-item active"><a class="nav-link" href="https://trustpatrick.com/#videos">Videos/Podcasts</a></li>
                        <li class="nav-item active"><a class="nav-link" href="https://trustpatrick.com/#resources">Resources</a></li>
                        <li class="nav-item active"><a class="nav-link" href="https://trustpatrick.com/#contact">Contact</a></li>
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Consumer Resources</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <div class="dropdown-menu-inner">
                                    <a class="dropdown-item" href="{{env('APP_URL')}}/help-center/">Consumer Help Center</a>
                                    <a class="dropdown-item" href="{{env('APP_URL')}}/verify-company">Verify A Company</a>
                                    <a class="dropdown-item" href="{{env('APP_URL')}}/consumers-corner/">Consumers Corner Show</a>
                                    <a class="dropdown-item" href="{{env('APP_URL')}}/videos-podcasts/">Video/Podcast Library</a>
                                </div>
                            </div>
                        </li> -->

                        <!-- <li class="nav-item"><a class="nav-link" href="{{env('APP_URL')}}/articles/">Articles</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Contact</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <div class="dropdown-menu-inner">
                                    <a class="dropdown-item" href="{{env('APP_URL')}}/contact/">General Contact</a>
                                    <a class="dropdown-item" href="{{env('APP_URL')}}/get-listed/">Become A Recommended Pro</a>
                                </div>
                            </div>
                        </li> -->

                        <li class="nav-item"><a class="nav-link fin-a-pro-btn" href="{{env('APP_URL')}}/find-a-pro/"><span>FIND A PRO</span></a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>