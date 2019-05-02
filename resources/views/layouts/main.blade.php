<!DOCTYPE html>
<html lang="en">
<head>

    {{-- meta --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{--Site title--}}
    @if(View::hasSection('title'))
        <title>{{ setting('company_name') }} - @yield('title')</title>
    @else
        <title>{{ setting('company_name') }}</title>
    @endif

    {{--favicon--}}
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('/build/frontend/images/favicon.ico')}}">
    <link href="{{ mix("css/bundle.css", 'build/frontend') }}" rel="stylesheet">

    {{--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries--}}
    {{--WARNING: Respond.js doesn't work if you view the page via file://--}}
    {{--[if lt IE 9]--}}
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    {{--[endif]--}}

</head>

<body>
{{--Top Bar Starts--}}
<div class="top-bar">
    <div class="container">
        <div class="tphone">
            <a href="tel:{{ setting('company_phone') }}"><i class="fa fa-volume-control-phone"></i> {{ setting('company_phone') }}</a>
        </div>
        <div class="tmail">
            <a href="mailto:{{ setting('email_account_support') }}"><i class="fa fa-paper-plane"></i> {{ setting('email_account_support') }}</a>
        </div>
        <div class="tsocial hidden">
            <a href=""><i class="fa fa-twitter"></i></a>
            <a href=""><i class="fa fa-linkedin"></i></a>
        </div>
    </div>
</div>
{{--Top Bar Ends--}}

@include('frontend.partials.navigation')

@yield('content')

{{--GOOGLE MAP JS--}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4aPWZJGzUv1Wi8zF8GgNdg2LQPlMjDl8"></script>
<script type="text/javascript" src="{{ mix("js/bundle.js", 'build/frontend') }}"></script>
</body>
</html>
