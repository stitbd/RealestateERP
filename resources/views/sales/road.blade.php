@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Road List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Road
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Project Name</th>
                                    <th>Sector No</th>
                                    <th>Road No</th>
                                    <th>Road Size</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($roads as $road)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $road->sector->project->name ?? '' }}</td>
                                        <td class="text-center">{{ $road->sector->sector_name ?? '' }}</td>
                                        <td class="text-center">{{ $road->road_name ?? '' }}</td>
                                        <td class="text-center">{{ $road->road_size ?? '' }}</td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".update-modal-{{ $road->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="{{ route('road_delete', $road->id) }}"
                                                onclick="return confirm('Are you sure you want to delete?');"
                                                style="padding: 2px;" class="delete btn btn-xs btn-danger  mr-1">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>


    <!-- Add Modal -->
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Road</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_road') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="project_id">Project<i class="text-danger">*</i></label>
                                <select name="project_id" id="project_id" class="form-control form-select" required onchange="selectSector()">
                                    <option value="" selected>Select Project</option>
                                    @foreach ($project_data as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <label for="sector_id">Sector<i class="text-danger">*</i></label>
                                <select name="sector_id" id="sector_id" class="form-control form-select" required>
                                    <option value="" selected>Select Sector</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Road No<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="road_name" placeholder="Road Name"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Road Size<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="road_size" placeholder="Road Size"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->



    <!-- Edit Modal -->
    @foreach ($roads as $road)
        {{-- @dd($sector) --}}
        <div class="modal fade update update-modal-{{ $road->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Road</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_road', $road->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                {{-- <div class="col-lg-12">
                                    <label for="project_id">Project<i class="text-danger">*</i></label>
                                    <select class="form-control form-select" required onchange="selectSector()" >
                                        <option value="" selected>Select Project</option>
                                        @foreach ($project_data as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-lg-12">
                                    <label for="sector_id">Sector<i class="text-danger">*</i></label>
                                    <select name="sector_id" class="form-control form-select sector_id_update" id="sector_id_update" required>
                                        <option value="" selected>Select Sector</option>
                                        @foreach ($sectors as $sector)
                                            <option value="{{ $sector->id }}"
                                                @if ($sector->id == $road->sector_id) selected @endif>
                                                {{ $sector->sector_name }} ({{ $sector->project->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Road No<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" value="{{ $road->road_name }}"
                                            id="road_name" name="road_name" placeholder="Road Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Road Size<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" value="{{ $road->road_size }}" name="road_size" placeholder="Road Size"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- /.modal -->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function selectSector(){
        var url = "{{ route('getProjectWisePloat') }}";
        const projectId = document.getElementById("project_id").value;
        $.ajax({
            type: "GET",
            url: url,
            data: {
                projectId: projectId
            },
            dataType: "json",
            success: function(response) {
                $("#sector_id").html(response);
            },
            error: function() {
                alert('An error occurred while fetching the road data.');
            }
        });

    }

</script>
