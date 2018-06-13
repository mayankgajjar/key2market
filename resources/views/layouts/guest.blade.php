<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="shortcut icon" href="{{{ asset('public/key2market-iconlet.png') }}}">
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    
    

    <link rel="stylesheet" href="{{ asset('public/css/chart/anomaly_detection.css?ver=4.9.4') }}">
    <link rel="stylesheet" href="{{ asset('public/css/chart/anomaly_detection_general.css?ver=4.9.4') }}">
    <link rel="stylesheet" href="{{ asset('public/css/chart/anomalydetection_theme_fixes.css?ver=4.9.4') }}">
    <link rel="stylesheet" href="{{ asset('public/css/chart/crypto-chart-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/chart/crypto-chart-public.css') }}">
    

    <script src="{{ asset('public/js/chart/anomaly_detect.js') }}"></script>
    <script src="{{ asset('public/js/chart/anomaly_detect_js.js') }}"></script>
    <script src="{{ asset('public/js/chart/dygraph-combined-dev.js') }}"></script>
    <script src="{{ asset('public/js/chart/crypto-chart-admin.js') }}"></script>

    
</head>

<body>
    <noscript>
        <div class="alert alert-warning">
            <strong>Opps ! </strong> JavaScript seems to be disabled in your browser. You must have JavaScript enabled in your browser to utillze the functionality of this website.
        </div>
    </noscript>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('#') }}">
                        <img src="https://s3-eu-west-1.amazonaws.com/k2m-web-assets/key2market-logo-long.png" alt="Key2Market - The Next Generation of Business Intelligence" style="width: 100px" class="logoDesktop hidden-phone">
                    </a>
                   
                    
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <!--<li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>-->
                        @else
                            <!--<li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>-->
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
    
    <!-- Scripts -->
    
</body>

</html>
