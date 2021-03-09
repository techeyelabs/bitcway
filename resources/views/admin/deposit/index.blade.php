@extends('admin.layouts.main')

@section('custom_css')

@endsection

@section('content')

<div id="wrap">
    <h2>Deposit List</h2>
    <hr>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive mt-5 text-center">
                <table class="table" id="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Amount (BTC)</th>
                            <th>Equivalent Amount (USDT</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th style="min-width: 150px;">Action</th>
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
            order: [[ 5, "desc" ]],
            ajax: '{!! route("admin-deposit-list-data") !!}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'amount', name: 'amount' },
                { data: 'equivalent_amount', name: 'equivalent_amount' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
