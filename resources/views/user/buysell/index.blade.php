@extends('user.layouts.main')

@section('custom_css')
    <style>
        .txtWhitecolor{
            color: white;
            font-size: 14px;
        }
        .txtHeadingColor{
            color: yellow;
        }
    </style>
@endsection
@section('content')

<div id="wrap">
    <h3 class="txtHeadingColor">{{__('title17')}}</h3>
    <hr>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <th class="txtWhitecolor">{{__('col8')}}</th>
                    <th class="txtWhitecolor">{{__('column1')}}</th>
                    <th class="txtWhitecolor">{{__('col10')}}</th>
                    <th class="txtWhitecolor">USD</th>
                    <th class="txtWhitecolor">{{__('col12')}}</th>
                </thead>
                <tbody>
                    <?php foreach($transactions as $item){?>
                    <tr>
                        <td class="txtWhitecolor">{{date('d/m/Y', strtotime($item->created_at))}}</td>
                        <td class="txtWhitecolor">{{$item->currency->name}}</td>
                        <td class="txtWhitecolor">{!! number_format((double)($item->amount), 8) !!}</td>
                        <td class="txtWhitecolor">{{$item->equivalent_amount}}</td>
                        <td class="txtWhitecolor">
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
