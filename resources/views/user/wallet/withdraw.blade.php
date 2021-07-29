@extends('user.layouts.main')

@section('custom_css')
@endsection

@section('content')
<div id="wrap" class="deposit">
    <h3 class="txtWhitecolor pageTitle">{{__('button4')}}</h3>
    <hr>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    @if(  Auth::user()->withdraw_message!= null || Auth::user()->withdraw_message!= "")
                        <div class="form-group text-center">
                            <h4 class="txtWhitecolor">{{$withdraw_message}}</h4>
                        </div>
                    @else

                    <template v-if='done'>
                        <div class="alert alert-success alert-dismissible fade show mb-3 bg-transparent " role="alert" style="color:white !important;" >
                            Withdraw request has been sent.
                        </div>
                        <a href="{{route('user-wallet',['id'=>1,app()->getLocale()])}}" class="btn btn-outline-info">Exit</a>
                    </template>

                    <template v-else>

                        <div class="form-group text-center">
                            <h4 class="txtWhitecolor" >Available: @{{balance}} USD</h4>
                        </div>
                        <span class="d-none" id="mainBalance">@{{balance}}</span>
                        <span class="d-none" id="availableBalance">@{{availableBalance}}</span>

                        <div class="form-group">
                            <label class="txtWhitecolor" for="">{{__('col10')}} (USD)</label>
                            <input type="number" class="form-control" aria-describedby="" name="amount" id="amount"
                                   value="{{old('amount')}}" placeholder="Enter amount in USD here..." required v-model="amount" :disabled="lockedBalanceFun<99" onkeyup="isBalanceAvailable()">
                            @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <label class="txtWhitecolor mt-3" for="">BTC Wallet Address</label>
                            <input type="text" class="form-control" aria-describedby="" name="walletAddress" id="walletAddress"
                                value="{{old('walletAddress')}}" placeholder="Enter BTC wallet address here..." required v-model="walletAddress">
                            @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button class="btn btn-outline-warning float-end" id="withdrawBtn" v-on:click="withdraw"  disabled >{{__('button4')}}</button>
                            @endif
                    </template>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('custom_js')
    <script>
        function isBalanceAvailable() {
            let availableBalance = parseFloat($('#availableBalance').text());
            $("#amount").keyup(function() {
                let amountInput = parseFloat($('#amount').val());
                let walletaddress = $('#walletAddress').val();
                if (amountInput >= 100 && amountInput <= availableBalance && walletaddress != ''){
                    $("#withdrawBtn").attr("disabled", false);
                }else{
                    $("#withdrawBtn").attr("disabled", true);
                }
            });

            $("#walletAddress").keyup(function() {
                let amountInput = parseFloat($('#amount').val());
                let walletaddress = $('#walletAddress').val();
                if (amountInput >= 100 && amountInput <= availableBalance && walletaddress != ''){
                    $("#withdrawBtn").attr("disabled", false);
                }else{
                    $("#withdrawBtn").attr("disabled", true);
                }
            });
        }
    </script>
    <script>
        let deposit = new Vue({
            el: '.deposit',
            data: {
                amount: '',
                walletAddress: '',
                balance: '{{Auth::user()->balance}}',
                done: false,
                availableBalance:''
            },
            mounted(){
            },
            computed: {
                lockedBalanceFun: function(){
                    let that = this;
                    if (that.balance > 199){
                        return that.availableBalance = that.balance - 100;
                    }else{
                        return that.availableBalance = 0;
                    }
                }
            },
            methods:{
                withdraw(){
                    let that = this;
                    if(that.amount >= this.balance){
                        toastr.error('invalid amount or rate!!');
                        return false;
                    }
                    showLoader('please wait...');
                    axios.post('{{route("user-withdraw-action", app()->getLocale())}}', {
                        amount: that.amount,
                        walletAddress: that.walletAddress
                    })
                    .then(function (response) {
                        console.log(response);
                        if(response.data.status) that.done = true;
                        else toastr.error('Error occured, please try again');
                        hideLoader();
                    })
                    .catch(function (error) {
                        toastr.error('Error occured, please try again');
                        hideLoader();
                    });
                }
            }
        });
    </script>
@endsection
