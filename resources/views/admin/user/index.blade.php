@extends('admin.layouts.main')

@section('custom_css')

@endsection

@section('content')

<div id="wrap">
    <h2>User List</h2>
    <hr>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive mt-5 text-center">
                <table class="table" id="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Furigana</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Phone</th>
                            <th>Memo</th>
                            <th>Asset</th>
                            <th>Created</th>
                            <th style="min-width: 170px;">Action</th>
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
    $(function() {
        window.dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 7, "desc" ]],
            ajax: '{!! route("admin-user-list-data") !!}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'furigana', name: 'furigana' },
                { data: 'email', name: 'email' },
                { data: 'username', name: 'username' },
                { data: 'phone', name: 'phone' },
                // { data: 'balance', name: 'balance' },
                { data: 'memo', name: 'memo' },
                // { data: 'status', name: 'status' },
                { data: 'asset', name: 'asset' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
