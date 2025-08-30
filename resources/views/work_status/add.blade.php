@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Add Work Status
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('save-work-status') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="Project">Project</label>
                                <select name="project_id" id="project_id" class="form-control" onchange="select_previous_data(this.value)" required>
                                    <option value="">Select One</option>
                                    @foreach ($project_data as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="date">Date</label>
                                <input type="date" name="log_date" required class="form-control">
                            </div>
                            <div class="col-lg-12 pt-3" id="old_data">

                            </div>
                            <div class="col-lg-12 pt-3">
                                
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

        $("#new_data tbody").append(row_data);
    }
    function select_previous_data(project_id){
        url = '{{ route('WorkStatusData', ":project_id") }}';
        url = url.replace(':project_id', project_id);
        //alert(url);
        $.ajax({
            cache   : false,
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
                $('#old_data').html(response);
            }
        })
    }
</script>

@endsection