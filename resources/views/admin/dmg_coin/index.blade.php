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
    <h3>MAB Chart Settings</h3>
    <hr>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    @include('includes.message')
                   <form action="" id="update_form" method="post">
                       @csrf
<!--                       <div class="form-group">
                           <label for="">Start Price</label>
                           <input type="number" step="any" class="form-control" name="start_price" placeholder="Enter start price..." value="" required>
                           @error('start_price')
                           <small class="text-danger">{{ $message }}</small>
                           @enderror
                       </div>-->
                       <div class="form-group">
                           <label for="">Start Date</label>
                           <input type="datetime-local" class="form-control" id='start_date' name="start_date" placeholder="Enter start price..." value="" required>
                           <input type="hidden" name="edit_id" id="edit_id" value="">
                           @error('start_date')
                           <small class="text-danger">{{ $message }}</small>
                           @enderror
                       </div>
                       <div class="form-group">
                           <label for="">End Date</label>
                           <input type="datetime-local" class="form-control" id="end_date" name="end_date" placeholder="Enter start price..." value="" required>
                           @error('end_date')
                           <small class="text-danger">{{ $message }}</small>
                           @enderror
                       </div>
                       <div class="form-group">
                           <label for="">Price Update (%)</label>
                           <input type="number" step="any" class="form-control" id="price_update" name="price_update" placeholder="Enter start price..." value="" required>
                           @error('price_update')
                           <small class="text-danger">{{ $message }}</small>
                           @enderror
                       </div>


<!--                       <button type="button" class="btn btn-outline-success add">Add periodic price change</button>-->
                       <hr>
                       <button type="submit" class="btn btn-primary btn-block float-right">Submit</button>
<!--                       <button class="btn btn-primary btn-block float-right" id='reset_form' style="display: none" onclick="reset()">Reset</button>-->
                   </form>
                </div>
            </div>

        </div>
    </div>
    <br>
    <div class="row" id="" >
        <div class="col-md-8 col-lg-6">

            <div class="card" style="padding-top: 5%; padding-bottom: 5%;">
                <div class="card-body">
                    <label> Update History</label>
                    <hr>
                    <table class="tables" id="tabledata">
                        <th  style="width:20%">Start Date</th>
                        <th  style="width:20%">End Date</th>
                        <th  style="width:20%;">Price Update(%)</th>
                        <th  style="width:20%">Action</th>
                        <tbody>
                        <?php $i = 0;
                            foreach($coin as $c) { ?>
                            <tr>
                                <td >{{$c['start_date']}}</td>
                                <td >{{$c['end_date']}}</td>
                                <td >{{$c['price_update']}}</td>

                                <?php if($i == 0){ ?>
                                    <td  style="cursor: pointer; color:green;" id="{{$c['id']}}" onclick="edit(this.id)">
                                        <i class="fa fa-pen"></i>
                                    </td>
                                <?php }?>
                            </tr>
                        <?php $i++; } ?>
                        </tbody>
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
        function edit(id){
            var price_update = $('#' + id).prev().text();
            var end_date = $('#' + id).prev().prev().text();
            var start_date = $('#' + id).prev().prev().prev().text();
            var res_start = start_date.split(" ");
            var res_end = end_date.split(" ");
            //console.log(res[1]);
            var length = 16;
            var enddate = end_date.substring(0,length);
            console.log(enddate)
            var startdate = start_date.substring(0,length);
            var put_start_date = $('input#start_date').val(res_start[0]+'T'+res_start[1]);
            var put_end_date = $('input#end_date').val(res_end[0]+'T'+res_end[1]);
            var put_price_update = $('input#price_update').val(price_update);
            var edit_id = $('input#edit_id').val(id);
          $('#reset_form').show();
        }
        function reset(){
            document.getElementById("update_form").reset();
             $('#reset_form').hide();
        }
    </script>
@endsection
