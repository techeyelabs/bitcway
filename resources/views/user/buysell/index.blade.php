@extends('user.layouts.main')

@section('custom_css')
    <style>
        .txtWhitecolor{
            color: #D3D6D8;
            font-size: 14px;
        }
        .txtHeadingColor{
            color: yellow;
        }
    </style>
@endsection
@section('content')

<div id="wrap">
    <h3 class="txtHeadingColor">Buy/Sell</h3>
    <hr>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <th class="txtWhitecolor">Date</th>
                    <th class="txtWhitecolor">Currency</th>
                    <th class="txtWhitecolor">Amount</th>
                    <th class="txtWhitecolor">Equivalent Amount (USDt)</th>
                    <th class="txtWhitecolor">Type</th>
                </thead>
                <tbody>
                    <?php foreach($transactions as $item){?>
                    <tr>
                        <td class="txtWhitecolor">{{date('d/m/Y H:i', strtotime($item->created_at))}}</td>
                        <td class="txtWhitecolor">{{$item->currency->name}}</td>
                        <td class="txtWhitecolor">{{$item->amount}}</td>
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
