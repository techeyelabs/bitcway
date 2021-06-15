@extends('user.layouts.main')

@section('custom_css')
    <style>
        .txtWhitecolor{
            color: white;
        }
        .txtHeadingColor{
            color: yellow;
        }
        table
        {
            table-layout: fixed !important;
            width: 100% !important;
        }
        table, th, td {
         text-align: center !important;
        }
        #myTab{
            width: 101%;
        }
        .walletBtn{
            width: 135px !important;
        }



</style>
@endsection
@section('content')

<div id="wrap">
    <h3 class="txtHeadingColor pageTitle">{{__('title7')}}</h3>
    <hr>
    <div class="card mt-3">
        <div class="">
            <div class="text-center mt-3">
                <h4 class="txtHeadingColor">{{__('title8')}} {{Auth::user()->balance}} USD</h4>
            </div>
            <div class="col-md-12 mb-1 mt-3" style="margin-left: 15px;">
                <a href="{{route('user-deposit', app()->getLocale())}}" type="button" class="btn btn-outline-info walletBtn" >{{__('button3')}}</a>
                <a href="{{route('user-withdraw', app()->getLocale())}}" type="button" class="btn btn-outline-warning walletBtn">{{__('button4')}}</a>
            </div>
            @if(isset($withdrawFlag))
            <div class="card-body" >
                <div class="text-center">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link txtWhitecolor" id="deposit-history-tab" data-bs-toggle="tab" href="#deposit-history" role="tab"
                                aria-controls="deposit-history" aria-selected="false">{{__('button5')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active txtWhitecolor" id="withdraw-histoty-tab" data-bs-toggle="tab" href="#withdraw-histoty" role="tab"
                                aria-controls="withdraw-histoty" aria-selected="true">{{__('button6')}}</a>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade row" id="deposit-history" role="tabpanel" aria-labelledby="home-tab" >
                            <table class="table col-md-6 tableWidth">
                                <thead>
                                    <th class="txtWhitecolor ">{{__('col8')}}</th>
                                    <th class="txtWhitecolor ">BTC</th>
                                    <th class="txtWhitecolor ">USD</th>
                                    <th class="txtWhitecolor ">{{__('status')}}</th>
                                </thead>Locked Savings Amount
                                <tbody>
                                    <?php foreach($deposit as $item){?>
                                    <tr>
                                        <td class="txtWhitecolor ">{{date('d/m/Y', strtotime($item->created_at->todatestring()))}}</td>
                                        <td class="txtWhitecolor ">{!! number_format((float)($item->amount), 5) !!}</td>
                                        <td class="txtWhitecolor ">{{$item->equivalent_amount}}</td>
                                        <td class="txtWhitecolor ">
                                            <?php if($item->status == 1) echo 'Approved';elseif($item->status == 2) echo 'Declined'; else echo 'Pending';?>
                                        </td>
                                    </tr>
                                    <?php }?>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show active " id="withdraw-histoty" role="tabpanel" aria-labelledby="withdraw-histoty-tab" >
                            <table class="table tableWidth">
                                <thead>
                                    <th class="txtWhitecolor ">{{__('col8')}}</th>
                                    <th class="txtWhitecolor ">{{__('col10')}}</th>
                                    <th class="txtWhitecolor ">{{__('status')}}</th>
                                </thead>
                                <tbody>
                                    <?php foreach($withdraw as $item){?>
                                    <tr>
                                        <td class="txtWhitecolor ">{{date('d/m/Y', strtotime($item->created_at->todatestring()))}}</td>
                                        <td class="txtWhitecolor ">{!! number_format((float)($item->amount), 5) !!}</td>
                                        <td class="txtWhitecolor ">
                                            <?php if($item->status == 1) echo 'Approved';elseif($item->status == 2) echo 'Declined'; else echo 'Pending';?>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card-body">
                <div class="text-center">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active txtWhitecolor" id="deposit-history-tab" data-bs-toggle="tab" href="#deposit-history" role="tab"
                                aria-controls="deposit-history" aria-selected="true">{{__('button5')}} </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link txtWhitecolor" id="withdraw-histoty-tab" data-bs-toggle="tab" href="#withdraw-histoty" role="tab"
                                aria-controls="withdraw-histoty" aria-selected="false">{{__('button6')}} </a>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active row" id="deposit-history" role="tabpanel" aria-labelledby="home-tab" >
                            <table class="table col-md-6">
                                <thead>
                                    <th class="txtWhitecolor">{{__('col8')}}</th>
                                    <th class="txtWhitecolor">BTC</th>
                                    <th class="txtWhitecolor">USD</th>
                                    <th class="txtWhitecolor">{{__('status')}}</th>
                                </thead>
                                <tbody>
                                    <?php foreach($deposit as $item){?>
                                    <tr>
                                        <td class="txtWhitecolor">{{date('d/m/Y', strtotime($item->created_at->todatestring()))}}</td>
                                        <td class="txtWhitecolor">{!! number_format((float)($item->amount), 5) !!}</td>
                                        <td class="txtWhitecolor">{{$item->equivalent_amount}}</td>
                                        <td class="txtWhitecolor">
                                            <?php
                                            if($item->status == 1) echo 'Approved';
                                            elseif($item->status == 2) echo 'Declined';
                                            elseif($item->status == 4) echo 'Direct Deposit';
                                            elseif($item->status == 5) echo 'Opening Bonus';
                                            else echo 'Pending';?>
                                        </td>
                                    </tr>
                                    <?php }?>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="withdraw-histoty" role="tabpanel" aria-labelledby="withdraw-histoty-tab" >
                            <table class="table">
                                <thead>
                                    <th class="txtWhitecolor">{{__('col8')}}</th>
                                    <th class="txtWhitecolor">{{__('col10')}}</th>
                                    <th class="txtWhitecolor">{{__('status')}}</th>
                                </thead>
                                <tbody>
                                    <?php foreach($withdraw as $item){?>
                                    <tr>
                                        <td class="txtWhitecolor">{{date('d/m/Y ', strtotime($item->created_at))}}</td>
                                        <td class="txtWhitecolor">{!! number_format((float)($item->amount), 5) !!}</td>
                                        <td class="txtWhitecolor">
                                            <?php if($item->status == 1) echo 'Approved';elseif($item->status == 2) echo 'Declined'; else echo 'Pending';?>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


@endsection

@section('custom_js')
@endsection
