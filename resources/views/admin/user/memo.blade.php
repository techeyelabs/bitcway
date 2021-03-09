@extends('admin.layouts.main')

@section('custom_css')
@endsection

@section('content')
<div id="wrap">
    <h3>User Memo</h3>
    <hr>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    @include('includes.message')

                    <form action="" method="POST">
                    @csrf


                   
                        <div class="form-group">
                            <label for="">Memo</label>
                            <input type="text" class="form-control" aria-describedby="" name="memo"
                                value="{{$user->memo}}" placeholder="Enter memo here..." required>
                            @error('memo')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                       

                        <button type="submit" class="btn btn-outline-primary float-end">Update</button>
                        <a href="{{route('admin-user-list')}}" class="btn btn-outline-danger float-end me-2">Cancel</a>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
