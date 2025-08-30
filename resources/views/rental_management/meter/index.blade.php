@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Meter List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Meter
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Meter No.</th>
                                    <th>Installation Date</th>
                                    <th>Property</th>
                                    <th>Unit No.</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($meter_data as $meter)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $meter->meter_number ?? '' }}</td>
                                        <td class="text-center">{{ $meter->installation_date ?? '' }}</td>
                                        <td class="text-center">{{ $meter->unit->property->name ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if (!$meter->unit)
                                                -
                                            @else
                                                {{ $meter->unit->unit_name ?? '' }},
                                                {{ $meter->unit->floor->floor_name ?? '' }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($meter->status == 1)
                                                <span
                                                    style="background-color: #28c76f; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Active</span>
                                            @else
                                                <span
                                                    style="background-color: #ea5455; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $meter->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-success  mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a data-toggle="modal" data-target=".update-modal-{{ $meter->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if ($meter->status == 1)
                                                <a href="{{ route('meter_status', $meter->id) }}" style="padding: 2px;"
                                                    class="delete btn btn-xs btn-danger  mr-1">
                                                    <i class="fa fa-arrow-down"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('meter_status', $meter->id) }}" style="padding: 2px;"
                                                    class="delete btn btn-xs btn-success  mr-1">
                                                    <i class="fa fa-arrow-up"></i>
                                                </a>
                                            @endif
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
                    <h4 class="modal-title">Add Meter</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_meter') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Meter Number<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="meter_number"
                                        placeholder="Meter Number" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Installation Date<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" name="installation_date" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Note<i class="text-danger">*</i></label>
                                    <textarea type="text" cols="3" class="form-control" name="note" placeholder="Note..." required></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success  text-center col-md-12 mb-3"><i
                                    class="fa fa-check"></i>
                                Save</button>
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
    @foreach ($meter_data as $meter)
        {{-- @dd($meter) --}}
        <div class="modal fade update update-modal-{{ $meter->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Meter</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_meter', $meter->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Meter Number<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="meter_number"
                                            placeholder="Meter Number" value="{{ $meter->meter_number }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Installation Date<i class="text-danger">*</i></label>
                                        <input type="date" class="form-control" name="installation_date"
                                            value="{{ $meter->installation_date }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Note<i class="text-danger">*</i></label>
                                        <textarea type="text" cols="3" class="form-control" name="note" placeholder="Note..." required>{!! $meter->note !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success  text-center col-md-12 mb-3"><i
                                class="fa fa-check"></i>
                            Update</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    <!-- /.modal -->

    {{-- View modal start --}}
    @foreach ($meter_data as $meter)
        <div class="modal fade view-modal-{{ $meter->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Meter Details</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Property: </th>
                                    <td>{{ $meter->unit->property->name ?? '' }}
                                        ({{ $meter->unit->floor->floor_name ?? '' }})</td>

                                    <th width="20%">Unit: </th>
                                    <td>{{ $meter->unit->unit_name ?? ''}}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Meter No.: </th>
                                    <td>{{ $meter->meter_number }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Installation Date: </th>
                                    <td>
                                        {{ $meter->installation_date }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Note: </th>
                                    <td>
                                        {!! $meter->note !!}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    {{-- View modal end --}}
@endsection
