@extends('user.layouts.auth')

@section('custom_css')
   <style>
       .txtWhitecolor {
           color: white !important;
       }
   </style>
@endsection
@section('content')

<div class="auth-container">
    
    <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title text-center txtWhitecolor">{{__('button2')}}</h3>
            <div class="card-text">
                @include('includes.message')
                <form method="POST" action="">
                    @csrf
                    <!-- <div class="form-group">
                        <label for="">Email address</label>
                        <input type="email" class="form-control" id="" aria-describedby="" name="email" value="{{old('email')}}" placeholder="Enter you email here..." required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> -->
                    <div class="form-group">
                        <label class="txtWhitecolor" for="">{{__('label1')}}</label>
                        <input type="text" class="form-control" id="" aria-describedby="" name="username" value="{{old('username')}}" placeholder="{{__('placeholder1')}}" required>
                        @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" style="color: white;">{{__('label2')}}</label>
                        <a href="{{route('forgot', app()->getLocale())}}" style="float:right;font-size:12px;color: white;">{{__('link1')}}</a>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="password" value="{{old('password')}}" placeholder="{{__('placeholder2')}}" required>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
{{--                    <div class="form-group form-check">--}}
{{--                        <input type="checkbox" class="form-check-input " id="exampleCheck1" name="remember" value="" placeholder="">--}}
{{--                        <label class="form-check-label"style="color: white;">{{__('checkbox1')}}</label>--}}
{{--                    </div>--}}
                    <button type="submit" class="btn btn-outline-warning float-right">{{__('title4')}}</button>

                    <div class="sign-up">
                        <a class="txtWhitecolor" href="{{route('signup', app()->getLocale())}}">{{__('link2')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

@endsection
