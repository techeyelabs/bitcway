@extends('admin.layouts.main')

@section('custom_css')
    <style>
        .dynamic-content{
            display: none;
        }
        .customClass1{
            font-size: 22px;
        }
        .customClass2{
            font-size: 17px;
            
        }
    </style>
@endsection

@section('content')
    <div id="wrap" class="deposit">
        <h3>DMGCoin Settings</h3>
        <hr>

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        {{-- @include('includes.message') --}}
                        @if(isset($user))
                            @foreach ($user as $u)
                                <form action="{{ route('admin-locked-savings-edit-action', \Crypt::encrypt($u->id)) }}" method="post">
                                {{-- <form action="" method="post"> --}}
                                    @csrf
                                    <div class="form-group">
                                        <label for="">Interest Rate</label>
                                        <input type="number" id="id-1" step="any" class="form-control" name="interest_rate" placeholder="Editing... " value="{{ $u->rate }}" required>
                                        @error('start_price')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Duration</label>
                                        <input type="number" id="id-2" class="form-control" name="duration" placeholder="Editing... " value="{{ $u->duration }}" required>
                                        @error('start_date')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Interest Per Lot</label>
                                        <input type="number" id="id-3" class="form-control" name="interest_per_lot" value="" required readonly>
                                    </div>
                                    <hr>
                                    <button type="submit" id="Submit" disabled="disabled"  class="btn btn-primary btn-block float-right">Send</button>
                                </form>
                            @endforeach

                        @else
                            <form action="{{ route('admin-locked-savings-settings') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="">Interest Rate</label>
                                    <input type="number" id="id-1" step="any" class="form-control" name="interest_rate" placeholder="Enter Interest Rate..." value="" required>
                                    @error('start_price')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Duration</label>
                                    <input type="number" id="id-2" class="form-control" name="duration" placeholder="Enter Duration..." value="" required>
                                    @error('start_date')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Interest Per Lot</label>
                                    <input type="number" id="id-3" class="form-control" name="interest_per_lot" value="" required readonly>
                                </div>
                                <hr>
                                <button type="submit" id="Submit" disabled="disabled"  class="btn btn-primary btn-block float-right">Send</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card" style="max-width: 74%;margin-left: 13%;">
                    <div class="card-body">
                        @include('includes.message')
                        <table style="width: 100%">
                            <tr class="row">
                                <th class="col-md-2 customClass1">Rate</th>
                                <th class="col-md-4 customClass1" style="text-align: center;">Duration</th>
                                <th class="col-md-3 customClass1">Interest Per Lot</th>
                                <th class="col-md-3 customClass1" style="text-align: center;">Action</th>
                            </tr>
                            @foreach($settings as $key => $value)
                            <tr class="row">
                                <td class="col-md-2 customClass2">{{$value->rate}}</td>
                                <td class="col-md-4 customClass2" style="text-align: center;">{{$value->duration}}</td>
                                <td class="col-md-3 customClass2" style="text-align: center;">{{$value->interest_per_lot}}</td>
                                <td class="col-md-3 customClass1" style="text-align: center;">
                                    <a href="{{route('admin-locked-savings-edit', \Crypt::encrypt($value->id))}}">
                                        <img src="/assets/1024px-OOjs_UI_icon_edit-ltr-progressive.svg.png" width="25" height="25">
                                        
                                    </a>
                                    <a  onclick="return confirm('Are you sure?')" href="{{route('admin-locked-savings-delete-action', \Crypt::encrypt($value->id))}}" >
                                        <i class="fa fa-trash" aria-hidden="true" width="20" height="20"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <div class="dynamic-content">
        <div class="parent">
            <hr>
            <div class="form-group">
                <label for="">Start Date</label> <button type="button" class="btn btn-outline-danger float-end btn-sm remove">remove</button>
                <input type="date" class="form-control" name="pstart_date[]" placeholder="Enter start price..." required>
            </div>
            <div class="form-group">
                <label for="">End Date</label>
                <input type="date" class="form-control" name="pend_date[]" placeholder="Enter start price..." required>
            </div>
            <div class="form-group">
                <label for="">Price Update (%)</label>
                <input type="number" step="any" class="form-control" name="pprice_update[]" placeholder="Enter start price..." required>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        $(function(){
            $('.add').on('click', function(){
                let content = $('.dynamic-content').html();
                $(this).before(content);
            });
            $(document).on('click', '.remove', function(){
                console.log('working');
                console.log($(this).parents('.parent'));
                $(this).parents('.parent').remove();
            })
        })
    </script>

    <script>
        $(function () {
            $("#id-1, #id-2").keyup(function () {
                $("#id-3").val((+$("#id-1").val() * +$("#id-2").val())/365);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('form input').keyup(function() {

                var empty = false;
                $('form input').each(function() {

                    if ($(this).val() == '') {
                        empty = true;
                    }
                });

                if (empty) {
                    $('#Submit').attr('disabled', 'disabled'); 
                } else {
                    $('#Submit').removeAttr('disabled'); 
                }
            });
        });
    </script>
@endsection
