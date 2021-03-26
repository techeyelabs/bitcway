@extends('front.layouts.main')

@section('custom_css')
    <style>
        .txtWhitecolor{
            color: #D3D6D8;
        }
        .txtHeadingColor{
            color: yellow;
        }

    </style>
@endsection

@section('content')
<div class=" text-center home-banner">
    <div class="card-body">
        <h1 class="card-title txtHeadingColor">The Home of Digital Asset <br>Trading & Finance</h1><br>
        <?php if(Auth::check()){?>
            <a href="{{route('user-dashboard')}}" class="btn btn-outline-warning btn-lg">DASHBOARD</a>
        <?php }else{?>
            <a href="{{route('signup')}}" class="btn btn-outline-warning btn-lg t">JOIN NOW</a>
        <?php }?>
        
    </div>
</div>

<div id="home">
    <div id="trackers">
        <table class="table trackers">
            <thead>
                <tr>
                    <th></th>
                    <th class="txtWhitecolor">SYMBOL</th>
                    <th class="txtWhitecolor">LAST PRICE</th>
                    <th class="txtWhitecolor">24H CHANGE</th>
                    <th class="txtWhitecolor">24H HIGH</th>
                    <th class="txtWhitecolor">24H LOW</th>
                    <th class="txtWhitecolor">VOLUME</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in trackers">
                    <td class="txtWhitecolor"></td>
                    <td class="txtWhitecolor">@{{splitCurrency(item[0])}}</td>
                    <td class="txtWhitecolor">@{{item[7]}} USD</td>
                    <td :class="{'text-danger': item[6]<0, 'text-success': item[6]>0}">@{{Math.abs((item[6]*100).toFixed(2))}}%</td>
                    <td class="txtWhitecolor">@{{item[9]}}</td>
                    <td class="txtWhitecolor">@{{item[10]}}</td>
                    <td class="txtWhitecolor">@{{Math.round(item[7]*item[8])}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('custom_js')
<script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>

<script>
    const socket = io('http://bitc-way.com:3000');
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
