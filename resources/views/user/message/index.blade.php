@extends('user.layouts.main')

@section('custom_css')
<style>
    .message{
        height: 100%;
        position: relative;
    }
    .right .message{
        text-align: right;
    }
    .message-input{
        width: 100%;
        position: absolute;
        bottom: 0px;
    }
    .message-list{
        height: calc(100% - 80px);
        overflow-y: scroll;
    }
    form{
        padding-top: 0px;
        font-size: 14px;
        margin-top: 0px;
    }
</style>
@endsection

@section('content')
<div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px; margin: auto;" bis_skin_checked="1">
    <div id="wrap" class="message">
        <h2>Message List</h2>
        <hr>
        <div class="card message-list">
            <div class="card-body">
                <div class="list-group" style="height: 800px;">
                                
                        <div v-for="item in messages" class="list-group-item  align-items-center" :class="{'right': item.type==1}" style="border: 0px solid ;">
                            
                                <div v-if="item.type == 1" style="margin-left: 50%; margin-bottom:5px;">
                                    <div class="time" style="font-size:10px; margin-left: 52%;"> <i class="far fa-clock"></i> @{{new Date(item.created_at).toLocaleString()}}</div>
                                    {{-- <strong>{{Auth::user()->first_name.' '.Auth::user()->last_name}}</strong> --}}
                                    <div class="message" style="text-align: left; width: 100%; border-radius: 10px; border: 1px solid #d2d2d2;
                                    padding: 10px; text-align: right; font-size: 18px;">
                                        @{{item.message}}
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="time" style="font-size:10px;" ><i class="far fa-clock"></i> @{{new Date(item.created_at).toLocaleString()}}</div>
                                    <div class="name d-flex justify-content-between message" style="text-align: left;width: 60%; border-radius: 10px; border: 1px solid #d2d2d2;
                                    padding: 10px; text-align: left; font-size: 18px;">
                                        @{{item.message}}
                                    </div>
                                    
                                </div>
                        </div>                   
                    </div>
            </div>
        </div>
        <div class="card message-input">
            <div class="card-body">
                <form action="#" v-on:submit="send">
                    <div class="input-group">
                        
                            <input v-model="message" type="text" class="form-control" style = "width: 283%;" placeholder="type your message here...">
                            <br><br>
                            <button  class="btn btn-outline-success mt-2" type="submit" :disabled="message == ''" style="margin: auto;">Send</button>
                        
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>



@endsection

@section('custom_js')
<script type="text/javascript">
    let message = new Vue({
        el: '.message',
        data: {
            messages: [],
            message: ''
        },
        mounted(){
            let that = this;
            $('.message-list').scrollTop($('.message-list')[0].scrollHeight);
            this.getMessage();
            setInterval(function(){
                that.getMessage();
            }, 5000);
        },
        methods:{
            send(e){
                e.preventDefault();
                let that = this;
                showLoader('Please wait...');
                axios.post('{{route("user-send-message")}}', {
                    message: that.message
                })
                .then(function (response) {
                    if(response.data.status) {
                        that.messages.push(response.data.item);
                        that.$nextTick(function(){
                            $('.message-list').scrollTop($('.message-list')[0].scrollHeight);
                        })
                        
                    }
                    else toastr.error('error occured,please try again');
                    that.message = '';
                    hideLoader();
                })
                .catch(function (error) {
                    toastr.error('error occured,please try again');
                    hideLoader();
                });
            },
            getMessage(){
                let that = this;
                axios.get('{{route("user-get-message")}}')
                .then(function (response) {
                    // handle success
                    console.log(response);
                    if(response.data.status) {
                        that.messages = response.data.messages;
                        that.$nextTick(function(){
                            $('.message-list').scrollTop($('.message-list')[0].scrollHeight);
                        })                        
                    }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                    hideLoader();
                    // setTimeout(that.getMessage(), 50000);
                });
            }
        }
    });
</script>
@endsection
