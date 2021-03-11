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
            <div class="text-center"><h4>Trade Wallet</h4></div>
            <ul class="list-group col-md-6 offset-md-3">
                <?php
                    $i = 0;
                    foreach($wallets as $index =>$item ){
                        $i++;
                ?>

{{--<span class="badge bg-primary pill" id="MyCoinCurrencyName{{$index}}">{{$item->currency->name}}</span>--}}
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col" id="MyCoinCurrencyName{{$index}}" style="text-align: left; color:white;">{{$item->currency->name}}</p>
                        <p class="col" id="CoinpriceIntoMycoin{{$index}}" style="text-align: right;color:white;"></p>
                        <p class="col" id="MyTotalCoinAmount{{$index}}" style="text-align: right;color:white;">{{$item->balance}}</p>
                    </li>
                <?php
                    }
                ?>
                <p style="display: none;" id="myCoinIndex">{{$i}}</p>
            </ul>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="text-center"><h4>Derivative Wallet</h4></div>
            <ul class="list-group col-md-6 offset-md-3">
                <?php
                    $i = 0;
                    foreach($wallets as $index =>$item ){
                        $i++;
                ?>
{{--<span class="badge bg-primary pill" id="MyCoinCurrencyName{{$index}}">{{$item->currency->name}}</span>--}}
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col" id="MyCoinCurrencyName{{$index}}" style="text-align: left; color:white;">{{$item->currency->name}}</p>
                        <p class="col" id="derivati8vePercent" style="text-align: left; color:white;">10X</p>
                        <p class="col" id="CoinpriceIntoMycoin2{{$index}}" style="text-align: right;color:white;"></p>
                        <p class="col" id="MyTotalCoinAmount{{$index}}" style="text-align: right;color:white;">{{$item->balance}}</p>
                    </li>

                    {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                         <span class="badge bg-primary pill" id="MyCoinCurrencyName{{$index}}">{{$item->currency->name}}</span>
                        <span class="badge bg-primary pill" id="CoinpriceIntoMycoin{{$index}}"></span>
                        <span class="badge bg-primary pill" id="MyTotalCoinAmount{{$index}}">{{$item->balance}}</span>
                    </li> --}}

                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col" id="MyCoinCurrencyName{{$index}}" style="text-align: left; color:white;">{{$item->currency->name}}</p>
                        <p class="col" id="CoinpriceIntoMycoin{{$index}}" style="text-align: right;color:white;margin-right: 15%;"></p>
                        <p class="col" id="MyTotalCoinAmount{{$index}}" style="text-align: right;color:white;">{{$item->balance}}</p>                   
                   </li>

                <?php
                    }
                ?>
                <p style="display: none;" id="myCoinIndex">{{$i}}</p>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>
<script>

 const socket = io('http://bitc-way.com:3000');
 let loaded = false;
        socket.on('trackers', (trackers) => {
            console.log(trackers);
            // Home.trackers = trackers.trackers;
            let indexNumber = $('#myCoinIndex').html();
            for(let i = 0;  i < indexNumber; i++){
                let currencyName = $('#MyCoinCurrencyName'+i).html();
                let currencyAmount = $('#MyTotalCoinAmount'+i).html();

                let full_data = trackers.trackers;
                full_data.forEach(async function (item){
                    if (item[0] === 't'+currencyName+'USD' ){
                        $('#CoinpriceIntoMycoin'+i).html((currencyAmount* item[1] ).toFixed(2));
<<<<<<< HEAD
                        $('#CoinpriceIntoMycoin2'+i).html((currencyAmount* item[1] ).toFixed(2));
=======
>>>>>>> origin/dev_ashik
                    }
                });
            };
            if(loaded == false){
                setInterval(function(){
                }, 5000);
                loaded = true;
            }
        })
</script>
@endsection
