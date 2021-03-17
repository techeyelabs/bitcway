@extends('admin.layouts.main')

@section('custom_css')
    <style>
        .dynamic-content{
            display: none;
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
                        @include('includes.message')
                        <form action="" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">Interest Rate</label>
                                <input type="number" step="any" class="form-control" name="interest_rate" placeholder="Enter Interest Rate..." value="" required>
                                @error('start_price')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Duration</label>
                                <input type="number" class="form-control" name="duration" placeholder="Enter Duration..." value="" required>
                                @error('start_date')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Interest Per Lot</label>
                                <input type="number" class="form-control" name="interest_per_lot" value="" required readonly>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary btn-block float-right">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        @include('includes.message')
                        <table style="width: 100%">
                            <tr>
                                <th>Rate</th>
                                <th>Duration</th>
                                <th>Interest Per Lot</th>
                                <th>Action</th>
                            </tr>
                            @foreach($settings as $key => $value)
                            <tr>
                                <td>{{$value->rate}}</td>
                                <td>{{$value->duration}}</td>
                                <td>{{$value->interest_per_lot}}</td>
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
@endsection
