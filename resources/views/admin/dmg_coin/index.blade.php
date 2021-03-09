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
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    @include('includes.message')
                   <form action="" method="post">
                       @csrf
                       <div class="form-group">
                           <label for="">Start Price</label>
                           <input type="number" step="any" class="form-control" name="start_price" placeholder="Enter start price..." value="{{$coin->start_price}}" required>
                           @error('start_price')
                           <small class="text-danger">{{ $message }}</small>
                           @enderror
                       </div>
                       <div class="form-group">
                           <label for="">Start Date</label>
                           <input type="date" class="form-control" name="start_date" placeholder="Enter start price..." value="{{$coin->start_date}}" required>
                           @error('start_date')
                           <small class="text-danger">{{ $message }}</small>
                           @enderror
                       </div>
                       <div class="form-group">
                           <label for="">End Date</label>
                           <input type="date" class="form-control" name="end_date" placeholder="Enter start price..." value="{{$coin->end_date}}" required>
                           @error('end_date')
                           <small class="text-danger">{{ $message }}</small>
                           @enderror
                       </div>
                       <div class="form-group">
                           <label for="">Price Update (%)</label>
                           <input type="number" step="any" class="form-control" name="price_update" placeholder="Enter start price..." value="{{$coin->price_update}}" required>
                           @error('price_update')
                           <small class="text-danger">{{ $message }}</small>
                           @enderror
                       </div>

                       <?php foreach ($periods as $key => $value) {?>
                            <div class="parent">
                                <hr>
                                <div class="form-group">
                                    <label for="">Start Date</label> <button type="button" class="btn btn-outline-danger float-end btn-sm remove">remove</button>
                                    <input type="date" class="form-control" name="pstart_date[]" value="{{$value->start_date}}" placeholder="Enter start price..." required>
                                </div>
                                <div class="form-group">
                                    <label for="">End Date</label>
                                    <input type="date" class="form-control" name="pend_date[]" value="{{$value->end_date}}" placeholder="Enter start price..." required>
                                </div>
                                <div class="form-group">
                                    <label for="">Price Update (%)</label>
                                    <input type="number" step="any" class="form-control" value="{{$value->price_update}}" name="pprice_update[]" placeholder="Enter start price..." required>
                                </div>
                            </div>
                       <?php }?>

                       <button type="button" class="btn btn-outline-success add">Add periodic price change</button>
                       <hr>
                       <button type="submit" class="btn btn-primary btn-block float-right">Update</button>

                   </form>

                    

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
