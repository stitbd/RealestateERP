@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Add Stock Out
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('save-stock-out') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- <div class="col-lg-4">
                                <label for="Project">Project</label>
                                <select name="project_id" id="project_id" class="form-control" required>
                                    <option value="">Select One</option>
                                    @foreach ($project_data as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="col-lg-4">
                                <label for="date">Stock Out Date</label>
                                <input type="date" name="out_date" required class="form-control">
                            </div>
                            <div class="col-lg-12 pt-3">
                                <table class="table table-bordered">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Item</th>
                                            <th>Remain Qty</th>
                                            <th>Qty</th>
                                            <th>Reason </th>
                                            <th>Remarks </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row_1" class="row_tr">
                                            <td>
                                                <select name="item_id[]" class="form-control item_select" id="item-1" onchange="itemInfo(this);">
                                                    <option value="">Select One</option>
                                                    @foreach ($item_data as $item)
                                                        <option value="{{$item->id}}">{{ $item->name.' - '.$item->size_type.' ('.$item->unit.')' }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number"  id="stock-1" class="form-control stock">
                                            </td>
                                            <td>
                                                <input type="number" name="qty[]" id="qty-1" class="form-control qty">
                                            </td>
                                            
                                            <td>
                                                <input type="text" name="reason[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="remarks[]" class="form-control">
                                            </td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                            
                            <div class="col-lg-6 pt-3">
                                <button type="button" class="btn btn-success" onclick="add_more()"><i class="fa fa-plus"></i> Add More</button>
                            </div>
                            <div class="col-lg-6 pt-3 text-right">
                                <button class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var i = 1;
    function add_more(){
        ++i;
        var row_data = $('.row_tr').html();
        var row_id = "row_" + i; 
        // row_data = '<tr>'+row_data+'</tr>';
        var new_row = '<tr id="' + row_id + '">' + row_data + '</tr>';
        $("table tbody").append(new_row);
        
        var next_row_item = $('#'+row_id).find('.item_select');
        var next_row_stock = $('#'+row_id).find('.stock');
        var next_row_hidden_stock = $('#'+row_id).find('.h_stock');
        var next_row_qty = $('#'+row_id).find('.qty');

        var next_item_id  = 'item-'+i;
        var next_stock_id  = 'stock-'+i;
        var next_hidden_id  = 'hidden_stock_'+i;
        var next_qty_id  =  'qty-'+i;

        next_row_item.attr('id',next_item_id);
        next_row_stock.attr('id',next_stock_id);
        next_row_qty.attr('id',next_qty_id);
        next_row_hidden_stock.attr('id',next_hidden_id);
    }

    function itemInfo(e){
        let text = e.id;
        let id = text.replace('item-','');
        let item_id = $('#item-'+id).val();
        let url = "{{route('filterItem')}}";

        $.ajax({
            type:"POST",
            url:url,
            data:{item_id},
            success:function(r){
                if(r <= 7){
                    $('#stock-'+id).css({
                        'font-weight': 'bold',
                        'color': 'red',
                    }).val(r);
                }else{
                    $('#stock-'+id).css({
                        'font-weight': 'bold',
                    }).val(r);
                }
            }
        });
    }
    
</script>

@endsection