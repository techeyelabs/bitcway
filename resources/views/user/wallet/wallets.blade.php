@extends('user.layouts.main')

@section('custom_css')

    <style>
        .formclas{
            padding-top: 0px;
            margin-top: 0px;
        }
        .modalbg{
            background-color: #102331;
        }
        .
    </style>
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
        {{--Trade Wallet Start--}}
        <div class="card mt-3">
            <div class="card-body">
                <div class="text-center"><h4>Trade Wallet</h4></div>
                <ul class="list-group col-md-6 offset-md-3">
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col" id="MyCoinCurrencyName" style="text-align: left; color:white;">Currency</p>
                        <p class="col" id="CoinpriceIntoMycoin" style="text-align: right;color:white;">Current Price</p>
                        <p class="col" id="MyTotalCoinAmount" style="text-align: right;color:white;">Balance</p>
                    </li>
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
        {{--Trade Wallet End--}}

        {{--Derivative Wallet Start--}}
        <div class="card mt-3">
            <div class="card-body">
                <div class="text-center"><h4>Derivative Wallet</h4></div>
                <div class="col-md-6 offset-md-3 mb-2">
                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#derivativeModal" style="margin-left: -13px;width: 100px;" onclick="setFlag(1)">Deposit</button>
                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#derivativeModal"  style="width: 100px;" onclick="setFlag(2)">Withdraw</button>
                </div>

                <ul class="list-group col-md-6 offset-md-3">
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col" id="MyCoinCurrencyName" style="text-align: left; color:white;">Currency</p>
                        <p class="col" id="derivati8vePercent" style="text-align: left; color:white;">Leverage</p>
                        <p class="col" id="derivati8vePercent" style="text-align: left; color:white;">Rate</p>
                        <p class="col" id="MyTotalCoinAmount" style="text-align: right;color:white;">Balance</p>
                        <p class="col" id="CoinpriceIntoMycoin2" style="text-align: right;color:white;">Current Price</p>

                    </li>
                    <?php
                    $ii = 0;
                    ?>
                    @foreach($transactionHistory as $index =>$item )
                        <?php
                        $ii++;
                        ?>
                        @if(($item->leverage)>0)
                            <li class="row list-group-item d-flex justify-content-between align-items-center">
                                <p class="col" id="MyCoinCurrencyName2" style="text-align: left; color:white;">{{$item->currency->name}}</p>
                                <p class="col" id="derivati8vePercent" style="text-align: left; color:white;">{{$item->leverage}}</p>
                                <p class="col" id="derivati8vePercent" style="text-align: left; color:white;">{{($item->equivalent_amount*$item->leverage)/100}}</p>
                                <p class="col" id="MyTotalCoinAmountlev" style="text-align: right;color:white;">{{$item->transactionhistory->balance}}</p>
                                <p class="col" id="CoinpriceIntoMycoin2{{$index}}" style="text-align: right;color:white;"></p>

                            </li>
                        @endif
                    @endforeach


                    <p style="display: none;" id="myCoinIndex2">{{$ii}}</p>
                </ul>
            </div>
        </div>
        {{--Derivative Wallet End--}}

        {{--Finance Wallet Start--}}
        <div class="card mt-3">
            <div class="card-body">
                <div class="text-center"><h4>Finance Wallet</h4></div>
                <ul class="list-group col-md-6 offset-md-3">
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col" id="MyCoinCurrencyName" style="text-align: left; color:white;">Lot</p>
                        <p class="col" id="CoinpriceIntoMycoin" style="text-align: left;color:white;">Value Date</p>
                        <p class="col" id="MyTotalCoinAmount" style="text-align: right;color:white;">Redemption Date</p>
                        <p class="col" id="MyTotalCoinAmount" style="text-align: right;color:white;">Expected Interest</p>
                    </li>
                    @foreach($finances as $finance)
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col" id="MyCoinCurrencyName" style="text-align: left; color:white;">{{$finance->lot_count}}</p>
                        <p class="col" id="CoinpriceIntoMycoin" style="text-align: left;color:white;">{{$finance->value_date}}</p>
                        <p class="col" id="MyTotalCoinAmount" style="text-align: right;color:white;">{{$finance->redemption_date}}</p>
                        <p class="col" id="MyTotalCoinAmount" style="text-align: right;color:white;">{{$finance->expected_interest}}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{--Finance Wallet End--}}
    </div>
    <!-- Modal -->
    <div class="modal fade" id="derivativeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " >
            <div class="modal-content border-light" >
                <div class="modal-header modalbg">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #ffffff;"></button>
                </div>
                <form class="formclas" action = "{{route('derivativedeposit')}}" method = "post" >
                    @csrf
                    <div class="modal-body modalbg" >
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label" style="color: #ffffff; padding-top: 0px;">Input Amount:</label>
                            <input type="text" class="form-control" name="derivativeamount" id="derivative-name" onkeyup="manage(this)">
                            <input type="hidden" name="flag" id="flag" value="">
                            <input type="hidden" name="amount" id="amount" value="{{Auth::user()->balance}}">
                            <input type="hidden" name="amount" id="derivativeanount" value="{{Auth::user()->derivative}}">
                           
                        </div>
                    </div>
                    <div class="modal-footer modalbg" >
                        <button class="btn btn-primary" id="btSubmit" disabled >Submit</button>
                    </div>
                </form>
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
            let indexNumber2 = $('#myCoinIndex2').html();
            for(let i = 0;  i < indexNumber; i++){
                let currencyName = $('#MyCoinCurrencyName'+i).html();
                let currencyAmount = $('#MyTotalCoinAmount'+i).html();


                let full_data = trackers.trackers;
                full_data.forEach(async function (item){
                    if (item[0] === 't'+currencyName+'USD' ){
                        $('#CoinpriceIntoMycoin'+i).html((currencyAmount* item[1] ).toFixed(2));
                        $('#CoinpriceIntoMycoin2'+i).html((currencyAmount* item[1] ).toFixed(2));
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
    <script>
        function setFlag(type){

            $('#flag').val(type);
        }
    </script>
    <script>
	function manage(amount) {
        var bt = document.getElementById('btSubmit');
        var available_balance_deposit = document.getElementById('amount').value;
        var available_balance_withdraw = document.getElementById('derivativeanount').value;
        var flag = document.getElementById('flag').value;
        console.log(flag);
        if(flag == 1){
            if (amount.value !== '' && parseFloat(amount.value) <= parseFloat(available_balance_deposit)) {
            bt.disabled = false;
        }
        else {
            bt.disabled = true;
        }
        } else{
            if (amount.value !== '' && parseFloat(amount.value) <= parseFloat(available_balance_withdraw)) {
                bt.disabled = false;
            }
            else {
                bt.disabled = true;
            }
        }
    }    
</script>

@endsection
