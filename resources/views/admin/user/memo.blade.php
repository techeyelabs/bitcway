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
                        <div class="form-group">
                            <label for="">Memo</label>
                            <label class="d-none" id="userId" for="">{{$user->id}}</label>
                            <input type="text" id="memoText" class="form-control" aria-describedby="" name="memo"
                                value="@php if (isset($user->memo)){echo $user->memo;}else{ echo "";} @endphp" placeholder="Enter memo here..." required>
                            @error('memo')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" onclick="saveMemo()" class="btn btn-outline-primary float-end">Update</button>
                        <a href="{{route('admin-user-list', app()->getLocale())}}" class="btn btn-outline-danger float-end me-2">Cancel</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('custom_js')
    <script>
        function saveMemo() {
            let memoText = $('#memoText').val();
            axios.post('{{route("admin-user-memo-action", [app()->getLocale(), $user->id])}}', {
                memo: memoText,
                id:"{{$user->id}}"
            })
                .then(function (response) {
                    toastr.success('Updated successfull');
                    window.location.reload();
                })
        }
    </script>
@endsection
