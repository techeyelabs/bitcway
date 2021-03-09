@extends('user.layouts.main')

@section('custom_css')

@endsection
@section('content')

<div id="wrap">
    <h2>Wallet</h2>
    <hr>
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h4>BALANCE: {{Auth::user()->balance}} USDT</h4>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="text-center">
                <div class="row">
                    <div class="col d-grid"><a href="{{route('user-deposit')}}" class="btn btn-block btn-lg btn-outline-info">Deposit</a></div>
                    <div class="col d-grid"><a href="{{route('user-withdraw')}}" class="btn btn-block btn-lg btn-outline-warning">Withdraw</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="text-center">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="deposit-history-tab" data-bs-toggle="tab" href="#deposit-history" role="tab"
                            aria-controls="deposit-history" aria-selected="true">Deposit History</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="withdraw-histoty-tab" data-bs-toggle="tab" href="#withdraw-histoty" role="tab"
                            aria-controls="withdraw-histoty" aria-selected="false">Withdraw Histoty</a>
                    </li>
                    
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active row" id="deposit-history" role="tabpanel" aria-labelledby="home-tab">
                        <table class="table col-md-6">
                            <thead>
                                <th>Date</th>
                                <th>Amount (BTC)</th>
                                <th>Amount (USDT)</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                <?php foreach($deposit as $item){?>
                                <tr>
                                    <td>{{date('d/m/Y H:i', strtotime($item->created_at))}}</td>
                                    <td>{{$item->amount}}</td>
                                    <td>{{$item->equivalent_amount}}</td>
                                    <td>
                                        <?php if($item->status == 1) echo 'Approved';elseif($item->status == 2) echo 'Declined'; else echo 'Pending';?>
                                    </td>
                                </tr>
                                <?php }?>
                               
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="withdraw-histoty" role="tabpanel" aria-labelledby="withdraw-histoty-tab">
                        <table class="table">
                            <thead>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                <?php foreach($withdraw as $item){?>
                                <tr>
                                    <td>{{date('d/m/Y H:i', strtotime($item->created_at))}}</td>
                                    <td>{{$item->amount}}</td>
                                    <td>
                                        <?php if($item->status == 1) echo 'Approved';elseif($item->status == 2) echo 'Declined'; else echo 'Pending';?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

@endsection
