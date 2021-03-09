@extends('admin.layouts.auth')

@section('custom_css')

@endsection
@section('content')

<div class="auth-container">
    
    <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title text-center">Admin Login</h3>
            <div class="card-text">
                @include('includes.message')
                <form method="POST" action="">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" value="{{old('email')}}" placeholder="Enter you email here..." required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <a href="{{route('forgot')}}" style="float:right;font-size:12px;">Forgot password?</a>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="password" value="{{old('password')}}" placeholder="Enter your password here..." required>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember" value="" placeholder="">
                        <label class="form-check-label" for="exampleCheck1">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block float-right">Login</button>

                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

@endsection
