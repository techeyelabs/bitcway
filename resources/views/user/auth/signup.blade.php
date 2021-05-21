@extends('user.layouts.auth')

@section('custom_css')
@endsection
@section('content')

<div class="auth-container">
    <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title text-center">{{__('nav7')}}</h3>
            <div class="card-text">
                @include('includes.message')
                <form method="POST" action="">
                    @csrf
                    <!-- to error: add class "has-danger" -->


                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleFirstName">{{__('label4')}}</label>
                                <input type="text" class="form-control" id="exampleFirstName"
                                    aria-describedby="textHelp" name="first_name" value="{{old('first_name')}}"
                                    placeholder="{{__('placeholder7')}}" required>
                                @error('first_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleLastName">{{__('label5')}}</label>
                                <input type="text" class="form-control" id="exampleLastName" aria-describedby="textHelp"
                                    name="last_name" value="{{old('last_name')}}"
                                    placeholder="{{__('placeholder8')}}" required>
                                @error('last_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">{{__('label6')}}</label>
                        <input type="text" class="form-control" id="" name="furigana"
                            value="{{old('furigana')}}" placeholder="{{__('placeholder9')}}" required>
                        @error('furigana')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">{{__('label7')}}</label>
                        <input type="text" class="form-control" id="" name="username"
                            value="{{old('username')}}" placeholder="{{__('placeholder10')}}" required>
                        @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="">{{__('label9')}}</label>
                        <input type="email" class="form-control" id="" name="email"
                            value="{{old('email')}}" placeholder="{{__('placeholder11')}}" required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">{{__('label11')}}</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="password" value=""
                            placeholder="{{__('placeholder12')}}" required>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword2">{{__('label12')}}</label>
                        <input type="password" class="form-control" id="exampleInputPassword2"
                            name="password_confirmation" value="" placeholder="{{__('placeholder13')}}" required>
                        @error('password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="agree" required>
                        <label class="form-check-label" for="exampleCheck1"><a href="">{{__('CHECKBOX')}}</a></label>
                        <br>
                        @error('agree')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-outline-warning float-right">{{__('BUTTON5')}}</button>

                    <div class="sign-up">
                        {{__('link2')}} <a href="{{route('login', app()->getLocale())}}"> {{__('button2')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

@endsection
