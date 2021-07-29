@extends('user.layouts.main')

@section('custom_css')
@endsection
@section('content')

<div id="wrap">
    <h3 class="txtHeadingColor pageTitle">{{__('title23')}}</h3>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @include('includes.message')
{{--                    <form action="{{route('user-update-profile-action', app()->getLocale())}}" method="POST">--}}
                        @csrf
                        <div class="form-group">
                            <label for="" class="txtWhitecolor">{{__('label4')}}</label>
                            <input type="text" class="form-control" aria-describedby="" name="first_name" id="first_name"
                                value="{{Auth::user()->first_name}}" placeholder="{{__('placeholder7')}}" required>
                            @error('first_name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="" class="txtWhitecolor">{{__('label5')}}</label>
                            <input type="text" class="form-control" aria-describedby="" name="last_name" id="last_name"
                                value="{{Auth::user()->last_name}}" placeholder="{{__('placeholder8')}}" required>
                            @error('last_name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="" class="txtWhitecolor">{{__('label6')}}</label>--}}
{{--                            <input type="text" class="form-control" aria-describedby="" name="furigana"--}}
{{--                                value="{{Auth::user()->furigana}}" placeholder="{{__('placeholder9')}}" required>--}}
{{--                            @error('furigana')--}}
{{--                            <small class="text-danger">{{ $message }}</small>--}}
{{--                            @enderror--}}

{{--                        </div>--}}

                        <div class="form-group">
                            <label for="" class="txtWhitecolor">{{__('label7')}}</label>
                            <input type="text" class="form-control" aria-describedby="" name="username" id="username"
                                value="{{Auth::user()->username}}" placeholder="{{__('placeholder10')}}" readonly>
                            @error('username')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="form-group">
                            <label for="" class="txtWhitecolor">{{__('label8')}}</label>
                            <input type="text" class="form-control" aria-describedby="" name="phone" id="phone"
                                value="{{Auth::user()->phone}}" placeholder="Enter phone no here...">
                            @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="form-group">
                            <label for="" class="txtWhitecolor">{{__('label9')}}</label>
                            <input type="email" class="form-control" aria-describedby="" name="email" id="email"
                                value="{{Auth::user()->email}}" placeholder="{{__('placeholder11')}}" readonly>
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" onclick="updateProfile()" class="btn btn-outline-warning float-end">{{__('button13')}}</button>
                        <a href="{{route('front-home', app()->getLocale())}}" class="btn btn-outline-info float-end me-2">{{__('button12')}}</a>
{{--                    </form>--}}
                </div>
            </div>

        </div>
    </div>
</div>


@endsection

@section('custom_js')
    <script>
        function updateProfile() {
            let first_name = $('#first_name').val();
            let last_name = $('#last_name').val();
            let username = $('#username').val();
            let phone = $('#phone').val();
            let email = $('#email').val();
            axios.post('{{route("user-update-profile-action", app()->getLocale())}}', {
                first_name: first_name,
                last_name:last_name,
                username:username,
                phone:phone,
                email:email
            })
                .then(function (response) {
                    toastr.success('Profile Updated successfull');
                    window.location.reload();
                })
        }
    </script>
@endsection
