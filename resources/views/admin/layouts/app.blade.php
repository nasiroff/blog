<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin') !!}/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin') !!}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin') !!}/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin') !!}/assets/css/layout.min.css" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin') !!}/assets/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin') !!}/assets/css/colors.min.css" rel="stylesheet" type="text/css">
    @stack('link')
    <script src="{!! asset('admin') !!}/global_assets/js/main/jquery.min.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/main/bootstrap.bundle.min.js"></script>
    <script src="{!! asset('admin') !!}/global_assets/js/plugins/loaders/blockui.min.js"></script>
    @stack('script')
</head>
<body>
<div class="navbar navbar-expand-md navbar-light navbar-static">
    <div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center">
        <div class="navbar-brand navbar-brand-md">
            <a href="index.html" class="d-inline-block">
                <img src="{!! asset('admin') !!}/global_assets/images/logo_light.png" alt="">
            </a>
        </div>
        <div class="navbar-brand navbar-brand-xs">
            <a href="index.html" class="d-inline-block">
                <img src="{!! asset('admin') !!}/global_assets/images/logo_icon_light.png" alt="">
            </a>
        </div>
    </div>
    <div class="d-flex flex-1 d-md-none">
        <div class="navbar-brand mr-auto">
            <a href="index.html" class="d-inline-block">
                <img src="{!! asset('admin') !!}/global_assets/images/logo_dark.png" alt="">
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>

        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="navbar-mobile" style="display: flex; justify-content: space-between">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="page-content">
    @include('admin.components.sidebar')
    <div class="content-wrapper">
    @yield('content')
        <div class="navbar navbar-expand-lg navbar-light">
            <div class="text-center d-lg-none w-100">
                <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse"
                        data-target="#navbar-footer">
                    <i class="icon-unfold mr-2"></i>
                    Footer
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; 2015 - 2018. <a href="#">Limitless Web App Kit</a> by <a
                            href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a>
					</span>
                <ul class="navbar-nav ml-lg-auto">
                    <li class="nav-item"><a href="https://kopyov.ticksy.com/" class="navbar-nav-link" target="_blank"><i
                                class="icon-lifebuoy mr-2"></i> Support</a></li>
                    <li class="nav-item"><a href="http://demo.interface.club/limitless/docs/" class="navbar-nav-link"
                                            target="_blank"><i class="icon-file-text2 mr-2"></i> Docs</a></li>
                    <li class="nav-item"><a
                            href="https://themeforest.net/item/limitless-responsive-web-application-kit/13080328?ref=kopyov"
                            class="navbar-nav-link font-weight-semibold"><span class="text-pink-400"><i
                                    class="icon-cart2 mr-2"></i> Purchase</span></a></li>
                </ul>
            </div>
        </div>
    </div>

</div>
</body>
</html>
