@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Add Stock Transfer
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('save-stock-transfer') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="Project">From Project</label>
                                <select name="project_id" id="project_id" class="form-control" required>
                                    <option value="">Select One</option>
                                    @foreach ($project_data as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label for="Project">To Company</label>
                                <select name="to_company_id" id="to_company_id" onchange="select_project_data(this.value)" class="form-control" required>
                                    <option value="">Select One</option>
                                    @foreach ($company_data as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="Project">To Project</label>
                                <select name="to_project_id" id="to_project_id" class="form-control" required>
                                    <option value="">Select One</option>
                                    
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label for="date">Date</label>
                                <input type="date" name="out_date" required class="form-control">
                            </div>
                            <div class="col-lg-12 pt-3">
                                <table class="table table-bordered">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Remarks </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row_1" class="row_tr">
                                            <td>
                                                <select name="item_id[]" class="form-control">
                                                    <option value="">Select One</option>
                                                    @foreach ($item_data as $item)
                                                        <option value="{{$item->id}}">{{ $item->name.' - '.$item->size_type.' ('.$item->unit.')' }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="qty[]" class="form-control">
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
    
    function select_project_data(company_id){
        url = '{{ route('select-transfer-project', ":company_id") }}';
        url = url.replace(':company_id', company_id);
        //alert(url);
        $.ajax({
            cache   : false,
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
                $('#to_project_id').html(response);
            }
        })
    }
</script>

@endsection