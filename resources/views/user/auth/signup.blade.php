@extends('user.layouts.auth')

@section('custom_css')
@endsection
@section('content')

<div class="auth-container">
    <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title text-center">Signup</h3>
            <div class="card-text">
                @include('includes.message')
                <form method="POST" action="">
                    @csrf
                    <!-- to error: add class "has-danger" -->


                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleFirstName">First Name</label>
                                <input type="text" class="form-control" id="exampleFirstName"
                                    aria-describedby="textHelp" name="first_name" value="{{old('first_name')}}"
                                    placeholder="Enter your first name here..." required>
                                @error('first_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleLastName">Last Name</label>
                                <input type="text" class="form-control" id="exampleLastName" aria-describedby="textHelp"
                                    name="last_name" value="{{old('last_name')}}"
                                    placeholder="Enter your last name here..." required>
                                @error('last_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Furigana</label>
                        <input type="text" class="form-control" id="" name="furigana"
                            value="{{old('furigana')}}" placeholder="Enter your furigana here..." required>
                        @error('furigana')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" class="form-control" id="" name="username"
                            value="{{old('username')}}" placeholder="Enter your username here..." required>
                        @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="form-control" id="" name="email"
                            value="{{old('email')}}" placeholder="Enter your email here..." required>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="password" value=""
                            placeholder="Enter password here..." required>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword2">Confirm Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword2"
                            name="password_confirmation" value="" placeholder="Enter password again here..." required>
                        @error('password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="agree" required>
                        <label class="form-check-label" for="exampleCheck1">I agree the <a href="">terms and
                                conditions</a></label>
                        <br>
                        @error('agree')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block float-right">Sign up</button>

                    <div class="sign-up">
                        Already have an account? <a href="{{route('login')}}">Log in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

@endsection
