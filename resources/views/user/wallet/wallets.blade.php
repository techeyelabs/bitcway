@extends('user.layouts.main')
@section('custom_css')
    <style>
        .formclas {
            padding-top: 0px;
            margin-top: 0px;
        }

        .modalbg {
            background-color: #102331;
        }

        .txtWhitecolor {
            color: #D3D6D8;
        }

        .txtHeadingColor {
            color: yellow;
        }
    </style>
@endsection

@section('content')
    <div id="wrap" class="deposit">
        <h3 class="txtHeadingColor">Assets</h3>
        <hr>

        {{--Margin Balance Start--}}
        <div class="card mt-3 " style="width: 400px">
            <div class="card-body">
                <div class="" style="margin: auto">
                    <div class="container-fluid" >
                        <div class="row  text-left " style="margin-left: -15px;">
                           <abbr title="Your Total Margin Balance"  class="txtHeadingColor text-left initialism">Total Margin Balance</abbr><br>
                            <h4 class="txtHeadingColor text-left mb-4" id="totalMArginBalanceId">00000000.00 <span style="font-size: 10px">USD</span></h4>

                            <abbr title="Your Total Wallet Balance"  class="txtHeadingColor text-left initialism">Total Wallet Balance</abbr><br>
                            <h4 class="txtHeadingColor text-left" style="font-size: 18px">{{$userBalance->balance}}<span style="font-size: 10px">USD</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--Margin Balance End--}}


        {{--Trade Wallet New Start--}}
        <div class="card mt-3 ">
            <div class="card-body">
                <div class="" style="margin: auto;">
                    <div class="container-fluid" >

                        <div class="row  text-left mb-3" style="margin-left: -15px;">
                            <abbr title="Trade Wallet"  class="txtHeadingColor text-left initialism">Trade Wallet</abbr><br>
                            <h4 class="txtHeadingColor text-left mb-2" id="totalAmount">00000000.00 <span style="font-size: 10px">USD</span></h4>
                        </div>
                    </div>
                </div>
                <div style="margin: auto; overflow-x: auto">
                    <ul class="container-fluid" style=" min-width: 500px;">
                        <li class="row list-group-item d-flex justify-content-between align-items-center ">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">Symbol</p>
                            <p class="col txtWhitecolor" id="" style="text-align: left;">Size</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount" style="text-align: center;">Entry Price</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin" style="text-align: center;">Mark Price</p>
                            <p class="col txtWhitecolor" id="unrealizedpnl" style="text-align: right;">Unrealized PNL</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">Action</p>
                        </li>
                        <?php
                        $i = 0;
                        foreach($wallets as $index =>$item ){
                        $i++;
                        ?>
                        <li class="row list-group-item d-flex justify-content-between align-items-center ">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName{{$index}}" style="text-align: left;">{{$item->currency->name}}</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinSize{{$index}}" style="text-align: left;">{!! number_format((double)($item->balance),5)!!}</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount{{$index}}" style="text-align: center;">{!! number_format((double)($item->equivalent_trade_amount),5)!!}</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin{{$index}}" style="text-align: center;">00.000000</p>
                            <p class="col " id="unrealizedpnl{{$index}}" style="text-align: right;">00.000000</p>
                            <p class="col txtHeadingColor" id="assetTradeSell{{$index}}" style="text-align: right; cursor: pointer">Trade</p>
                        </li>
                        <?php
                        }
                        ?>
                        <p style="display: none;" id="myCoinIndex">{{$i}}</p>
                    </ul>
                </div>
            </div>
        </div>
        {{--Trade Wallet New End--}}

        {{--Derivative Wallet Start--}}
        <div class="card mt-3">
            <div class="card-body">
                <div class="mb-4" style="margin: auto">
                    <div class="container-fluid">
                        <div class="text-left">
                            <abbr title="Derivative Wallet"  class="txtHeadingColor text-left initialism">Derivative Wallet</abbr><br>
                            <h4 class="txtHeadingColor text-left mb-3" id="totalDerivativeAmount">00000000.00 <span style="font-size: 10px">USD</span></h4>
                        </div>
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                                data-bs-target="#derivativeModal" style="width: 100px;"
                                onclick="setFlag(1)">Deposit
                        </button>
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                data-bs-target="#derivativeModal" style="width: 100px;" onclick="setFlag(2)">Withdraw
                        </button>
                    </div>
                </div>
                <div style="margin: auto; overflow-x: auto">
                    <ul class="container-fluid" style=" min-width: 600px;">
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">Symbol</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount" style="text-align: left; ">Size</p>
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">Entry Price</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin2" style="text-align: center;">Mark Price</p>
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: right;">Unrealized PNL</p>
                            <p class="col txtWhitecolor" id="derivati8vePercent" style="text-align: right;">Leverage</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">Action</p>
                        </li>
                        <?php
                        $j = 0;
                        foreach($transactionHistory as $index =>$item ){
                        if(($item->leverage) >= 1 ){
                        $j++;
                        ?>
                        {{--{!! number_format((double)($item->equivalent_amount / $item->leverage),5) !!}--}}
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName2{{$j}}" style="text-align: left;">{{$item->currencyName->name}}</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount2{{$j}}" style="text-align: left;">{!! number_format((double)($item->amount), 5) !!}</p>
                            <p class="col txtWhitecolor" id="derivativeEntryPrice{{$j}}" style="text-align: left;">{{($item->equivalent_amount)}}</p>
                            <p class="col txtWhitecolor" id="CoinpriceintoMycoin2{{$j}}" style="text-align: center;">00.000000</p>
                            <p class="col " id="derivativeUnrealizedPrice{{$j}}" style="text-align: right;color:white;">00.000000</p>
                            <p class="col txtWhitecolor" id="derivativePercent{{$j}}" style="text-align: right; color:white;">{{$item->leverage}}</p>
                            <p class="col txtHeadingColor" id="assetDerivativeSell{{$j}}" style="text-align: right;cursor: pointer">Trade</p>
                        </li>
                        <?php
                        }
                        }

                        ?>
                        <p style="display: none;" id="myCoinIndex2">{{$j}}</p>
                    </ul>
                </div>
            </div>
        </div>
        {{--Derivative Wallet End--}}

        {{--Finance Wallet Start--}}
        <div class="card mt-3">
            <div class="card-body">
                <div class="" style="margin: auto">
                    <div class="container-fluid" >
                        <div class="text-left mb-3">
                            <abbr title="Finance Wallet"  class="txtHeadingColor text-left initialism">Finance Wallet</abbr><br>
                            <h4 class="txtHeadingColor text-left mb-3" id="totalFinanceAmount">00000000.00 <span style="font-size: 10px">USD</span></h4>
                        </div>
                    </div>
                </div>
                <div style="margin: auto; overflow-x: auto">
                    <ul class="container-fluid" style=" min-width: 400px;">
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="" style="text-align: left; ">Symbol</p>
                            <p class="col txtWhitecolor" id="" style="text-align: left; ">Lot</p>
                            <p class="col txtWhitecolor" id="" style="text-align: center;">Value Date</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">Redemption Date</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">Expected Interest</p>
                        </li>
                        <?php
                        $k = 0;
                        foreach($finances as $index=>$finance){
                        $k++;
                        ?>
                            <li class="row list-group-item d-flex justify-content-between align-items-center">
                                <p class="col txtWhitecolor" id="currencyName{{$k}}" style="text-align: left;">{{$finance->currency->name}}</p>
                                <p class="col txtWhitecolor" id="lot{{$k}}" style="text-align: left;">{{$finance->lot_count}}</p>
                                <p class="col txtWhitecolor" id="valueDate{{$k}}" style="text-align: center;">{{date('d/m/Y', strtotime($finance->value_date))}}</p>
                                <p class="col txtWhitecolor" id="redeamDate{{$k}}" style="text-align: right;">{{date('d/m/Y', strtotime($finance->redemption_date))}}</p>
                                <p class="col txtWhitecolor" id="expectedInterest{{$k}}" style="text-align: right;">{{$finance->expected_interest}}</p>
                                <p class="d-none" id="lotSize{{$k}}" style="text-align: left;">{{$finance->lot_size}}</p>
                                <p class="d-none" id="coinWithInterest{{$k}}" style="text-align: left;"></p>
                            </li>
                        <?php
                            }
                        ?>
                        <p style="display: none;" id="myCoinIndex3">{{$k}}</p>
                    </ul>
                </div>
        </div>
        {{--Finance Wallet End--}}
    </div>
    <!-- Modal -->
    <div class="modal fade" id="derivativeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content border-light">
                <div class="modal-header modalbg">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="background-color: #ffffff;"></button>
                </div>
                <form class="formclas" action="{{route('derivativedeposit')}}" method="post">
                    @csrf
                    <div class="modal-body modalbg">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label"
                                   style="color: #ffffff; padding-top: 0px;">Input Amount:</label>
                            <input type="text" class="form-control" name="derivativeamount" id="derivative-name"
                                   onkeyup="manage(this)">
                            <input type="hidden" name="flag" id="flag" value="">
                            <input type="hidden" name="amount" id="amount" value="{{Auth::user()->balance}}">
                            <input type="hidden" name="amount" id="derivativeanount"
                                   value="{{Auth::user()->derivative}}">
                        </div>
                    </div>
                    <div class="modal-footer modalbg">
                        <input type="submit" class="btn btn-outline-warning" id="btSubmit" disabled value="Submit"
                               onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const socket = io('http://192.144.82.234:3000/');
        let loaded = false;
        socket.on('trackers', (trackers) => {
            // console.log(trackers);
            // Home.trackers = trackers.trackers;
            let totalValue = 0;
            let totalDerivativeValue = 0
            let indexNumber = $('#myCoinIndex').html();
            for (let i = 0; i < indexNumber; i++) {
                let currencyName = $('#MyCoinCurrencyName' + i).html();
                let currencyAmount = $('#MyTotalCoinAmount' + i).html();
                let tradeCurrencySize = $('#MyTotalCoinSize' + i).html();
                let tradeMarkPrice = $('#CoinpriceIntoMycoin' + i).html();

                // console.log("Name:",currencyName," Size:", tradeCurrencySize, " MarkPrice:", tradeMarkPrice);

                let full_data = trackers.trackers;
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + currencyName + 'USD') {
                       $('#CoinpriceIntoMycoin' + i).html((tradeCurrencySize * item[1]));
                    }
                });
            }

            for (let i = 0; i < indexNumber; i++) {
                totalValue += parseFloat($('#CoinpriceIntoMycoin' + i).text());
                $('#totalAmount').html((totalValue).toFixed(2));
            }

            for (let i = 0; i < indexNumber; i++) {
               var tradeUnrealizedpnlid = document.getElementById('unrealizedpnl'+ i);
               let tradeEntryPrice = $('#MyTotalCoinAmount' + i).html();
               let tradeMarkPrice = $('#CoinpriceIntoMycoin' + i).html();
               let tradeUnrealizedpnl = tradeMarkPrice-tradeEntryPrice;
               // console.log(tradeUnrealizedpnl);

                if (tradeUnrealizedpnl < 0){
                    $('#unrealizedpnl'+ i ).html((tradeUnrealizedpnl).toFixed(6));
                    tradeUnrealizedpnlid.style.color = 'red'
                }else{
                    $('#unrealizedpnl'+ i ).html((tradeUnrealizedpnl).toFixed(6));
                    tradeUnrealizedpnlid.style.color = 'yellow'
                }
            }
            for (let i = 0; i < indexNumber; i++) {
                let currencyName = $('#MyCoinCurrencyName' + i).html();
                let currencyAmount = $('#MyTotalCoinAmount' + i).html();
                let tradeCurrencySize = $('#MyTotalCoinSize' + i).html();
                let tradeMarkPrice = $('#CoinpriceIntoMycoin' + i).html();

                $( "#assetTradeSell" + i ).click(function() {
                    axios.post('{{route("user-trade-sell")}}',{
                        currency: currencyName,
                        sellAmount: tradeCurrencySize,
                        calcSellAmount: tradeMarkPrice
                    })
                        .then(function (response) {
                            if(response.data.status){
                                toastr.success('Sell successfull');
                                window.location.href = '{{route("user-wallets")}}';
                                return false;
                            }
                            // toastr.error('Error occured !!');
                        })
                        .catch(function (error) {
                            toastr.error('Error occured !!');
                        });
                });

                // console.log("Name:",currencyName," Size:", tradeCurrencySize, " MarkPrice:", tradeMarkPrice);
            }

            let indexNumber2 = $('#myCoinIndex2').html();
            for (let j = 1; j <= indexNumber2; j++) {
                let currencyName = $('#MyCoinCurrencyName2' + j).html();
                let currencyAmount = $('#MyTotalCoinAmount2' + j).html();

                let full_data = trackers.trackers;
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + currencyName + 'USD') {
                        $('#CoinpriceintoMycoin2' + j).html((currencyAmount * item[1]).toFixed(2));
                    }
                });
            }

            for (let j = 1; j <= indexNumber2; j++) {
                    totalDerivativeValue += parseFloat($('#CoinpriceintoMycoin2' + j).text());
                    $('#totalDerivativeAmount').html((totalDerivativeValue).toFixed(4));
                }

            for (let j = 1; j <= indexNumber2; j++) {
                var derivativeUnrealizedpnlid = document.getElementById('derivativeUnrealizedPrice'+ j);
                let derivativeEntryPrice = $('#derivativeEntryPrice' + j).html();
                let derivativeMarkPrice = $('#CoinpriceintoMycoin2' + j).html();
                let derivativeUnrealizedpnl = derivativeMarkPrice-derivativeEntryPrice;
                if (derivativeUnrealizedpnl < 0){
                    $('#derivativeUnrealizedPrice'+ j ).html((derivativeUnrealizedpnl).toFixed(6));
                    derivativeUnrealizedpnlid.style.color = 'red'
                }else{
                    $('#derivativeUnrealizedPrice'+ j ).html((derivativeUnrealizedpnl).toFixed(6));
                    derivativeUnrealizedpnlid.style.color = 'yellow'
                }
            }
            for (let j = 1; j <= indexNumber2; j++) {
                let derivativeCurrencyName = $('#MyCoinCurrencyName2' + j).html();
                let derivativeCurrencySize = $('#MyTotalCoinAmount2' + j).html();
                let derivativeMarkPrice = $('#CoinpriceintoMycoin2' + j).html();

                $( "#assetDerivativeSell" + j ).click(function() {
                    // console.log("in");
                    axios.post('{{route("user-trade-sell")}}',{
                        currency: derivativeCurrencyName,
                        sellAmount: derivativeCurrencySize,
                        calcSellAmount: derivativeMarkPrice,
                        derivativeType: '0'
                    })
                        .then(function (response) {
                            if(response.data.status){
                                toastr.success('Sell successfull');
                                window.location.href = '{{route("user-wallets")}}';
                                return false;
                            }
                            // toastr.error('Error occured !!');
                        })
                        .catch(function (error) {
                            toastr.error('Error occured !!');
                        });
                });

                // console.log("Name:",derivativeCurrencyName," Size:", derivativeCurrencySize, " MarkPrice:", derivativeMarkPrice);
            }

            let totalFinanceValue = 0.00;
            let indexNumber3 = $('#myCoinIndex3').html();
        
            for (let k = 1; k <= indexNumber3; k++) {
                let currencyName = $('#currencyName' + k).html();
                let coinLot = parseFloat($('#lot' + k).html());
                let coinLotSize = parseFloat($('#lotSize'+ k).html());
                let coinExpectedInterest = parseFloat($('#expectedInterest' + k).html());
                let totalCoinValue  = ((coinLot*coinLotSize) + coinExpectedInterest);

                console.log(coinLot, coinLotSize, coinExpectedInterest);
               
                // let totalFinanceWallet = (coinLot*coinLotSize) + coinExpectedInterest;
               
                let full_data = trackers.trackers;
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + currencyName + 'USD') {
                       $('#coinWithInterest' + k).html((totalCoinValue * item[1]).toFixed(4));
                    }
                });
                for (let a = 0; a < indexNumber3; a++) {
                    console.log(parseFloat($('#coinWithInterest' + a).text()));
                    totalFinanceValue += parseFloat($('#coinWithInterest' + a).text());
                    $('#totalFinanceAmount').html((totalFinanceValue).toFixed(4));
                }
              
            }
            if (loaded == false) {
                setInterval(function () {
                }, 1000);
                loaded = true;
            }

            let tradeMargin = parseFloat($('#').html());
            let derivativeMargin = parseFloat($('#').html()); 
            let financeMargin = parseFloat($('#').html());
            totalMargin = tradeMargin + derivativeMargin + financeMargin;
            $('#totalMArginBalanceId').html(totalMargin);
        })
    </script>

    <script>
        function setFlag(type) {

            $('#flag').val(type);
        }
    </script>
    <script>
        function manage(amount) {
            var bt = document.getElementById('btSubmit');
            var available_balance_deposit = document.getElementById('amount').value;
            var available_balance_withdraw = document.getElementById('derivativeanount').value;
            var flag = document.getElementById('flag').value;
            if (flag == 1) {
                if (amount.value !== '' && parseFloat(amount.value) <= parseFloat(available_balance_deposit)) {
                    bt.disabled = false;
                } else {
                    bt.disabled = true;
                }
            } else {
                if (amount.value !== '' && parseFloat(amount.value) <= parseFloat(available_balance_withdraw)) {
                    bt.disabled = false;
                } else {
                    bt.disabled = true;
                }
            }
        }
    </script>
@endsection
