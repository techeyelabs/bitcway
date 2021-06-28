@extends('front.layouts.main')

@section('custom_css')
    <style>
        .indexTableTextHide{
            overflow:hidden;
            white-space:nowrap;
            max-width:32px;
        }
        table th {
            /*//position: -webkit-sticky !important; // this is for all Safari (Desktop & iOS), not for Chrome*/
            position: sticky !important;
            top: 0;
            z-index: 10;
            background-color: #081420 !important;
        }
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
                <img class="ad" src="./images/client_banner3.png">
            </a>
        <?php } ?>
        <div class="card-body" style="margin-top: 30px !important;">
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
                    <td v-cloak class="txtWhitecolor">
                        <div class="indexTableTextHide">
                            @{{splitCurrency(item[0].substring(0,4))}}
                        </div>
                    </td>
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

    {{--<div class="section2 vol_sec bgco dwhite center_cls">
        <div class="volume-totals">
            <div class="vol_sec_list row">
                <div class="vol_sec_item col-md-4">
                    <div class="vol_sec_fig">$<span class=" ">1,869,037,849</span></div>
                    <div>{{__('24hvolume')}}</div>
                </div>
                <div class="vol_sec_item col-md-4">
                    <div class="vol_sec_fig">$<span class=" ">17,688,693,886</span></div>
                    <div>{{__('7dvolume')}}</div>
                </div>
                <div class="vol_sec_item col-md-4">
                    <div class="vol_sec_fig">$<span class=" ">146,050,057,901</span></div>
                    <div>{{__('30dvolume')}}</div>
                </div>
            </div>
        </div>
    </div>--}}

    <div class="section3 vol_sec">
        <section class="features dark">
            <div class="content content_wrapper">
                <div>
                    <div class="lp_title">{{__('hfeatures')}}</div>
                    <div class="lp_subtitle">{{__('hfeaturestitle')}}</div>
                </div>
                <div class="row features-grid pc-feature-grid">
                    <div class="col-md-4 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <img src="./images/exchange.png" alt="exchange"
                                     style="min-width:120px;width:120px;height:120px;margin-right:20px">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">{{__('hfeaturessubtitle1')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails1')}}</p>
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
                                    <h1 class="boxh1">{{__('hfeaturessubtitle2')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails2')}}</p>
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
                                    <h1 class="boxh1">{{__('hfeaturessubtitle3')}}</h1>
                                    <div class="description">
                                        <p>
                                            {{__('hfeaturessubdetails3')}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mobile-feature-grid">
                    <div class="col-md-1">
                        <img src="./images/exchange.png" alt="exchange"
                             style="min-width:120px;width:120px;height:120px;margin-right:20px">
                    </div>
                    <div class="col-md-3 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">{{__('hfeaturessubtitle1')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails1')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <img src="./images/fundmerge.png" alt="exchange"
                             style="min-width:120px;width:120px;height:120px;margin-right:20px">
                    </div>
                    <div class="col-md-3 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">{{__('hfeaturessubtitle2')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails2')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-1">
                        <img src="./images/trademerge.png" alt="exchange"
                             style="min-width:120px;width:120px;height:120px;margin-right:20px">
                    </div>
                    <div class="col-md-3 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">{{__('hfeaturessubtitle3')}}</h1>
                                    <div class="description">
                                        <p>
                                            {{__('hfeaturessubdetails3')}}
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
                                    <h1 class="boxh1">{{__('hfeaturessubtitle4')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails4')}}</p>
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
                                    <h1 class="boxh1">{{__('hfeaturessubtitle5')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails5')}}</p>
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
                                    <h1 class="boxh1">{{__('hfeaturessubtitle6')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails6')}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mobile-feature-grid">
                    <div class="col-md-1">
                        <img src="./images/ordertype.png" alt="exchange"
                             style="min-width:120px;width:120px;height:120px;margin-right:20px">
                    </div>
                    <div class="col-md-3 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">{{__('hfeaturessubtitle4')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails4')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-1">
                        <img src="./images/custominterface.png" alt="exchange"
                             style="min-width:120px;width:120px;height:120px;margin-right:20px">
                    </div>
                    <div class="col-md-3 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">

                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">{{__('hfeaturessubtitle5')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails5')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-1">
                        <img src="./images/security.png" alt="exchange"
                             style="min-width:120px;width:120px;height:120px;margin-right:20px">
                    </div>
                    <div class="col-md-3 ">
                        <div class="box">
                            <div id="" style="display:flex;flex-direction:row" class="">
                                <div id="" style="display:flex;flex-direction:column" class="">
                                    <h1 class="boxh1">{{__('hfeaturessubtitle6')}}</h1>
                                    <div class="description">
                                        <p>{{__('hfeaturessubdetails6')}}
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
                    <div class="lp_title">{{__('afeatures')}}</div>
                    <div class="lp2_subtitle">{{__('afeaturestitle')}}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 sec4_desc">
                    <div class="">
                        <h1 class="" style="font-size: 22px">{{__('afeaturessubtitle1')}}</h1>
                        <div class="description mt-4">
                            <p>{{__('afeaturessubdetails1')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 sec4_desc">
                    <div class="">
                        <h1 class="" style="font-size: 22px">{{__('afeaturessubtitle2')}}</h1>
                        <div class="description mt-4">
                            <p style="">{{__('afeaturessubdetails2')}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 sec4_desc">
                    <div class="">
                        <h1 class="" style="font-size: 22px">{{__('afeaturessubtitle3')}}</h1>
                        <div class="description mt-4">
                            <p>{{__('afeaturessubdetails3')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 sec4_desc">
                    <div class="">
                        <h1 class="" style="font-size: 22px">{{__('afeaturessubtitle4')}}</h1>
                        <div class="description mt-4">
                            <p style="">{{__('afeaturessubdetails4')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="section5  mt-20 bg-black" style="height: 300px">
       <div class="row">
           <div class="col-md-8" style="margin-top: 5%">
               <div class="content content_wrapper">
                   <div class="sec5_desc">
                       <div class="lp2_subtitle lp2_subtitle2" style="color: white !important;margin-left: 15px"><span>{{__('bbannertext1')}}</span> </br><span>{{__('bbannertext2')}}</span></div>
                   </div>
               </div>
           </div>
           <div class="col-md-4 sec5_motoright" style="height: 150px !important; margin-top: 5%">
            <div class="buttons_row" style="position: relative">
                <a style="position:absolute;top:80px;" href="{{route('signup', app()->getLocale())}}" class="btn btn-outline-warning btn-lg sec5_btn1" >{{__('bbutton1')}}</a>
                <a style="position:absolute;top:80px;left: 120px" href="{{route('login', app()->getLocale())}}" class="btn btn-outline-warning btn-lg sec5_btn2" >{{__('bbutton2')}}</a>
            </div>
        </div>
       </div>
    </div>
    <div id="thisdiv"></div>
@endsection

@section('custom_js')
    <script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>

    <script>
        const socket = io('http://192.144.82.234:3000/');
        // const socket = io('https://bitc-way.com:3000/');
        socket.on('trackers', (trackers) => {
            console.log(trackers);
            Home.trackers = trackers.trackers.trackers;
            // Home.trackers = trackers.trackers;
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
