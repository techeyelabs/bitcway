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

    </style>
@endsection

@section('content')
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
                            <h4 class="txtWhitecolor text-left mb-4" ><span id="totalMArginBalanceId">00.000000</span><span style="font-size: 10px">USD</span></h4>
                            <div class="col-md-12 row">
                                <div class="col-md-8">
                                    <abbr title="{{__('your_total_wallet_balance')}}"  class="txtWhitecolor text-left initialism">{{__('total_wallet_balance')}}</abbr><br>
                                    <h4 class="txtWhitecolor text-left" >{{$userBalance->balance}}<span style="font-size: 10px"> USD</span></h4>
                                </div>
                                <div class="col-md-4" style="margin-top: 3px;">
                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                                            data-bs-target="#derivativeModal" style="width: 100px;"
                                            onclick="setFlag(1)">{{__('button11')}}
                                    </button>
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
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">{{__('column1')}}</p>
                            <p class="col txtWhitecolor" id="" style="text-align: left;">{{__('size')}}</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount" style="text-align: center;">{{__('entryprice')}}</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin" style="text-align: center;">{{__('markprice')}}</p>
                            <p class="col txtWhitecolor" id="unrealizedpnl" style="text-align: right;">Unrealized PNL</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">{{__('action')}}</p>
                        </li>
                        <?php
                        $i = 0;
                        foreach($wallets as $index =>$item ){
                        $i++;
                        ?>
                        <li class="row list-group-item d-flex justify-content-between align-items-center ">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName{{$index}}" style="text-align: left;">{{($item->currency->name == 'ADA') ? 'MAB' : $item->currency->name}}</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinSize{{$index}}" style="text-align: left;">{!! number_format((double)($item->balance),5)!!}</p>
{{--                            <p class="col txtWhitecolor" id="MyTotalCoinAmount{{$index}}" style="text-align: center;">{!! number_format((double)($item->equivalent_trade_amount),5)!!}</p>--}}
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount{{$index}}" style="text-align: center;">{!! number_format((double)($item->currency_price),5)!!}</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin{{$index}}" style="text-align: center;">00.000000</p>
                            <p class="col " id="unrealizedpnl{{$index}}" style="text-align: right;">00.000000</p>
                            <p class="col txtHeadingColor"  style="text-align: right;"><span id="assetTradeSell{{$index}}" style="cursor: pointer;">{{__('title9')}}</span></p>
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
                            <abbr title="Derivative Wallet"  class="txtWhitecolor text-left initialism">{{__('title20')}}</abbr><br>
                            <span class="d-none" id="derivativeBalance">{{$userDerivativeBalance->derivative}}</span>
                            <h4 class="txtWhitecolor text-left mb-3" id="totalDerivativeAmount">{{$userDerivativeBalance->derivative}}<span style="font-size: 10px">USD</span></h4>
                        </div>
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                data-bs-target="#derivativeModal" style="width: 100px;" onclick="setFlag(2)">{{__('button11')}}
                        </button>
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
                            <p class="col txtHeadingColor"  style="text-align: right;"><span id="assetDerivativeSell{{$j}}" style="cursor: pointer;">{{__('title9')}}</span></p>
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
                    <h5 class="modal-title txtWhitecolor" id="modalFlag"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="background-color: #ffffff;"></button>
                </div>
                <form class="formclas" action="{{route('derivativedeposit', app()->getLocale())}}" method="post">
                    @csrf
                    <div class="modal-body modalbg">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label"
                                   style="color: #ffffff; padding-top: 0px;">{{__('label3')}}:</label>
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
        // const socket = io('http://192.144.82.234:3000/');
        const socket = io('https://bitc-way.com:3000/');
        let loaded = false;
        socket.on('trackers', (trackers) => {
            // console.log(trackers);
            // Home.trackers = trackers.trackers;
            let totalValue = 0;
            let totalDerivativeValue = 0
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
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + realcurrname + 'USD') {
                        console.log(realcurrname);
                        // parseFloat($('#CoinpriceIntoMycoin' + i).html((tradeCurrencySize * item[1]).toFixed(5)));
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
            for (let s = 0; s < indexNumber; s++) {
                let currencyName = $('#MyCoinCurrencyName' + s).html();
                let currencyAmount = parseFloat($('#MyTotalCoinAmount' + s).html());
                let tradeCurrencySize = parseFloat($('#MyTotalCoinSize' + s).html());
                let tradeMarkPrice = parseFloat($('#CoinpriceIntoMycoin' + s).html());

                $( "#assetTradeSell" + s ).click(function() {
                    axios.post('{{route("user-trade-sell", app()->getLocale())}}',{
                        currency: currencyName,
                        sellAmount: tradeCurrencySize,
                        calcSellAmount: tradeMarkPrice
                    })
                        .then(function (response) {
                            if(response.data.status){
                                toastr.success('Sell successfull');
                                window.location.href = '{{route("user-wallets", app()->getLocale())}}';
                                return false;
                            }
                            // toastr.error('Error occured !!');
                        })
                        .catch(function (error) {
                            toastr.error('Error occured !!');
                        });
                });
            }







            // let indexNumber2 = $('#myCoinIndex2').html();
            // for (let j = 1; j <= indexNumber2; j++) {
            //     let currencyName = $('#MyCoinCurrencyName2' + j).html();
            //     let currencyAmount = parseFloat($('#MyTotalCoinAmount2' + j).html());
            //
            //     let full_data = trackers.trackers;
            //     full_data.forEach(async function (item) {
            //         if (item[0] === 't' + currencyName + 'USD') {
            //             let derivativeCurrentMarketPrice =  parseFloat($('#CoinpriceintoMycoin2' + j).html((currencyAmount * item[1]).toFixed(5)));
            //             console.log(derivativeCurrentMarketPrice);
            //             // let derivativeCurrentMarketPrice =  parseFloat($('#CoinpriceintoMycoin2' + j).html((currencyAmount * item[1]).toFixed(5)));
            //             // parseFloat($('#CoinpriceintoMycoin2' + j).html((currencyAmount * item[1]).toFixed(5)));
            //             parseFloat($('#CoinpriceintoMycoin2' + j).html(( item[1]).toFixed(5)));
            //         }
            //     });
            // }
            // let mainDerivativeBalance = parseFloat($('#mainDerivativeBalance').text());
            // for (let dv = 1; dv <= indexNumber2; dv++) {
            //         totalDerivativeValue += parseFloat($('#derivativeAmountWithPNL' + dv).text());
            //         parseFloat($('#totalDerivativeAmount').html((totalDerivativeValue+mainDerivativeBalance).toFixed(5)));
            // }
            // for (let dbPNL = 1; dbPNL<=indexNumber2; dbPNL++){
            //     let derivativeMarkPrice = parseFloat($('#CoinpriceintoMycoin2' + dbPNL).html().replace(',',''));
            //     let derivativeLoan = parseFloat($('#derivativeLoan' + dbPNL).html().replace(',',''));
            //     derivativePNL = parseFloat(derivativeMarkPrice - derivativeLoan);
            //     parseFloat($('#derivativeAmountWithPNL' + dbPNL).html(derivativePNL));
            // }
            //
            // for (let dupnl = 1; dupnl <= indexNumber2; dupnl++) {
            //     var derivativeUnrealizedpnlid = document.getElementById('derivativeUnrealizedPrice'+ dupnl);
            //     // let derivativeEntryPrice = parseFloat($('#derivativeEntryPrice' + dupnl).html().replace(',',''));
            //     let derivativeEntryPrice = parseFloat($('#derivativeEntryCurrencyPrice' + dupnl).html().replace(',',''));
            //     let derivativeMarkPrice = parseFloat($('#CoinpriceintoMycoin2' + dupnl).html());
            //     let derivativeUnrealizedpnl = parseFloat(derivativeMarkPrice-derivativeEntryPrice);
            //     if (derivativeUnrealizedpnl < 0){
            //         parseFloat($('#derivativeUnrealizedPrice'+ dupnl ).html((derivativeUnrealizedpnl).toFixed(5)));
            //         derivativeUnrealizedpnlid.style.color = '#dc3545'
            //     }else{
            //         parseFloat($('#derivativeUnrealizedPrice'+ dupnl ).html((derivativeUnrealizedpnl).toFixed(5)));
            //         derivativeUnrealizedpnlid.style.color = '#198754'
            //     }
            // }

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
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + realcurrname2 + 'USD') {
                        if (realcurrname == 'ADA'){
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


            for (let ds = 1; ds <= indexNumber2; ds++) {
                let derivativeCurrencyName = $('#MyCoinCurrencyName2' + ds).html();
                let derivativeCurrencySize = parseFloat($('#MyTotalCoinAmount2' + ds).html());
                let derivativeMarkPrice = parseFloat($('#CoinpriceintoMycoin2' + ds).html());

                $( "#assetDerivativeSell" + ds ).click(function() {
                    axios.post('{{route("user-trade-sell", app()->getLocale())}}',{
                        currency: derivativeCurrencyName,
                        sellAmount: derivativeCurrencySize,
                        calcSellAmount: derivativeMarkPrice,
                        derivativeType: '0'
                    })
                        .then(function (response) {
                            if(response.data.status){
                                toastr.success('Sell successfull');
                                window.location.href = '{{route("user-wallets", app()->getLocale())}}';
                                return false;
                            }
                            // toastr.error('Error occured !!');
                        })
                        .catch(function (error) {
                            toastr.error('Error occured !!');
                        });
                });
            }

            let totalFinanceValue = 0.00;
            let indexNumber3 = $('#myCoinIndex3').html();
        
            for (let k = 1; k <= indexNumber3; k++) {
                let currencyName = $('#currencyName' + k).html();
                let coinLot = parseFloat($('#lot' + k).html());
                let coinLotSize = parseFloat($('#lotSize'+ k).html());
                let coinExpectedInterest = parseFloat($('#expectedInterest' + k).html());
                let totalCoinValue  = parseFloat(((coinLot*coinLotSize) + coinExpectedInterest));
               
               
                let full_data = trackers.trackers.trackers;

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
            if (loaded == false) {
                setInterval(function () {
                }, 1000);
                loaded = true;
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
        function setFlag(type) {
            $('#flag').val(type);
            var flag = document.getElementById('flag').value;
            if (flag == 1){
                $('#modalFlag').html("{{__('transferDerivative')}}");
            }else if(flag == 2){
                $('#modalFlag').html("{{__('transferWallet')}}");
            }
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
    <script>
        function autoRefreshPage()
        {
            toastr.success('Auto reload');
            window.location = window.location.href;
        }
        setInterval('autoRefreshPage()', 300000);
    </script>
@endsection
