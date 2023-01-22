<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/clock.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/clock/clock.css') }}" rel="stylesheet">
    <link href="{{ asset('css/attendance/buttonAttendance.css') }}" rel="stylesheet">
    <link href="{{ asset('css/attendance/attendanceIcons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/attendance.css') }}" rel="stylesheet"> 
    <link href="{{ asset('css/login/login.css') }}" rel="stylesheet"> 
    <link href="{{ asset('css/dailyAttendance/dailyAttendanceCalendar.css') }}" rel="stylesheet"> 
    <link href="{{ asset('css/dailyAttendance/attendanceExport.css') }}" rel="stylesheet"> 
    <link href="{{ asset('css/dailyAttendance/dailyAttendanceSearch.css') }}" rel="stylesheet"> 
   
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                   <strong>{{ config('app.name', 'Laravel') }}</strong> 
                </a>
                
                {{-- Routeが同じの場合は、メニューに下線 --}}
                @if(Route::currentRouteName()=='home')
                    <a class="navbar-brand" href="{{route('home')}}" style="border-bottom: solid 3px white">
                        <i class="fa-regular fa-clock"></i> 打刻
                    </a>
                @else
                    <a class="navbar-brand" href="{{route('home')}}">
                        <i class="fa-regular fa-clock"></i> 打刻
                    </a>
                @endif
                {{-- Routeが同じの場合は、メニューに下線 --}}
                @if(Route::currentRouteName()=='attendance.dailyAttendance')
                    <a class="navbar-brand" href="{{route('attendance.dailyAttendance')}}" style="border-bottom: solid 3px white">
                        <i class="fa-solid fa-calendar-days"></i> 日次勤怠
                    </a>
                @else
                    <a class="navbar-brand" href="{{route('attendance.dailyAttendance')}}">
                        <i class="fa-solid fa-calendar-days"></i> 日次勤怠
                    </a> 
                    
                @endif
                

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else 
                            {{-- <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-to-bracket"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li> --}}
                            <div class="dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                  <li>
                                    <a class="dropdown-item" href="#"
                                        onclick="event.preventDefault();
                                        document.getElementById('').submit();">
                                        <i class="material-icons" id="manage_accounts">manage_accounts</i> アカウント情報
                                    </a>
                                      
                                  </li>
                                  <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-to-bracket"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                  </li>
                                  
                                </ul>
                            </div>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <style>
            #manage_accounts{
                font-size: 18px;
                vertical-align: middle;
            }
        </style>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
