@extends('user.layouts.main')

@section('custom_css')
    <style>
        .txtWhitecolor{
            color: #D3D6D8;
        }
    </style>
@endsection

@section('content')
<div id="wrap" class="deposit">
    <h3 class="txtWhitecolor">Withdraw</h3>
    <hr>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">

                    @if($notification->display_message == 1)
                    <div class="form-group text-center">
                        <h4 class="txtWhitecolor">{{$notification->message}}</h4>
                    </div>
                    @else

                    <template v-if='done'>

                        <div class="alert alert-success alert-dismissible fade show mb-3 bg-transparent " role="alert" style="color:#D3D6D8 !important;" >
                            Withdraw request has been sent.
                        </div>
                        <a href="{{route('user-wallet',['id'=>1])}}" class="btn btn-outline-info">Exit</a>
                    </template>

                    <template v-else>
                        <div class="form-group text-center">
                            <h4 class="txtWhitecolor">Available: @{{balance}} USD</h4>
                        </div>
                        
                        <div class="form-group">
                            <label class="txtWhitecolor" for="">Amount (USD)</label>
                            <input type="number" class="form-control" aria-describedby="" name="amount"
                                value="{{old('amount')}}" placeholder="Enter amount in bitcoin here..." required v-model="amount" :disabled="balance<=0">                            
                            @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        

                        <a href="#" class="btn btn-outline-warning float-end" v-on:click="withdraw" :class="{disabled: amount<=0}">Withdraw</a>
                    </template>
                    @endif
                    

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
                amount: 0,
                balance: '{{Auth::user()->balance}}',
                done: false
            },
            mounted(){

            },

            methods:{
                withdraw(){
                    let that = this;
                    if(that.amount > this.balance){
                        toastr.error('invalid amount or rate!!');
                        return false;
                    }
                    showLoader('please wait...');
                    axios.post('{{route("user-withdraw-action")}}', {
                        amount: that.amount
                    })
                    .then(function (response) {
                        console.log(response);
                        if(response.data.status) that.done = true;
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
