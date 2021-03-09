@extends('user.layouts.main')

@section('custom_css')

@endsection
@section('content')

<div id="wrap">
    <h2>Buy/Sell</h2>
    <hr>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <th>Date</th>
                    <th>Coin</th>
                    <th>Amount</th>
                    <th>Equivalent Amount (USDT)</th>
                    <th>Type</th>
                </thead>
                <tbody>
                    <?php foreach($transactions as $item){?>
                    <tr>
                        <td>{{date('d/m/Y H:i', strtotime($item->created_at))}}</td>
                        <td>{{$item->currency->name}}</td>
                        <td>{{$item->amount}}</td>
                        <td>{{$item->equivalent_amount}}</td>
                        <td>
                            {{$item->type==1?'Buy':'Sell'}}
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

@endsection
