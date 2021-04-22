@extends('admin.layouts.main')

@section('custom_css')

@endsection

@section('content')

    <div id="wrap">
        <h2>Withdraw List</h2>
        <hr>
        <div class="card mb-5">
            <div class="card-body col-md-8 text-center offset-2">
                <h4 class="txtHeadingColor">Withdraw Message Box</h4>
                <hr>
                <form action="{{route('admin-withdraw-notification')}}" method="post">
                    @csrf
                    <div class="form-group">
                <textarea type="text" class="form-control withdrawMessageArea" id="withdrawMessageArea" aria-describedby="" name="withdrawMessage" rows="5"
                           onkeypress="withdrawModifyButtonCheck()" required>{{ $notification->message }}</textarea>
                        <br>
                        <span>
                            @if($notification->display_message == 0)
                            <input type="checkbox" id="checkbox" name="checkbox" onchange="withdrawModifyButtonCheck()">
                            @elseif($notification->display_message == 1)
                            <input type="checkbox" id="checkbox" name="checkbox" checked onchange="withdrawModifyButtonCheck()">
                            @endif
                            <label for="notification"> Disable Withdraw & Send Notification</label>
                        </span>
                    </div>
                    <button href="#" class="btn btn-outline-warning text-center withdrawSubmitBtn" disabled>Submit</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive mt-5 text-center">
                    <table class="table" id="data-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>(USD)</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th style="min-width: 125px;">Action</th>
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
                order: [[3, "ASC"]],
                ajax: '{!! route("admin-withdraw-list-data") !!}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'amount', name: 'amount'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
    <script>
        function withdrawModifyButtonCheck()
        {
            if ($('textarea#withdrawMessageArea').val() != null  ||  $('input[id="checkbox"]:checked').length > 0){

                $('.withdrawSubmitBtn').attr('disabled', false);
            } else {
                console.log("2");
                $('.withdrawSubmitBtn').attr('disabled', true);
            }
        }
    </script>

@endsection
