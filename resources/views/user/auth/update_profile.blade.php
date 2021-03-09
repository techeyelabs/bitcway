@extends('user.layouts.main')

@section('custom_css')
@endsection
@section('content')

<div id="wrap">
    <h2>Update Profile Information</h2>
    <hr>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @include('includes.message')

                    <form action="" method="POST">
                        @csrf



                        <div class="form-group">
                            <label for="">First Name</label>
                            <input type="text" class="form-control" aria-describedby="" name="first_name"
                                value="{{Auth::user()->first_name}}" placeholder="Enter first name here..." required>
                            @error('first_name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>


                        <div class="form-group">
                            <label for="">Last Name</label>
                            <input type="text" class="form-control" aria-describedby="" name="last_name"
                                value="{{Auth::user()->last_name}}" placeholder="Enter last name here..." required>
                            @error('last_name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="form-group">
                            <label for="">Furigana</label>
                            <input type="text" class="form-control" aria-describedby="" name="furigana"
                                value="{{Auth::user()->furigana}}" placeholder="Enter furigana here..." required>
                            @error('furigana')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" class="form-control" aria-describedby="" name="username"
                                value="{{Auth::user()->username}}" placeholder="Enter username here..." readonly>
                            @error('username')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="form-group">
                            <label for="">Phone No</label>
                            <input type="text" class="form-control" aria-describedby="" name="phone"
                                value="{{Auth::user()->phone}}" placeholder="Enter phone no here...">
                            @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" class="form-control" aria-describedby="" name="email"
                                value="{{Auth::user()->email}}" placeholder="Enter email here..." readonly>
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>



                        <button type="submit" class="btn btn-outline-primary float-end">Change</button>
                        <a href="{{route('user-dashboard')}}" class="btn btn-outline-danger float-end me-2">Cancel</a>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection

@section('custom_js')
@endsection
