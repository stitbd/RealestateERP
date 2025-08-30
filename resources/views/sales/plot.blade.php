@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Plot List
                        </h3>
                        <a href="{{ route('plot-create') }}" class="text-end col-sm-2 btn btn-success btn-sm">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Plot
                        </a>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <table id="plotTable" class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Project Name</th>
                                    <th>Sector No</th>
                                    <th>Road No</th>
                                    <th>Plot Size</th>
                                    <th>Plot No</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($plots as $plot)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $plot->project->name ?? '' }}</td>
                                        <td class="text-center">{{ $plot->sector->sector_name ?? '' }}</td>
                                        <td class="text-center">{{ $plot->road->road_name ?? '' }}</td>
                                        <td class="text-center">
                                            {{ $plot->plotType ? $plot->plotType->plot_type . '(' . $plot->plotType->percentage . ' শতাংশ)' : '' }}
                                        </td>
                                        <td class="text-center">{{ $plot->plot_no ?? '' }}</td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $plot->id }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a data-toggle="modal" data-target=".update-modal-{{ $plot->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="{{ route('plot_delete', $plot->id) }}"
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
                    <h4 class="modal-title">Add Plot</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_plot') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Plot No<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="plot_no" placeholder="Plot No"
                                        required>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Sector No.<i class="text-danger">*</i></label>
                                    <select name="sector_id" id="sector_id" class="form-control form-select" required
                                        onchange="sectorIdWiseRoad()">
                                        <option value="" selected>Select Sector</option>
                                        @foreach ($sectors as $sector)
                                            <option value="{{ $sector->id }}">{{ $sector->sector_name }}
                                                ({{ $sector->project->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="road_id">Road No.<i class="text-danger">*</i></label>
                                <select name="road_id" id="road_id" class="form-control form-select" required>
                                    <option value="" selected>Select Road</option>
                                    @foreach ($roads as $road)
                                        <option value="{{ $road->id }}">{{ $road->road_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Block No.<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="block_no" placeholder="Block No">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Measurement<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="measurement" placeholder="Measurement">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Facing<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="facing" placeholder="Facing">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Plot Size (Katha)<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="plot_size" placeholder="Plot Size">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Plot Type<i class="text-danger">*</i></label>
                                    <select name="plot_type" id="plot_type" class="form-control form-select" required>
                                        <option value="" selected>Select Type</option>
                                        <option value="residential">Residential Plot</option>
                                        <option value="commercial">Commercial Plot</option>
                                        <option value="industrial">Industrial Plot</option>
                                        <option value="agricultural">Agricultural Plot</option>
                                        <option value="mixed-Use">Mixed-Use Plot</option>
                                        <option value="recreational">Recreational Plot</option>
                                        <option value="institutional">Institutional Plot</option>
                                        <option value="green_belt">Green Belt Plot</option>
                                        <option value="development">Development Plot</option>
                                        <option value="heritage">Heritage Plot</option>
                                        <option value="environmentally_sensitive">Environmentally Sensitive Plot</option>
                                    </select>
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
    @foreach ($plots as $plot)
        {{-- @dd($plot) --}}
        @php
            $plot_types = App\Models\PlotType::where(['project_id' => $plot->project_id])
                ->with('company', 'project')
                ->get();
        @endphp
        <div class="modal fade update update-modal-{{ $plot->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Plot</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_plot', $plot->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Plot No<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" value="{{ $plot->plot_no }}"
                                            id="plot_no" name="plot_no" placeholder="Plot Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Sector No.<i class="text-danger">*</i></label>
                                        <select name="sector_id" id="edit_sector_id{{ $plot->id }}"
                                            onchange="sectorIdWiseRoadEdit({{ $plot->id }})"
                                            class="form-control form-select" required>
                                            <option value="" selected>Select Sector</option>
                                            @foreach ($sectors as $sector)
                                                <option value="{{ $sector->id }}"
                                                    @if ($sector->id == $plot->sector_id) selected @endif>
                                                    {{ $sector->sector_name }} ({{ $sector->project->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="road_id">Road No.<i class="text-danger">*</i></label>
                                    <select name="road_id" id="edit_road_id{{ $plot->id }}" class="form-control"
                                        required>
                                        <option value="" selected>Select Road</option>
                                        @foreach ($roads as $road)
                                            <option value="{{ $road->id }}"
                                                @if ($road->id == $plot->road_id) selected @endif>
                                                {{ $road->road_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Block No.<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="block_no"
                                            value="{{ $plot->block_no }}" placeholder="Block No">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Measurement<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="measurement"
                                            value="{{ $plot->measurement }}" placeholder="Measurement">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Facing<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="facing"
                                            value="{{ $plot->facing }}" placeholder="Facing">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Type of Plot in Katha(কাঠার ধরন)<i
                                                class="text-danger">*</i></label>
                                        <select name="type_id" id="plot-type" class="form-control"
                                            onchange="getPlotSize(this);">
                                            <option value="">Select Plot Katha Type</option>
                                            @foreach ($plot_types as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ $type->id == $plot->type_id ? 'selected' : '' }}>
                                                    {{ $type->plot_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Plot Type<i class="text-danger">*</i></label>
                                        <select name="plot_type" id="plot_type" class="form-control form-select"
                                            required>
                                            <option value="" selected>Select Type</option>
                                            <option
                                                value="residential"{{ $plot->plot_type == 'residential' ? 'selected' : '' }}>
                                                Residential Plot</option>
                                            <option
                                                value="commercial"{{ $plot->plot_type == 'commercial' ? 'selected' : '' }}>
                                                Commercial Plot</option>
                                            <option
                                                value="industrial"{{ $plot->plot_type == 'industrial' ? 'selected' : '' }}>
                                                Industrial Plot</option>
                                            <option
                                                value="agricultural"{{ $plot->plot_type == 'agricultural' ? 'selected' : '' }}>
                                                Agricultural Plot</option>
                                            <option
                                                value="mixed_use"{{ $plot->plot_type == 'mixed_use' ? 'selected' : '' }}>
                                                Mixed-Use Plot</option>
                                            <option
                                                value="recreational"{{ $plot->plot_type == 'recreational' ? 'selected' : '' }}>
                                                Recreational Plot</option>
                                            <option
                                                value="institutional"{{ $plot->plot_type == 'institutional' ? 'selected' : '' }}>
                                                Institutional Plot</option>
                                            <option
                                                value="green_belt"{{ $plot->plot_type == 'green_belt' ? 'selected' : '' }}>
                                                Green Belt Plot</option>
                                            <option
                                                value="development"{{ $plot->plot_type == 'development' ? 'selected' : '' }}>
                                                Development Plot</option>
                                            <option
                                                value="heritage"{{ $plot->plot_type == 'heritage' ? 'selected' : '' }}>
                                                Heritage Plot</option>
                                            <option
                                                value="environmentally_sensitive"{{ $plot->plot_type == 'environmentally_sensitive' ? 'selected' : '' }}>
                                                Environmentally Sensitive Plot
                                            </option>
                                        </select>
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

    <!-- View Modal -->
    @foreach ($plots as $plot)
        <div class="modal fade view-modal-{{ $plot->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-m">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Plot</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Plot No: </th>
                                    <td>{{ $plot->plot_no }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Project: </th>
                                    <td>
                                        {{ $plot->sector->project->name ?? '' }}
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Sector No: </th>
                                    <td>
                                        {{ $plot->sector->sector_name ?? '' }}
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Road No: </th>
                                    <td>{{ $plot->road->road_name ?? '' }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Block No: </th>
                                    <td>{{ $plot->block_no ?? '' }}</td>
                                </tr>
                            </table>

                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Measurement: </th>
                                    <td>{{ $plot->measurement ?? '' }}</td>
                                </tr>

                            </table>
                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Facing: </th>
                                    <td>{{ $plot->facing }}</td>
                                </tr>

                            </table>
                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Plot Size: </th>
                                    <td>{{ $plot->plotType ? $plot->plotType->plot_type . '(' . $plot->plotType->percentage . ' শতাংশ)' : '' }}
                                    </td>
                                </tr>

                            </table>
                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Plot Type: </th>
                                    <td>{{ $plot->plot_type }}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    <!-- /.modal -->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function sectorIdWiseRoad() {
        var sector_id = document.getElementById('sector_id').value;
        var url = "{{ route('getSectorWiseRoad') }}";

        $.ajax({
            type: "GET",
            url: url,
            data: {
                sector_id: sector_id
            },
            dataType: "json",
            success: function(response) {
                $('#road_id').empty().append('<option value="" selected>Select Road</option>');
                $.each(response, function(index, road) {
                    $('#road_id').append('<option value="' + road.id + '">' + road.road_name +
                        '</option>');
                });
            },
            error: function() {
                alert('An error occurred while fetching the road data.');
            }
        });
    }
</script>

<script>
    function sectorIdWiseRoadEdit(plotId, selectedRoadId) {
        var sector_id = document.getElementById('edit_sector_id' + plotId).value;
        var url = "{{ route('getSectorWiseRoad') }}";

        $.ajax({
            type: "GET",
            url: url,
            data: {
                sector_id: sector_id
            },
            dataType: "json",
            success: function(response) {
                var roadSelect = $('#edit_road_id' + plotId);
                roadSelect.empty().append('<option value="" selected>Select Road</option>');
                $.each(response, function(index, road) {
                    var selected = road.id == selectedRoadId ? ' selected' : '';
                    roadSelect.append('<option value="' + road.id + '"' + selected + '>' + road
                        .road_name + '</option>');
                });
            },
            error: function() {
                alert('An error occurred while fetching the road data.');
            }
        });
    }

    $(document).ready(function() {
        $('[id^=edit_sector_id]').each(function() {
            var plotId = $(this).attr('id').replace('edit_sector_id', '');
            var selectedRoadId = $('#edit_road_id' + plotId).val();
            if ($(this).val()) {
                sectorIdWiseRoadEdit(plotId, selectedRoadId);
            }
        });

        $('[id^=edit_sector_id]').change(function() {
            var plotId = $(this).attr('id').replace('edit_sector_id', '');
            sectorIdWiseRoadEdit(plotId, null);
        });
    });
</script>

<!-- jQuery এবং DataTables স্ক্রিপ্ট যোগ করুন -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // DataTable ইনিশিয়ালাইজ করুন
        var table = $('#plotTable').DataTable({
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
