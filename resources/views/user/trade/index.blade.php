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
            margin-left: 6px;
        }
        .main-app-container{
            margin: 0 5px;
            flex-grow: 1;
            min-width: 0;
        }
        .txtWhitecolor{
            color: #D3D6D8;
        }
        .txtHeadingColor{
            color: yellow;
        }
        .table>:not(caption)>*>* {
            padding: 2px 20px 2px 20px !important;
        }

    </style>
@endsection
@section('content')
    <div id="wrap" class="trade">
        @if(isset($type))
            <h3 class="txtHeadingColor">Derivatives</h3>
        @else
            <h3 class="txtHeadingColor">Trade</h3>
        @endif
        <hr>
        <div class="row" style="display: flex;">
            <div class="col-md-3 sidebar">
                <div class="card" >
                    <div class="card-body">
                        <div id="trackers">
                            <div class="text-center title txtHeadingColor"><h4>TICKERS</h4></div>
                            <table class="tables trackers">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="txtWhitecolor">SYMBOL</th>
                                        <th class="txtWhitecolor">LAST PRICE</th>
                                        <th class="txtWhitecolor">24H CHANGE</th>
                                        {{-- <th>24H HIGH</th>
                                        <th>24H LOW</th> --}}
                                        <th class="txtWhitecolor">VOLUME</th>
                                    </tr>
                                </thead>
                                <tbody>
{{--                                    <tr v-for="item in trackers" v-on:click="setCurrency(item)" :class="{active: item[0] == selectedItem[0]}" >--}}
                                    <tr v-for="item in trackers"  :class="{active: item[0] == selectedItem[0]}" v-on:click="setCurrency(item)">
                                        <td></td>
                                        <td v-cloak id="currencyNameid" class="txtWhitecolor">@{{splitCurrency(item[0])}}</td>
                                        <td style=""><span v-cloak style="color: #D3D6D8;font-size: 12px;">@{{item[7]}}</span> USD</td>
                                        <td v-cloak :class="{'text-danger': item[6]<0, 'text-success': item[6]>0}">@{{Math.abs((item[6]*100).toFixed(2))}}%</td>
                                        {{-- <td>@{{item[3]}}</td>
                                        <td>@{{item[4]}}</td> --}}
                                        <td v-cloak class="txtWhitecolor">@{{Math.round(item[7]*item[8])}}</td>
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

                                {{-- <small class="float-end">BALANCE: @{{usdBalance}}</small> --}}
                                <hr>
                                <div class="row">
                                    <div class="col ">
                                        <div class="form-group">
                                            <label for="" class="txtWhitecolor">USD:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control mb-1" placeholder="" readonly v-model="selectedPrice" style="cursor: not-allowed;">
{{--                                                <div class="input-group-append">--}}
{{--                                                    <span class="input-group-text" id="">--}}
{{--                                                       <span class="text-muted">~@{{calcAmount}}</span>--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
                                            </div>
                                            <span v-cloak class="text-muted form-control">~@{{calcAmount}}</span>
                                            <small class="txtWhitecolor">BID</small>
                                            <small v-cloak class="float-end text-success cursor-pointer " v-on:click="selectedPrice=latestBid">
                                                <i v-if="bidIncrease" class="fas fa-sort-up"></i>
                                                <i v-else class="fas fa-sort-down"></i>
                                                @{{latestBid}}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label v-cloak class="txtWhitecolor" for="">@{{currency}}:</label>
                                            <input type="number" class="form-control mb-1" placeholder="" v-model="amount">
                                            <small class="txtWhitecolor">ASK</small>
                                            <small v-cloak class="float-end text-danger cursor-pointer" v-on:click="selectedPrice=latestAsk">
                                                <i v-if="askIncrease" class="fas fa-sort-up"></i>
                                                <i v-else class="fas fa-sort-down"></i>
                                                @{{latestAsk}}
                                            </small>
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
                                <div class="row mt-5">
                                    <div class="col d-grid">
                                        <button class="btn btn-block btn-success" :disabled="amount<=0 || derivativeRange > derivativeBalance" v-on:click="derivativeBuy">Derivative Buy</button>
                                    </div>
                                    <div class="col d-grid">
                                        <button class="btn btn-block btn-danger" :disabled="amount<=0 || amount > leverageWalletAmount" v-on:click="derivativeSell">Derivative Sell</button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card-body">
                            <h4 v-cloak class="txtHeadingColor">ORDER FORM: @{{currency}}</h4>
                            {{-- <small class="float-end">BALANCE: @{{usdBalance}}</small> --}}
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="txtWhitecolor" for="">USD:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control mb-1" placeholder="" readonly v-model="selectedPrice" style="cursor: not-allowed;">
{{--                                            <div class="input-group-append">--}}
{{--                                                <span class="input-group-text" id="">--}}
{{--                                                   <span class="text-muted">~@{{calcAmount}}</span>--}}
{{--                                                </span>--}}
{{--                                            </div>--}}
                                        </div>
                                        <span v-cloak class="text-muted form-control">~@{{calcAmount}}</span>
                                        <small class="txtWhitecolor">BID</small>
                                        <small v-cloak class="float-end text-success cursor-pointer" v-on:click="selectedPrice=latestBid">
                                            <i v-if="bidIncrease" class="fas fa-sort-up"></i>
                                            <i v-else class="fas fa-sort-down"></i>
                                            @{{latestBid}}
                                        </small>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-block btn-success" :disabled="amount<=0 || calcAmount > usdBalance" v-on:click="buy">Exchange Buy</button>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group" style="margin-bottom: 51px;">
                                        <label v-cloak class="txtWhitecolor" for="">@{{currency}}:</label>
                                        <input type="text" class="form-control mb-1" placeholder="" v-model="amount">
                                        <small class="txtWhitecolor">ASK</small>
                                        <small v-cloak class="float-end text-danger cursor-pointer" v-on:click="selectedPrice=latestAsk">
                                            <i v-if="askIncrease" class="fas fa-sort-up"></i>
                                            <i v-else class="fas fa-sort-down"></i>
                                            @{{latestAsk}}
                                        </small>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-block btn-danger" :disabled="amount<=0 || amount > balance" v-on:click="sell">Exchange SELL</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col main-app-container">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center title mb-2 txtHeadingColor"><h4 v-cloak>Showing Chart for @{{currency}}</h4></div>
                        <div id="chart" style="height:600px; display: none"></div>
                        <div id="tradingview_f7648" ></div>
                    </div>
                </div>
                {{-- <div class="mt-3">
                    <div class="">
                        <div class="row">
                            <div class="col">

                                <div class="card">
                                    <div class="card-body">
                                        <h4>Buy @{{currency}}</h4> <small class="float-end">BALANCE: @{{usdBalance}}</small>
                                        <hr>
                                        <div class="form-group">
                                            <label for="">Price:</label>
                                            <input type="text" class="form-control" placeholder="Coin exchange proce.." :value="selectedItem[7]">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Amount:</label> <small class="float-end" :class="{'text-danger': calcAmount>usdBalance, 'text-success': calcAmount<=usdBalance}">TOTAL: @{{calcAmount}}</small>
                                            <input type="number" class="form-control" placeholder="Enter buy amount..." v-model="buyAmount">
                                        </div>
                                        <div class="d-grid">
                                            <button class="btn btn-block btn-success" :disabled="buyAmount<=0 || calcAmount > usdBalance" v-on:click="buy">BUY</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h4>Sell @{{currency}}</h4> <small class="float-end">BALANCE: @{{balance}}</small>
                                        <hr>
                                        <div class="form-group">
                                            <label for="">Price:</label>
                                            <input type="text" class="form-control" placeholder="Coin exchange proce.." :value="selectedItem[7]">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Amount:</label> <small class="float-end" :class="{'text-danger': sellAmount>balance, 'text-success': sellAmount<=balance}">TOTAL: @{{calcSellAmount}}</small>
                                            <input type="number" class="form-control" placeholder="Enter sell amount..." v-model="sellAmount">
                                        </div>
                                        <div class="d-grid">
                                            <button class="btn btn-block btn-danger" :disabled="sellAmount<=0 || sellAmount > balance" v-on:click="sell">SELL</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="card mt-3">
                    <div class="card-body">
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
                                        <tbody style="background-color: #1142304d;">
                                            <tr v-for="(item, index) in bids">
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
                                        <tbody style="background-color:#942f3e6e; ">
                                            <tr v-for="(item, index) in asks">
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
        const socket = io('http://bitc-way.com:3000');
        // showLoader('Loading...');
        let loaded = false;
        socket.on('trackers', (trackers) => {
            Home.trackers = trackers.trackers;
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

        let w = null;
        var totalSellAmount = 0;
        var currencies = <?php echo json_encode($currency); ?>;

        // const OrderBook Start
        $(document).ready(function() {
            getInitialOrder("tBTCUSD");
        });
        // const OrderBook End

        let getInitialOrder = function (currency) {
            let CurrencyApi = ' ';
            if(currency == undefined){
                CurrencyApi = 'https://api.bitfinex.com/v2/book/tBTCUSD/P0';
            } else {
                CurrencyApi = 'https://api.bitfinex.com/v2/book/'+currency+'/P0';
            }
            console.log(CurrencyApi);
            axios.get(CurrencyApi, {
                headers: {
                    'Access-Control-Allow-Origin': '*',
                    'Access-Control-Allow-Methods':'GET,PUT,POST,DELETE,PATCH,OPTIONS',
                }
            })
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
                            item = items;
                            // console.log("Book:", item);
                            // if(item[2] > 0){
                            //     if(Home.bids.length > 25) Home.bids.pop();
                            //     Home.bids = [item].concat(Home.bids);
                            // }else if(item[2] < 0){
                            //     if(Home.asks.length > 25) Home.asks.pop();
                            //     Home.asks = [item].concat(Home.asks);
                            // }
                        }
                        Home.latestBid = Home.bids[0][0];
                        Home.bidIncrease = Home.bids[0][0]>Home.bids[1][0];
                        title = Home.bidIncrease?'▲':'▼';
                        document.title = title+" "+Home.latestBid+" "+Home.currency+"/USD";

                        Home.latestAsk = Home.asks[0][0];
                        Home.askIncrease = Home.asks[0][0]>Home.asks[1][0];
                    }
                })
                .catch(error => "404")

        }

        let getOrders = function(currency){

            if(w) w.close();
            w = new WebSocket('wss://api-pub.bitfinex.com/ws/2');
            w.onmessage = function(msg){
                items = JSON.parse(msg.data);
                // console.log(msg.data);
                if (items.event) return;
                console.log("BitBook1:", items[2]);
                if(items[1]){
                    if(items[1].length > 3){
                        bids = [];
                        asks = [];
                        items[1].forEach(function(item){
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
                        // console.log("BitBook:", item);
                        if(item[2] > 0){
                            if(Home.bids.length > 25) Home.bids.pop();
                            Home.bids = [item].concat(Home.bids);
                        }else if(item[2] < 0){
                            if(Home.asks.length > 25) Home.asks.pop();
                            Home.asks = [item].concat(Home.asks);
                        }
                    }
                    Home.latestBid = Home.bids[0][0];
                    Home.bidIncrease = Home.bids[0][0]>Home.bids[1][0];
                    title = Home.bidIncrease?'▲':'▼';
                    document.title = title+" "+Home.latestBid+" "+Home.currency+"/USD";

                    Home.latestAsk = Home.asks[0][0];
                    Home.askIncrease = Home.asks[0][0]>Home.asks[1][0];
                }
            }

            let msg = JSON.stringify({ 
                event: 'subscribe', 
                channel: 'book',
                freq: 'F1',
                symbol: currency
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
                amount: '',
                balance: 0,
                usdBalance: '{{Auth::user()->balance}}',
                derivativeBalance:'{{Auth::user()->derivative}}',
                leverageWalletAmount: 0,
                bids: [],
                asks: [],
                latestBid: 0,
                bidIncrease: false,
                latestAsk: 0,
                askIncrease: false,
                selectedPrice: '',
                derivativeValue:'1'
            },
            mounted() {

            },
            computed:{
                currency(){
                    let currency = this.selectedItem[0]?this.selectedItem[0]:'tBTCUSD';
                    currency = this.splitCurrency(currency);
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
                    return (this.amount*this.selectedPrice)/this.derivativeValue;
                }
            },
            methods: {
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
                    return currency;
                },
                setCurrency(item){
                    let coin = item[0];
                    let symbolx = coin.substr(1);

                    let currentCoin = this.splitCurrency(symbolx);
                    if(currentCoin == "MAB"){
                        currentCoin = "ADA";
                        symbolx = "ADAUSD";
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
                getChartData(){
                    let that = this;
                    let currency = that.selectedItem[0];
                    // showLoader('Loading ...');
                    axios.get('{{route("user-get-chart-data")}}', {params: {currency: currency, user_currency: that.currency}})
                    .then(function (response) {
                        let chartData = [];
                        if(response.data.status){
                            that.balance = response.data.balance;
                            response.data.chartData.forEach(function(item){       
                                let newChartData = { time: item[0], open: item[1], high: item[3], low: item[4], close: item[2]};
                                chartData.push(newChartData);
                            });
                            setTimeout(function(){
                                that.drawChart(chartData);
                            }, 100);
                        }

                    })
                    .catch(function (error) {
                        // console.log(error);
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
                    that.chart = LightweightCharts.createChart(document.getElementById('chart'), {
                        layout: {
                            backgroundColor: '#000000',
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
                            borderColor: 'rgba(197, 203, 206, 0.8)',
                        },
                    });

                    var candleSeries = that.chart.addCandlestickSeries({
                        upColor: 'rgba(255, 144, 0, 1)',
                        downColor: '#000',
                        borderDownColor: 'rgba(255, 144, 0, 1)',
                        borderUpColor: 'rgba(255, 144, 0, 1)',
                        wickDownColor: 'rgba(255, 144, 0, 1)',
                        wickUpColor: 'rgba(255, 144, 0, 1)',
                    });
                    data.sort((a, b) => (a.time > b.time) ? 1 : -1);
                    candleSeries.setData(data);
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
                        calcBuyAmount: that.calcAmount

                    })
                    .then(function (response) {
                        if(response.data.status){
                            toastr.success('Buy successfull');
                            window.location.href = '{{route("user-wallets")}}';
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
                    if(that.calcAmount <= 0 || that.amount > that.balance) {
                        toastr.error('Invalid amount !!');
                        return false;
                    }

                    showLoader('Processing...');
                    axios.post('{{route("user-trade-sell")}}', {
                        currency: that.currency,
                        sellAmount: that.amount,
                        calcSellAmount: that.calcAmount
                    })
                    .then(function (response) {
                        if(response.data.status){
                            toastr.success('Sell successfull');
                            window.location.href = '{{route("user-wallets")}}';
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
                        currency: that.currency,
                        buyAmount: that.amount,
                        calcBuyAmount: that.calcAmount,
                        leverage: $("#sliderRange").val()
                    })
                        .then(function (response) {
                            if(response.data.status){
                                toastr.success('Buy successfull');
                                window.location.href = '{{route("user-wallets")}}';
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
                        currency: that.currency,
                        sellAmount: that.amount,
                        calcSellAmount: that.calcAmount,
                        derivativeType: '0'
                    })
                        .then(function (response) {
                            if(response.data.status){
                                toastr.success('Sell successfull');
                                window.location.href = '{{route("user-wallets")}}';
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
                getOrders(){

                    let that = this;
                    let currency = that.selectedItem[0];
                    getOrders(currency);
                    getInitialOrder(currency);
                },

            },
            beforeMount(){

            },

        });


    </script>
@endsection
