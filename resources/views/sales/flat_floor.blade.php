@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Floor List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Floor
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <table id="flat_floorTable" class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Project Name</th>
                                    <th>Floor No</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($flat_floors as $flat_floor)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $flat_floor->project->name ?? '' }}</td>
                                        <td class="text-center">{{ $flat_floor->floor_no ?? '' }}</td>
                                        <td class="text-center">
                                            {{-- <a data-toggle="modal" data-target=".view-modal-{{ $plot->id }}"><i
                                                class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a> --}}
                                            <a data-toggle="modal" data-target=".update-modal-{{ $flat_floor->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- <a href="{{ route('plot_delete', $plot->id) }}"
                                                onclick="return confirm('Are you sure you want to delete?');"
                                                style="padding: 2px;" class="delete btn btn-xs btn-danger  mr-1">
                                                <i class="fas fa-trash"></i>
                                            </a> --}}
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
                    <h4 class="modal-title">Add Floor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_flat_floor') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="project_id">Project<i class="text-danger">*</i></label>
                                <select name="project_id" id="project_id" class="form-control form-select" required>
                                    <option value="" selected>Select Project</option>
                                    @foreach ($project_data as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Floor No<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="floor_no" placeholder="Floor No"
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
    @foreach ($flat_floors as $flat_floor)
        {{-- @dd($plot) --}}
        <div class="modal fade update update-modal-{{ $flat_floor->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Floor</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_flat_floor', $flat_floor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="project_id">Project<i class="text-danger">*</i></label>
                                    <select name="project_id" id="project_id" class="form-control form-select" required>
                                        <option value="" selected>Select Project</option>
                                        @foreach ($project_data as $project)
                                            <option value="{{ $project->id }}" @if ($project->id == $flat_floor->project_id) selected @endif>{{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Floor No<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="floor_no" value="{{ $flat_floor->floor_no }}" placeholder="Floor No"
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

<!-- jQuery এবং DataTables স্ক্রিপ্ট যোগ করুন -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // DataTable ইনিশিয়ালাইজ করুন
        var table = $('#flat_floorTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search all columns...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)"
            }
        });

        // সার্চ ইনপুটের জন্য ইভেন্ট হ্যান্ডলার
        $('#searchInput').keyup(function() {
            table.search($(this).val()).draw();
        });
    });
</script>
