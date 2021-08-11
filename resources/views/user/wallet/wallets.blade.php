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

        .tradeType-select{
            width: 150px;
            background-color: #102331;
            border: #102331;
            color: white;
            height: 25px;
        }

        .asset-input{
            background-color: #102331;
            border: 1px solid #102331;
            color: white
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
                            <h4 class="txtWhitecolor text-left mb-4" >
                                <span id="totalMArginBalanceId">{{$userBalance->balance}}</span><span style="font-size: 10px">&nbsp; USD</span>
                                <span id="walletbalanceToAdd" style="display: none">{{$userBalance->balance}}</span>
                            </h4>
                            <div class="col-md-12 row">
                                <div class="col-md-8">
                                    <abbr title="{{__('your_total_wallet_balance')}}"  class="txtWhitecolor text-left initialism">{{__('total_wallet_balance')}}</abbr><br>
                                    <h4 class="txtWhitecolor text-left" >{{($userBalance->balance == '0') ? '00.00' : number_format((float) ($userBalance->balance),2,'.', '')}}<span style="font-size: 10px">&nbsp; USD</span></h4>
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
                            <h4 class="txtWhitecolor text-left mb-2" id="totalAmount">00.00<span style="font-size: 10px">&nbsp; USD</span></h4>
                        </div>
                    </div>
                </div>
                <div style="margin: auto; overflow-x: auto">
                    <ul class="container-fluid" style=" min-width: 1000px;">
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
                            {{--<p class="col txtWhitecolor" id="MyTotalCoinAmount{{$index}}" style="text-align: center;">{!! number_format((double)($item->equivalent_trade_amount),5)!!}</p>--}}
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount{{$index}}" style="text-align: center;">{!! number_format((double)($item->currency_price),5)!!}</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin{{$index}}" style="text-align: center;">00.00</p>
                            <p class="col " id="unrealizedpnl{{$index}}" style="text-align: right;">00.00</p>
                            <p class="col d-none" id="totalTradeAmount{{$index}}" style="text-align: right;color: #00aced">000.00</p>
                            <p class="col txtHeadingColor"  style="text-align: right;"><span id="assetTradeSell{{$index}}" style="cursor: pointer;" onclick="toggleForm('{{$index}}')" >{{__('title9')}}</span></p>
                            {{--<p class="col txtHeadingColor"  style="text-align: right;"><span id="assetTradeSell{{$index}}" style="cursor: pointer;" onclick="trade_Sell_Function('{{$index}}')" >{{__('title9')}}</span></p>--}}
                        </li>
                        <li class="row list-group-item d-flex justify-content-between align-items-center" id="{{$index}}" style="display: none !important">
                            <span class="col txtWhitecolor" style="text-align: right">
                                <select name="tradeType_{{$index}}" id="tradeType_{{$index}}" class="tradeType-select" onchange="limitAmountShow({{$index}})">
                                  <option value="0">Market</option>
                                  <option value="1">Limit</option>
                                </select>
                            </span>
                            <span class="col txtWhitecolor" style="text-align: center">
                                <input class="asset-input" id="trade-amount_{{$index}}" name="trade-amount" placeholder="Size"/>
                            </span>
                            <span class="col txtWhitecolor" style="text-align: center">
                                <input class="asset-input" id="limit-rate_{{$index}}" name="limit-rate" placeholder="Limit price" disabled/>
                            </span>
                            <input type="hidden" id="id_{{$index}}" name="id_{{$index}}" value="{{$item->id}}">
                            <span class="col txtHeadingColor" style="text-align: left; cursor: pointer" onclick="trade('{{$index}}', 'trade')">Submit</span>
                        </li>
                        <?php
                        }
                        ?>
                        <br/>
                        <div class="mb-2" style="margin: auto">
                            <div class="container-fluid">
                                <div class="text-left" style="margin-left: -10px">
                                    <abbr title="Derivative Wallet"  class="txtWhitecolor text-left initialism">Trade limits</abbr><br>
                                </div>
                            </div>
                        </div>
                        <table style="min-width: 400px; color: white !important">
                            <tr>
                                <th>Limit</th>
                                <th>Size</th>
                                <th>SYMBOL</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach ($limitBuySell as $index => $lst ){ ?>
                            <tr>
                                <td>{{$lst->priceLimit}}</td>
                                <td>{{$lst->currencyAmount}}</td>
                                <td>{{($lst->currency->name == 'ADA')? 'MAB' : $lst->currency->name}}</td>
                                <td>
                                    <i class="fas fa-trash deleteEnabled" style="color: green; cursor: pointer" onclick="tradeDelete('{{$lst->id}}')"></i>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
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
                            <span class="d-none" id="derivativeBalance">{{($userDerivativeBalance->derivative == '0') ? '00.00' : $userDerivativeBalance->derivative}}</span>
                            <h4 class="txtWhitecolor text-left mb-3" id="totalDerivativeAmount">{{($userDerivativeBalance->derivative == '0') ? '00.00' : $userDerivativeBalance->derivative}}<span style="font-size: 10px">&nbsp; USD</span></h4>
                        </div>
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                data-bs-target="#derivativeModal" style="width: 100px;" onclick="setFlag(2)">{{__('button11')}}
                        </button>
                    </div>
                </div>
                <div style="margin: auto; overflow-x: auto">
                    <ul class="container-fluid" style=" min-width: 1000px;">
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">{{__('column1')}}</p>
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">{{__('col12')}}</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount" style="text-align: left; ">{{__('size')}}</p>
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">{{__('entryprice')}}</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin2" style="text-align: center;">{{__('markprice')}}</p>
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: right;">Unrealized PNL</p>
                            <p class="col txtWhitecolor" id="derivati8vePercent" style="text-align: right;">{{__('col16')}}</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">Action</p>
                        </li>
                        <?php
                        $j = 0;
                        foreach($transactionHistory as $index => $item ){
                            if(($item->leverage) >= 1 ){
                                $j++;
                        ?>
                        {{--{!! number_format((double)($item->equivalent_amount / $item->leverage),5) !!}--}}
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName2{{$j}}" style="text-align: left;">{{($item->currencyName->name == 'ADA') ? 'MAB' : $item->currencyName->name}}</p>
                            <p class="col txtWhitecolor" id="type{{$j}}" style="text-align: left;">{{$item->trade_type}}</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount2{{$j}}" style="text-align: left;">{{$item->amount}}</p>
                            <p class="col txtWhitecolor d-none previous" id="derivativeEntryPrice{{$j}}" style="text-align: left;">{{($item->equivalent_amount)}}</p>
                            <p class="col txtWhitecolor" id="derivativeCurrencyEntryPrice{{$j}}" style="text-align: left;">{{($item->derivative_currency_price)}}</p>
                            <p class="d-none" id="derivativeLoan{{$j}}" >{{($item->derivativeLoan)}}</p>
                            <p class="col txtWhitecolor d-none previous" id="CoinpriceintoMycoin2{{$j}}" style="text-align: center;">00.00</p>
                            <p class="col txtWhitecolor" id="CoinpriceintoMyCurrency{{$j}}" style="text-align: center;">00.000000</p>
                            <p class="d-none" id="derivativeAmountWithPNL{{$j}}">00.000000</p>
                            <p class="col " id="derivativeUnrealizedPrice{{$j}}" style="text-align: right;color:white;">00.00</p>
                            <p class="col d-none previous" id="derivativeUnrealizedCurrencyPrice{{$j}}" style="text-align: right;color:white;">00.00</p>
                            <p class="col txtWhitecolor" id="derivativePercent{{$j}}" style="text-align: right; color:white;">{{$item->leverage}}</p>
                            <p class="col d-none" id="totalDerivativeAmount{{$j}}" style="text-align: right;color: #00aced">000.00</p>
                            <p class="col d-none" id="id{{$j}}">{{$item->id}}</p>
                            <p class="col txtHeadingColor"  style="text-align: right;"><span id="assetDerivativeSell{{$j}}"  style="cursor: pointer;" onclick="toggleForm('derivative_{{$j}}')">{{__('title9')}}</span></p>
                            {{--<p class="col txtHeadingColor"  style="text-align: right;"><span id="assetDerivativeSell{{$j}}"  style="cursor: pointer;" onclick="derivative_Sell_Function('{{$j}}')">{{__('title9')}}</span></p>--}}
                        </li>
                        <li class="row list-group-item d-flex justify-content-between align-items-center" id="derivative_{{$j}}" style="display: none !important">
                            <span class="col txtWhitecolor" style="text-align: right">
                                <select name="tradeType_derivative_{{$j}}" id="tradeType_derivative_{{$j}}" class="tradeType-select" onchange="limitAmountShowDerivative({{$j}})">
                                  <option value="0">Market</option>
                                  <option value="1">Limit</option>
                                </select>
                            </span>
                            <span class="col txtWhitecolor" style="text-align: center">
                                <input class="asset-input" id="trade-amount_derivative_{{$j}}" name="trade-amount_derivative_{{$j}}" placeholder="Size"/>
                            </span>
                            <span class="col txtWhitecolor" style="text-align: center">
                                <input class="asset-input" id="limit-rate_derivative_{{$j}}" name="limit-rate_derivative_{{$j}}" placeholder="Limit price" disabled/>
                            </span>
                            <input type="hidden" id="id_derivative_{{$j}}" name="id_derivative_{{$j}}" value="{{$item->id}}">
                            <span class="col txtHeadingColor" style="text-align: left; cursor: pointer" onclick="trade('{{$j}}', 'derivative')">Submit</span>
                        </li>
                        <?php
                        }
                        }
                        ?>
                        <br/>
                        <div class="mb-2" style="margin: auto">
                            <div class="container-fluid">
                                <div class="text-left" style="margin-left: -10px">
                                    <abbr title="Derivative Wallet"  class="txtWhitecolor text-left initialism">Leverage trade limits</abbr><br>
                                </div>
                            </div>
                        </div>
                        <table style="min-width: 400px; color: white !important">
                            <tr>
                                <th>Limit</th>
                                <th>Size</th>
                                <th>Position</th>
                                <th>SYMBOL</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach ($leverageSettlementLimits as $index => $lst ){ ?>
                            <tr>
                                <td>{{$lst->limit_rate}}</td>
                                <td>{{$lst->amount}}</td>
                                <td class={{($lst->type == 1)? 'text-success' : 'text-danger'}}>{{($lst->type == 1)? 'buy' : 'sell'}}</td>
                                <td>{{($lst->currency->name == 'ADA')? 'MAB' : $lst->currency->name}}</td>
                                <td>
                                    <i class="fas fa-trash deleteEnabled" style="color: green; cursor: pointer" onclick="settlementDelete('{{$lst->id}}')"></i>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

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
                            <h4 class="txtWhitecolor text-left mb-3" id="totalFinanceAmount">00.00<span style="font-size: 10px">&nbsp; USD</span></h4>
                        </div>
                    </div>
                </div>
                <div style="margin: auto; overflow-x: auto">
                    <ul class="container-fluid" style=" min-width: 1000px;">
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="" style="text-align: left; ">{{__('column1')}}</p>
                            <p class="col txtWhitecolor" id="" style="text-align: left; ">{{__('col4')}}</p>
                            <p class="col txtWhitecolor" id="" style="text-align: left; ">Total Coin</p>
                            <p class="col txtWhitecolor" id="" style="text-align: center;">{{__('col5')}} </p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">{{__('col6')}} </p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">{{__('col7')}}</p>
                        </li>
                        <?php
                        $k = 0;
                        foreach($finances as $index=>$finance){
                            if ($finance->status == 1){
                            $k++;
                        ?>
                            <li class="row list-group-item d-flex justify-content-between align-items-center">{{--$finance->currency->name--}}
                                <p class="col txtWhitecolor" id="currencyName{{$k}}" style="text-align: left;">@php if($finance->currency->name == "ADA"){echo "MAB";}else{echo $finance->currency->name;} @endphp</p>
                                <p class="col txtWhitecolor" id="lot{{$k}}" style="text-align: left;">{{$finance->lot_count}}</p>
                                <p class="col txtWhitecolor" id="" style="text-align: left;">{{$finance->lot_count * $finance->lot_size}}</p>
                                <p class="col txtWhitecolor" id="valueDate{{$k}}" style="text-align: center;">{{date('d/m/Y', strtotime($finance->value_date))}}</p>
                                <p class="col txtWhitecolor" id="redeamDate{{$k}}" style="text-align: right;">{{date('d/m/Y', strtotime($finance->redemption_date))}}</p>
                                <p class="col txtWhitecolor" id="expectedInterest{{$k}}" style="text-align: right;">{{$finance->expected_interest}}</p>
                                <p class="d-none" id="lotSize{{$k}}" style="text-align: left;">{{$finance->lot_size}}</p>
                                <p class="d-none" id="coinWithInterest{{$k}}" style="text-align: left;"></p>
                            </li>
                        <?php
                            }
                        }
                        ?>
                        <p style="display: none;" id="myCoinIndex3">{{$k}}</p>
                    </ul>
                </div>
        </div>
        {{--Finance Wallet End--}}
    </div>
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
                                   style="color: #ffffff; padding-top: 0px;">{{__('label3')}}</label>
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
        console.log("here");
        socket.on('trackers', (trackers) => {
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
                let value = 0;
                // let full_data = trackers.trackers;
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + realcurrname + 'USD') {
                        console.log(realcurrname);
                        // parseFloat($('#CoinpriceIntoMycoin' + i).html((tradeCurrencySize * item[1]).toFixed(5)));
                        if (realcurrname == 'ADA') {
                            value = item[3] * {{$ratio}};
                        } else {
                            value = item[3];
                        }
                        parseFloat($('#CoinpriceIntoMycoin' + i).html((value).toFixed(5)));
                        parseFloat($('#totalTradeAmount' + i).html((tradeCurrencySize * value).toFixed(5)));
                    }
                });
            }


            for (let t = 0; t < indexNumber; t++) {
                totalValue += parseFloat($('#CoinpriceIntoMycoin' + t).text()) * parseFloat($('#MyTotalCoinSize' + t).text());
                parseFloat($('#totalAmount').html((totalValue).toFixed(5)));
            }

            for (let tupnl = 0; tupnl < indexNumber; tupnl++) {
                var tradeUnrealizedpnlid = document.getElementById('unrealizedpnl'+ tupnl);
                var amountId = parseFloat($('#MyTotalCoinSize' + tupnl).text().replace(',', ''));
                let tradeEntryPrice = parseFloat($('#MyTotalCoinAmount' + tupnl).text().replace(',', ''));
                let tradeMarkPrice = parseFloat($('#CoinpriceIntoMycoin' + tupnl).html());
                let tradeUnrealizedpnl = (tradeMarkPrice - tradeEntryPrice) * amountId;

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
                let tradeType = $('#type' + j).html();
                let realcurrname2 = '';
                if (currencyName == 'MAB') {
                    realcurrname2 = 'ADA';
                } else {
                    realcurrname2 = currencyName;
                }

                let full_data = trackers.trackers.trackers;
                let value = 0;
                full_data.forEach(async function (item) {
                    if (tradeType == 'buy'){
                        if (item[0] === 't' + realcurrname2 + 'USD') {
                            if (realcurrname2 == 'ADA'){
                                value = item[3] * {{$ratio}};
                            } else {
                                value = item[3]
                            }
                        }
                    } else {
                        if (item[0] === 't' + realcurrname2 + 'USD') {
                            if (realcurrname2 == 'ADA'){
                                value = item[1] * {{$ratio}} ;
                            } else {
                                value = item[1];
                            }
                        }
                    }
                    parseFloat($('#CoinpriceintoMycoin2' + j).html((currencyAmount * value).toFixed(5)));
                    parseFloat($('#CoinpriceintoMyCurrency' + j).html(( value).toFixed(5)));
                    parseFloat($('#totalDerivativeAmount' + j).html(( currencyAmount*value).toFixed(5)));
                });
            }

            for (let dv = 1; dv <= indexNumber2; dv++) {
                let derivativeBalance = parseFloat($('#derivativeBalance').text());
                let type = $('#type' + dv).text();
                // totalDerivativeValue += (parseFloat($('#CoinpriceintoMycoin2' + dv).text())/parseFloat($('#derivativePercent' + dv).text()));
                let loan = (((parseFloat($('#derivativePercent' + dv).text()) - 1) / parseFloat($('#derivativePercent' + dv).text())) *
                            parseFloat($('#derivativeCurrencyEntryPrice' + dv).text())) * (parseFloat($('#MyTotalCoinAmount2' + dv).text()));
                if (type == 'buy'){
                    totalDerivativeValue += (parseFloat($('#MyTotalCoinAmount2' + dv).text())) * (parseFloat($('#CoinpriceintoMyCurrency' + dv).text())) - loan ;
                } else {
                    // console.log("first", 2 * (parseFloat($('#derivativeCurrencyEntryPrice' + dv).text())));
                    // console.log("second", parseFloat($('#CoinpriceintoMyCurrency' + dv).text()));
                    // console.log("third", loan);
                    // console.log()
                    totalDerivativeValue += ((parseFloat($('#MyTotalCoinAmount2' + dv).text())) * ( 2 * (parseFloat($('#derivativeCurrencyEntryPrice' + dv).text())) - (parseFloat($('#CoinpriceintoMyCurrency' + dv).text())) )) - loan ;
                }
                console.log(totalDerivativeValue);

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
                var derivativeAmountId = parseFloat($('#MyTotalCoinAmount2' + dupnl).html().replace(',',''));
                var type = $('#type' + dupnl).html();
                let derivativeEntryPrice = parseFloat($('#derivativeEntryPrice' + dupnl).html().replace(',',''));
                let derivativeMarkPrice = parseFloat($('#CoinpriceintoMycoin2' + dupnl).html());
                let derivativeCurrencyEntryPrice = parseFloat($('#derivativeCurrencyEntryPrice' + dupnl).html().replace(',',''));
                let derivativeCurrencyMarkPrice = parseFloat($('#CoinpriceintoMyCurrency' + dupnl).html());
                console.log(type);
                let derivativeUnrealizedpnl = 0.00;
                if (type === 'buy'){
                    derivativeUnrealizedpnl = parseFloat(derivativeCurrencyMarkPrice - derivativeCurrencyEntryPrice) * derivativeAmountId;
                } else {
                    derivativeUnrealizedpnl = parseFloat(derivativeCurrencyEntryPrice - derivativeCurrencyMarkPrice) * derivativeAmountId;
                }

                if (derivativeUnrealizedpnl < 0){
                    parseFloat($('#derivativeUnrealizedPrice'+ dupnl ).html((derivativeUnrealizedpnl).toFixed(5)));
                    derivativeUnrealizedpnlid.style.color = '#dc3545'
                }else{
                    parseFloat($('#derivativeUnrealizedPrice'+ dupnl ).html((derivativeUnrealizedpnl).toFixed(5)));
                    derivativeUnrealizedpnlid.style.color = '#198754'
                }
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
                // let full_data = trackers.trackers;

                full_data.forEach(async function (item) {
                    if (item[0] === 't' + currencyName + 'USD') {
                        parseFloat($('#coinWithInterest' + k).html((totalCoinValue * item[1]).toFixed(5)));
                    }
                });
                for (let a = 1; a <= indexNumber3; a++) {
                    totalFinanceValue += parseFloat($('#coinWithInterest' + a).html());
                    if (isNaN(totalFinanceValue)){
                    parseFloat($('#totalFinanceAmount').html("00.00"));
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
            let wallet = parseFloat($('#walletbalanceToAdd').html());
            let totalMargin = parseFloat(tradeMargin + derivativeMargin + financeMargin);
            if (isNaN(totalMargin)) {
                parseFloat($('#totalMArginBalanceId').html("00.00"));
            }
            else if (totalMargin == 0){
                parseFloat($('#totalMArginBalanceId').html(wallet));
            }else{
                let total = parseFloat(totalMargin) + parseFloat(wallet);
                parseFloat($('#totalMArginBalanceId').html((total).toFixed(5)));
            }
        })
    </script>
    <script>
        function trade_Sell_Function(index) {
            console.log(index);
            let currencyName = $('#MyCoinCurrencyName' + index).html();
            let tradeCurrencySize = parseFloat($('#MyTotalCoinSize' + index).html());
            let tradeSellAmount = parseFloat($('#totalTradeAmount' + index).html());

            axios.post('{{route("user-trade-sell", app()->getLocale())}}', {
                currency: currencyName,
                sellAmount: tradeCurrencySize,
                calcSellAmount: tradeSellAmount
            }).then(function (response) {
                if (response.data.status) {
                    toastr.success('Sell successfull');
                    window.location.href = '{{route("user-wallets", app()->getLocale())}}';
                        return false;
                }
                toastr.error('Error occured !!');
            }).catch(function (error) {
                toastr.error('Error occured !!');
            });
        }
    </script>
    <script>
        function derivative_Sell_Function(index) {
            let derivativeCurrencyName = $('#MyCoinCurrencyName2' + index).html();
            let id = parseInt($('#id' + index).html());
            let derivativeCurrencySize = parseFloat($('#MyTotalCoinAmount2' + index).html());
            let totalDerivativeAmount = parseFloat($('#totalDerivativeAmount' + index).html());

            axios.post('{{route("user-trade-sell", app()->getLocale())}}',{
                currency: derivativeCurrencyName,
                sellAmount: derivativeCurrencySize,
                calcSellAmount: totalDerivativeAmount,
                derivativeType: '0',
                id: id
            }).then(function (response) {
                if(response.data.status) {
                    toastr.success('Trade successfull');
                    window.location.href = '{{route("user-wallets", app()->getLocale())}}';
                    return false;
                }
                toastr.error('Error occured !!');
            }).catch(function (error) {
                toastr.error('Error occured !!');
            });
        }
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

        function toggleForm(id){
            console.log(id);
            var elems = document.getElementById(id);
            if (elems.style.display === 'block'){
                elems.style.cssText += ';display:none !important;'
            } else {
                elems.style.cssText += ';display:block !important;'
            }
        }

        function trade(index, type){
            //for regular trade
            var tradeType = $('#tradeType_'+index).val();
            var tradelimitRate = parseFloat($('#limit-rate_'+index).val());
            var tradeamount = parseFloat($('#trade-amount_'+index).val());
            var tradeCurrency = $('#MyCoinCurrencyName'+index).html();
            var tradePrice = parseFloat($('#CoinpriceIntoMycoin'+index).html());
            var tradeId = $('#id_'+index).val();

            //for derivative trade
            var tradeTypeDerivative = $('#tradeType_derivative_'+index).val();
            var tradelimitRateDerivative = parseFloat($('#limit-rate_derivative_'+index).val());
            var tradeamountDerivative = parseFloat($('#trade-amount_derivative_'+index).val());
            var tradeCurrencyDerivative = $('#MyCoinCurrencyName2'+index).html();
            var tradePriceDerivative = parseFloat($('#CoinpriceintoMyCurrency'+index).html());
            var tradeIdDerivative = $('#id_derivative_'+index).val();
            var derivativeTradeType = $('#type'+index).html();
            var derivativeItemId = parseInt($('#id'+index).html());

            let tradeCurrencySize = parseFloat($('#MyTotalCoinSize' + index).html());
            var tradeCurrencySizeDerivative = parseFloat($('#MyTotalCoinAmount2'+index).html());

            // showLoader('Processing...');
            if (type == 'trade'){
                if (tradeamount > tradeCurrencySize){
                    toastr.error('Invalid amount !!');
                    return false;
                }
                if (tradeType == "0"){
                    axios.post('{{route("user-trade-sell", app()->getLocale())}}', {
                        currency        : tradeCurrency,
                        sellAmount      : tradeamount,
                        calcSellAmount  : tradePrice * tradeamount
                    }).then(function (response) {
                        if(response.data.status){
                            toastr.success('Trade successfull');
                            window.location.reload();
                            return false;
                        }
                        toastr.error('Invalid data provided !!');
                    }).catch(function (error) {
                        toastr.error('Invalid data provided !!');
                    });
                } else {
                    derivative = 0;
                    axios.post('{{route("user-limit-sell", app()->getLocale())}}', {
                        currency            : tradeCurrency,
                        limitType           : 2,
                        priceLimit          : tradelimitRate,
                        currencyAmount      : tradeamount,
                        transactionStatus   : 1,
                        derivative          : derivative,
                        is_from_asset       : 1,
                        lastPrice           : tradePrice
                    }).then(function (response) {
                        if(response.data.status){
                            toastr.success('Trade successfull');
                            window.location.reload();
                            return false;
                        }
                        toastr.error('Error occured !!');
                    }).catch(function (error) {
                        toastr.error('Error occured !!');
                    });
                }

            } else {
                if (tradeamountDerivative > tradeCurrencySizeDerivative){
                    toastr.error('Invalid amount !!');
                    return false;
                }
                if (tradeTypeDerivative == "0"){
                    derivative = 0;
                    axios.post('{{route("user-trade-sell", app()->getLocale())}}', {
                        currency: tradeCurrencyDerivative,
                        sellAmount: tradeamountDerivative,
                        calcSellAmount: tradePriceDerivative * tradeamountDerivative,
                        derivativeType: '0',
                        id: tradeIdDerivative
                    }).then(function (response) {
                        if(response.data.status){
                            toastr.success('Trade successfull');
                            window.location.reload();
                            return false;
                        }
                        toastr.error('Error occured !!');
                    }).catch(function (error) {
                        toastr.error('Error occured !!');
                    });
                } else {
                    var derivative = parseInt($('#derivativePercent'+index).html());
                    axios.post('{{route("user-limit-derivative-settlement", app()->getLocale())}}', {
                        currency            : tradeCurrencyDerivative,
                        limitType           : 2,
                        priceLimit          : tradelimitRateDerivative,
                        currencyAmount      : tradeamountDerivative,
                        transactionStatus   : 1,
                        derivative          : derivative,
                        itemId              : derivativeItemId,
                        derivativeTradeType : derivativeTradeType,
                        lastPrice           : tradePriceDerivative
                    }).then(function (response) {
                        if(response.data.status){
                            toastr.success('Trade successfull');
                            window.location.reload();
                            return false;
                        }
                        toastr.error('Error occured !!');
                    }).catch(function (error) {
                        toastr.error('Error occured !!');
                    });
                }
            }
        }

        function settlementDelete(id){
            axios.post('{{route("user-delete-settlement", app()->getLocale())}}', {
                id : id,
            }).then(function (response) {
                if(response.data.status){
                    toastr.success('Deletion successfull');
                    window.location.reload();
                    return false;
                }
                toastr.error('Error occured !!');
            }).catch(function (error) {
                toastr.error('Error occured !!');
            });
        }

        function tradeDelete(id){
            axios.post('{{route("user-delete-asset-trade", app()->getLocale())}}', {
                id : id,
            }).then(function (response) {
                if(response.data.status){
                    toastr.success('Deletion successfull');
                    window.location.reload();
                    return false;
                }
                toastr.error('Error occured !!');
            }).catch(function (error) {
                toastr.error('Error occured !!');
            });
        }

        function limitAmountShow(id){
            if ($('#tradeType_' + id).val() == 1){
                $('#limit-rate_' + id ).attr('disabled', false);
            } else {
                $('#limit-rate_' + id ).attr('disabled', true);
            }
        }

        function limitAmountShowDerivative(id){
            console.log($('#tradeType_derivative_' + id).val());
            if ($('#tradeType_derivative_' + id).val() == 1){
                $('#limit-rate_derivative_' + id ).attr('disabled', false);
            } else {
                $('#limit-rate_derivative_' + id ).attr('disabled', true);
                $('#limit-rate_derivative_' + id ).val('');
            }
        }

        setTimeout(function(){
            window.location.reload(1);
        }, 60000);
    </script>
@endsection
