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
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" >
    
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- Sweetalert -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet" type="text/css">
    <!-- Sweetalert -->
    <link href="{{ asset('public/loading/loading.css') }}" rel="stylesheet">
    <script src="{{ asset('public/loading/loading.js') }}"></script>

    
    <style>
        .required {    
            color: red;
            font-size: 30px;
            line-height: 26px;
            vertical-align: middle;
            display: inline-block;
        }
        .required_note{
            color: red;
            font-style: italic;
        }
        .navbar-brand {
            float: left;
            padding: 14px 15px;
            font-size: 14px;
            line-height: 22px;
            height: 50px;
            text-transform: capitalize;
        }
    </style>
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
                    <a class="navbar-brand" href="{{route('home')}}">
                        <img src="{{ asset('public/key2market-logo-long.png') }}" style="width: 100px" class="logoDesktop hidden-phone">
                    </a>
                    @guest

                    @else 
                    <ul class="nav navbar-nav">
                        <a class="navbar-brand" href="{{route('pipe.index',$user_data->id)}}">Anomaly Detection</a>
                        <a class="navbar-brand" href="{{route('pipe.inactive',$user_data->id)}}">Inactive Data Sources</a>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                 Data Monitor <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                 <li>
                                     <a href="{{route('connection.index',$user_data->id)}}">Connections</a>
                                     <a href="{{route('monitor.index',$user_data->id)}}">Tables</a>
                                 </li>
                            </ul>
                        </li>
                    </ul>
                    @endguest
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
                            <li class="dropdown">
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
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('public/js/app.js') }}"></script>
</body>
</html>
