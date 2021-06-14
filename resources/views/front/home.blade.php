@extends('front.layouts.main')

@section('custom_css')
    <style>

        /*.features .features-grid {*/
        /*    display: flex;*/
        /*    flex-direction: row;*/
        /*    flex-wrap: wrap;*/
        /*    margin-top: 75px;*/
        /*}*/
    </style>
@endsection

@section('content')
    {{--<div class="card-body" style="margin-top: 50px !important;">--}}
    {{--    <img src="./images/bonusBanner.jpg">--}}
    {{--</div>--}}
    <div class=" text-center home-banner">
        <?php if(!Auth::check()){?>
            <a  href="{{route('signup', app()->getLocale())}}">
                <img class="ad" src="./images/client_banner1.png">
            </a>
        <?php } ?>
        <div class="card-body" style="margin-top: 25px !important;">
            <h1 class="card-title txtHeadingColor">{{__('title')}} <br>{{__('title2')}}</h1><br>
            <?php if(Auth::check()){?>
            <a href="{{route('user-wallets', app()->getLocale())}}"
               class="btn btn-outline-warning btn-lg">{{__('my_assets')}}</a>
            <?php }else{?>
            <a href="{{route('signup', app()->getLocale())}}"
               class="btn btn-outline-warning btn-lg t">{{__('button1')}}</a>
            <?php }?>
        </div>
    </div>

    <div id="home">
        <div id="trackers">
            <table class="table trackers tableClass">
                <thead>
                <tr>
                    <th></th>
                    <th class="txtWhitecolor">{{__('column1')}}</th>
                    <th class="txtWhitecolor">{{__('last_price')}}</th>
                    <th class="txtWhitecolor">{{__('24h_change')}}</th>
                    <th class="txtWhitecolor">{{__('column4')}}</th>
                    <th class="txtWhitecolor">{{__('column5')}}</th>
                    <th class="txtWhitecolor">{{__('column6')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in trackers">
                    <td class="txtWhitecolor"></td>
                    <td v-cloak class="txtWhitecolor">@{{splitCurrency(item[0])}}</td>
                    <td v-cloak class="txtWhitecolor">@{{item[7]}} USD</td>
                    <td v-cloak :class="{'text-danger': item[6]<0, 'text-success': item[6]>0}">
                        @{{Math.abs((item[6]*100).toFixed(2))}}%
                    </td>
                    <td v-cloak class="txtWhitecolor">@{{item[9]}}</td>
                    <td v-cloak class="txtWhitecolor">@{{item[10]}}</td>
                    <td v-cloak class="txtWhitecolor">@{{Math.round(item[7]*item[8])}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="section2 vol_sec bgco dwhite center_cls">
        <div class="volume-totals">
            <div class="vol_sec_list row">
                <div class="vol_sec_item col-md-4">
                    <div class="vol_sec_fig">$<span class=" ">1,755,170,131</span></div>
                    <div>24 Hour Volume</div>
                </div>
                <div class="vol_sec_item col-md-4">
                    <div class="vol_sec_fig">$<span class=" ">21,633,229,657</span></div>
                    <div>7 Day Volume</div>
                </div>
                <div class="vol_sec_item col-md-4">
                    <div class="vol_sec_fig">$<span class=" ">173,425,341,195</span></div>
                    <div>30 Day Volume</div>
                </div>
            </div>
        </div>
    </div>

    <div class="section3">
        <section class="features dark">
            <div class="content content_wrapper">
                <div>
                    <div class="lp_title">Features</div>
                    <div class="lp_subtitle">World class trading platform</div>
                </div>
                <div class="row features-grid">
                    <div class="col-md-4 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <img src="./images/exchange.png" alt="exchange"
                                     style="min-width:120px;width:120px;height:120px;margin-right:20px">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">Exchange</h1>
                                    <div class="description">
                                        <p>
                                            BitC-Way offers order books with top tier liquidity, allowing users to
                                            easily exchange Bitcoin, Ethereum, EOS, Litecoin, Ripple, NEO and many other
                                            digital assets with minimal slippage.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <img src="./images/fundmerge.png" alt="exchange"
                                     style="min-width:120px;width:120px;height:120px;margin-right:20px">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">Margin funding</h1>
                                    <div class="description">
                                        <p>
                                            Liquidity providers can generate yield by providing funding to traders
                                            wanting to trade with leverage. Funding is traded on an order book at
                                            various rates and periods.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <img src="./images/trademerge.png" alt="exchange"
                                     style="min-width:120px;width:120px;height:120px;margin-right:20px">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">Margin trading</h1>
                                    <div class="description">
                                        <p>
                                            BitC-Way allows up to 10x leverage trading by providing traders with access
                                            to the peer-to-peer funding market.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row features-grid mt-5">
                    <div class="col-md-4 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <img src="./images/ordertype.png" alt="exchange"
                                     style="min-width:120px;width:120px;height:120px;margin-right:20px">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">Order types</h1>
                                    <div class="description">
                                        <p>
                                            BitC-Way offers a suite of order types to give traders the tools they need
                                            for every scenario. Discover more about our most advanced Algorithmic orders
                                            types.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <img src="./images/custominterface.png" alt="exchange"
                                     style="min-width:120px;width:120px;height:120px;margin-right:20px">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">Customizable interface</h1>
                                    <div class="description">
                                        <p>
                                            Organize your workspace according to your needs: compose your layout, choose
                                            between themes, and set up notifications.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <img src="./images/security.png" alt="exchange"
                                     style="min-width:120px;width:120px;height:120px;margin-right:20px">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">Security</h1>
                                    <div class="description">
                                        <p>
                                            Security of user information and funds is our first priority. Learn more
                                            about our security features and integrations.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="section4 bgco" style="margin-top: 20px">
        <section class="features">
            <div class="content content_wrapper">
                <div>
                    <div class="lp_title">ADVANCED FEATURES FOR PROFESSIONALS</div>
                    <div class="lp2_subtitle">To meet the highest of demands</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 sec4_desc">
                    <div class="">
                        <h1 class="" style="font-size: 22px">Professional connectivity</h1>
                        <div class="description mt-4">
                            <p>
                                Partnering with Market Synergy, corporate accounts and professional traders can take
                                advantage of the fastest trading speeds through institutional-grade connectivity and
                                co-location services with direct access to our digital asset gateway.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 sec4_desc">
                    <div class="">
                        <h1 class="" style="font-size: 22px">Advanced chart tools</h1>
                        <div class="description mt-4">
                            <p style="">
                                BitC-Way facilitates a graphical trading experience with advanced charting functionality
                                that allows traders to visualise orders, positions and price alerts, tap to modify order
                                properties, and annotate to their trading strategy.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 sec4_desc">
                    <div class="">
                        <h1 class="" style="font-size: 22px">Corporate account and professional trading</h1>
                        <div class="description mt-4">
                            <p>
                                BitC-Way has a bespoke offering expertly tailored to meet the specific needs of
                                professional and institutional traders including sub-accounts, expedited verification,
                                and dedicated customer support.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 sec4_desc">
                    <div class="">
                        <h1 class="" style="font-size: 22px">API</h1>
                        <div class="description mt-4">
                            <p style="">
                                The BitC-Way REST and Websocket APIs are designed to facilitate access to all features
                                of the BitC-Way platform, allowing full integration with traders’ own products and
                                platforms.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="section5 row mt-20 bg-black" style="height: 300px">
       <div class="col-md-8" style="margin-top: 5%">
           <div class="content content_wrapper">
               <div class="sec5_desc">
                   <div class="lp2_subtitle lp2_subtitle2" style="color: white !important;margin-left: 15px">Join Bitc-Way and start </br> trading today</div>
               </div>
           </div>
       </div>
        <div class="col-md-4 sec5_motoright" style="height: 150px !important; margin-top: 5%">
            <div class="buttons_row" style="position: relative">
                <a style="position:absolute;top:80px;" href="{{route('signup', app()->getLocale())}}" class="btn btn-outline-warning btn-lg sec5_btn1" >Sign Up</a>
                <a style="position:absolute;top:80px;left: 120px" href="{{route('login', app()->getLocale())}}" class="btn btn-outline-warning btn-lg sec5_btn2" >Log In</a>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>

    <script>
        const socket = io('http://192.144.82.234:3000/');
        // const socket = io('http://localhost:3000');
        socket.on('trackers', (trackers) => {
            console.log(trackers);
            Home.trackers = trackers.trackers;
        })

        let Home = new Vue({
            el: '#home',
            data: {
                message: 'Hello Vue!',
                trackers: []
            },
            mounted() {
            },
            methods: {
                splitCurrency(currency) {
                    currency = currency.split('t').join('');
                    currency = currency.split('USD').join('');
                    return currency;
                },
            }
        });

    </script>
@endsection
