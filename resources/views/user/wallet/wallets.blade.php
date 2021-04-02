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

        .txtWhitecolor {
            color: #D3D6D8;
        }

        .txtHeadingColor {
            color: yellow;
        }
    </style>
@endsection

@section('content')
    <div id="wrap" class="deposit">
        <h3 class="txtHeadingColor">Assets</h3>
        <hr>
        <div class="card mt-3 ">
            <div class="card-body">
                <div class="" style="margin: auto">
                    <ul class="container-fluid" style="max-width: 700px">

                        <div class="row  text-left mb-3" style="margin-left: -15px;">
                            <h4 class="txtHeadingColor text-left">Trade Wallet (Equivalent Asset Amount : <span id="totalAmount">00000000.00</span> USD)</h4>
                            <h4 class="col-md-4 txtHeadingColor text-left" id="totalAmount"></h4>
                        </div>


                        <li class="row list-group-item d-flex justify-content-between align-items-center ">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left;">Symbol</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount" style="text-align: center;">Amount</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin" style="text-align: right;">Current
                                Price</p>
                        </li>
                        <?php
                        $i = 0;
                        foreach($wallets as $index =>$item ){
                        $i++;
                        ?>
                        <li class="row list-group-item d-flex justify-content-between align-items-center ">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName{{$index}}"
                               style="text-align: left;">{{$item->currency->name}}</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount{{$index}}"
                               style="text-align: center;">{{$item->balance}}</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin{{$index}}"
                               style="text-align: right;"></p>
                        </li>
                        <?php
                        }
                        ?>
                        <p style="display: none;" id="myCoinIndex">{{$i}}</p>
                    </ul>
                </div>
            </div>
        </div>
        {{--Trade Wallet End--}}

        {{--Derivative Wallet Start--}}
        <div class="card mt-3">
            <div class="card-body">
                <div class="mb-4" style="margin: auto">
                    <div class="container-fluid" style="max-width: 700px">
                        <div class="text-left" style="margin-left: -15px;"><h4 class="txtHeadingColor">Derivative
                                Wallet</h4></div>
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                                data-bs-target="#derivativeModal" style="margin-left: -13px;width: 100px;"
                                onclick="setFlag(1)">Deposit
                        </button>
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                data-bs-target="#derivativeModal" style="width: 100px;" onclick="setFlag(2)">Withdraw
                        </button>
                    </div>
                </div>
                <div style="margin: auto">
                    <ul class="container-fluid" style="max-width: 700px">
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left; color:white;">
                                Symbol</p>
                            <p class="col txtWhitecolor" id="derivati8vePercent" style="text-align: left; color:white;">
                                Leverage</p>
                            <p class="col txtWhitecolor" id="derivati8vePercent" style="text-align: left; color:white;">
                                Rate</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount" style="text-align: right;color:white;">
                                Amount</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin2"
                               style="text-align: right;color:white;">Current Price</p>

                        </li>
                        <?php
                        $j = 0;
                        foreach($transactionHistory as $index =>$item ){
                        if(($item->leverage) >= 1 ){
                        $j++;
                        ?>
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName2{{$j}}"
                               style="text-align: left; color:white;">{{$item->currencyName->name}}</p>
                            <p class="col txtWhitecolor" id="derivativePercent{{$j}}"
                               style="text-align: left; color:white;">{{$item->leverage}}</p>
                            <p class="col txtWhitecolor" id="derivativePercent{{$j}}"
                               style="text-align: left; color:white;">{!! number_format((double)($item->equivalent_amount / $item->leverage),5) !!}</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount2{{$j}}"
                               style="text-align: right;color:white;">{!! number_format((double)($item->amount), 5) !!}</p>
                            <p class="col txtWhitecolor" id="CoinpriceintoMycoin2{{$j}}"
                               style="text-align: right;color:white;"></p>

                        </li>
                        <?php
                        }
                        }

                        ?>
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
                    <ul class="container-fluid" style="max-width: 700px">
                        <div class="text-left mb-3" style="margin-left: -15px;"><h4 class="txtHeadingColor">Finance
                                Wallet</h4></div>
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="MyCoinCurrencyName" style="text-align: left; ">Lot</p>
                            <p class="col txtWhitecolor" id="CoinpriceIntoMycoin" style="text-align: left;">Value
                                Date</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount" style="text-align: right;">Redemption
                                Date</p>
                            <p class="col txtWhitecolor" id="MyTotalCoinAmount" style="text-align: right;">Expected
                                Interest</p>
                        </li>
                        @foreach($finances as $finance)
                            <li class="row list-group-item d-flex justify-content-between align-items-center">
                                <p class="col txtWhitecolor" id="MyCoinCurrencyName"
                                   style="text-align: left;">{{$finance->lot_count}}</p>
                                <p class="col txtWhitecolor" id="CoinpriceIntoMycoin"
                                   style="text-align: left;">{{date('d/m/Y', strtotime($finance->value_date))}}</p>
                                <p class="col txtWhitecolor" id="MyTotalCoinAmount"
                                   style="text-align: right;">{{date('d/m/Y', strtotime($finance->redemption_date))}}</p>
                                <p class="col txtWhitecolor" id="MyTotalCoinAmount"
                                   style="text-align: right;">{{$finance->expected_interest}}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        {{--Finance Wallet End--}}
    </div>
    <!-- Modal -->
    <div class="modal fade" id="derivativeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content border-light">
                <div class="modal-header modalbg">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="background-color: #ffffff;"></button>
                </div>
                <form class="formclas" action="{{route('derivativedeposit')}}" method="post">
                    @csrf
                    <div class="modal-body modalbg">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label"
                                   style="color: #ffffff; padding-top: 0px;">Input Amount:</label>
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
    <script>
        const socket = io('http://bitc-way.com:3000');
        let loaded = false;
        socket.on('trackers', (trackers) => {
            // console.log(trackers);
            // Home.trackers = trackers.trackers;
            let totalValue = 0;
            let indexNumber = $('#myCoinIndex').html();
            for (let i = 0; i <= indexNumber; i++) {
                let currencyName = $('#MyCoinCurrencyName' + i).html();
                let currencyAmount = $('#MyTotalCoinAmount' + i).html();


                let full_data = trackers.trackers;
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + currencyName + 'USD') {
                        $('#CoinpriceIntoMycoin' + i).html((currencyAmount * item[1]).toFixed(2));
                    }
                });
            }

            for (let i = 0; i < indexNumber; i++) {
                totalValue += parseFloat($('#CoinpriceIntoMycoin' + i).text());
                $('#totalAmount').html((totalValue).toFixed(2));
                $('#totalAsset').html((totalValue).toFixed(2));

            }

            let indexNumber2 = $('#myCoinIndex2').html();
            for (let j = 1; j <= indexNumber2; j++) {
                let currencyName = $('#MyCoinCurrencyName2' + j).html();
                let currencyAmount = $('#MyTotalCoinAmount2' + j).html();

                let full_data = trackers.trackers;
                full_data.forEach(async function (item) {
                    if (item[0] === 't' + currencyName + 'USD') {
                        $('#CoinpriceintoMycoin2' + j).html((currencyAmount * item[1]).toFixed(2));
                    }
                });
            }
            if (loaded == false) {
                setInterval(function () {
                }, 1000);
                loaded = true;
            }
        })
    </script>

    <script>
        function setFlag(type) {

            $('#flag').val(type);
        }
    </script>
    <script>
        function manage(amount) {
            var bt = document.getElementById('btSubmit');
            var available_balance_deposit = document.getElementById('amount').value;
            var available_balance_withdraw = document.getElementById('derivativeanount').value;
            var flag = document.getElementById('flag').value;
            console.log(flag);
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
    </script>
    <script>

    </script>
@endsection
