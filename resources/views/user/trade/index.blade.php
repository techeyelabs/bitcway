@extends('user.layouts.main')

@section('custom_css')
    <style>
        #trackers {
            /*max-height: calc(100vh - 420px);*/
            overflow-y: scroll;
            margin-top: 50px;
        }
        tr{
            cursor: pointer;
        }
        tr.active{
            background-color: #081420;
        }
        .orders{
            max-height: 595px;
            overflow-y: hidden;
        }
        .cursor-pointer{
            cursor: pointer;
        }
        #rangeInput{
            width: 560px;
            max-width: 100%;
        }
        th{
            font-size: 10px !important
        }
        .tables{
            padding: 5px 0px 5px 0px
        }
        .sidebar{
            width: 375px;
            min-width: 375px;
            z-index: 6;
            /*margin-left: 6px;*/
        }
        .main-app-container{
            margin: 0 5px;
            flex-grow: 1;
            min-width: 0;
        }

        .table>:not(caption)>*>* {
            padding: 2px 20px 2px 20px !important;
        }
        .selectClass{
            background-color: #081420 !important;
            color: darkgray !important;
            border: none;
        }
        .coloredbid{
            background-color: #1142304d;;
        }
        .coloredask{
            background-color: #942f3e6e;;
        }

         .loader {
             border: 16px solid #f3f3f3;
             border-radius: 50%;
             border-top: 16px solid #3498db;
             width: 120px;
             height: 120px;
             -webkit-animation: spin 2s linear infinite;
             animation: spin 2s linear infinite;
         }

        / Safari /
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }



    </style>
@endsection
@section('content')
    <div id="wrap" class="trade">
        @if(isset($type))
            <h3 class="txtHeadingColor pageTitle">Derivatives</h3>
        @else
            <h3 class="txtHeadingColor pageTitle">Trade</h3>
        @endif
        <hr>
        <div class="row" style="display: flex;">
            <div class="col-md-3 sidebar">
                <div class="card tickersDiv" >
                    <div class="card-body">
                        <div id="trackers">
                            <div class="text-center title txtHeadingColor"><h4>TICKERS</h4></div>
                            <table class="tables trackers" style="width: 100%; table-layout: fixed">
                                <thead>
                                    <tr>
                                        <th class="txtWhitecolor th1">SYMBOL</th>
                                        <th class="txtWhitecolor th2">LAST</th>
                                        <th class="txtWhitecolor th3">24H</th>
                                        <th class="txtWhitecolor th4">VOL <u>USD</u></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in trackers"  :class="{active: item[0] == selectedItem[0]}"  v-on:click="setCurrency(item);">
                                        <td v-cloak id="currencyNameid" class="txtWhitecolor td1">@{{splitCurrency(item[0])}}</td>
                                        <td class="td2" ><span v-cloak style="color: white;font-size: 12px;">@{{item[7]}}</span> USD</td>
                                        <td class="td3"  v-cloak :class="{'text-danger': item[6]<0, 'text-success': item[6]>0}">@{{Math.abs((item[6]*100).toFixed(2))}}%</td>
                                        <td v-cloak class="txtWhitecolor td4">@{{Math.round(item[7]*item[8])}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="card mt-3" >
                    <div class="card" >
                        @if(isset($type))
                            <div class="card-body">
                                <h4 v-cloak class="txtHeadingColor " >ORDER FORM: @{{currency}}</h4>
                                <hr>
                                <div class="row">
                                    <div class="col ">
                                        <select class="form-select selectClass" aria-label="Default select example" id="choseOrderType" onchange="choseOrderType('derivative')">
                                            <option value="0" selected>Market</option>
                                            <option value="1">Limit</option>
                                        </select>
                                        <div class="form-group">
                                            <div id="limitDiv"  style="display: none">
                                                <label class="txtWhitecolor" for="" style="margin-top: 10px;">Limit:</label>
                                                <input type="hidden" name="editId" id="editId" value="" >
                                                <div class="input-group">
                                                    <input type="text" id="limitAmountId" onkeyup="limitLength()" class="form-control mb-1" placeholder=""  v-model="limitAmount">
                                                    <span v-if="limitAmount.length" id="limitAmountInputLength" class="input-count d-none">@{{limitAmount.length}}</span>
                                                </div>
                                            </div>
                                            <label class="txtWhitecolor d-none" for="" style="margin-top: 10px;">USD:</label>
                                            <div class="input-group d-none">
                                                <input type="text" class="form-control mb-1" placeholder="" readonly v-model="selectedPrice" style="cursor: not-allowed;">
                                            </div>
                                            <div id="totalAmountDiv">
                                                <label class="txtWhitecolor"  for="" style="margin-top: 10px;">Total:</label>
                                                <span v-cloak class="text-muted form-control" id="totalamountVal"  style="">~@{{calcAmount}}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
{{--                                            <label for="" class="txtWhitecolor" id="tradeCurrencyName">USD:</label>--}}
{{--                                            <div class="input-group">--}}
{{--                                                <input type="text" class="form-control mb-1" placeholder="" readonly v-model="selectedPrice" style="cursor: not-allowed;">--}}
{{--                                            </div>--}}
{{--                                            <span v-cloak class="text-muted form-control"  id="bidbox" style="margin-top: 10px;">~@{{calcAmount}}</span>--}}
                                            <small class="txtWhitecolor">BID</small>
                                            <small v-cloak class="float-end text-success cursor-pointer " id="bidval" v-on:click="selectedPrice=latestBid">
                                                <i v-if="bidincreased == 'increased'" class="fas fa-sort-up"></i>
                                                <i v-else class="fas fa-sort-down"></i>
                                                @{{latestBid}}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div id="selectType" style="">
                                            <div class="form-check form-check-inline" id="limitBuyId" >
                                                <input class="form-check-input"  type="checkbox" id="limitBuyInput" value="1"  onclick="enableLimitBuy('derivative')" disabled style="display:none;">
                                                <label class="form-check-label txtWhitecolor" id="limitBuyInputLevel" for="inlineCheckbox1"  style="display:none;">Buy</label>
                                            </div>
                                            <div class="form-check form-check-inline" id="limitSellId" >
                                                <input class="form-check-input "  type="checkbox" id="limitSellInput" value="2"  disabled onclick="enableLimitSell('derivative')" style="display:none;" >
                                                <label class="form-check-label txtWhitecolor" id="limitSellInputLevel" for="inlineCheckbox2" style="display:none;">Sell</label>
                                            </div>
                                        </div>
                                        <div id="coinDiv" style="display: none">
                                            <label v-cloak class="txtWhitecolor" id="coinId" for="" style="margin-bottom: 5px">@{{currency}}:</label>

                                            <input type="text" id="totalLimitCurrencyId" onkeyup="limitLength()" class="form-control mb-1" placeholder="" v-model="totalLimitCurrency">

                                            <span v-if="totalLimitCurrency.length" id="totalLimitCurrencyInputLength" class="input-count d-none">@{{totalLimitCurrency.length}}</span>

                                            <p class="d-none" id="calcLimitAmountId">@{{ calcLimitAmount }}</p>
                                        </div>
                                        <div class="form-group" id="tradeCoinForm" style="margin-top: 14px">
                                            <label v-cloak class="txtWhitecolor" for="">@{{currency}}:</label>
                                            <input type="number" id='coinval' class="form-control mb-1" placeholder=" " v-model="amount">
                                        </div>
                                        <div class="form-group">
{{--                                            <label v-cloak class="txtWhitecolor"  id="askboxlabel" for="" style="margin-top: 20px;">@{{currency}}:</label>--}}
{{--                                            <input type="number" class="form-control mb-1" id="askbox" placeholder="" v-model="amount">--}}
                                            <div id="askDiv" style="margin-bottom: 15px;">
                                                <small class="txtWhitecolor">ASK</small>
                                                <small v-cloak class="float-end text-danger cursor-pointer" id="askval" v-on:click="selectedPrice=latestAsk">
                                                    <i v-if="askincreased == 'askincreased'" class="fas fa-sort-up"></i>
                                                    <i v-else class="fas fa-sort-down"></i>
                                                    @{{latestAsk}}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div >
                                        <small v-cloak class="float-end text-success cursor-pointer">
                                            ~@{{derivativeRange}}
                                        </small>
                                        <input id="sliderRange" class="form-control" type="number"  min="1" value="1" max="100" oninput="rangeInput.value=sliderRange.value" v-model="derivativeValue"/><br>
                                        <input id="rangeInput" type="range" min="1" value="1" max="100" oninput="sliderRange.value=rangeInput.value" v-model="derivativeValue"/>
                                        <input id="derivativeType" type="hidden" name="derivativeType" />
                                    </div>
                                </div>
                                <div class="row mt-5" id="marketbutton" >
                                    <div class="col d-grid">
                                        <button id='derivativeNormalBuy'class="btn btn-block btn-success" :disabled="amount<=0 || derivativeRange > derivativeBalance" v-on:click="derivativeBuy">Derivative Buy</button>
                                        <button id='derivativeLimitBuy' class="btn btn-block btn-success" disabled style="display:none;" v-on:click="limitBuy">Derivative Buy</button>
                                    </div>
                                    <div class="col d-grid">
                                        <button  :load="logdata(amount)"id='derivativeNormalSell' class="btn btn-block btn-danger" :disabled="amount <= 0 || amount > leverageWalletAmount" v-on:click="derivativeSell">Derivative Sell</button>
                                        <button id='derivativeLimitSell' class="btn btn-block btn-danger" disabled  style="display:none;" v-on:click="limitSell">Derivative Sell</button>
                                    </div>
                                </div>

                            </div>
                        @else
                            <div class="card-body">
                            <h4 v-cloak class="txtHeadingColor">ORDER FORM: @{{currency}}</h4>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <select class="form-select selectClass" aria-label="Default select example" id="choseOrderType" onchange="choseOrderType()">
                                        <option value="0" selected>Market</option>
                                        <option value="1">Limit</option>
                                    </select>
                                    <div class="form-group">
                                        <input type="hidden" name="editId" id="editId" value="" >
                                        <div id="limitDiv"  style="display: none">
                                            <label class="txtWhitecolor" for="" style="margin-top: 10px;">Limit:</label>
                                            <div class="input-group" id="limitinputdiv">
                                                <input type="text" id="limitAmountId" onkeyup="limitLength()" class="form-control mb-1" placeholder="" v-model="limitAmount">
                                                <span v-if="limitAmount.length" id="limitAmountInputLength" class="input-count d-none">@{{limitAmount.length}}</span>
                                            </div>
                                        </div>
                                        <label class="txtWhitecolor d-none" for="" style="margin-top: 10px;">USD:</label>
                                        <div class="input-group d-none">
                                            <input type="text" class="form-control mb-1" placeholder="" readonly v-model="selectedPrice" style="cursor: not-allowed;">
                                        </div>
                                        <div id="totalAmountDiv">
                                            <label class="txtWhitecolor"  for="" style="margin-top: 10px;">Total:</label>
                                            <span v-cloak id="totalamountVal" class="text-muted form-control" style="">~@{{calcAmount}}</span>
                                        </div>
                                    </div>
                                    <div class="" style="margin-bottom: 15px;">
                                        <small class="txtWhitecolor">BID</small>
                                        <small v-cloak class="float-end text-success cursor-pointer" id="bidval" v-on:click="selectedPrice=latestBid">
                                            <i v-if="bidincreased == 'increased'" :load="logbid(bidincreased)"  class="fas fa-sort-up"></i>
                                            <i v-else class="fas fa-sort-down"></i>
                                            @{{latestBid}}
                                        </small>
                                    </div>
                                        <button id="normalBuy" class="btn btn-block btn-success" :disabled="amount<=0 || calcAmount > usdBalance" v-on:click="buy" style="width: 100%;">Exchange Buy</button>
                                        <button id="normalLimitBuyButton" class="btn btn-block btn-success" disabled v-on:click="limitBuy" style="display:none;width: 100%;margin-top:-5px;">Exchange Buy</button>
                                </div>
                                <div class="col">

                                    <div id="selectType" style="">
                                        <div class="form-check form-check-inline" id="limitBuyId" >
                                            <input class="form-check-input"  type="checkbox" id="limitBuyInput" value="1"   disabled  onclick="enableLimitBuy()" style="display:none;">
                                            <label class="form-check-label txtWhitecolor" id="limitBuyInputLevel" for="inlineCheckbox1" style="display:none;">Buy</label>
                                        </div>
                                        <div class="form-check form-check-inline" id="limitSellId" >
                                            <input class="form-check-input "  type="checkbox" id="limitSellInput" value="2" disabled onclick="enableLimitSell()"  style="display:none;">
                                            <label class="form-check-label txtWhitecolor" id="limitSellInputLevel" for="inlineCheckbox2"  style="display:none;">Sell</label>
                                        </div>
                                    </div>
                                    <div id="coinDiv" style="display: none">
                                        <label v-cloak class="txtWhitecolor" id="coinId" for="" style="margin-bottom: 5px">@{{currency}}:</label>
                                        <input type="text" id="totalLimitCurrencyId" onkeyup="limitLength()" class="form-control mb-1" placeholder="" v-model="totalLimitCurrency">
                                        <span v-if="totalLimitCurrency.length" id="totalLimitCurrencyInputLength" class="input-count d-none">@{{totalLimitCurrency.length}}</span>
                                        <p class="d-none" id="calcLimitAmountId">@{{ calcLimitAmount }}</p>
                                    </div>
                                    <div class="form-group" id="tradeCoinForm" style="margin-top: 14px">
                                        <label v-cloak class="txtWhitecolor" for="">@{{currency}}:</label>
                                        <input type="text" id='coinval' class="form-control mb-1" placeholder=" " v-model="amount">
                                    </div>
                                    <div id="askDiv" style="margin-bottom: 15px;">
                                        <small class="txtWhitecolor">ASK</small>
                                        <small v-cloak class="float-end text-danger cursor-pointer" id="askval" v-on:click="selectedPrice=latestAsk">
                                            <i v-if="askincreased =='increased'" :load="logask(askincreased)" class="fas fa-sort-up"></i>
                                            <i v-else class="fas fa-sort-down"></i>
                                            @{{latestAsk}}
                                        </small>
                                    </div>
                                        <button id="normalSell" class="btn btn-block btn-danger" :disabled="amount<=0 || amount > balance" v-on:click="sell" style="width: 100%;">Exchange Sell</button>
                                        <button id="normalLimitSellButton" class="btn btn-block btn-danger" disabled v-on:click="limitSell" style="display:none;width: 100%;">Exchange Sell</button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
                <div class="card mt-3" id="pendingTrade" style="display: none">
                    <div class="card">
                        <div class="card-body buyselldata">
                            <h4 v-cloak class="txtHeadingColor">Pending Trade: <span id="currcoin">@{{currency}}</span></h4>
                            <hr>
                            <table class="tables" id="tabledata">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 main-app-container">
                <div class="card">
                    <div class="card-body graphDiv" >
                        <div class="text-center title mb-2 txtHeadingColor"></div>

                        <div id='buttonrow' class="chart-top-row">
                            <span><h6 v-cloak>@{{currency}}/USD</h6></span>
                            <span class="interval" id='1m' v-on:click="getChartData('interval','1m')">1m</span>
                            <span class="interval" id="15m" v-on:click="getChartData('interval','15m')">15m</span>
                            <span class="interval" id="30m" v-on:click="getChartData('interval','30m')">30m</span>
                            <span class="interval" id="1h"  v-on:click="getChartData('interval','1h')">1h</span>
                            <span class="interval" id="6h"  v-on:click="getChartData('interval','6h')">6h</span>
                            <span style="margin-left: 10px">BitcWay</span>
                        </div>
                        <div id="chart" style="height:465px; display: block; color: white; background-color: #171b26">
                            <div class="loader" style="display: none">

                            </div>
                        </div>

                        <div id='buttonrow' class="chart-top-row">
                            <span class="interval border-removal" id="3Y" v-on:click="getChartData('range','3Y')">3Y</span>
                            <span class="interval border-removal" id="1Y" v-on:click="getChartData('range', '1Y')">1Y</span>
                            <span class="interval border-removal" id="3M" onclick="getChartData('range', this.id)">3M</span>
                            <span class="interval border-removal" id="1M" onclick="getChartData('range', this.id)">1M</span>
                            <span class="interval border-removal" id="7D" onclick="getChartData('range', this.id)">7D</span>
                            <span class="interval border-removal" id="3D" onclick="getChartData('range', this.id)">3D</span>
                            <span class="interval border-removal" id="1D" onclick="getChartData('range', this.id)">1D</span>
                            <span class="interval border-removal" id="6h" onclick="getChartData('range', this.id)">6h</span>
                            <span class="interval border-removal" id="1h" onclick="getChartData('range', this.id)">1h</span>
                        </div>
                        <div id="tradingview_f7648" class="d-none"></div>
                    </div>
                </div>
                <div class="card mt-3 orderBookDiv">
                    <div class="card-body ">
                        <h4 class="txtHeadingColor" v-cloak>Order Book: @{{currency}}/USD</h4>
                        <hr>
                        <div class="">
                            <div class="row">
                                <div class="col orders">
                                    <table class="table">
                                        <thead>
                                            <th class="txtWhitecolor">Count</th>
                                            <th class="txtWhitecolor">Amount</th>
                                            <th class="txtWhitecolor">Total</th>
                                            <th class="txtWhitecolor">Price</th>
                                        </thead>
                                        {{--<tbody style="background-color: #1142304d;">--}}
                                        <tbody>
                                            <tr v-for="(item, index) in bids" :class="{ 'coloredbid': bidsprev.length > 0 && bidsprev[index][2] != bids[index][2] }">
                                                <td v-cloak class="txtWhitecolor">@{{item[1]}}</td>
                                                <td v-cloak class="txtWhitecolor">@{{item[2].toFixed(4)}}</td>
                                                <td v-cloak class="txtWhitecolor">@{{(itemSetBids(index)).toFixed(4)}}</td>
                                                <td v-cloak class="txtWhitecolor">@{{item[0]}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col orders">
                                    <table class="table">
                                        <thead>
                                            <th class="txtWhitecolor">Price</th>
                                            <th class="txtWhitecolor">Total</th>
                                            <th class="txtWhitecolor">Amount</th>
                                            <th class="txtWhitecolor">Count</th>
                                        </thead>
                                        {{--<tbody style="background-color:#942f3e6e; ">--}}
                                        <tbody>
                                            <tr v-for="(item, index) in asks" :class="{ 'coloredask': asksprev.length > 0 && asksprev[index][2] != asks[index][2] }">
                                                <td v-cloak class="txtWhitecolor">@{{item[0]}}</td>
                                                <td v-cloak class="txtWhitecolor">@{{(Math.abs(itemSetAsks(index))).toFixed(4)}}</td>
                                                <td v-cloak class="txtWhitecolor">@{{(Math.abs(item[2])).toFixed(4)}}</td>
                                                <td v-cloak class="txtWhitecolor">@{{item[1]}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        function limitLength() {
            var buyCheckBox = document.getElementById("limitBuyInput");
            var sellCheckBox = document.getElementById("limitSellInput");
            let cLimit = parseInt($('#totalLimitCurrencyInputLength').html());
            let aLimit = parseInt($('#limitAmountInputLength').html());
            if (aLimit > 0 && cLimit > 0){
                buyCheckBox.disabled  = false;
                sellCheckBox.disabled = false;
            }

        }
    </script>
    <script>
        function choseOrderType( limitype) {
            let type                  = $("#choseOrderType").val();
            var buyCheckBoxLevel      = document.getElementById("limitBuyInputLevel");
            var sellCheckBoxLevel     = document.getElementById("limitSellInputLevel");
            var buyCheckBox           = document.getElementById("limitBuyInput");
            var sellCheckBox          = document.getElementById("limitSellInput");
            var limitDiv              = document.getElementById("limitDiv");
            var selectType            = document.getElementById("selectType");
            var coinDiv               = document.getElementById("coinDiv");
            var tradeCoinForm         = document.getElementById("tradeCoinForm");
            var askDiv                = document.getElementById("askDiv");
            var totalAmountDiv        = document.getElementById("totalAmountDiv");
            var coinId                = document.getElementById("coinId");
            var normalSellButton      = document.getElementById("normalSell");
            var normalBuyButton       = document.getElementById("normalBuy");
            var normalLimitSellButton = document.getElementById("normalLimitSellButton");
            var normalLimitBuyButton  = document.getElementById("normalLimitBuyButton");
            var pendingTrade          = document.getElementById("pendingTrade");
            // var bidbox=document.getElementById("bidbox");
            // var askbox=document.getElementById("askbox");
            // var askboxlabel=document.getElementById("askboxlabel");
            if (type == 1){
                $(".deleteEnabled").show();
                $(".deleteDisabled").hide();
                pendingTrade.style.display      = "block";
                coinId.style.marginTop          = "5px";
                askDiv.style.marginTop          = "14px";
                buyCheckBox.disabled            = true;
                sellCheckBox.disabled           = true;
                buyCheckBox.style.display       = "block";
                sellCheckBox.style.display      = "block";
                limitDiv.style.display          = "block";
                coinDiv.style.display           = "block";
                tradeCoinForm.style.display     = "none";
                totalAmountDiv.style.display    = "none";
                buyCheckBoxLevel.style.display  = "block";
                sellCheckBoxLevel.style.display = "block";
                selectType.style.marginBottom   = "15px";

                $('#totalLimitCurrencyId').val("");
                $('#limitAmountId').val("");

                if (limitype === "derivative"){
                    document.getElementById("derivativeNormalSell").style.display = 'none';
                    document.getElementById("derivativeNormalBuy").style.display  = 'none';
                    document.getElementById("derivativeLimitBuy").style.display   = 'block';
                    document.getElementById("derivativeLimitSell").style.display  = 'block';
                    $('#limitAmountId').val("");
                    $('#totalLimitCurrencyId').val("");
                    // $('#limitAmountInputLength').html("");
                    // $('#totalLimitCurrencyInputLength').html("");

                }
                else{
                    normalLimitBuyButton.style.display  = "block";
                    normalLimitSellButton.style.display = "block";
                    normalSellButton.style.display      = "none";
                    normalBuyButton.style.display       = "none";
                }

            }
            else
            {
                pendingTrade.style.display      = "none";
                buyCheckBox.style.display       = "none";
                sellCheckBox.style.display      = "none";
                buyCheckBox.disabled            = true;
                buyCheckBox.checked             = false;
                sellCheckBox.disabled           = true;
                sellCheckBox.checked            = false;
                limitDiv.style.display          = "none";
                coinDiv.style.display           = "none";
                selectType.style.marginBottom   = "15px";
                buyCheckBoxLevel.style.display  = "none";
                sellCheckBoxLevel.style.display = "none";
                totalAmountDiv.style.display    = "block";
                tradeCoinForm.style.display     = "block";

                //document.getElementsByClassName("editdisabled").style.display='block'
                $(".deleteDisabled").show();
                $(".deleteEnabled").hide();

                if (limitype === "derivative"){
                    document.getElementById("derivativeNormalSell").style.display = 'block';
                    document.getElementById("derivativeNormalBuy").style.display  = 'block';
                    document.getElementById("derivativeLimitBuy").style.display   = 'none';
                    document.getElementById("derivativeLimitSell").style.display  = 'none';

                }
                else
                {
                    normalLimitBuyButton.style.display  = "none";
                    normalLimitSellButton.style.display = "none";
                    normalSellButton.style.display      = "block";
                    normalBuyButton.style.display       = "block";
                }

            }
        }
    </script>
    <script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>
    <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script type="text/javascript">
        new TradingView.widget({
            "width": "auto",
            "height": 515,
            "symbol": "BTCUSD",
            "timezone": "Etc/UTC",
            "theme": "dark",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "withdateranges": true,
            "range": "YTD",
            "hide_side_toolbar": true,
            "allow_symbol_change": true,
            "details": false,
            "hotlist": false,
            "calendar": false,
            "container_id": "tradingview_f7648"
        });
    </script>
    <script>
        $(".page-wrapper").removeClass("toggled");
    </script>
    <script>
        // var dumCoin = ["tOMGC:USD", 3.00, 3.01111, 3.411, 311.1100000, -0.0999, -0.000222, 301.00111, 115.88027091, 372.28, 356];
        const socket = io('http://192.144.82.234:3000/');
        //const socket = io('http://130.51.180.11:3000/');
        // const socket = io('http://127.0.0.1:3005/');
        // showLoader('Loading...');
        // const socket = io('http://192.168.1.29:3000/');
        let loaded = false;
        //showLoader("Loading");
        socket.on('trackers', (trackers) => {
            Home.trackers   = trackers.trackers;
            let volumeIndex = Home.trackers;
            for (let i = 0; i < volumeIndex.length; i++)
            {
                let volume = trackers.trackers[i][7] * trackers.trackers[i][8];
                trackers.trackers[i][11] = volume;
            }
            // Home.trackers.push(dumCoin);
            let coinData = Home.trackers;

            for (let i = 0; i < coinData.length; i++ ){
                if (coinData[i][0] == "tADAUSD" ){
                    Home.trackers[i][0] = "tMABUSD";
                }
            }

            if(loaded == false){
                hideLoader();
                Home.selectedItem = Home.trackers[0];
                Home.getChartData();
                setInterval(function(){
                    Home.getOrders();
                }, 2000);
                loaded = true;
            }
        })

        let w               = null;
        var totalSellAmount = 0;
        var currencies      = <?php echo json_encode($currency); ?>;
        // const OrderBook Start
        $(document).ready(function() {
            var item = ["tBTCUSD"];
            getInitialOrder("tBTCUSD");
            Home.setCurrency(item);
        });
        // const OrderBook End

        let getInitialOrder = function (currency) {
            let CurrencyApi = ' ';
            if(currency == undefined){
                CurrencyApi = 'https://api.bitfinex.com/v2/book/tBTCUSD/P0';
            } else {
                CurrencyApi = 'https://api.bitfinex.com/v2/book/'+currency+'/P0';
            }
            CurrencyApi = 'http://bitc-way.com/get-order';
            axios.get(CurrencyApi, {params: {currency: currency}})
                .then(response => {
                    items = response.data;
                    if(items){
                        if(items.length > 3){
                            bids = [];
                            asks = [];
                            items.forEach(function(item){
                                if(item[2] > 0){
                                    bids.push(item);
                                }else{
                                    asks.push(item);
                                }
                            });
                            Home.bids = bids;
                            Home.asks = asks;
                        }else{
                            item = items[1];
                            if (item[2] > 0) {
                                if (Home.bids.length > 25) Home.bids.pop();
                                Home.bids = [item].concat(Home.bids);
                            } else if (item[2] < 0) {
                                if (Home.asks.length > 25) Home.asks.pop();
                                Home.asks = [item].concat(Home.asks);
                            }
                        }
                        /***** code for bid price arrow ***/
                            //Home.latestBid = Home.bids[0][0];
                        var getlatestval = document.getElementById("bidval").textContent;
                        if(getlatestval === 0){
                            Home.latestBid = Home.bids[0][0];
                        }
                        else{
                            Home.latestBid = getlatestval;
                        }

                        Home.bidIncrease = Home.bids[0][0] > Home.bids[1][0];
                        //Home.bidIncrease = Home.latestBid>Home.bids[0][0] > Home.bids[1][0];
                        if (Home.bids[0][0] > Home.latestBid)
                        {
                            //Home.bidIncrease=Home.bids[0][0] > Home.latestBid;
                            Home.bidincreased = 'increased';
                        }
                        else
                        {
                            Home.bidincreased = 'decreased';
                        }
                        /*** end ****/

                        Home.latestBid = Home.bids[0][0];
                        title = Home.bidIncrease ? '▲' : '▼';

                        /*** code for ask price arrow**/
                        document.title = title + " " + Home.latestBid + " " + Home.currency + "/USD";
                        var getaskval= document.getElementById('askval').textContent;
                        if(getaskval === 0)
                        {
                            Home.latestAsk = Home.bids[0][0];
                        }
                        else
                        {
                            Home.latestAsk=getaskval;
                        }
                        if (Home.asks[0][0] > Home.latestAsk)
                        {
                            Home.askincreased = 'increased';
                        }
                        else
                        {
                            Home.askincreased = 'decreased';
                        }
                        /*** end****/

                        Home.latestAsk = Home.asks[0][0];
                        Home.askIncrease = Home.asks[0][0] > Home.asks[1][0];
                    }
                })
                .catch(error => "404")
        }

        let getOrders = function(currency){
            if (Home.lastcurrency != currency) {
                Home.lastcurrency = currency;
            }
            if (w) w.close();
            w = new WebSocket('wss://api-pub.bitfinex.com/ws/2');
            w.onmessage = function(msg){
                items   = JSON.parse(msg.data);
                if (items.event) return;
                var title;
                Home.bidsprev = Home.bids;
                Home.asksprev = Home.asks;
                if (items[1]) {
                    if (items[1].length > 3) {
                        bids = [];
                        asks = [];
                        items[1].forEach(function (item) {
                            if (item[2] > 0) {
                                bids.push(item);
                            } else {
                                asks.push(item);
                            }
                        });
                        Home.bids = bids;
                        Home.asks = asks;
                    } else {
                        item = items[1];
                        if (item[2] > 0) {
                            if (Home.bids.length > 25) Home.bids.pop();
                            Home.bids = [item].concat(Home.bids);
                        } else if (item[2] < 0) {
                            if (Home.asks.length > 25) Home.asks.pop();
                            Home.asks = [item].concat(Home.asks);
                        }
                    }
                    /***** code for bid price arrow ***/
                    //Home.latestBid = Home.bids[0][0];
                    var getlatestval = document.getElementById("bidval").textContent;
                    if(getlatestval === 0){
                        Home.latestBid = Home.bids[0][0];
                    }
                    else{
                        Home.latestBid = getlatestval;
                    }

                    Home.bidIncrease = Home.bids[0][0] > Home.bids[1][0];
                    //Home.bidIncrease = Home.latestBid>Home.bids[0][0] > Home.bids[1][0];
                    if (Home.bids[0][0] > Home.latestBid)
                    {
                        //Home.bidIncrease=Home.bids[0][0] > Home.latestBid;
                        Home.bidincreased = 'increased';
                    }
                    else
                    {
                        Home.bidincreased = 'decreased';
                    }
                    /*** end ****/

                    Home.latestBid = Home.bids[0][0];
                    title          = Home.bidIncrease ? '▲' : '▼';

                    /*** code for ask price arrow**/
                    document.title = title + " " + Home.latestBid + " " + Home.currency + "/USD";
                    var getaskval  = document.getElementById('askval').textContent;
                    if(getaskval === 0)
                    {
                        Home.latestAsk = Home.bids[0][0];
                    }
                    else
                    {
                        Home.latestAsk = getaskval;
                    }
                    if (Home.asks[0][0] > Home.latestAsk)
                    {
                        Home.askincreased = 'increased';
                    }
                    else
                    {
                        Home.askincreased = 'decreased';
                    }
                    /*** end****/

                    Home.latestAsk   = Home.asks[0][0];
                    Home.askIncrease = Home.asks[0][0] > Home.asks[1][0];

                }
            }

            let msg = JSON.stringify({ 
                event   : 'subscribe',
                channel : 'book',
                freq    : 'F1',
                symbol  : currency
            })

            w.onopen = function(event){
                w.send(msg);
            }
        };

        let Home = new Vue({
            el: '.trade',
            data: {
                message: 'Hello Vue!',
                trackers: [],
                chart: null,
                selectedItem: [],
                buyAmount: 0,
                sellAmount: 0,
                amount: 0,
                balance: 0,
                usdBalance: '{{Auth::user()->balance}}',
                derivativeBalance:'{{Auth::user()->derivative}}',
                leverageWalletAmount: 0,
                bids: [],
                asks: [],
                bidsprev: [],
                asksprev: [],
                latestBid: 0,
                bidIncrease: false,
                latestAsk: 0,
                askIncrease: false,
                selectedPrice: '',
                derivativeValue:'1',
                limitAmount:'',
                totalLimitCurrency: '',
                bidincreased:  '',
                askincreased: '',
                lastcurrency: 'tBTCUSD'
            },
            mounted() {

            },
            computed:{
                currency(){
                    let currency = this.selectedItem[0]?this.selectedItem[0]:'tBTCUSD';
                    currency = this.splitCurrency(currency);
                    //leverageWalletAmount = currencies['BTC']['amount'];
                    return currency;
                },
                calcAmount(){
                    return this.buyAmount*this.selectedItem[7];
                },
                calcSellAmount(){
                    return this.selectedItem[7]*this.sellAmount;
                },
                calcAmount(){
                    return this.amount*this.selectedPrice;
                },
                derivativeRange(){
                    if(document.getElementById("limitAmountId").value !=""){
                        var limitval=document.getElementById("limitAmountId").value;
                        return (this.limitAmount)/this.derivativeValue;
                    }
                    else{
                        return (this.amount*this.selectedPrice)/this.derivativeValue;
                    }
                },
                calcLimitAmount(){
                    return this.totalLimitCurrency*this.limitAmount;
                },
                volumeArr(){

                }
            },
            methods: {
                logbid(bidincreased) {
                },
                logask(askincreased) {
                },
               /* log(leverageWalletAmount) {
                },*/
                logdata(amount) {
                    this.amount               = amount
                    var currcoin              = this.currency;
                    if(typeof currencies[currcoin] === 'undefined') {
                        leverageWalletAmount = 0;
                    }
                    else {
                        leverageWalletAmount      = currencies[currcoin]['amount']
                    }
                    this.leverageWalletAmount = leverageWalletAmount;
                },
                itemSetBids(index) {
                    let result = 0;
                    for (let i = 0; i <= index ; i++){
                        result += this.bids[i][2];
                    }
                    return result;
                },
                itemSetAsks(index) {
                    let result = 0;
                    for (let i = 0; i <= index ; i++){
                        result += this.asks[i][2];
                    }
                    return result;
                },
                splitCurrency(currency){
                    currency = currency.split('t').join('');
                    currency = currency.split('USD').join('');
                    if(currency.length>4){
                        currency = currency.substr(0,4);
                    }
                    return currency;
                },
                setCurrency(item){
                    let coin    = item[0];
                    let symbolx = coin.substr(1);
                    getInitialOrder('t'+symbolx);

                    let currentCoin = this.splitCurrency(symbolx);
                    get_buy_sell_data(currentCoin);
                    if(currentCoin == "MAB") {
                        currentCoin = "ADA";
                        symbolx     = "ADAUSD";
                    }
                        new TradingView.widget({
                            "width": "auto",
                            "height": 515,
                            "symbol": symbolx,
                            "timezone": "Etc/UTC",
                            "theme": "dark",
                            "style": "1",
                            "locale": "en",
                            "toolbar_bg": "#f1f3f6",
                            "enable_publishing": false,
                            "withdateranges": true,
                            "range": "YTD",
                            "hide_side_toolbar": true,
                            "allow_symbol_change": true,
                            "details": false,
                            "hotlist": false,
                            "calendar": false,
                            "container_id": "tradingview_f7648"
                        });
                    this.selectedItem = item;
                    this.getChartData();

                    if(currencies[currentCoin]){
                        totalSellAmount = currencies[currentCoin]['amount'];
                        // totalSellAmount = currencies['BTC']['amount'];
                    }
                    else{
                        totalSellAmount = 0;
                    }
                    this.leverageWalletAmount = totalSellAmount;
                },
                setCurrencyPrev(item){
                    this.selectedItem = item;
                    this.getChartData();
                },
                getChartData(type,value){
                    //alert('I am here');
                    if (type!='' && type ==='interval'){
                        var interval_value = value;
                    }
                    else{
                       var interval_value="";

                    }
                    if (type ==='range'){
                        //alert(value);
                        var range_value = value;
                        var current_date = new Date();
                        var year = current_date.getFullYear();
                        var month = current_date.getMonth();
                        var day = current_date.getDate();

                        if (range_value === '3Y'){

                            var end_date = new Date(year - 3, month, day);
                           var enddate = current_date.getTime();
                           var startdate=end_date.getTime();


                        }
                        if (range_value === '1Y'){
                            var end_date = new Date(year - 1, month, day);
                            var enddate = current_date.getTime();
                            var startdate=end_date.getTime();
                        }
                        if (range_value == '3M'){

                        }
                        if (range_value == '7D'){

                        }
                        if (range_value == '3D'){

                        }
                        if (range_value == '1D'){

                        }
                        if (range_value == '6h'){

                        }
                        if (range_value == '1h'){

                        }
                    }
                    else{
                        var start ="";
                        var end   =""

                    }




                    let that = this;
                    let currency = that.selectedItem[0];
                    // showLoader('Loading ...');
                    axios.get('{{route("user-get-chart-data")}}', {params: {currency: currency, user_currency: that.currency, interval:interval_value, start:startdate, end:enddate}})
                    .then(function (response) {
                        let chartData = [];
                        if(response.data.status){
                            that.balance = response.data.balance;
                            response.data.chartData.forEach(function(item){
                                // const dateObject = new Date(item[0]);
                                // const humanDateFormat = dateObject.toLocaleString();
                                // var getdate= humanDateFormat.split(" ");
                                // var datevalue= formatDate(getdate[0]);
                                let newChartData = { time: item[0]/1000 , open: item[1], high: item[3], low: item[4], close: item[2]};
                                chartData.push(newChartData);

                            });
                            if(currency == 'tMABUSD'){
                                var coindata=[
                                    { "time":1621604,"open": 39252, "high":39839,"low":39252, "close":39821.73222635},
                                    { "time":1621518,"open": 39689, "high":39037,"low":631.40783786, "close":39252}
                                ]

                                //chartData.push(coindata);
                            }
                            setTimeout(function(){
                                that.drawChart(chartData);
                            }, 100);
                        }

                    })
                    .catch(function (error) {
                    })
                    .then(function () {
                        hideLoader();
                    });
                },
                drawChart(data){
                    let that = this;
                    if(that.chart) {
                        that.chart.remove();
                    }
                //     var chartElement="<div style='background-color:#171b26;'>"
                //     +"<button>1D</button>"
                //     +"<button>1W</button>"
                //     +"<button>1M</button>"
                // "</div>";
                //     $('#chart').empty();
                    that.chart = LightweightCharts.createChart(document.getElementById('chart'), {
                        layout: {
                            backgroundColor: '#171b26',
                            textColor: 'rgba(255, 255, 255, 0.9)',
                        },
                        grid: {
                            vertLines: {
                                color: 'rgba(197, 203, 206, 0.5)',
                            },
                            horzLines: {
                                color: 'rgba(197, 203, 206, 0.5)',
                            },
                        },
                        crosshair: {
                            mode: LightweightCharts.CrosshairMode.Normal,
                        },
                        rightPriceScale: {
                            borderColor: 'rgba(197, 203, 206, 0.8)',
                        },
                        timeScale: {
                            visible:true,
                            timeVisible:true,
                            secondsVisible:false,
                            borderColor: 'rgba(197, 203, 206, 0.8)',
                        },
                    });
                    var volumeSeries = that.chart.addHistogramSeries({
                        color: '#26a69a',
                        priceFormat: {
                            type: 'volume',
                        },
                        priceScaleId: '',
                        scaleMargins: {
                            top: 0.8,
                            bottom: 0,
                        },
                    });

                    var candleSeries = that.chart.addCandlestickSeries({
                        //upColor: 'rgba(255, 144, 0, 1)',
                        //downColor: '#000',
                        //borderDownColor: 'rgba(255, 144, 0, 1)',
                        //borderUpColor: 'rgba(255, 144, 0, 1)',
                        //wickDownColor: 'rgba(255, 144, 0, 1)',
                        //wickUpColor: 'rgba(255, 144, 0, 1)',
                    });

                    data.sort((a, b) => (a.time > b.time) ? 1 : -1);
                    candleSeries.setData(data);
                    // volumeSeries.setData([
                    //     { time: '2021-05-19', value: 1621404000000, color: 'rgba(0, 150, 136, 0.8)' },
                    //     { time: '2021-04-22', value: 21737523.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     { time: '2021-03-23', value: 29328713.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     { time: '2021-02-24', value: 37435638.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     { time: '2021-01-25', value: 25269995.00, color: 'rgba(255,82,82, 0.8)' },
                    //     { time: '2021-10-26', value: 24973311.00, color: 'rgba(255,82,82, 0.8)' },
                    //     { time: '2021-10-29', value: 22103692.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     { time: '2021-09-30', value: 25231199.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     { time: '2021-10-31', value: 24214427.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2021-11-01', value: 22533201.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2021-11-02', value: 14734412.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2021-11-05', value: 12733842.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-06', value: 12371207.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-07', value: 14891287.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-08', value: 12482392.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-09', value: 17365762.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-12', value: 13236769.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-13', value: 13047907.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-11-14', value: 18288710.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-15', value: 17147123.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-16', value: 19470986.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-19', value: 18405731.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-20', value: 22028957.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-11-21', value: 18482233.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-11-23', value: 7009050.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-11-26', value: 12308876.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-27', value: 14118867.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-11-28', value: 18662989.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-11-29', value: 14763658.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-11-30', value: 31142818.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-12-03', value: 27795428.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-04', value: 21727411.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-06', value: 26880429.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-07', value: 16948126.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-10', value: 16603356.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-12-11', value: 14991438.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-12-12', value: 18892182.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-13', value: 15454706.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-14', value: 13960870.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-17', value: 18902523.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-18', value: 18895777.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-19', value: 20968473.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-12-20', value: 26897008.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-21', value: 55413082.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-24', value: 15077207.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2018-12-26', value: 17970539.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-12-27', value: 17530977.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-12-28', value: 14771641.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2018-12-31', value: 15331758.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-02', value: 13969691.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-01-03', value: 19245411.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-04', value: 17035848.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-07', value: 16348982.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-08', value: 21425008.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-09', value: 18136000.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-01-10', value: 14259910.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-11', value: 15801548.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-14', value: 11342293.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-15', value: 10074386.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-16', value: 13411691.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-01-17', value: 15223854.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-01-18', value: 16802516.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-22', value: 18284771.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-01-23', value: 15109007.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-24', value: 12494109.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-01-25', value: 17806822.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-01-28', value: 25955718.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-01-29', value: 33789235.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-01-30', value: 27260036.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-01-31', value: 28585447.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-02-01', value: 13778392.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-02-04', value: 15818901.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-02-05', value: 14124794.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-02-06', value: 11391442.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-02-07', value: 12436168.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-02-08', value: 12011657.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-02-11', value: 9802798.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-02-12', value: 11227550.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-02-13', value: 11884803.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-02-14', value: 11190094.00, color: 'rgba(255,82,82, 0.8)' },
                    //     // { time: '2019-02-15', value: 15719416.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-02-19', value: 12272877.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //     // { time: '2019-02-20', value: 11379006.00, color: 'rgba(0, 150, 136, 0.8)' },
                    //      //{ time: '2019-02-21', value: 14680547.00, color: 'rgba(0, 150, 136, 0.8)' },
                    // ]);
                },
                buy(){

                    let that = this;

                    if(that.calcAmount <= 0 || that.calcAmount > that.usdBalance) {
                        toastr.error('Invalid amount !!');
                        return false;
                    }
                    showLoader('Processing...');
                    axios.post('{{route("user-trade-buy")}}', {
                        currency: that.currency,
                        buyAmount: that.amount,
                        calcBuyAmount: that.calcAmount,
                        derivativeUserMoney: 0,
                        derivativeLoan: 0

                    })
                    .then(function (response) {
                        if(response.data.status){
                            toastr.success('Buy successfull');
                            //window.location.href = '{{route("user-wallets")}}';
                            //window.location.href = '{{route("user-trade")}}';
                            hideLoader();
                            get_buy_sell_data(that.currency);
                            return false;
                        }
                        toastr.error('Error occured !!');
                    })
                    .catch(function (error) {
                        toastr.error('Error occured !!');
                    });
                },
                sell(){
                    let that = this;
                    /*alert(that.calcAmount);
                    alert(that.balance);*/

                    if(that.calcAmount <= 0 || that.amount > that.balance) {
                        toastr.error('Invalid amount !!');
                        return false;
                    }

                    showLoader('Processing...');
                    axios.post('{{route("user-trade-sell")}}', {
                        currency        : that.currency,
                        sellAmount      : that.amount,
                        calcSellAmount  : that.calcAmount
                    })
                    .then(function (response) {
                        if(response.data.status){
                            toastr.success('Sell successfull');
                            {{--window.location.href = '{{route("user-wallets")}}';--}}
                            //window.location.href = '{{route("user-trade")}}';
                            hideLoader();
                            get_buy_sell_data(that.currency);
                            return false;
                        }
                        toastr.error('Error occured !!');
                    })
                    .catch(function (error) {
                        toastr.error('Error occured !!');
                    });
                },
                derivativeBuy(){

                    let that = this;
                    if(that.calcAmount <= 0 || that.calcAmount > that.usdBalance) {
                        toastr.error('Invalid amount !!');
                        return false;
                    }
                    showLoader('Processing...');
                    axios.post('{{route("user-trade-buy")}}', {
                    currency                : that.currency,
                        buyAmount           : that.amount,
                        calcBuyAmount       : that.calcAmount,
                        leverage            : $("#sliderRange").val(),
                        derivativeUserMoney : that.derivativeRange
                    })
                        .then(function (response) {
                            if(response.data.status){
                                toastr.success('Buy successfull');
                                {{--window.location.href = '{{route("user-wallets")}}';--}}
                                //window.location.href = '{{route("user-trade-derivative")}}';
                                $('#limitAmountId').val("");
                                $('#totalLimitCurrencyId').val("");
                                hideLoader();
                                get_buy_sell_data(that.currency);
                                return false;
                            }
                            toastr.error('Error occured !!');
                        })
                        .catch(function (error) {
                            toastr.error('Error occured !!');
                        });
                },
                derivativeSell(){
                    let that = this;
                    if(that.calcAmount <= 0 || that.amount > that.leverageWalletAmount) {
                        toastr.error('Invalid amount !!');
                        return false;
                    }

                    showLoader('Processing...');
                    axios.post('{{route("user-trade-sell")}}', {
                        currency       : that.currency,
                        sellAmount     : that.amount,
                        calcSellAmount : that.calcAmount,
                        derivativeType : '0'
                    })
                        .then(function (response) {
                            if(response.data.status){
                                toastr.success('Sell successfull');
                                {{--window.location.href = '{{route("user-wallets")}}';--}}
                                //window.location.href = '{{route("user-trade-derivative")}}';
                                hideLoader();
                                get_buy_sell_data(that.currency);
                                return false;
                            }
                                toastr.error('Error occured !!');
                                window.location.reload();
                        })
                        .catch(function (error) {
                                toastr.error('Error occured !!');
                                window.location.reload();
                        });
                },
                limitBuy(){
                    let sellCheckBox   = document.getElementById("limitSellInput");
                    //for Derivative buy/sell
                    var chk_derivative = document.getElementById("rangeInput");
                    // var check_id = document.getElementById("editId");
                    // if(check_id){
                    //     var idval=check_id.value;
                    // }
                    // else{
                    //     var idval="";
                    // }
                    if(chk_derivative){
                        var derivative = chk_derivative.value;
                    }
                    else{
                        var derivative = 0;
                    }

                    let buyCheckedValue = $('#limitBuyInput:checked').val();
                    if (buyCheckedValue == 1){
                        sellCheckBox.disabled = true;
                        let that = this;
                        //remove commented code to check invalid amount
                        // let calcLimit = that.calcLimitAmount;
                        // if(that.calcLimitAmount <= 0 || that.calcLimitAmount > that.usdBalance) {
                        //     toastr.error('Invalid limit amount !!');
                        //     return false;
                        // }
                        showLoader('Processing...');
                        axios.post('{{route("user-limit-buy")}}', {
                            currency          : that.currency,
                            limitType         : 1,
                            priceLimit        : that.limitAmount,
                            currencyAmount    : that.totalLimitCurrency,
                            transactionStatus : 1,
                            derivative        : derivative,
                            //id:idval

                        })
                            .then(function (response) {
                                if(response.data.status){
                                    toastr.success('Limit Buy successfull');
                                    {{--window.location.href = '{{route("user-wallets")}}';--}}
                                    hideLoader();
                                    sellCheckBox.disabled = false;
                                    get_buy_sell_data(that.currency);

                                    //if derivative buy/sell
                                    {{--if(chk_derivative){--}}
                                    {{--    window.location.href = '{{route("user-trade-derivative")}}';--}}
                                    {{--}--}}
                                    {{--else{--}}
                                    {{--    window.location.href = '{{route("user-trade")}}';--}}
                                    {{--}--}}

                                    return false;
                                }
                                toastr.error('Error occured(Limit) !!');
                            })
                            .catch(function (error) {
                                toastr.error('Error occured(Limit) !!');
                            });
                    }

                },
                limitSell(){
                    let buyCheckBox    = document.getElementById("limitBuyInput");
                    var chk_derivative = document.getElementById("rangeInput");
                    if(chk_derivative){
                        var derivative = chk_derivative.value;
                    }
                    else{
                        var derivative = 0;
                    }
                    let sellCheckedValue = $('#limitSellInput:checked').val();
                    if (sellCheckedValue == 2){
                        buyCheckBox.disabled = true;
                        let that = this;

                        //remove commented code to check invalid amount
                        // if(that.calcLimitAmount <= 0 || that.totalLimitCurrency > that.balance) {
                        //     toastr.error('Invalid Limit Sell amount !!');
                        //     return false;
                        // }

                        showLoader('Processing...');
                        axios.post('{{route("user-limit-sell")}}', {
                            currency            : that.currency,
                            limitType           : 2,
                            priceLimit          : that.limitAmount,
                            currencyAmount      : that.totalLimitCurrency,
                            transactionStatus   : 1,
                            derivative:derivative
                        })
                            .then(function (response) {
                                if(response.data.status){
                                    toastr.success('Limit Sell successfull');
                                    {{--window.location.href = '{{route("user-wallets")}}';--}}
                                    buyCheckBox.disabled = false;
                                    hideLoader();
                                    get_buy_sell_data(that.currency);
                                    {{--if(chk_derivative){--}}
                                    {{--    window.location.href = '{{route("user-trade-derivative")}}';--}}
                                    {{--}--}}
                                    {{--else{--}}
                                    {{--    window.location.href = '{{route("user-trade")}}';--}}
                                    {{--}--}}

                                    return false;
                                }
                                toastr.error('Error occured(Limit Sell) !!');
                            })
                            .catch(function (error) {
                                toastr.error('Error occured(Limit Sell) !!');
                            });
                    }
                },

                getOrders(){
                    let that = this;
                    let currency = that.selectedItem[0];
                    getOrders(currency);
                    // getInitialOrder(currency);
                },
            },
            beforeMount(){
            },
        });


        function enableLimitBuy(type){
            if(type === 'derivative'){
                var bt      = document.getElementById('derivativeLimitBuy');
                bt.disabled = document.getElementById('limitBuyInput').checked ? false : true;
                document.getElementById('limitSellInput').checked = false;
                document.getElementById('derivativeLimitSell').disabled = true;
            }
            else{
                var bt      = document.getElementById('normalLimitBuyButton');
                bt.disabled = document.getElementById('limitBuyInput').checked ? false : true;
                document.getElementById('limitSellInput').checked = false;
                document.getElementById('normalLimitSellButton').disabled = true;
            }
        };
       function enableLimitSell(type){
           if(type === 'derivative'){
               var bt      = document.getElementById('derivativeLimitSell');
               bt.disabled = document.getElementById('limitSellInput').checked ? false : true;
               document.getElementById('limitBuyInput').checked = false;
               document.getElementById('derivativeLimitBuy').disabled = true;
           }
           else{
               var bt      = document.getElementById('normalLimitSellButton');
               bt.disabled = document.getElementById('limitSellInput').checked ? false : true;
               document.getElementById('limitBuyInput').checked = false;
               document.getElementById('normalLimitBuyButton').disabled = true;
           }
        };
       function limitDelete(id) {
           var idval    = $("#editId").val(id);
           var val_id   = $("#editId").val();
           var currcoin = $("#currcoin").text();
           //alert(val_id);
           var confirm_message = confirm("Do you want to delete this Trade?");
           if (confirm_message == true) {
               showLoader('Processing...');
                   axios.post('{{route("user-limit-delete")}}', {
                       limitId: val_id,
                   })
                       .then(function (response) {
                           if(response.data.status){
                               toastr.success('Limit Delete successfull');
                               get_buy_sell_data(currcoin);
                               hideLoader();
                               return false;
                           }
                       })

               }


       }
       /*function updatelimit(id, pricelimit, amount, position){


           var inputfield="<input type='text' id='limitAmountId'  class='form-control mb-1' >";
           var currencyinput="<input type='text' id='totalLimitCurrencyId'  class='form-control mb-1' placeholder='' >";



           $("#limitinputdiv").html(inputfield);
           $("#currencyinputdiv").html(currencyinput);
           $("#totalLimitCurrencyId").val(amount);
           $("#limitAmountId").val(pricelimit);
           var idval= $("#editId").val(id);

           if (position ===1){
               $("#limitBuyInput").prop('checked', true);
               $("#limitSellInput").prop('disabled', true);
               $("#limitSellInput").prop('checked', false);
               $("#normalLimitBuyButton").prop('disabled', false);
               $("#normalLimitSellButton").prop('disabled', true);
           }
           else{
               $("#limitBuyInput").prop('disabled', false);
               $("#limitBuyInput").prop('checked', false);
               $("#limitSellInput").prop('checked', true);
               $("#normalLimitBuyButton").prop('disabled', true);
               $("#normalLimitSellButton").prop('disabled', false);
           }
       }*/
        /**
         * Generates HTML table for Buy/Sell Pending
         * @param string coin name
         * @author Mahbub (mahbub.benri@gmail.com)
         */
        function get_buy_sell_data(coin){
            //alert(coin);
            var type = "{{ Request::get('type') }}";
            if(type){
                type = 'derivative';
            }
            else{
                   type = "trade";
            }
            $("#tabledata").html("");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url     : '{{route("get-buy-sell-pending-data")}}',
                type    : "Get",
                data    : {coin:coin, type:type}, // the value of input
                success : function(response){ // What to do if we succeed
                    var data = "";
                    if(response.length < 0 || response === "no data"){
                        $("#tabledata").html("<div>No Pending Buy/Sell Data Found.</div>");
                    }
                  else{
                        data+="<thead>" +
                                "<tr>" +
                                    "<th class='txtWhitecolor th5'>LIMIT</th>" +
                                    "<th class='txtWhitecolor th6'>AMOUNT</th>" +
                                    "<th class='txtWhitecolor th7'>POSITION</th>" +
                                    "<th class='txtWhitecolor th8'>ACTION</th>"+
                                " </tr>" +
                              "</thead>";
                        data+="<tbody>";
                        for (var i = 0; i < response.length; i++)
                        {
                            if(response[i].limitType==1)
                            {
                                var position="Buy";
                                var classname='text-success';
                            }
                            else
                            {
                                var position="Sell";
                                var classname='text-danger';
                            }
                            var num = response[i].currencyAmount;
                            var decimal_count = num.toString().split('.');

                            //decimal[1] is the number of digits after the decimal point

                            if (decimal_count.length>1 && decimal_count[1].length >5)
                            {
                                var amount = response[i].currencyAmount;
                            }
                            else
                            {
                                var amount = response[i].currencyAmount.toFixed(5);
                            }
                            let type = $("#choseOrderType").val();
                            if (type == 1){
                                var button_code = "<i class='fas fa-trash deleteEnabled' style='color:green;' onclick='limitDelete("+response[i].id+")'></i><i class='fas fa-trash deleteDisabled' style='display:none;' ></i>";
                            }
                            else{
                                var button_code =  "<i class='fas fa-trash deleteEnabled' style='display:none;color:green;' onclick='limitDelete("+response[i].id+")'></i><i class='fas fa-trash deleteDisabled'></i>";
                            }
                            data+="<tr>" +
                                    "<td class='txtWhitecolor tdlimit'>"+response[i].priceLimit+"</td>" +
                                    "<td class='txtWhitecolor tdamount'>"+amount+"</td>" +
                                    "<td class='tdposition "+ classname +"'>" + position + "</td>" +
                                    "<td class='tdposition td8'>"+button_code+"</td>"
                                   "</tr>";
                        }
                        data+="</tbody>";
                        $("#tabledata").html(data);
                    }
                }
            });
        }
        // function syncToInterval(interval) {
        //     if (areaSeries) {
        //         chart.removeSeries(areaSeries);
        //         areaSeries = null;
        //     }
        //     candle = chart.addCandleSeries({
        //         topColor: 'rgba(76, 175, 80, 0.56)',
        //         bottomColor: 'rgba(76, 175, 80, 0.04)',
        //         lineColor: 'rgba(76, 175, 80, 1)',
        //         lineWidth: 2,
        //     });
        //     candle.setData(seriesesData.get(interval));
        // }
        // var intervals = ['1D', '1W', '1M', '1Y'];
        // var switcherElement = createSimpleSwitcher(intervals, intervals[0], syncToInterval);
        //
        // var chartElement = document.createElement('div');
        // function createSimpleSwitcher(items, activeItem, activeItemChangedCallback) {
        //     var switcherElement = document.createElement('div');
        //     switcherElement.classList.add('switcher');
        //
        //     var intervalElements = items.map(function(item) {
        //         var itemEl = document.createElement('button');
        //         itemEl.innerText = item;
        //         itemEl.classList.add('switcher-item');
        //         itemEl.classList.toggle('switcher-active-item', item === activeItem);
        //         itemEl.addEventListener('click', function() {
        //             onItemClicked(item);
        //         });
        //         switcherElement.appendChild(itemEl);
        //         return itemEl;
        //     });
        //
        //     function onItemClicked(item) {
        //         if (item === activeItem) {
        //             return;
        //         }
        //
        //         intervalElements.forEach(function(element, index) {
        //             element.classList.toggle('switcher-active-item', items[index] === item);
        //         });
        //
        //         activeItem = item;
        //
        //         activeItemChangedCallback(item);
        //     }
        //
        //     return switcherElement;
        // }
        // document.body.appendChild(chartElement);
        // document.body.appendChild(switcherElement);
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }

        /***Load data for first time***/
        get_buy_sell_data('BTC');
    </script>
@endsection
