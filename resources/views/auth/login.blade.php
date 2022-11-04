@extends('layouts.auth.auth')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="{{ Route::currentRouteName() }}" class="h2"><b>Account</b> Login</a>
        </div>
        <div class="card-body">
            @error('email')
                <span class="text text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Enter Email" autocomplete="off"
                        required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control"
                            placeholder="Enter your account password" autocomplete="off" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <p class="mb-1">
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </p>
            <p class="mb-1">
                <a href="{{ route('register') }}">Dont have an account? Register</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
@endsection
