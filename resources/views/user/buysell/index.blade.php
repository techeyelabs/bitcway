@extends('user.layouts.main')

@section('custom_css')
@endsection
@section('content')

<div id="wrap">
    <h3 class="txtHeadingColor pageTitle">{{__('title17')}}</h3>
    <hr>
    <div class="card">
        <div class="card-body historyTable">
            <table class="table">
                <thead>
                    <th class="txtWhitecolor fs14px">{{__('col8')}}</th>
                    <th class="txtWhitecolor fs14px">{{__('column1')}}</th>
                    <th class="txtWhitecolor fs14px">{{__('col10')}}</th>
                    <th class="txtWhitecolor fs14px">USD</th>
                    <th class="txtWhitecolor fs14px">{{__('col12')}}</th>
                </thead>
                <tbody>
                    <?php foreach($transactions as $item){?>
                    <tr>
                        <td class="txtWhitecolor fs14px">{{date('d/m/Y', strtotime($item->created_at))}}</td>
                        <td class="txtWhitecolor fs14px">{{$item->currency->name}}</td>
                        <td class="txtWhitecolor fs14px">{!! number_format((double)($item->amount), 8) !!}</td>
                        <td class="txtWhitecolor fs14px">{{$item->equivalent_amount}}</td>
                        <td class="txtWhitecolor fs14px">
                            {{$item->type==1?'Buy':'Sell'}}
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

@endsection
