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

<div id="wrap" class="message">
    <h2>Message List</h2>
    <hr>
    <div class="card message-list">
        <div class="card-body">
            <ul class="list-group">
                              
                    <li v-for="item in messages" class="list-group-item  align-items-center" :class="{'right': item.type==2}">
                        
                            <div v-if="item.type == 1" class="name d-flex justify-content-between">
                                <strong>{{$user->first_name.' '.$user->last_name}}</strong> 
                                <small class="time"><i class="far fa-clock"></i> @{{new Date(item.created_at).toLocaleString()}}</small>
                            </div>
                            <div v-else class="name d-flex justify-content-between">
                                <small class="time"> <i class="far fa-clock"></i> @{{new Date(item.created_at).toLocaleString()}}</small>
                                <strong>Admin</strong>
                            </div>
                            <div class="message">@{{item.message}}</div>
                    </li>
                
            </ul>
        </div>
    </div>
    <div class="card message-input">
        <div class="card-body">
            <form action="#" v-on:submit="send">
                <div class="input-group">
                    
                        <input v-model="message" type="text" class="form-control" placeholder="type your message here...">
                        <button  class="btn btn-outline-success" type="submit" :disabled="message == ''">Send</button>
                    
                </div>
            </form>
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
