@extends('admin.layouts.main')

@section('custom_css')

@endsection

@section('content')

    <div id="wrap">
        <h3><u>Withdraw Message</u></h3>
        <hr>

        {{--Withdraw Message form--}}
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        @include('includes.message')
                        <form action="{{route('admin-withdraw-message-action', app()->getLocale())}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label  class="txtWhitecolor">Select User</label>
                                <select id="select_user" class="form-control form-select" name="user_id"
                                        aria-label="Default select example">
                                    <option value="0">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->email}}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger"></small>
                            </div>

                            <div class="form-group">
                                <label for="" class="txtWhitecolor">Message</label>
                                <input type="text" id="withdraw_message" class="form-control" name="withdraw_message" placeholder="" value="" required>
                                <small class="text-danger"></small>
                            </div>
                            <hr>
                            <div class=" gap-2 col-6 mx-auto text-center">
                                <button type="submit" id="Submit" class="btn btn-primary  text-center"
                                        style="width: 80px; color: white;">Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{--Withdraw Message form end--}}

        <h2>Withdraw Message History</h2>
        <hr>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mt-5 text-center">
                    <table class="table" id="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>



@endsection

@section('custom_js')
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script> --}}
    <script type="text/javascript">
        $(function () {
            window.dataTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                order: [[3, "desc"]],
                ajax: '{!! route("admin-withdraw-message-history", app()->getLocale()) !!}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'withdraw_message', name: 'message'},
                    {data: 'action', name: 'action'},
                ]
            });
        });
    </script>
@endsection
