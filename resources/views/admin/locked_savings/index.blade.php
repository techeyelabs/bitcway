@extends('admin.layouts.main')

@section('custom_css')
    <style>
        .dynamic-content {
            display: none;
        }

        .customClass1 {
            font-size: 22px;
        }

        .customClass2 {
            font-size: 17px;
        }
        .inputGroupDiv{
            margin: .25rem .70rem .24rem 0.70rem !important;
        }
        .lockedSavingInputFroup{
        width: 23%;
        /*max-width: 100%;*/
        }
    </style>
@endsection

@section('content')
    <div id="wrap" class="deposit">
        <h3><u>Locked Savings Setting</u> <span style="font-size: 14px;">Flexible deposits, higher profits</span></h3>
        <hr>
        {{--New locked Saving Setting Start--}}
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        @if(isset($user))
                            @foreach ($user as $u)
                                <form action="{{ route('admin-locked-savings-edit-action', \Crypt::encrypt($u->id)) }}" method="post">
                                    @csrf

                                    <div class="form-group">
                                        <label for="" class="txtWhitecolor">Coin Select</label>
                                        <select id="coinOption" class="form-control form-select lockedSavingInputFroup" name="selectCoinName" aria-label="Default select example" >
                                            <option value="{{$u->currency->name}}">{{$u->currency->name}}</option>
                                        </select>
                                        <small class="text-danger"></small>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="txtWhitecolor">Lot Size</label>
                                        <input type="number" id="lotSize" step="any" class="form-control lockedSavingInputFroup" name="lotSize" placeholder="100" value="{{$u->lot_size}}" required>
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="txtWhitecolor">Duration</label>
                                        <input type="number" id="duration1" class="form-control col inputGroupDiv" name="duration1" placeholder="" value="{{$u->duration_1}}" required>
                                        <input type="number" id="duration2" class="form-control col inputGroupDiv" name="duration2" placeholder="" value="{{$u->duration_2}}" required>
                                        <input type="number" id="duration3" class="form-control col inputGroupDiv" name="duration3" placeholder="" value="{{$u->duration_3}}" required>
                                        <input type="number" id="duration4" class="form-control col inputGroupDiv" name="duration4" placeholder="" value="{{$u->duration_4}}" required>
                                        <small class="text-danger"></small>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="txtWhitecolor">Annual Interest</label>
                                        <input type="number" step="any" id="rate1" class="form-control col inputGroupDiv" name="rate1" placeholder="" value="{{$u->rate_1}}" required>
                                        <input type="number" step="any" id="rate2" class="form-control col inputGroupDiv" name="rate2" placeholder="" value="{{$u->rate_2}}" required>
                                        <input type="number" step="any" id="rate3" class="form-control col inputGroupDiv" name="rate3" placeholder="" value="{{$u->rate_3}}" required>
                                        <input type="number" step="any" id="rate4" class="form-control col inputGroupDiv" name="rate4" placeholder="" value="{{$u->rate_4}}" required>
                                        <small class="text-danger"></small>
                                    </div>
                                    <hr>
                                    <div class=" gap-2 col-6 mx-auto text-center">
                                        <button type="submit" id="Submit" class="btn btn-primary  text-center" style="width: 80px; color: white;">Submit</button>
                                    </div>
                                </form>
                            @endforeach
                        @else
                                <form action="{{ route('admin-locked-savings-settings') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="" class="txtWhitecolor">Coin Select</label>
                                <select id="coinOptions" class="form-control form-select lockedSavingInputFroup" name="selectCoinName" aria-label="Default select example" >
                                    <option selected>Select Currency</option>
                                </select>
                                <small class="text-danger"></small>
                            </div>

                            <div class="form-group">
                                <label for="" class="txtWhitecolor">Lot Size</label>
                                <input type="number" id="lotSize" step="any" class="form-control lockedSavingInputFroup" name="lotSize" placeholder="100" value="" required>
                                <small class="text-danger"></small>
                            </div>
                            <div class="form-group row">
                                <label for="" class="txtWhitecolor">Duration</label>
                                <input type="number" id="duration1" class="form-control col inputGroupDiv" name="duration1" placeholder="0" value="" required>
                                <input type="number" id="duration2" class="form-control col inputGroupDiv" name="duration2" placeholder="0" value="" required>
                                <input type="number" id="duration3" class="form-control col inputGroupDiv" name="duration3" placeholder="0" value="" required>
                                <input type="number" id="duration4" class="form-control col inputGroupDiv" name="duration4" placeholder="0" value="" required>
                                <small class="text-danger"></small>
                            </div>
                            <div class="form-group row">
                                <label for="" class="txtWhitecolor">Annual Interest</label>
                                <input type="number" step="any" id="rate1" class="form-control col inputGroupDiv" name="rate1" placeholder="0.00" value="" required>
                                <input type="number" step="any" id="rate2" class="form-control col inputGroupDiv" name="rate2" placeholder="0.00" value="" required>
                                <input type="number" step="any" id="rate3" class="form-control col inputGroupDiv" name="rate3" placeholder="0.00" value="" required>
                                <input type="number" step="any" id="rate4" class="form-control col inputGroupDiv" name="rate4" placeholder="0.00" value="" required>
                                <small class="text-danger"></small>
                            </div>
                            <hr>
                            <div class=" gap-2 col-6 mx-auto text-center">
                                <button type="submit" id="Submit" class="btn btn-primary  text-center" style="width: 80px; color: white;">Submit</button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{--New locked Saving Setting End--}}


        {{--Previous locked Saving Setting Start--}}
{{--        <div class="row mt-5" style="display: block">--}}
{{--            <div class="col-md-6 offset-md-3">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-body">--}}
{{--                        --}}{{-- @include('includes.message') --}}
{{--                        @if(isset($user))--}}
{{--                            @foreach ($user as $u)--}}
{{--                                <form action="{{ route('admin-locked-savings-edit-action', \Crypt::encrypt($u->id)) }}"--}}
{{--                                      method="post">--}}
{{--                                    --}}{{-- <form action="" method="post"> --}}
{{--                                    @csrf--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="">Interest Rate</label>--}}
{{--                                        <input type="number" id="id-1" step="any" class="form-control"--}}
{{--                                               name="interest_rate" placeholder="Editing... " value="{{ $u->rate }}"--}}
{{--                                               required>--}}
{{--                                        @error('start_price')--}}
{{--                                        <small class="text-danger">{{ $message }}</small>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="">Duration</label>--}}
{{--                                        <input type="number" id="id-2" class="form-control" name="duration"--}}
{{--                                               placeholder="Editing... " value="{{ $u->duration }}" required>--}}
{{--                                        @error('start_date')--}}
{{--                                        <small class="text-danger">{{ $message }}</small>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="">Interest Per Lot</label>--}}
{{--                                        <input type="number" id="id-3" class="form-control" name="interest_per_lot"--}}
{{--                                               value="" required readonly>--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}
{{--                                    <button type="submit" id="Submit" disabled="disabled"--}}
{{--                                            class="btn btn-primary btn-block float-right">Send--}}
{{--                                    </button>--}}
{{--                                </form>--}}
{{--                            @endforeach--}}

{{--                        @else--}}
{{--                            <form action="{{ route('admin-locked-savings-settings') }}" method="post">--}}
{{--                                @csrf--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="">Interest Rate</label>--}}
{{--                                    <input type="number" id="id-1" step="any" class="form-control" name="interest_rate"--}}
{{--                                           placeholder="Enter Interest Rate..." value="" required>--}}
{{--                                    @error('start_price')--}}
{{--                                    <small class="text-danger">{{ $message }}</small>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="">Duration</label>--}}
{{--                                    <input type="number" id="id-2" class="form-control" name="duration"--}}
{{--                                           placeholder="Enter Duration..." value="" required>--}}
{{--                                    @error('start_date')--}}
{{--                                    <small class="text-danger">{{ $message }}</small>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="">Interest Per Lot</label>--}}
{{--                                    <input type="number" id="id-3" class="form-control" name="interest_per_lot" value=""--}}
{{--                                           required readonly>--}}
{{--                                </div>--}}
{{--                                <hr>--}}
{{--                                <button type="submit" id="Submit" disabled="disabled"--}}
{{--                                        class="btn btn-primary btn-block float-right">Send--}}
{{--                                </button>--}}
{{--                            </form>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        {{--Previous locked Saving Setting End--}}
        {{--Previous locked Saving Setting Div2 Start--}}
{{--        <div class="row" style="display: block">--}}
{{--            <div class="col-md-8 offset-md-2">--}}
{{--                <div class="card" style="max-width: 74%;margin-left: 13%;">--}}
{{--                    <div class="card-body">--}}
{{--                        @include('includes.message')--}}
{{--                        <table style="width: 100%">--}}
{{--                            <tr class="row">--}}
{{--                                <th class="col-md-2 customClass1">Rate</th>--}}
{{--                                <th class="col-md-4 customClass1" style="text-align: center;">Duration</th>--}}
{{--                                <th class="col-md-3 customClass1">Interest Per Lot</th>--}}
{{--                                <th class="col-md-3 customClass1" style="text-align: center;">Action</th>--}}
{{--                            </tr>--}}
{{--                            @foreach($settings as $key => $value)--}}
{{--                                <tr class="row">--}}
{{--                                    <td class="col-md-2 customClass2">{{$value->rate}}</td>--}}
{{--                                    <td class="col-md-4 customClass2"--}}
{{--                                        style="text-align: center;">{{$value->duration}}</td>--}}
{{--                                    <td class="col-md-3 customClass2"--}}
{{--                                        style="text-align: center;">{{$value->interest_per_lot}}</td>--}}
{{--                                    <td class="col-md-3 customClass1" style="text-align: center;">--}}
{{--                                        <a href="{{route('admin-locked-savings-edit', \Crypt::encrypt($value->id))}}">--}}
{{--                                            <img src="/assets/1024px-OOjs_UI_icon_edit-ltr-progressive.svg.png"--}}
{{--                                                 width="25" height="25">--}}

{{--                                        </a>--}}
{{--                                        <a onclick="return confirm('Are you sure?')"--}}
{{--                                           href="{{route('admin-locked-savings-delete-action', \Crypt::encrypt($value->id))}}">--}}
{{--                                            <i class="fa fa-trash" aria-hidden="true" width="20" height="20"></i>--}}
{{--                                        </a>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}
        {{--Previous locked Saving Setting Div2 End--}}


        {{--New locked Saving Setting Div2 End--}}
        <div class="card mt-3">
            <div class="card-body">

                <div style="margin: auto; overflow-x: auto">
                    <ul class="container-fluid" style=" min-width: 600px;">
                        <li class="row list-group-item d-flex justify-content-between align-items-center">
                            <p class="col txtWhitecolor" id="" style="text-align: left; ">Symbol</p>
                            <p class="col txtWhitecolor" id="" style="text-align: left; ">Annualized Interest Rate</p>
                            <p class="col txtWhitecolor" id="" style="text-align: center;">Duration (Days)</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">Lot Size</p>
                            <p class="col txtWhitecolor" id="" style="text-align: right;">Action</p>
                        </li>
                        <div id="btnDurationId">
                            @foreach($settings as $index => $FinanceSetting)
                                <li class="row list-group-item d-flex justify-content-between align-items-center">
                                    <p class="col txtWhitecolor" id="currencyName{{$index}}" style="text-align: left;">{{$FinanceSetting->currency->name}}</p>
                                    <p class="col txtWhitecolor" id="selectedRate{{$index}}" style="text-align: left;">{{$FinanceSetting->rate_1}}%</p>
                                    <p style="display: none" id="selectedDuration"></p>
                                    <p class="col btn-group"  role="group" aria-label="Basic outlined example" style="width: 10px !important; text-align: center">
                                            <button type="button" class="btn btnDuration btn-outline-primary " onclick="rateFun({{$FinanceSetting->rate_1}}, {{ $index}}); rateDuration({{$FinanceSetting->duration_1}})">{{$FinanceSetting->duration_1}}</button>
                                            <button type="button" class="btn btnDuration btn-outline-primary " onclick="rateFun({{$FinanceSetting->rate_2}}, {{ $index}}); rateDuration({{$FinanceSetting->duration_2}})">{{$FinanceSetting->duration_2}}</button>
                                            <button type="button" class="btn btnDuration btn-outline-primary" onclick="rateFun({{$FinanceSetting->rate_3}}, {{ $index}}); rateDuration({{$FinanceSetting->duration_3}})">{{$FinanceSetting->duration_3}}</button>
                                            <button type="button" class="btn btnDuration btn-outline-primary" onclick="rateFun({{$FinanceSetting->rate_4}}, {{ $index}}); rateDuration({{$FinanceSetting->duration_4}})">{{$FinanceSetting->duration_4}}</button>
                                    </p>
                                    <p class="col txtWhitecolor" id="lotSize{{$index}}" style="text-align: right;">{{$FinanceSetting->lot_size}}</p>
                                    <p class="col txtWhitecolor" id="" style="text-align: right;">
                                        <a style="margin-right: 20px" class="txtHeadingColor" href="{{route('admin-locked-savings-edit', \Crypt::encrypt($FinanceSetting->id))}}"><i class="fas fa-edit"></i></a>
                                        <a class="txtHeadingColor" href="{{route('admin-locked-savings-delete-action', \Crypt::encrypt($FinanceSetting->id))}}"><i class="fas fa-trash-alt"></i></a>
                                    </p>
                                </li>
                            @endforeach
                        </div>
                    </ul>
                </div>
            </div>
            {{--Finance Wallet End--}}
        </div>
        {{--New locked Saving Setting Div2 End--}}
    </div>


    <div class="dynamic-content">
        <div class="parent">
            <hr>
            <div class="form-group">
                <label for="">Start Date</label>
                <button type="button" class="btn btn-outline-danger float-end btn-sm remove">remove</button>
                <input type="date" class="form-control" name="pstart_date[]" placeholder="Enter start price..."
                       required>
            </div>
            <div class="form-group">
                <label for="">End Date</label>
                <input type="date" class="form-control" name="pend_date[]" placeholder="Enter start price..." required>
            </div>
            <div class="form-group">
                <label for="">Price Update (%)</label>
                <input type="number" step="any" class="form-control" name="pprice_update[]"
                       placeholder="Enter start price..." required>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>
    <script>
        const socket = io('http://192.144.82.234:3000/');
        let response = 0;
        socket.on('trackers', (trackers) => {
            response++;
            if (response == 1){
                let options = "";
                for (let i = 0; i < trackers.trackers.length; i++){
                    let c = trackers.trackers[i][0];
                    let name = splitCurrency(c);
                    options += "<option value ="+name+">"+name+"</option>";
                }
                $('#coinOptions').html(options);
            }
        });
        function splitCurrency(currency){
            currency = currency.split('t').join('');
            currency = currency.split('USD').join('');
            return currency;
        }
    </script>
    <script>
        function rateDuration(val) {
            // console.log(val);
            $('#selectedDuration').html(val);
        }
        function rateFun(value, index) {
            $('#selectedRate'+ index).html(value+"%");
        }
    </script>
    <script>
        $(function () {
            $('.add').on('click', function () {
                let content = $('.dynamic-content').html();
                $(this).before(content);
            });
            $(document).on('click', '.remove', function () {
                // console.log('working');
                // console.log($(this).parents('.parent'));
                $(this).parents('.parent').remove();
            })
        })
    </script>
    <script>
        $(function () {
            $("#id-1, #id-2").keyup(function () {
                $("#id-3").val((+$("#id-1").val() * +$("#id-2").val()) / 365);
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('form input').keyup(function () {

                var empty = false;
                $('form input').each(function () {

                    if ($(this).val() == '') {
                        empty = true;
                    }
                });

                // if (empty) {
                //     $('#Submit').attr('disabled', 'disabled');
                // } else {
                //     $('#Submit').removeAttr('disabled');
                // }
            });
        });
    </script>

    <script>
        // Add active class to the current button (highlight it)
        var header = document.getElementById("btnDurationId");
        var btns = header.getElementsByClassName("btnDuration");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("active");
                if (current.length > 0) {
                    current[0].className = current[0].className.replace(" active", "");
                }
                this.className += " active";
            });
        }
    </script>
@endsection
