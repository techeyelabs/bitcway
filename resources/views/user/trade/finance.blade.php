@extends('user.layouts.main')

@section('custom_css')
    <style>
        #trackers {
            max-height: calc(100vh - 420px);
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
            max-height: 500px;
            overflow-y: scroll;
        }
        .cursor-pointer{
            cursor: pointer;
        }
        table{
            width: 100%
        }
        .day-block{
            padding: 5px 25px 5px 25px;
            border: 1px solid;
            border-radius: 4px;
        }
        .lot-input{
            background-color: #102331;
            width: 90%;
            border: 1px solid #818896;
            border-radius: 4px;
            margin-top: 10px;
            color: white
        }
        .side-divs{
            height: 0px;
        }
        .confirm-button{
            background-color: #081420;
            border: 1px solid grey;
            border-radius: 4px;
            padding: 4px 25px 5px 25px;
            margin-top: 15px;
            font-size: 15px;
        }
        .txtWhitecolor{
            color: #D3D6D8;
            text-align: left !important;

        }
        .txtHeadingColor{
            color: yellow;
        }
        li{
            text-align: left !important;
        }

    </style>
@endsection
@section('content')

    <div id="wrap" class="trade">
        <h3 class="txtHeadingColor">Finance</h3>
        <hr>
        <div class="card">
            <div class="card-body text-center">
                <h4 class="txtHeadingColor">Locked Savings Amount:  {{$total}} USD</h4>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <ul class="container-fluid" style="max-width: 700px">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="txtWhitecolor">Rate</span>
                        <span class="txtWhitecolor">Duration</span>
                        <span class="txtWhitecolor" style="margin-right: 60px;">Interest Per Lot</span>
                        <span class="txtWhitecolor"></span>
                    </li>
                    @foreach($settings as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="txtWhitecolor">{{$item->rate}}</span>
                            <span class="txtWhitecolor">{{$item->duration}}</span>
                            <span class="txtWhitecolor">{{$item->interest_per_lot}}</span>
                            <span class="btn-outline-warning btn  cursor-pointer"  onclick="changeData({{$item->id}}, {{$item->rate}}, {{$item->duration}}, {{$item->interest_per_lot}})">Transfer</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <form method="post" action="{{route('user-trade-finance-entry')}}">
            @csrf
            <div class="card mt-3 confirmation" id="confirmation" style="display: none">
                <div class="card-body list-group col-md-8 offset-md-2">
                   <div class="row col-md-12">
                       <div class="col-md-6 mb-5 mt-4" style="padding-left: 5%">
                           <div class="txtWhitecolor">Coin<br/><h4>MAB</h4></div>
                           <div class="txtWhitecolor">Activity Duration <span class="day-block"><span id="days">7</span> days</span></div><br/>
                           <div class="txtWhitecolor">Lot amount (Available Lot: {!! floor($dummy_coin_balance/5) !!})</div>
                           <div>
                               <input type="number" class="lot-input" name="lot" id="lot" onchange="setTotalInterest(this)">
                               <input type="hidden" id="ratePerLot" name="ratePerLot">
                               <input type="hidden" id="plan" name="plan">
                               <input type="hidden" id="balance" name="balance" value={{$dummy_coin_balance}}>
                           </div>
                           <div class="txtWhitecolor">= <span id="total">0</span>&nbsp;<span>MAB</span></div>
                       </div>
                       <div class="row col-md-6 mb-5 mt-4">
                           <div>
                               <span class="txtWhitecolor" style="float: left">Lot Size</span>
                               <span class="txtWhitecolor" style="float: right">5 MAB</span>
                           </div>
                           <div>
                               <span class="txtWhitecolor" style="float: left">Interest Per Lot</span>
                               <span class="txtWhitecolor" style="float: right" id="ipl">0.1201 (6.31% Annually)</span>
                           </div>
                           <div>
                               <span class="txtWhitecolor" style="float: left">Value Date</span>
                               <span class="txtWhitecolor" style="float: right">{!! date('Y-m-d h:i:s') !!}</span>
                           </div>
                           <div>
                               <span class="txtWhitecolor" style="float: left">Redemption Date</span>
                               <span class="txtWhitecolor" style="float: right" id="termination">2021-02-26 09:00</span>
                           </div>
                           <div>
                               <span class="txtWhitecolor" style="float: left">Expected Interest</span>
                               <span class="txtWhitecolor" style="float: right" id="interest">0 MAB</span>
                           </div>
                       </div>
                   </div>
                </div>
                <div class="card-body list-group col-md-8 offset-md-2">
                    <div class="row col-md-12 txtWhitecolor" style="text-align: right">
                        <span>
                            <input type="checkbox" id="acceptance" name="acceptance" value="confirm" onchange="confirmButtonCheck()">
                            <label for="vehicle1"> I have read and accepted the terms and conditions of MABCoin</label>
                        </span>
                    </div>
                    <div class="row col-md-12" style="text-align: right">
                        <span>
                            <button class="confirm-button btn-outline-warning" disabled>Confirm Transaction</button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
        <div class="card mt-3">
            <div class="card-body">
                <ul class="container-fluid" style="max-width: 968px">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <p class="col-1 txtWhitecolor ">Lot</p>
                        <p class="col-4 txtWhitecolor">Value Date</p>
                        <p class="col-4 txtWhitecolor">Redemption Date</p>
                        <p class="col-2 txtWhitecolor">Expected Interest</p>
                    </li>
                    @foreach($history as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="col-1 txtWhitecolor">{{$item->lot_count}}</p>
                            <p class="col-4 txtWhitecolor">{{date('d/m/Y ', strtotime($item->value_date))}}</p>
                            <p class="col-4 txtWhitecolor">{{date('d/m/Y ', strtotime($item->redemption_date))}}</p>
                            <p class="col-2 txtWhitecolor">{{$item->expected_interest}}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>


@endsection

@section('custom_js')
    <script>
        function changeData(id, rate, duration, interest)
        {
            console.log(rate);
            console.log(duration);
            console.log(interest);
            $('.confirmation').show();
            $('#days').html(duration);
            $('#ratePerLot').val(interest);
            $('#plan').val(id);
            $('#ipl').html(interest+' ('+rate+'%'+' Annually)');

            let myDate = new Date(new Date().getTime()+(parseInt(duration)*24*60*60*1000));
            $('#termination').html(myDate.getFullYear()+'-'+("0" + myDate.getMonth()).slice(-2)+'-'+("0" + myDate.getDate()).slice(-2)+' '+myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds());
            var elmnt = document.getElementById("confirmation");
            elmnt.scrollIntoView();



        }

        function confirmButtonCheck()
        {
            if ($('input[class="lot-input"]').val() > 0 && $('input[id="acceptance"]:checked').length > 0 && $('#balance').val()/5 >= $('input[class="lot-input"]').val()){
                $('.confirm-button').attr('disabled', false);
            } else {
                $('.confirm-button').attr('disabled', true);
            }
        }

        function setTotalInterest(value)
        {
            console.log(value.value);
            console.log($('#balance').val());
            let totalinterest = $('#ratePerLot').val() * (value.value);
            $('#interest').html(totalinterest+' MAB');
            $('#total').html(value.value * 5);
            if (value.value > 0 && $('input[id="acceptance"]:checked').length > 0 && parseInt($('#balance').val()/5) >= value.value){
                $('.confirm-button').attr('disabled', false);
            } else {
                $('.confirm-button').attr('disabled', true);
            }
        }
    </script>
    <script>
        $("#button").click(function() {
            $('html, body').animate({
                scrollTop: $("#myDiv").offset().top
            }, 2000);
        });
    </script>
@endsection
