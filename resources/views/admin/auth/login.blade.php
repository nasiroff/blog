<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="{!! asset('admin/global_assets/css/icons/icomoon/styles.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin/assets/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin/assets/css/bootstrap_limitless.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin/assets/css/layout.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin/assets/css/components.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('admin/assets/css/colors.min.css') !!}" rel="stylesheet" type="text/css">
    <script src="{!! asset('admin/global_assets/js/main/jquery.min.js') !!}"></script>
    <script src="{!! asset('admin/global_assets/js/main/bootstrap.bundle.min.js') !!}"></script>
    <script src="{!! asset('admin/global_assets/js/plugins/loaders/blockui.min.js') !!}"></script>
    <script src="{!! asset('admin/assets/js/app.js') !!}"></script>
</head>
<body>

<div class="navbar navbar-expand-md navbar-dark">
    <div class="navbar-brand">
        <img src="{!! asset('admin/global_assets/images/logo_light.png') !!}" alt="">
    </div>
</div>
<div class="page-content">
    <div class="content-wrapper">
        <div class="content d-flex justify-content-center align-items-center">
            @if($errors->any())
                {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
            @endif
            <form class="login-form" method="post" action="{!! route('admin.login') !!}">
                @csrf
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                            <h5 class="mb-0">Login to your account</h5>
                            <span class="d-block text-muted">Enter your credentials below</span>
                        </div>

                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <input type="text" class="form-control" name="username" placeholder="Username">
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                            <div class="form-control-feedback">
                                <i class="icon-lock2 text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Sign in <i
                                    class="icon-circle-right2 ml-2"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
