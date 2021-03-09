@extends('admin.layouts.main')

@section('custom_css')
<style>
    .item{
        width: 100%;
        display: block;
    }
</style>
@endsection

@section('content')

<div id="wrap">
    <h2>Message List</h2>
    <hr>
    <div class="card">
        <div class="card-body">
            <ul class="list-group">
                <?php foreach($messages as $item){?>
                <a class="item" href="{{route('admin-message-details', ['to_id' => $item->user_id])}}">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        
                            <span>
                                {{$item->user->first_name.' '.$item->user->last_name}} at {{date('d/m/Y H:i', strtotime($item->created_at))}}
                                <br>
                                <small>{{$item->latestMessage()->message}}</small>
                            </span>

                            <span class="badge bg-primary pill">{{$item->unread()}}</span>
                        
                    </li>
                </a>
                <?php }?>
            </ul>
        </div>
    </div>
    
</div>



@endsection

@section('custom_js')
<script type="text/javascript">
</script>
@endsection
