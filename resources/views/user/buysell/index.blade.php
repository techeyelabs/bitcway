@extends('user.layouts.main')

@section('custom_css')
    <style>
        .loss{
            color: #dc3545;
        }
        .profit{
            color: #198754;
        }
    </style>
@endsection
@section('content')

<div id="wrap">
    <h3 class="txtHeadingColor pageTitle">{{__('title17')}}</h3>
    <hr>
    <div class="card">
        <div class="col-md-12 mb-1 mt-3" style="margin-left: 15px;">
            <a href="javascript:toggleType('trade')" type="button" class="btn btn-outline-info walletBtn" >{{__('menuoption3')}}</a>
            <a href="javascript:toggleType('derivative')" type="button" class="btn btn-outline-warning walletBtn">{{__('menuoption4')}}</a>
        </div>
        <div class="card-body historyTable">
            <table class="table" id="trade">
                <thead>
                    <th class="txtWhitecolor fs14px">{{__('col8')}}</th>
                    <th class="txtWhitecolor fs14px">{{__('column1')}}</th>
                    <th class="txtWhitecolor fs14px">{{__('size')}}</th>
                    <th class="txtWhitecolor fs14px">{{__('entryprice')}} (USD)</th>
                    <th class="txtWhitecolor fs14px">Settlement Price (USD)</th>
                    <th class="txtWhitecolor fs14px">Profit</th>
                </thead>
                <tbody>
                    <?php foreach($trades as $item){?>
                    <tr>
                        <td class="txtWhitecolor fs14px">{{date('Y-m-d H:i:s', strtotime($item->created_at))}}</td>
                        <td class="txtWhitecolor fs14px">{{ ($item->currency->name == 'ADA') ? 'MAB' : $item->currency->name}}</td>
                        <td class="txtWhitecolor fs14px">{!! number_format((double)($item->amount), 8) !!}</td>
                        <td class="txtWhitecolor fs14px">{!! (double)($item->entry_price) !!}</td>
                        <td class="txtWhitecolor fs14px">{!! (double)($item->equivalent_amount / $item->amount) !!}</td>
                        <td class="fs14px {{($item->profit < 0)? 'loss': 'profit'}}">{{$item->profit}}</td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <table class="table" id="derivative" style="display: none">
                <thead>
                    <th class="txtWhitecolor fs14px">{{__('col8')}}</th>
                    <th class="txtWhitecolor fs14px">{{__('column1')}}</th>
                    <th class="txtWhitecolor fs14px">{{__('col12')}}</th>
                    <th class="txtWhitecolor fs14px">{{__('size')}} (USD)</th>
                    <th class="txtWhitecolor fs14px">{{__('entryprice')}} (USD)</th>
                    <th class="txtWhitecolor fs14px">Settlement Price (USD)</th>
                    <th class="txtWhitecolor fs14px">Profit</th>
                </thead>
                <tbody>
                    <?php foreach($derivatives as $item){?>
                    <tr>
                        <td class="txtWhitecolor fs14px">{{date('d/m/Y', strtotime($item->created_at))}}</td>
                        <td class="txtWhitecolor fs14px">{{ ($item->currency->name == 'ADA') ? 'MAB' : $item->currency->name}}</td>
                        <td class="txtWhitecolor fs14px">{{$item->type==1?'Buy':'Sell'}}</td>
                        <td class="txtWhitecolor fs14px">{!! number_format((double)($item->amount), 8) !!}</td>
                        <td class="txtWhitecolor fs14px">{!! (double)($item->entry_price) !!}</td>
                        <td class="txtWhitecolor fs14px">{!! (double)($item->equivalent_amount / $item->amount) !!}</td>
                        <td class="fs14px {{($item->profit < 0)? 'loss': 'profit'}}">{{$item->profit}}</td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

    <script>
        function toggleType(type){
            if(type == 'trade'){
                $('#trade').show();
                $('#derivative').hide();
            } else {
                $('#trade').hide();
                $('#derivative').show();
            }

        }
    </script>

@endsection
