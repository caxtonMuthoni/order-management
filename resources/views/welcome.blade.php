<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Order Management Made easier</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
      
      <link rel="stylesheet" href="{{ secure_asset('css/main.css')}}">
</head>

<body class="antialiased">
    <nav class="nav">
        <div class="nav-bar__logo nav__logo-1 js--logo-1">
            <img src="{{asset('/imgs/OR.svg')}}" alt="logo" class="nav-bar__logo-image">
        </div>

        <div class="nav-bar__logo nav__logo-2 js--logo-2">
            <img src="{{asset('/imgs/OR.svg')}}" alt="logo" class="nav__logo-2-image">
        </div>

        <ul class="nav-items" id="menucontainer">
            @if (Route::has('login'))
            @auth
            <li class="nav-item" style="margin-left: 2rem;"><a href="/home" class="nav-link">Dashboard</a></li>
            <li class="nav-item" style="margin-left: 2rem;"><a href="" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="nav-link">Logout</a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            @else
            <li class="nav-item" style="margin-left: 2rem;"><a href="/login" class="nav-link">Sign in</a></li>
            @if (Route::has('register'))
                <li class="nav-item" style="margin-left: 2rem;"><a href="/register" class="nav-link">sign up</a></li>
                @endif
            @endauth
            @endif
        </ul>
        <i class="fas fa-bars  nav-icon  js--open-menu"></i>
        <i class="fas fa-window-close nav-small-icon  js--close-menu "></i>
    </nav>

    <header class="header" id="home">
        <div class="header__content">
            <h1 class="heading-1">Order Management Made Easier</h1>
            <h4 class="heading-4 heading-4--light">Manage your orders centrally with an online order manager. Hit the get started button to create an account.</h4>
            <a href="/register" class="btn__home">Get Started Now</a>
            <a href="/login" class="btn-learn">Login to my account</a>
        </div>
        <div class="header__ilust">
            <img src="{{asset('imgs/svgs/home.svg')}}" alt="home">
        </div>
    </header>
    </div>
</body>

</html>