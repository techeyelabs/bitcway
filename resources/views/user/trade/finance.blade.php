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
            /*text-align: left !important;*/

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

{{--        <div class="card mt-3">--}}
{{--            <div class="card-body">--}}
{{--                <ul class="container-fluid" style="max-width: 700px">--}}
{{--                    <li class="list-group-item d-flex justify-content-between align-items-center">--}}
{{--                        <span class="txtWhitecolor">Rate</span>--}}
{{--                        <span class="txtWhitecolor">Duration</span>--}}
{{--                        <span class="txtWhitecolor" style="margin-right: 60px;">Interest Per Lot</span>--}}
{{--                        <span class="txtWhitecolor"></span>--}}
{{--                    </li>--}}
{{--                    @foreach($settings as $item)--}}
{{--                        <li class="list-group-item d-flex justify-content-between align-items-center">--}}
{{--                            <span class="txtWhitecolor">{{$item->rate}}</span>--}}
{{--                            <span class="txtWhitecolor">{{$item->duration}}</span>--}}
{{--                            <span class="txtWhitecolor">{{$item->interest_per_lot}}</span>--}}
{{--                            <span class="btn-outline-warning btn  cursor-pointer"  onclick="changeData({{$item->id}}, {{$item->rate}}, {{$item->duration}}, {{$item->interest_per_lot}})">Transfer</span>--}}
{{--                            <span class="btn-outline-warning btn  cursor-pointer"  onclick="changeData({{$item->id}}, 6.31, 14, {{$item->interest_per_lot}})">Transfer</span>--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}

        {{--Finance locked Saving Setting Div2 End--}}

        <div class="card mt-3">
            <div class="card-body">

                <div style="margin: auto; overflow-x: auto">
                    <ul class="container-fluid" style=" min-width: 600px;">
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="" style="text-align: left; ">Symbol</p>
                            <p class="col txtWhitecolor" id="" style="text-align: left; ">Annualized Interest Rate</p>
                            <p class="col txtWhitecolor" id="" style="text-align: center;">Duration (Days)</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">Interest Per Lot</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">Action</p>
                        </li>
                        <div id="btnDurationId">
                            @foreach($lockedFinanceSettings as $index => $FinanceSetting)
                            <li class="row list-group-item d-flex justify-content-between align-items-center">
                                <p class="col txtWhitecolor" id="currencyName{{$index}}" data-value="{{$FinanceSetting->currency_id}}" style="text-align: left;">{{$FinanceSetting->currency->name}}</p>
                                <p class="col txtWhitecolor" id="selectedRate{{$index}}" style="text-align: left;">{{$FinanceSetting->rate_1}}%</p>
                                <p class="d-none" id="planId{{$index}}">{{$FinanceSetting->id}}</p>
                                <p class="d-none" id="selectedDuration{{$index}}"></p>
                                <p class="d-none" id="currencyId{{$index}}">{{$FinanceSetting->currency_id}}</p>
                                <p class="d-none" id="lotSize{{$index}}">{{$FinanceSetting->lot_size}}</p>
                                <p class="col btn-group" role="group" aria-label="Basic outlined example" style="width: 10px !important; text-align: center">
                                    <button type="button" class="btn btn-outline-primary btnDuration shadow-none" id="durationBtn1{{$index}}" onclick="rateFun({{$FinanceSetting->rate_1}}, {{$index}}); rateDuration({{$FinanceSetting->duration_1}}, {{$index}}); interestPerLotFun({{$FinanceSetting->rate_1}}, {{$FinanceSetting->duration_1}}, {{$index}}); transerButFun({{$index}});">{{$FinanceSetting->duration_1}}</button>
                                    <button type="button" class="btn btn-outline-primary btnDuration shadow-none" id="durationBtn2{{$index}}" onclick="rateFun({{$FinanceSetting->rate_2}}, {{$index}}); rateDuration({{$FinanceSetting->duration_2}}, {{$index}}); interestPerLotFun({{$FinanceSetting->rate_2}}, {{$FinanceSetting->duration_2}}, {{$index}}); transerButFun({{$index}});">{{$FinanceSetting->duration_2}}</button>
                                    <button type="button" class="btn btn-outline-primary btnDuration shadow-none" id="durationBtn3{{$index}}" onclick="rateFun({{$FinanceSetting->rate_3}}, {{$index}}); rateDuration({{$FinanceSetting->duration_3}}, {{$index}}); interestPerLotFun({{$FinanceSetting->rate_3}}, {{$FinanceSetting->duration_3}}, {{$index}}); transerButFun({{$index}});">{{$FinanceSetting->duration_3}}</button>
                                    <button type="button" class="btn btn-outline-primary btnDuration shadow-none" id="durationBtn4{{$index}}" onclick="rateFun({{$FinanceSetting->rate_4}}, {{$index}}); rateDuration({{$FinanceSetting->duration_4}}, {{$index}}); interestPerLotFun({{$FinanceSetting->rate_4}}, {{$FinanceSetting->duration_4}}, {{$index}}); transerButFun({{$index}});">{{$FinanceSetting->duration_4}}</button>
                                </p>
                                <p class="col txtWhitecolor" id="interestPerLot{{$index}}" style="text-align: right;">{!! number_format((float)(($FinanceSetting->rate_1/365)*$FinanceSetting->duration_1), 4) !!}</p>
                                <p class="col txtWhitecolor" id="" style="text-align: right;">
                                    <button class="btn-outline-warning btn cursor-pointer" id="transferBtn{{$index}}"  onclick="changeData({{$index}});" disabled>Transfer</button>

                                    <span style="display: none" id="lotCurrencyAmount{{$index}}">{{isset($dummy_coin_balance[$FinanceSetting->currency_id]) ? $dummy_coin_balance[$FinanceSetting->currency_id]:0}}</span>
                                </p>
                            </li>
                            @endforeach
                        </div>
                    </ul>
                </div>
            </div>
            {{--Finance Wallet End--}}
        </div>
        {{--Finance locked Saving Setting Div2 End--}}

        <form method="post" action="{{route('user-trade-finance-entry')}}">
            @csrf
            <div class="card mt-3 confirmation" id="confirmation" style="display: none">
                <div class="card-body list-group col-md-8 offset-md-2">
                   <div class="row col-md-12">
                       <div class="col-md-6 mb-5 mt-4" style="padding-left: 5%">
                           <div class="txtWhitecolor" id="coinName">Coin<br/><h4>MAB</h4></div>
                           <span class="d-none" id="coinName_hidden">Coin</span>
                           <div class="txtWhitecolor">Activity Duration <span class="day-block"><span id="duration"></span> days</span></div><br/>
                           <div class="txtWhitecolor" >Lot amount (Available Lot: <span id="lotCurrency">00</span>)</div>
                           <div>
                               <input type="number" class="lot-input" name="lot" id="lot" onkeyup="setTotalInterest(this)">
                               <input type="hidden" id="ratePerLot" name="ratePerLot" value="">
                               <input type="hidden" id="plan" name="plan" value="">
                               <input class="d-none" type="number" id="coiId" name="coinId" value="">
{{--                               <input type="hidden" id="balance" name="balance" value="">--}}
                           </div>
                           <div class="txtWhitecolor">= <span id="total">0</span>&nbsp;<span id="totalCoinName">Coin</span></div>
                           <input type="hidden" id="totalCoin" name="totalCoin" value="">
                       </div>
                       <div class="row col-md-6 mb-5 mt-4">
                           <div>
                               <span class="txtWhitecolor" style="float: left">Lot Size</span>
                               <span class="txtWhitecolor" id="lotSizeId" style="float: right">5 MAB</span>
                               <span class="d-none" id="lotSizeId_hidden"></span>
                               <input class="d-none" type="number" id="coinLotSize" name="coinLotSize" value="">
                           </div>
                           <div>
                               <span class="txtWhitecolor" style="float: left">Interest Per Lot</span>
                               <span class="txtWhitecolor"  style="float: right" id="ipl">0.0000 (0.00% Annually)</span>
                               <span class="d-none" id="ipl_hidden"></span>
                           </div>
                           <div>
                               <span class="txtWhitecolor" style="float: left">Value Date</span>
                               <span class="txtWhitecolor" style="float: right">{!! date('Y-m-d h:i:s') !!}</span>
                           </div>
                           <div>
                               <span class="txtWhitecolor" style="float: left">Redemption Date</span>
                               <span class="txtWhitecolor" style="float: right" id="termination">2021-00-00 00:00</span>
                               <input type="hidden" id="redemptionTimeId" name="redemptionTime" value="">
                           </div>
                           <div>
                               <span class="txtWhitecolor" style="float: left">Expected Interest</span>
                               <span class="txtWhitecolor" style="float: right" id="interest">0 Coin</span>
                               <input type="hidden" id="expected_interest" name="expected_interest" value="">
                           </div>
                       </div>
                   </div>
                </div>
                <div class="card-body list-group col-md-8 offset-md-2">
                    <div class="row col-md-12 txtWhitecolor" style="text-align: right">
                        <span>
                            <input type="checkbox" id="acceptance" name="acceptance" value="confirm" onchange="confirmButtonCheck()">
                            <label for="vehicle1"> I have read and accepted the terms and conditions of <span id="termCoinName">MAB</span> Coin</label>
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


{{--        <div class="card mt-3">--}}
{{--            <div class="card-body">--}}
{{--                <ul class="container-fluid" style="max-width: 968px">--}}
{{--                    <li class="list-group-item d-flex justify-content-between align-items-center">--}}
{{--                        <p class="col-1 txtWhitecolor ">Lot</p>--}}
{{--                        <p class="col-4 txtWhitecolor">Value Date</p>--}}
{{--                        <p class="col-4 txtWhitecolor">Redemption Date</p>--}}
{{--                        <p class="col-2 txtWhitecolor">Expected Interest</p>--}}
{{--                    </li>--}}
{{--                    @foreach($history as $item)--}}
{{--                        <li class="list-group-item d-flex justify-content-between align-items-center">--}}
{{--                            <p class="col-1 txtWhitecolor">{{$item->lot_count}}</p>--}}
{{--                            <p class="col-4 txtWhitecolor">{{date('d/m/Y ', strtotime($item->value_date))}}</p>--}}
{{--                            <p class="col-4 txtWhitecolor">{{date('d/m/Y ', strtotime($item->redemption_date))}}</p>--}}
{{--                            <p class="col-2 txtWhitecolor">{{$item->expected_interest}}</p>--}}
{{--                        </li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}


    </div>


@endsection

@section('custom_js')

    <script>
        function transerButFun(index) {
            $('#transferBtn'+index).prop("disabled",false);
            for(let a = 0; a <4; a++){
                if (a == parseInt(index))
                    continue
                else
                    $('#transferBtn'+a).prop("disabled",true);
            }
        }
        function rateDuration(val, index) {
            $('#selectedDuration'+ index).html(val);
        }
        function rateFun(value, index) {
            $('#selectedRate'+ index).html(value+ "%");
        }
        function interestPerLotFun(interestRate, duration, index) {
            let InterestPerLot = ((interestRate/365)*duration);
            $('#interestPerLot'+ index).html((InterestPerLot).toFixed(4));
        }
        function changeData(index)
        {
            let availableLot = 0;
            let planId = $('#planId' + index).html();
            let currencyId = $('#currencyId'+ index).html();
            let currencyName = $('#currencyName'+ index).html();
            let selectedRate = $('#selectedRate'+ index).html();
            let selectedDuration =  $('#selectedDuration' + index).html();
            let lotSize = $('#lotSize'+ index).html();
            let lotCoinAmount = $('#lotCurrencyAmount'+ index).html();
            let perLotInterest = $('#interestPerLot' + index).html();
            let transferButton = $('transferBtn' + index).html();


            availableLot = Math.floor(lotCoinAmount/lotSize);

            $('#ipl_hidden').html(perLotInterest);
            $('#coinName_hidden').html(currencyName);
            $('#lotSizeId_hidden').html(lotSize);
            $('#plan').val(planId);
            $('#coiId').val(currencyId);
            $('#redemptionTimeId').val(selectedDuration);
            $('#termCoinName').html(currencyName);
            $('#coinLotSize').val(lotSize);


            $('.confirmation').show();
            $('#coinName').html(currencyName);
            $('#duration').html(selectedDuration);
            $('#lotSizeId').html(lotSize+' '+currencyName);
            $('#ipl').html(perLotInterest+' ('+ selectedRate+' Annually)');
            let myDate = new Date(new Date().getTime()+(parseInt(selectedDuration)*24*60*60*1000));
            $('#termination').html(myDate.getFullYear()+'-'+("0" + myDate.getMonth()).slice(-2)+'-'+("0" + myDate.getDate()).slice(-2)+' '+myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds());


            if (availableLot > 0){
                $('#lotCurrency').html(availableLot);
            }else{
                $('#lotCurrency').html(0);
            }


            // $('#days').html(duration);
            // $('#ratePerLot').val(interest);
            // $('#plan').val(id);
            // $('#ipl').html(interest+' ('+rate+'%'+' Annually)');
            //
            // let myDate = new Date(new Date().getTime()+(parseInt(duration)*24*60*60*1000));
            // $('#termination').html(myDate.getFullYear()+'-'+("0" + myDate.getMonth()).slice(-2)+'-'+("0" + myDate.getDate()).slice(-2)+' '+myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds());
            var elmnt = document.getElementById("confirmation");
            elmnt.scrollIntoView();

        }

        function confirmButtonCheck()
        {
            let availableLot = $('#lotCurrency').html();
            if ($('input[class="lot-input"]').val() > 0 && $('input[id="acceptance"]:checked').length > 0 && availableLot >= $('input[class="lot-input"]').val()){
                $('.confirm-button').attr('disabled', false);
            } else {
                $('.confirm-button').attr('disabled', true);
            }
        }

        function setTotalInterest(value)
        {
            let interestPerRate =$('#ipl_hidden').html();
            $('#ratePerLot').val(interestPerRate);
            let totalInterest = interestPerRate * value.value;
            let currencyName = $('#coinName_hidden').html();
            $('#interest').html(totalInterest+' '+currencyName);
            $('#expected_interest').val(totalInterest);
            let hiddenLotSize = $('#lotSizeId_hidden').html();
            $('#total').html(hiddenLotSize * value.value);
            $('#totalCoinName').html(currencyName);
            $('#totalCoin').val(hiddenLotSize * value.value);


            // let totalinterest = $('#ratePerLot').val() * (value.value);
            // $('#total').html(value.value * 5);
            // if (value.value > 0 && $('input[id="acceptance"]:checked').length > 0 && parseInt($('#balance').val()/5) >= value.value){
            //     $('.confirm-button').attr('disabled', false);
            // } else {
            //     $('.confirm-button').attr('disabled', true);
            // }
        }
    </script>
    <script>
        $("#button").click(function() {
            $('html, body').animate({
                scrollTop: $("#myDiv").offset().top
            }, 2000);
        });
    </script>
    <script>
        var header = document.getElementById("btnDurationId");
        var btns = header.getElementsByClassName("btnDuration");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("active");
                if (current.length > 0) {
                    current[0].className = current[0].className.replace(" active", "");
                }
                this.className += " active";
            });
        }

    </script>
    <script>

    </script>
@endsection
