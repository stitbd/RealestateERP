@extends('layouts.app')
@section('content')
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title col-sm-11">
                        Plot Size/Type List
                    </h3>
                    <button class="text-end col-sm-1 btn btn-success btn-sm"  data-toggle="modal"
                    data-target="#exampleModal" >+Add</button>
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('plot_type') }}" method="get">
                        <div class="row pb-3">
                            <div class="col-lg-6">
                                <label for="search">Search By Project</label>
                                 <select name="project_id" id="project_id" class="form-control">
                                    <option value="">Select a Project</option>
                                    @foreach($project_data as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="action">Action</label> <br />
                                <button class="btn btn-success btn-block">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Project Name</th>
                                <th>Plot Size/Type</th>
                                <th>Percentage (শতাংশ)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                          @foreach($plot_types as $v_type)
                          <tr  class="text-center">
                            <td>@php
                                $i = $loop->iteration
                            @endphp {{$i}}</td>
                            <td>{{$v_type->project->name}}</td>
                            <td>{{$v_type->plot_type}}</td>
                            <td>{{$v_type->percentage}}</td>
                            <td>
                                <a data-toggle="modal"
                                    data-target=".update-modal-{{$v_type->id}}"
                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                   <i class="fas fa-edit"></i>
                                </a>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                           {{$plot_types->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade create_modal" id="exampleModal"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-center">
                <h5 >Add Plot Size/Type</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('plot_type_store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row pt-3">
                        <label for="category" class="col-sm-3 col-form-label">Project</label>
                        <label for="project_id" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <select name="project_id" id="project_id" class="form-control">
                                <option value="">Select a Project</option>
                                @foreach($project_data as $data)
                                    <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                   <div class="form-group row pt-3">
                        <label for="plot_type" class="col-sm-3 col-form-label">Plot Size(কাঠা)</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="plot_type" type="text" class="form-control" placeholder="Write Size............">
                        </div>
                    </div>

                   <div class="form-group row pt-3">
                        <label for="percentage" class="col-sm-3 col-form-label">Percentage (শতাংশ)</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="percentage" type="float" class="form-control" placeholder="Write Percentage....">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($plot_types as $type)
<div class="modal fade update update-modal-{{$type->id}}" id="exampleModal"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-center">
                <h5 >Update Plot Size/Type Info</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('plot_type_update',$type->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="type_id" value="{{$type->id}}">
                    <div class="form-group row pt-3">
                        <label for="project" class="col-sm-3 col-form-label">Project </label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <select name="project_id" id="" class="form-control">
                                @foreach($project_data as $v_project)
                                <option value="{{$v_project->id}}" @if($v_project->id == $type->project_id) selected @endif>{{$v_project->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                   <div class="form-group row pt-3">
                        <label for="plot_type" class="col-sm-3 col-form-label">Plot Size/Type</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="plot_type" type="text" class="form-control" value="{{$type->plot_type}}" placeholder="Write Plot Size/Type............">
                        </div>
                    </div>
                   <div class="form-group row pt-3">
                        <label for="head_name" class="col-sm-3 col-form-label">Percentage (শতাংশ)</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="percentage" type="float" class="form-control" value="{{$type->percentage}}" placeholder="Write Percentage............">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
function updateValue() {
    let checkbox = document.getElementById('yes');
    checkbox.value = checkbox.checked ? 1 : 0;
}
</script>
