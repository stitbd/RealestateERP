@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Add Material Requisition
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('save-requisition') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="Project">Project</label>
                                <select name="project_id" id="project_id" class="form-control" required>
                                    <option value="">Select One</option>
                                    @foreach ($project_data as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="date">Date</label>
                                <input type="date" name="requisition_date" required class="form-control">
                            </div>
                            <div class="col-lg-12 pt-3" id="old_data">

                            </div>
                            <div class="col-lg-12 pt-3">
                                <table class="table table-bordered">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Item</th>
                                            <th>Estimated Qty</th>
                                            <th>Additional Qty</th>
                                            <th>Total Required Qty</th>
                                            <th>Total Received Qty</th>
                                            <th>Total Consumed Qty</th>
                                            <th>Total Stock Qty</th>
                                            <th>Balance Required Qty</th>
                                            <th>Progress Of Work Based On Material Received</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row_1" class="row_tr">
                                            <td>
                                                <select name="item_id[]" id="" class="form-control">
                                                    <option value="">Select One</option>
                                                    @foreach ($item_data as $item)
                                                        <option value="{{$item->id}}">{{ $item->name.' - '.$item->size_type.' ('.$item->unit.')' }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="estimated_qty[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="additional_qty[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="total_required_qty[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="received_qty[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="consumed_qty[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="stock_qty[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="balance_required_qty[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="work_progress[]" class="form-control">
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
    function add_more(){
        var row_data = $('.row_tr').html();
        row_data = '<tr>'+row_data+'</tr>';

        $("table tbody").append(row_data);
    }
    
</script>

@endsection