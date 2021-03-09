@extends('front.layouts.main')

@section('custom_css')

@endsection

@section('content')
<div class="card text-center home-banner">
   
    <div class="card-body">
        <h1 class="card-title">Special title treatment</h1>
        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
        <?php if(Auth::check()){?>
            <a href="{{route('user-dashboard')}}" class="btn btn-primary btn-lg">DASHBOARD</a>
        <?php }else{?>
            <a href="{{route('signup')}}" class="btn btn-primary btn-lg">JOIN NOW</a>
        <?php }?>
        
    </div>
</div>

<div id="home">
    <div id="trackers">
        <div class="text-center title"><h4>Current Rate/Change</h4></div>
        <table class="table trackers">
            <thead>
                <tr>
                    <th></th>
                    <th>CURRENCY</th>
                    <th>LAST PRICE</th>
                    <th>24H CHANGE</th>
                    <th>24H HIGH</th>
                    <th>24H LOW</th>
                    <th>VOLUME</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in trackers">
                    <td></td>
                    <td>@{{splitCurrency(item[0])}}</td>
                    <td>@{{item[7]}} USDT</td>
                    <td :class="{'text-danger': item[6]<0, 'text-success': item[6]>0}">@{{Math.abs((item[6]*100).toFixed(2))}}%</td>
                    <td>@{{item[9]}}</td>
                    <td>@{{item[10]}}</td>
                    <td>@{{Math.round(item[7]*item[8])}}</td>
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
