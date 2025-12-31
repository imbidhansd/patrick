<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
        <!-- App title -->
        <title>Trust Patrick Referral Network</title>

        <!-- App css -->
        @include ('find_a_pro.includes._css')
        <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/find_a_pro.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/find_a_pro_responsive.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/wp-style-custom.css') }}" />
        @yield('page_css')



        <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
        <meta name="title" content="Find A Pro - {{env('APP_NAME')}}" />
        <meta name="description" content="Trust Patrick - Looking for companies you can trust? Having problems with a company and need our help? TrustPatrick.com, home of Consumer Advocate Patrick Mattingley and the host of Consumers Corner show is the place to go for all the help you need." />

            
        <link rel="canonical" href="{{env('APP_URL')}}/find-a-pro" />


        <meta property="og:url" content="{{env('APP_URL')}}/find-a-pro" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="Find A Pro | {{env('APP_NAME')}}" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:description" content="Trust Patrick - Looking for companies you can trust? Having problems with a company and need our help? TrustPatrick.com, home of Consumer Advocate Patrick Mattingley and the host of Consumers Corner show is the place to go for all the help you need.
        <meta property="og:site_name" content="{{env('APP_NAME')}}" />
        <meta property="og:image" content="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/20240113155245/trust_patrick_social_media_v2.jpg" />


        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:description" content="Trust Patrick - Looking for companies you can trust? Having problems with a company and need our help? TrustPatrick.com, home of Consumer Advocate Patrick Mattingley and the host of Consumers Corner show is the place to go for all the help you need." />
        <meta name="twitter:title" content="Find A Pro | {{env('APP_NAME')}}" />

        <meta name="twitter:image" content="" />

        <meta name="twitter:image:alt" content="Find A Pro | {{env('APP_NAME')}} Recommended Companies">


    </head>
    <body>
        @include ('company.includes.wp-header')    
        @yield('content')
        @include ('company.includes.wp-footer')


        @include ('admin.includes._js')
        @include ('company.includes.sticky_js')
        @yield('page_js')
    </body>
</html>
