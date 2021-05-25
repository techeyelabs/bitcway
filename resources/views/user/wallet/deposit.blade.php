@extends('user.layouts.main')

@section('custom_css')
    <style>
        .txtWhitecolor{
            color: white;
        }
    </style>
@endsection

@section('content')
    <div id="wrap" class="deposit">
        <h3 class="txtWhitecolor">{{__('button3')}}</h3>
        <hr>

        <div class="row">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="txtWhitecolor" for="">{{__('col14')}} (BTC)</label>
                            <input type="number" class="form-control" aria-describedby="" name="amount"
                                   value="{{old('amount')}}" placeholder="Enter amount in bitcoin here..." required v-model="amount" v-on:keyup="hcgenerate">
                            @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group text-center">
                            <h4 class="txtWhitecolor">Equivalent: <span id="amountRate"> @{{amount*rate}}</span> (USD)</h4>
                        </div>
                        <button class="btn btn-outline-warning float-end" onclick="gatewaypost()">{{__('button3')}}</button>

                        <div class="BITCPaymentGateway">
                            <form id="formForGateway" action ="https://api.saiwin.co/generate" method = "post">
                                <input type = "text" name = "hash_key" id="hash_key" value = "{{$hash_key}}">
                                <input type = "text" name = "site_id" id="site_id" value = "{{$site_id}}">
                                <input type ="number" name = "trading_id" id="trading_id" value = "{{$trading_id}}">
                                <input type = "number" name ="amount" id="rate" value = "">
                                <input type ="text" name = "hc" id="hc" value = "">
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        function gatewaypost() {
            axios.post('{{route("getwayUriResponse", app()->getLocale())}}', {
                site_id: $('#site_id').val(),
                trading_id: $('#trading_id').val(),
                amount: $('#amountRate').html(),
                hc: $('#hc').val()
            })
                .then(function (response) {
                    let url = response.data;
                    location.href = url.url;
                    hideLoader();
                })

        }
    </script>
    <script>
        let deposit = new Vue({
            el: '.deposit',
            data: {
                rate: '{{$rate}}',
                amount: 0,
                qrCode: false,
                wallet: '1MoLoCh1srp6jjQgPmwSf5Be5PU98NJHgx',
                qrCodeLink: null
            },
            mounted(){

            },

            methods:{
                hcgenerate(){
                    let that = this;
                    $('#rate').val(that.amount*that.rate);
                    // showLoader('please wait...');
                    axios.post('{{route("hcgenerate", app()->getLocale())}}', {
                        hash_key: $('#hash_key').val(),
                        site_id: $('#site_id').val(),
                        trading_id: $('#trading_id').val(),
                        rate: parseFloat($('#amountRate').html())
                    })
                        .then(function (response) {
                            console.log(response);
                            $("#hc").val(response.data[0]);
                            hideLoader();
                        })
                        .catch(function (error) {
                            toastr.error('error occured,please try again');
                            hideLoader();
                        });
                },
                done(){
                    let that = this;
                    showLoader('please wait...');
                    axios.post('{{route("user-deposit-action", app()->getLocale())}}', {
                        amount: that.amount,
                        rate: that.rate
                    })
                        .then(function (response) {
                            console.log(response);
                            if(response.data.status) {
                                location.href = "{{route('user-wallet', app()->getLocale())}}";
                            }
                            else toastr.error('error occured,please try again');
                            hideLoader();
                        })
                        .catch(function (error) {
                            toastr.error('error occured,please try again');
                            hideLoader();
                        });
                }
            }
        });
    </script>
@endsection
