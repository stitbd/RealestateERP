@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Flat List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Flat
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <table id="flatTable" class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Project Name</th>
                                    <th>Floor No</th>
                                    <th>Flat No</th>
                                    <th>Flat Size</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($flats as $flat)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $flat->flat_floor->project->name ?? '' }}</td>
                                        <td class="text-center">{{ $flat->flat_floor->floor_no ?? '' }}</td>
                                        <td class="text-center">{{ $flat->flat_no ?? '' }}</td>
                                        <td class="text-center">{{ $flat->flat_size ?? '' }}</td>
                                        <td class="text-center">
                                            {{-- <a data-toggle="modal" data-target=".view-modal-{{ $plot->id }}"><i
                                                class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a> --}}
                                            <a data-toggle="modal" data-target=".update-modal-{{ $flat->id }}"
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
                    <h4 class="modal-title">Add Flat</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_flat') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            {{-- <div class="col-lg-12">
                                <label for="flat_floor_id">Floor<i class="text-danger">*</i></label>
                                <select name="flat_floor_id" id="flat_floor_id" class="form-control chosen-select" required>
                                    <option value="">Select Floor</option>
                                    @foreach ($flat_floors as $flat_floor)
                                        @php
                                            $floorExists = App\Models\Flat::where('flat_floor_id', $flat_floor->id)
                                                ->where(['company_id' => Session::get('company_id')])
                                                ->where('status', 1)
                                                ->exists();
                                        @endphp

                                        @if (!$floorExists)
                                            <option value="{{ $flat_floor->id }}">{{ $flat_floor->floor_no }}
                                                ({{ $flat_floor->project->name ?? '' }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-lg-12">
                                <label for="flat_floor_id">Floor<i class="text-danger">*</i></label>
                                <select name="flat_floor_id" id="flat_floor_id" class="form-control chosen-select" required>
                                    <option value="">Select Floor</option>
                                    @foreach ($flat_floors as $flat_floor)
                                        <option value="{{ $flat_floor->id }}">
                                            {{ $flat_floor->floor_no }} ({{ $flat_floor->project->name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12 pt-3">
                                <div class="mb-3">
                                    <label class="form-label">Flat No<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="flat_no" placeholder="Flat No"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Flat Size (sq. ft.)<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="flat_size" placeholder="Flat Size"
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
    @foreach ($flats as $flat)
        {{-- @dd($flat) --}}
        <div class="modal fade update update-modal-{{ $flat->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Flat</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_flat', $flat->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="flat_floor_id">Floor<i class="text-danger">*</i></label>
                                    <select name="flat_floor_id" id="flat_floor_id" class="form-control chosen-select"
                                        required>
                                        <option value="">Select Floor</option>
                                        @foreach ($flat_floors as $flat_floor)
                                            <option value="{{ $flat_floor->id }}"
                                                @if ($flat_floor->id == $flat->flat_floor_id) selected @endif>
                                                {{ $flat_floor->floor_no }} ({{ $flat_floor->project->name ?? '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 pt-3">
                                    <div class="mb-3">
                                        <label class="form-label">Flat No<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="flat_no"
                                            value="{{ $flat->flat_no }}" placeholder="Flat No" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Flat Size (sq. ft.)<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="flat_size"
                                            value="{{ $flat->flat_size }}" placeholder="Flat Size" required>
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
@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(".chosen-select").chosen({
            width: "100%"
        });
    </script>

    <!-- jQuery এবং DataTables স্ক্রিপ্ট যোগ করুন -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            // DataTable ইনিশিয়ালাইজ করুন
            var table = $('#flatTable').DataTable({
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
@endpush
