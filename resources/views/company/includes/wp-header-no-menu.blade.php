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
                
                <div class="collapse navbar-collapse" id="Navigation">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active"><a class="nav-link" href="{{env('APP_URL')}}/">&nbsp;</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>