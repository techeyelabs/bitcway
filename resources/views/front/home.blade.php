@extends('front.layouts.main')

@section('custom_css')

@endsection

@section('content')
<div class=" text-center home-banner">
    <div class="card-body">
        <h1 class="card-title txtHeadingColor">{{__('title')}} <br>{{__('title2')}}</h1><br>
        <?php if(Auth::check()){?>
            <a href="{{route('user-wallets', app()->getLocale())}}" class="btn btn-outline-warning btn-lg">{{__('my_assets')}}</a>
        <?php }else{?>
            <a href="{{route('signup', app()->getLocale())}}" class="btn btn-outline-warning btn-lg t">{{__('button1')}}</a>
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
                <tr v-for="item in trackers" >
                    <td class="txtWhitecolor"></td>
                    <td v-cloak class="txtWhitecolor">@{{splitCurrency(item[0])}}</td>
                    <td v-cloak class="txtWhitecolor">@{{item[7]}} USD</td>
                    <td v-cloak :class="{'text-danger': item[6]<0, 'text-success': item[6]>0}">@{{Math.abs((item[6]*100).toFixed(2))}}%</td>
                    <td v-cloak class="txtWhitecolor">@{{item[9]}}</td>
                    <td v-cloak class="txtWhitecolor">@{{item[10]}}</td>
                    <td v-cloak class="txtWhitecolor">@{{Math.round(item[7]*item[8])}}</td>
                </tr>
            </tbody>
        </table>
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
        mounted() {},
        methods: {
            splitCurrency(currency){
                currency = currency.split('t').join('');
                currency = currency.split('USD').join('');
                return currency;
            },
        }
    });

</script>
@endsection
