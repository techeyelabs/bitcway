@extends('user.layouts.main')

@section('custom_css')
@endsection

@section('content')
<div id="wrap" class="deposit">
    <h2>Deposit</h2>
    <hr>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">

                    <template v-if='qrCode'>
                        <div class="text-center">
                            <h5>Scan following QR code from your wallet app to complete deposit</h5>
                            <hr>
                            <h6 class="">Pay: @{{parseFloat(amount).toFixed(8)}} BTC </h6>
                            <h6 class="mb-3">To: @{{wallet}}</h6>
                            <img :src="qrCodeLink" alt="">
                        </div>
                        <hr>
                        <a href="{{route('user-wallet')}}" class="btn btn-outline-danger mt-2" >Cancel</a>
                        <a href="#" class="btn btn-outline-success mt-2" v-on:click="done">Done</a>
                    </template>

                    <template v-else>                            
                        <div class="form-group">
                            <label for="">Amount (BTC)</label>
                            <input type="number" class="form-control" aria-describedby="" name="amount"
                                value="{{old('amount')}}" placeholder="Enter amount in bitcoin here..." required v-model="amount">                            
                            @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group text-center">
                            <h4>Equivalent: @{{(amount*rate).toFixed(2)}} (USDT</h4>
                        </div>

                        <a href="#" class="btn btn-outline-success float-end" v-on:click="deposit">Deposit</a>
                    </template>

                    

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('custom_js')
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
                deposit(){
                    let that = this;
                    if(that.amount <= 0 || that.rate <= 0){
                        toastr.error('invalid amount or rate!!');
                        return false;
                    }
                    this.qrCode = true;
                    let walletLink = 'bitcoin:'+this.wallet+'?amount='+that.amount;
                    that.qrCodeLink = "https://chart.googleapis.com/chart?chs=250x250&chld=L|2&cht=qr&chl="+encodeURIComponent(walletLink);
                },
                done(){
                    let that = this;
                    showLoader('please wait...');
                    axios.post('{{route("user-deposit-action")}}', {
                        amount: that.amount,
                        rate: that.rate
                    })
                    .then(function (response) {
                        console.log(response);
                        if(response.data.status) {
                            location.href = "{{route('user-wallet')}}";
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
