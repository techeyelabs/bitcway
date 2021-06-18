@extends('user.layouts.main')

@section('custom_css')

@endsection

@section('content')
    <div id="wrap" class="deposit">
        <h3 class="txtWhitecolor pageTitle">{{__('button3')}}</h3>
        <hr>
        <div class="row">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group text-center">
                            <h4 class="txtWhitecolor">{{__('deposittran')}}</h4>
                        </div>
                        <hr>
                        <div class="form-group text-center">
                            <h3 class="txtWhitecolor">{{$amount}} USD</h3>
                        </div>
                        <div class="card-body">
                            <a href="#" class="card-link"><span >{{__('deposittranback')}}</span></a>
                            <a href="https://api.saiwin.co/payment?c={{$urlCode}}" class="card-link float-end">
                                <button class="btn btn-outline-warning float-end">{{__('proceed')}}</button>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('custom_js')
    <script>
        function gatewaypost() {

        }
    </script>
@endsection
