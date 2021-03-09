@extends('user.layouts.auth')

@section('custom_css')

@endsection
@section('content')

<div class="auth-container">
    <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title text-center">Forgot Password</h3>
            <div class="card-text">
                @include('includes.message')
                <form method="POST" action="">
                    @csrf
                    <div class="form-group">
                        <label for="">Email address</label>
                        <input type="email" class="form-control" id="" aria-describedby="emailHelp" name="email" value="{{old('email')}}" placeholder="Enter you email here..." required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block float-right">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

@endsection
