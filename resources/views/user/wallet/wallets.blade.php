@extends('user.layouts.main')

@section('custom_css')
@endsection

@section('content')
<div id="wrap" class="deposit">
    <h2>Assets</h2>
    <hr>
    <div class="card">
        <div class="card-body text-center">
            <h4>Equivalent Asset Amount:  {{$total}} USDT</h4>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="text-center"><h4>Asset Breakdown</h4></div>
            

            <ul class="list-group col-md-6 offset-md-3">
                <?php foreach($wallets as $item){?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{$item->currency->name}}
                    <span class="badge bg-primary pill">{{$item->balance}}</span>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
