@extends('layouts.app')

@section('title', 'Home page')

@section('content')
    <div class="container">
        <form method="POST" action="{!! route('site.register') !!}">
            @csrf
            <div class="row mb-2">
                <div class="col-3">
                    <label for="username">Username</label>
                </div>
                <div class="col-9">
                    <input class="form-control"
                           name="username"
                           id="username"
                           type="text"
                           placeholder="Username"
                           value="{!! old('username') !!}"
                           aria-label="Username" required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-3">
                    <label for="email">Email</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="email" id="email" type="email" placeholder="Email" required
                           value="{!! old('email') !!}">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-3">
                    <label for="name">Name</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="name" id="name" type="text" placeholder="Name" required
                           value="{!! old('name') !!}">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-3">
                    <label for="password">Password</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="password" id="password" type="password" placeholder="Password"
                           aria-label="Password" required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-3">
                    <label for="password-confirmation">Confirm password</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="password_confirmation" id="password-confirmation"
                           type="password" placeholder="Confirm password" required>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-success">
                    Login
                    <i class="fa fa-sign-in-alt"></i>
                </button>
            </div>

        </form>
    </div>
@endsection
