@extends('user.layouts.auth')

@section('custom_css')

@endsection
@section('content')

<div class="auth-container">
    <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title text-center">Reset Password</h3>
            <div class="card-text">
                @include('includes.message')
                <form method="POST" action="">
                    @csrf
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" id="" name="password" value="{{old('password')}}" placeholder="Enter your new password here..." required>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" class="form-control" id="" name="password_confirmation" value="{{old('password_confirmation')}}" placeholder="Enter your password again here..." required>
                        @error('password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                   
                    <button type="submit" class="btn btn-primary btn-block float-right">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

@endsection
