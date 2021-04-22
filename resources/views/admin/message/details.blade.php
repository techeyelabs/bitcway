@extends('admin.layouts.main')

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
        position: relative;
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
    .txtHeadingColor{
        color: yellow;
    }
    .txtWhitecolor{
        color: #D3D6D8 !important;
    }
    .msgHeader{
        background-color: #102331;
        padding: 10px 0px 10px 20px;
        margin-bottom: 0px !important;
        border-radius: 10px 10px 0px 0px !important;
    }
    .txtHeadingColor{
        color: yellow;
    }
</style>
@endsection

@section('content')
<div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px; margin: auto;" bis_skin_checked="1">
    <div id="wrap" class="message">
        <h3 class="txtHeadingColor msgHeader">@{{ user }}</h3>

        <div class="card message-list">
            <div class="card-body">
                <div class="list-group" style="height: 650px;background-color: #081420;display:none;">
                                
                        <div v-for="item in messages" class="list-group-item  align-items-center" :class="{'right': item.type==2}" style="border: 0px solid ;">
                            
                                <div v-if="item.type == 2" style="margin-left: 50%; margin-bottom:5px;">
                                    <div class="time" style="font-size:10px; margin-left: 52%;"><i class="far fa-clock"></i> @{{new Date(item.created_at).toLocaleString()}}</div>
                                    {{-- <strong>{{$user->first_name.' '.$user->last_name}}</strong>  --}}
                                    <div class="message txtWhitecolor" style="text-align: right; width: 100%; border-radius: 10px; border: 1px solid #d2d2d2;
                                        padding: 10px; text-align: right; font-size: 18px;">
                                        <pre style="white-space: break-spaces !important;">@{{item.message}}</pre>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="time" style="font-size:10px;"> <i class="far fa-clock"></i> @{{new Date(item.created_at).toLocaleString()}}</div>
                                    {{-- <strong>Admin</strong> --}}
                                    <div class="name d-flex justify-content-between message txtWhitecolor" style="text-align: left;width: 60%; border-radius: 10px; border: 1px solid #d2d2d2;
                                        padding: 10px; text-align: left; font-size: 18px;">
                                        <pre style="white-space: break-spaces !important;">@{{item.message}}</pre>
                                    </div>
                                </div>
                                {{-- <div class="message">@{{item.message}}</div> --}}
                            </div>
                    
                    </div>
            </div>
        </div>
        <div class="card message-input">
            <div class="card-body">
                <form action="#" v-on:submit="send">
                    <div class="input-group">
                        
                        <textarea v-model="message" type="text" class="form-control" style = "width: 283%;height: 75px;" placeholder="type your message here..."></textarea>
                            <br><br>
                            <button  class="btn btn-outline-warning mt-2" type="submit" :disabled="message == ''" style="margin: auto;">Send</button>
                        
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
            user: '',
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
            $('div.list-group').css('display', 'block');

        },
        methods:{
            send(e){
                e.preventDefault();
                let that = this;
                showLoader('Please wait...');
                axios.post('{{route("admin-send-message")}}', {
                    to_id: {{$user->id}},
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
                axios.get('{{route("admin-get-message")}}', {
                    params: {
                        to_id: {{$user->id}}
                    }
                })
                .then(function (response) {
                    // handle success
                    console.log(response);
                    if(response.data.status) {
                        that.user = response.data.messages[0].user.username;
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
