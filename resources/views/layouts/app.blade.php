<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{!! asset('fontawesome/css/all.min.css') !!}">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="{!! asset('css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/templatemo-xtra-blog.css') !!}" rel="stylesheet">
</head>
<body>
<header class="tm-header" id="tm-header">
    <div class="tm-header-wrapper">
        <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="tm-site-header">
            @auth
                <div class="mb-3 mx-auto tm-site-logo">
                    <i class="fas fa-times fa-2x"></i>
                </div>
                <h1 class="text-center">Xtra Blog</h1>
            @endauth
        </div>
        <nav class="tm-nav" id="tm-nav">
            <ul>
                @guest
                    <li class="tm-nav-item {!! activeRoute('site.login.*') !!}">
                        <a href="{!! route('site.login.page') !!}" class="tm-nav-link">
                            <i class="fas fa-home"></i>
                            Login
                        </a>
                    </li>
                    <li class="tm-nav-item {!! activeRoute('site.register.*') !!}">
                        <a href="{!! route('site.register.page') !!}" class="tm-nav-link">
                            <i class="fas fa-home"></i>
                            Register
                        </a>
                    </li>
                @endguest
                <li class="tm-nav-item {!! activeRoute('site.home') !!}">
                    <a href="{!! route('site.home') !!}" class="tm-nav-link">
                        <i class="fas fa-home"></i>
                        Blog Home
                    </a>
                </li>
                @auth
                    <li class="tm-nav-item {!! activeRoute('site.posts.create') !!}">
                        <a href="{!! route('site.posts.create') !!}" class="tm-nav-link">
                            <i class="fas fa-plus-circle"></i>
                            Submit Post
                        </a>
                    </li>
                    <li class="tm-nav-item {!! activeRoute('site.chat') !!}">
                        <a href="{!! route('site.chat') !!}" class="tm-nav-link">
                            <i class="fas fa-plus-circle"></i>
                            Chat
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
        <div class="tm-mb-65">
            <a rel="nofollow" href="https://fb.com/templatemo" class="tm-social-link">
                <i class="fab fa-facebook tm-social-icon"></i>
            </a>
            <a href="https://twitter.com" class="tm-social-link">
                <i class="fab fa-twitter tm-social-icon"></i>
            </a>
            <a href="https://instagram.com" class="tm-social-link">
                <i class="fab fa-instagram tm-social-icon"></i>
            </a>
            <a href="https://linkedin.com" class="tm-social-link">
                <i class="fab fa-linkedin tm-social-icon"></i>
            </a>
        </div>
        @auth
            <form action="{!! route('site.logout') !!}" method="post">
                @csrf
                <button type="submit" class="btn__logout">LOGOUT</button>
            </form>
        @endauth
    </div>
</header>
<div class="container-fluid">
    <main class="tm-main">
        @if($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif
        <div class="row tm-row">
            <div class="col-12">
                <form method="GET" class="form-inline tm-mb-80 tm-search-form">
                    <input class="form-control tm-search-input" name="query" type="text" placeholder="Search..."
                           aria-label="Search">
                    <button class="tm-search-button" type="submit">
                        <i class="fas fa-search tm-search-icon" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>
        @yield('content')
    </main>
</div>
<script src="{!! asset('js/jquery.min.js') !!}"></script>
<script src="{!! asset('js/templatemo-script.js') !!}"></script>
@stack('scripts')
</body>
</html>
