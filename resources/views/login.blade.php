@extends('layouts.app')

@section('title', 'Home page')

@section('content')
    <div class="container">
        <form method="POST" action="{!! route('site.login') !!}">
            @csrf
            <div class="row mb-2">
                <div class="col-3">
                    <label for="username">Username</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="username" id="username" type="text" placeholder="Username"
                           aria-label="Username">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-3">
                    <label for="password">Password</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="password" id="password" type="password" placeholder="Password"
                           aria-label="Password">
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
