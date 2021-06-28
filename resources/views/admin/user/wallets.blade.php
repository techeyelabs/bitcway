@extends('admin.layouts.main')

@section('custom_css')
@endsection

@section('content')
{{--<div id="wrap" class="deposit">
    <h2>Assets</h2>
    <hr>
    <div class="card">
        <div class="card-body text-center">
            <h4>Equivalent Asset Amount:  {{$total}} USD</h4>
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
</div>--}}
<div id="wrap" class="deposit">
    <h3 class="txtHeadingColor pageTitle">{{__('menuoption7')}}</h3>
    <hr>

    {{--Margin Balance Start--}}
    <div class="card mt-3 " style="width: 400px">
        <div class="card-body">
            <div class="" style="margin: auto">
                <div class="row container-fluid" >
                    <div class="  text-left " style="margin-left: -15px;">
                        <abbr title="{{__('your_total_margin_balance')}}"  class="txtWhitecolor text-left initialism">{{__('total_margin_balance')}}</abbr><br>
                        <h4 class="txtWhitecolor text-left mb-4" ><span id="totalMArginBalanceId">00.00</span><span style="font-size: 10px">USD</span></h4>
                        <div class="col-md-12 row">
                            <div class="col-md-8">
                                <abbr title="{{__('your_total_wallet_balance')}}"  class="txtWhitecolor text-left initialism">{{__('total_wallet_balance')}}</abbr><br>
                                <h4 class="txtWhitecolor text-left" >{{$userBalance->balance}}<span style="font-size: 10px"> USD</span></h4>
                            </div>
                        </div>
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
                        <abbr title="{{__('Btrade_wallet')}}"  class="txtWhitecolor text-left initialism">{{__('trade_wallet')}}</abbr><br>
                        <h4 class="txtWhitecolor text-left mb-2" id="totalAmount">00000000.00 <span style="font-size: 10px">USD</span></h4>
                    </div>
                </div>
            </div>
            <div style="margin: auto; overflow-x: auto">
                <ul class="container-fluid" style=" min-width: 600px;">
                    <li class="row list-group-item d-flex justify-content-between align-items-center ">
                        <p class="col txtWhitecolor" id="" style="text-align: left;">{{__('column1')}}</p>
                        <p class="col txtWhitecolor" id="" style="text-align: left;">{{__('size')}}</p>
                        <p class="col txtWhitecolor" id="" style="text-align: center;">{{__('entryprice')}}</p>
                        <p class="col txtWhitecolor" id="" style="text-align: center;">{{__('markprice')}}</p>
                        <p class="col txtWhitecolor" id="" style="text-align: right;">Unrealized PNL</p>
                        <p class="col txtWhitecolor d-none" id="" style="text-align: right;">{{__('action')}}</p>
                    </li>
                    @php
                        $i = 0;
                        foreach($wallets as $index =>$item ){
                    @endphp
                    <li class="row list-group-item d-flex justify-content-between align-items-center ">
                        <p class="col txtWhitecolor" id="MyCoinCurrencyName{{$i}}" style="text-align: left;">{{($item->currency->name == 'ADA') ? 'MAB' : $item->currency->name}}</p>
                        <p class="col txtWhitecolor" id="MyTotalCoinSize{{$i}}" style="text-align: left;">{!! number_format((double)($item->balance),5)!!}</p>
                        <p class="col txtWhitecolor" id="MyTotalCoinAmount{{$i}}" style="text-align: center;">{!! number_format((double)($item->currency_price),5)!!}</p>
                        <p class="col txtWhitecolor" id="CoinpriceIntoMycoin{{$i}}" style="text-align: center;">00.000000</p>
                        <p class="col " id="unrealizedpnl{{$i}}" style="text-align: right;">00.000000</p>
                        <p class="col txtHeadingColor d-none"  style="text-align: right;"><span id="assetTradeSell{{$i}}" style="cursor: pointer;">{{__('title9')}}</span></p>
                    </li>
                    @php
                        $i++; }
                    @endphp
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
                        <abbr title="Derivative Wallet"  class="txtWhitecolor text-left initialism">{{__('title20')}}</abbr><br>
                        <span class="d-none" id="derivativeBalance">{{$userDerivativeBalance->derivative}}</span>
                        <h4 class="txtWhitecolor text-left mb-3" id="totalDerivativeAmount">{{$userDerivativeBalance->derivative}}<span style="font-size: 10px">USD</span></h4>
                    </div>
                </div>
            </div>
            <div style="margin: auto; overflow-x: auto">
                <ul class="container-fluid" style=" min-width: 600px;">
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">{{__('column1')}}</p>
                        <p class="col txtWhitecolor" id="MyTotalCoinAmount" style="text-align: left; ">{{__('size')}}</p>
                        <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">{{__('entryprice')}}</p>
                        <p class="col txtWhitecolor" id="CoinpriceIntoMycoin2" style="text-align: center;">{{__('markprice')}}</p>
                        <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: right;">Unrealized PNL</p>
                        <p class="col txtWhitecolor" id="derivati8vePercent" style="text-align: right;">{{__('col16')}}</p>
                        <p class="col txtWhitecolor d-none" id="" style="text-align: right;">Action</p>
                    </li>
                    @php
                        $j = 0;
                        foreach($transactionHistory as $index =>$item ){
                        if(($item->leverage) >= 1 ){
                        $j++;
                    @endphp
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col txtWhitecolor" id="MyCoinCurrencyName2{{$j}}" style="text-align: left;">{{($item->currencyName->name == 'ADA') ? 'MAB' : $item->currencyName->name}}</p>
                        <p class="col txtWhitecolor" id="MyTotalCoinAmount2{{$j}}" style="text-align: left;">{!! number_format((double)($item->amount), 5) !!}</p>
                        <p class="col txtWhitecolor d-none previous" id="derivativeEntryPrice{{$j}}" style="text-align: left;">{{($item->equivalent_amount)}}</p>
                        <p class="col txtWhitecolor" id="derivativeCurrencyEntryPrice{{$j}}" style="text-align: left;">{{($item->derivative_currency_price)}}</p>
                        <p class="d-none" id="derivativeLoan{{$j}}" >{{($item->derivativeLoan)}}</p>
                        <p class="col txtWhitecolor d-none previous" id="CoinpriceintoMycoin2{{$j}}" style="text-align: center;">00.000000</p>
                        <p class="col txtWhitecolor" id="CoinpriceintoMyCurrency{{$j}}" style="text-align: center;">00.000000</p>
                        <p class="d-none" id="derivativeAmountWithPNL{{$j}}">00.000000</p>
                        <p class="col " id="derivativeUnrealizedPrice{{$j}}" style="text-align: right;color:white;">00.000000</p>
                        <p class="col d-none previous" id="derivativeUnrealizedCurrencyPrice{{$j}}" style="text-align: right;color:white;">00.000000</p>
                        <p class="col txtWhitecolor" id="derivativePercent{{$j}}" style="text-align: right; color:white;">{{$item->leverage}}</p>
                        <p class="col txtHeadingColor d-none"  style="text-align: right;"><span id="assetDerivativeSell{{$j}}" style="cursor: pointer;">{{__('title9')}}</span></p>
                    </li>
                    @php
                        }
                           }
                    @endphp
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
                        <abbr title="Finance Wallet"  class="txtWhitecolor text-left initialism">{{__('title21')}}</abbr><br>
                        <h4 class="txtWhitecolor text-left mb-3" id="totalFinanceAmount">00000000.00 <span style="font-size: 10px">USD</span></h4>
                    </div>
                </div>
            </div>
            <div style="margin: auto; overflow-x: auto">
                <ul class="container-fluid" style=" min-width: 400px;">
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col txtWhitecolor" id="" style="text-align: left; ">{{__('column1')}}</p>
                        <p class="col txtWhitecolor" id="" style="text-align: left; ">{{__('col4')}}</p>
                        <p class="col txtWhitecolor" id="" style="text-align: center;">{{__('col5')}} </p>
                        <p class="col txtWhitecolor" id="" style="text-align: right;">{{__('col6')}} </p>
                        <p class="col txtWhitecolor" id="" style="text-align: right;">{{__('col7')}}</p>
                    </li>
                   @php
                       $k = 0;
                       foreach($finances as $index=>$finance){
                       $k++;
                   @endphp
                    <li class="row list-group-item d-flex justify-content-between align-items-center">
                        <p class="col txtWhitecolor" id="currencyName{{$k}}" style="text-align: left;">{{$finance->currency->name}}</p>
                        <p class="col txtWhitecolor" id="lot{{$k}}" style="text-align: left;">{{$finance->lot_count}}</p>
                        <p class="col txtWhitecolor" id="valueDate{{$k}}" style="text-align: center;">{{date('d/m/Y', strtotime($finance->value_date))}}</p>
                        <p class="col txtWhitecolor" id="redeamDate{{$k}}" style="text-align: right;">{{date('d/m/Y', strtotime($finance->redemption_date))}}</p>
                        <p class="col txtWhitecolor" id="expectedInterest{{$k}}" style="text-align: right;">{{$finance->expected_interest}}</p>
                        <p class="d-none" id="lotSize{{$k}}" style="text-align: left;">{{$finance->lot_size}}</p>
                        <p class="d-none" id="coinWithInterest{{$k}}" style="text-align: left;"></p>
                    </li>
                    @php
                        }
                    @endphp
                    <p style="display: none;" id="myCoinIndex3">{{$k}}</p>
                </ul>
            </div>
        </div> 
        {{--Finance Wallet End--}}
    </div>
</div>
@endsection

@section('custom_js')
    <script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        // const socket = io('http://192.144.82.234:3000/');
        const socket = io('https://bitc-way.com:3000/');
        let loaded = false;
        socket.on('trackers', (trackers) => {
            let totalValue = 0;
            let totalDerivativeValue = 0;
            let totalFinanceValue = 0.00;
            let indexNumber = $('#myCoinIndex').html();
            for (let i = 0; i < indexNumber; i++) {
                let currencyName = $('#MyCoinCurrencyName' + i).html();
                let currencyAmount = parseFloat($('#MyTotalCoinAmount' + i).html());
                let tradeCurrencySize = parseFloat($('#MyTotalCoinSize' + i).html());
                let tradeMarkPrice = parseFloat($('#CoinpriceIntoMycoin' + i).html());
                let realcurrname = '';
                if (currencyName == 'MAB') {
                    realcurrname = 'ADA';
                } else {
                    realcurrname = currencyName;
                }

                let full_data = trackers.trackers.trackers;
                // let full_data = trackers.trackers;
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + realcurrname + 'USD') {
                        if (realcurrname == 'ADA'){
                            item[1] = item[1] * (Math.random() * ({{$current_price * 1.1}} - {{$current_price}}) + {{$current_price}}) / item[1] ;
                        }
                        parseFloat($('#CoinpriceIntoMycoin' + i).html((item[1]).toFixed(5)));
                    }
                });
            }

            for (let t = 0; t < indexNumber; t++) {
                totalValue += parseFloat($('#CoinpriceIntoMycoin' + t).text()) * parseFloat($('#MyTotalCoinSize' + t).text());
                parseFloat($('#totalAmount').html((totalValue).toFixed(5)));
            }

            for (let tupnl = 0; tupnl < indexNumber; tupnl++) {
                var tradeUnrealizedpnlid = document.getElementById('unrealizedpnl'+ tupnl);
                let tradeEntryPrice = parseFloat($('#MyTotalCoinAmount' + tupnl).text().replace(',', ''));
                let tradeMarkPrice = parseFloat($('#CoinpriceIntoMycoin' + tupnl).html());
                let tradeUnrealizedpnl = tradeMarkPrice - tradeEntryPrice;

                if (tradeUnrealizedpnl < 0){
                    parseFloat($('#unrealizedpnl'+ tupnl).html((tradeUnrealizedpnl).toFixed(5)));
                    tradeUnrealizedpnlid.style.color = '#dc3545'
                }else{
                    parseFloat($('#unrealizedpnl'+ tupnl).html((tradeUnrealizedpnl).toFixed(5)));
                    tradeUnrealizedpnlid.style.color = '#198754'
                }
            }
            let indexNumber2 = $('#myCoinIndex2').html();
            for (let j = 1; j <= indexNumber2; j++) {
                let currencyName = $('#MyCoinCurrencyName2' + j).html();
                let currencyAmount = parseFloat($('#MyTotalCoinAmount2' + j).html());

                let realcurrname2 = '';
                if (currencyName == 'MAB') {
                    realcurrname2 = 'ADA';
                } else {
                    realcurrname2 = currencyName;
                }

                let full_data = trackers.trackers.trackers;
                // let full_data = trackers.trackers;
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + realcurrname2 + 'USD') {
                        if (realcurrname2 == 'ADA'){
                            item[1] = item[1] * (Math.random() * ({{$current_price * 1.1}} - {{$current_price}}) + {{$current_price}}) / item[1] ;
                        }
                        parseFloat($('#CoinpriceintoMycoin2' + j).html((currencyAmount * item[1]).toFixed(5)));
                        parseFloat($('#CoinpriceintoMyCurrency' + j).html(( item[1]).toFixed(5)));
                    }
                });
            }
            for (let dv = 1; dv <= indexNumber2; dv++) {
                let derivativeBalance = parseFloat($('#derivativeBalance').text());
                totalDerivativeValue += (parseFloat($('#CoinpriceintoMycoin2' + dv).text())/parseFloat($('#derivativePercent' + dv).text()));
                parseFloat($('#totalDerivativeAmount').html((totalDerivativeValue + derivativeBalance).toFixed(5)));
            }
            for (let dbPNL = 1; dbPNL<=indexNumber2; dbPNL++){
                let derivativeMarkPrice = parseFloat($('#CoinpriceintoMycoin2' + dbPNL).html().replace(',',''));
                let derivativeLoan = parseFloat($('#derivativeLoan' + dbPNL).html().replace(',',''));
                derivativePNL = parseFloat(derivativeMarkPrice - derivativeLoan);
                parseFloat($('#derivativeAmountWithPNL' + dbPNL).html(derivativePNL));
            }
            for (let dupnl = 1; dupnl <= indexNumber2; dupnl++) {
                var derivativeUnrealizedpnlid = document.getElementById('derivativeUnrealizedPrice'+ dupnl);
                let derivativeEntryPrice = parseFloat($('#derivativeEntryPrice' + dupnl).html().replace(',',''));
                let derivativeMarkPrice = parseFloat($('#CoinpriceintoMycoin2' + dupnl).html());
                let derivativeCurrencyEntryPrice = parseFloat($('#derivativeCurrencyEntryPrice' + dupnl).html().replace(',',''));
                let derivativeCurrencyMarkPrice = parseFloat($('#CoinpriceintoMyCurrency' + dupnl).html());
                let derivativeUnrealizedpnl = parseFloat(derivativeCurrencyMarkPrice - derivativeCurrencyEntryPrice);
                if (derivativeUnrealizedpnl < 0){
                    parseFloat($('#derivativeUnrealizedPrice'+ dupnl ).html((derivativeUnrealizedpnl).toFixed(5)));
                    derivativeUnrealizedpnlid.style.color = '#dc3545'
                }else{
                    parseFloat($('#derivativeUnrealizedPrice'+ dupnl ).html((derivativeUnrealizedpnl).toFixed(5)));
                    derivativeUnrealizedpnlid.style.color = '#198754'
                }
            }

            let indexNumber3 = $('#myCoinIndex3').html();

            for (let k = 1; k <= indexNumber3; k++) {
                let currencyName = $('#currencyName' + k).html();
                let coinLot = parseFloat($('#lot' + k).html());
                let coinLotSize = parseFloat($('#lotSize'+ k).html());
                let coinExpectedInterest = parseFloat($('#expectedInterest' + k).html());
                let totalCoinValue  = parseFloat(((coinLot*coinLotSize) + coinExpectedInterest));


                let full_data = trackers.trackers.trackers;
                // let full_data = trackers.trackers;

                full_data.forEach(async function (item) {
                    if (item[0] === 't' + currencyName + 'USD') {
                        parseFloat($('#coinWithInterest' + k).html((totalCoinValue * item[1]).toFixed(5)));
                    }
                });
                for (let a = 1; a <= indexNumber3; a++) {
                    totalFinanceValue += parseFloat($('#coinWithInterest' + a).html());
                    if (isNaN(totalFinanceValue)){
                        parseFloat($('#totalFinanceAmount').html("00.00000"));
                    }else{
                        parseFloat($('#totalFinanceAmount').html((totalFinanceValue).toFixed(5)));
                    }
                }

            }
            let tradeMargin = parseFloat($('#totalAmount').html());
            let derivativeMargin = parseFloat($('#totalDerivativeAmount').html());
            let financeMargin = parseFloat($('#totalFinanceAmount').html());
            totalMargin = parseFloat(tradeMargin + derivativeMargin + financeMargin);
            if (isNaN(totalMargin)) {
                parseFloat($('#totalMArginBalanceId').html("00.000000"));
            }
            else{
                parseFloat($('#totalMArginBalanceId').html((totalMargin).toFixed(5)));
            }


        })
    </script>
    <script>

    </script>
@endsection
