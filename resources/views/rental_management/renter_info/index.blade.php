@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Renter List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Renter
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Renter Name</th>
                                    <th>Property</th>
                                    <th>Floor</th>
                                    <th>Unit</th>
                                    <th>Rent</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($renter_data as $renter)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $renter->name ?? '' }}</td>
                                        <td class="text-center">{{ $renter->property->name ?? '' }}</td>
                                        <td class="text-center">{{ $renter->floor->floor_name ?? '' }}</td>
                                        <td class="text-center">{{ $renter->unit->unit_name ?? '' }}</td>
                                        <td class="text-center">{{ $renter->unit->rent_amount ?? '' }} Tk.</td>
                                        <td class="text-center">
                                            @if ($renter->status == 1)
                                                <span
                                                    style="background-color: #28c76f; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Active</span>
                                            @else
                                                <span
                                                    style="background-color: #ea5455; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $renter->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-success  mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a data-toggle="modal" data-target=".update-modal-{{ $renter->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if ($renter->status == 1)
                                                <a href="{{ route('renter_status', $renter->id) }}" style="padding: 2px;"
                                                    class="delete btn btn-xs btn-danger  mr-1">
                                                    <i class="fa fa-arrow-down"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('renter_status', $renter->id) }}" style="padding: 2px;"
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
                    <h4 class="modal-title">Add Renter</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_renter') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-4">
                                <label for="property_id">Property<i class="text-danger">*</i></label>
                                <select name="property_id" id="property_id" class="form-control form-select" required>
                                    <option value="">Select property</option>
                                    @foreach ($properties as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label for="floor_id">Floor<i class="text-danger">*</i></label>
                                <select name="floor_id" id="floor_id" class="form-control form-select" required>
                                    <option value="">Select floor</option>
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label for="unit_id">Unit<i class="text-danger">*</i></label>
                                <select name="unit_id" id="unit_id" class="form-control form-select" required>
                                    <option value="">Select Unit</option>
                                </select>
                            </div>

                            <div class="col-lg-6 p-2">
                                <div class="mb-3">
                                    <label class="form-label">Renter Father Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="father_name"
                                        placeholder="Renter Father Name" required>
                                </div>
                            </div>
                            <div class="col-lg-6 p-2">
                                <div class="mb-3">
                                    <label class="form-label">Renter Mother Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="mother_name"
                                        placeholder="Renter Mother Name" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Renter Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="name" placeholder="Renter Name"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Renter Business Category<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="business_category"
                                        placeholder="Renter Business Category" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Renter Business Address<i class="text-danger">*</i></label>
                                    <textarea class="form-control" rows="2" name="business_address" placeholder="Renter Address" required></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Present Address<i class="text-danger">*</i></label>
                                    <textarea class="form-control" rows="2" name="present_address" placeholder="Present Address" required></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Permanent Address<i class="text-danger">*</i></label>
                                    <textarea class="form-control" rows="2" name="permanent_address" placeholder="Permanent Address" required></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="phone" placeholder="Phone Number"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email<i class="text-danger">*</i></label>
                                    <input type="email" class="form-control" name="email" placeholder="Email"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="agreement_document">Agreement Document<i
                                                class="text-danger">*</i></label>
                                        <input type="file" name="agreement_document" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Agreement End Notice<i class="text-danger">*</i></label>
                                    <textarea rows="2" class="form-control" name="agreement_end_notice" placeholder="Agreement End Notice"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Rent Start Date<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" name="rent_start_date" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Rent End Date<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" name="rent_end_date" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Agreement Period<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="agreement_period" required
                                        placeholder="Agreement Period">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Advance<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="advance" placeholder="Advance"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="nid">NID Copy<i class="text-danger">*</i></label>
                                        <input type="file" name="nid" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="trade_license">Trade License<i class="text-danger">*</i></label>
                                        <input type="file" name="trade_license" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Starting Month<i class="text-danger">*</i></label>
                                    <input type="month" class="form-control" name="starting_month"
                                        placeholder="Starting Month" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Rent Duration<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="rent_duration"
                                        placeholder="Day of Month Between 1 to 30" required>
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
    @foreach ($renter_data as $renter)
        {{-- @dd($renter) --}}
        <div class="modal fade update update-modal-{{ $renter->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Renter</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_renter', $renter->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="property_id">Property<i class="text-danger">*</i></label>
                                    <select name="property_id" id="property_id_{{ $renter->id }}" class="form-control form-select"
                                        required>
                                        <option value="">Select property</option>
                                        @foreach ($properties as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == $renter->property_id) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="floor_id">Floor<i class="text-danger">*</i></label>
                                    <select name="floor_id" id="floor_id_{{ $renter->id }}" class="form-control form-select" required>
                                        <option value="">Select floor</option>
                                        @foreach ($floors as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == $renter->floor_id) selected @endif>{{ $item->floor_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="unit_id">Unit<i class="text-danger">*</i></label>
                                    <select name="unit_id" id="unit_id_{{ $renter->id }}" class="form-control form-select" required>
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == $renter->unit_id) selected @endif>{{ $item->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 p-2">
                                    <div class="mb-3">
                                        <label class="form-label">Renter Father Name<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="father_name"
                                            placeholder="Renter Father Name" required value="{{ $renter->father_name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 p-2">
                                    <div class="mb-3">
                                        <label class="form-label">Renter Mother Name<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="mother_name"
                                            value="{{ $renter->mother_name }}" placeholder="Renter Mother Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Renter Name<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Renter Name" value="{{ $renter->name }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Renter Business Category<i
                                                class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="business_category"
                                            placeholder="Renter Business Category" required
                                            value="{{ $renter->business_category }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Renter Business Address<i
                                                class="text-danger">*</i></label>
                                        <textarea class="form-control" rows="2" name="business_address" placeholder="Renter Address" required>{{ $renter->business_address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Present Address<i class="text-danger">*</i></label>
                                        <textarea class="form-control" rows="2" name="present_address" placeholder="Present Address" required>{{ $renter->present_address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Permanent Address<i class="text-danger">*</i></label>
                                        <textarea class="form-control" rows="2" name="permanent_address" placeholder="Permanent Address" required>{{ $renter->permanent_address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control" name="phone"
                                            placeholder="Phone Number" value="{{ $renter->phone }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email<i class="text-danger">*</i></label>
                                        <input type="email" class="form-control" name="email" placeholder="Email"
                                            value="{{ $renter->email }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="agreement_document">Agreement Document</label>
                                            <input type="file" name="agreement_document" class="form-control" />
                                        </div>
                                        <img src="{{ asset('/upload_images/agreement_document/' . $renter->agreement_document) }}"
                                            alt="agreement_document" style="height: 100px">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Agreement End Notice<i class="text-danger">*</i></label>
                                        <textarea rows="2" class="form-control" name="agreement_end_notice" placeholder="Agreement End Notice"
                                            required>{{ $renter->agreement_end_notice }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Rent Start Date<i class="text-danger">*</i></label>
                                        <input type="date" class="form-control" name="rent_start_date" required
                                            value="{{ $renter->rent_start_date }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Rent End Date<i class="text-danger">*</i></label>
                                        <input type="date" class="form-control" name="rent_end_date" required
                                            value="{{ $renter->rent_end_date }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Agreement Period<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="agreement_period" required
                                            placeholder="Agreement Period" value="{{ $renter->agreement_period }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Advance<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control" name="advance" placeholder="Advance"
                                            value="{{ $renter->advance }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="nid">NID Copy<i class="text-danger">*</i></label>
                                            <input type="file" name="nid" class="form-control" />
                                        </div>
                                        <img src="{{ asset('/upload_images/nid_copy/' . $renter->nid) }}" alt="nid"
                                            style="height: 100px">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="trade_license">Trade License<i class="text-danger">*</i></label>
                                            <input type="file" name="trade_license" class="form-control" />
                                        </div>
                                        <img src="{{ asset('/upload_images/trade_license/' . $renter->trade_license) }}"
                                            alt="trade_license" style="height: 100px">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Starting Month<i class="text-danger">*</i></label>
                                        <input type="month" class="form-control" name="starting_month"
                                            value="{{ $renter->starting_month }}" placeholder="Starting Month" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Rent Duration<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="rent_duration"
                                            value="{{ $renter->rent_duration }}"
                                            placeholder="Day of Month Between 1 to 30" required>
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

    <!-- View Modal -->
    @foreach ($renter_data as $renter)
        <div class="modal fade view-modal-{{ $renter->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Renter Details</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Renter Name: </th>
                                    <td>{{ $renter->name }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Property: </th>
                                    <td>{{ $renter->property->name }}</td>

                                    <th width="20%">Floor: </th>
                                    <td>{{ $renter->floor->floor_name ?? '' }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Unit: </th>
                                    <td>{{ $renter->unit->unit_name }}</td>
                                </tr>
                            </table>

                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Father Name: </th>
                                    <td>{{ $renter->father_name }}</td>

                                    <th width="20%">Mother Name: </th>
                                    <td>{{ $renter->mother_name }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Present Address: </th>
                                    <td>{{ $renter->present_address }}</td>

                                    <th width="20%">Permanent Address: </th>
                                    <td>{{ $renter->permanent_address }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Business Category: </th>
                                    <td>{{ $renter->business_category }}</td>

                                    <th width="20%">Business Address: </th>
                                    <td>{{ $renter->business_address }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">NID: </th>
                                    <td><img src="{{ asset('upload_images/nid_copy/' . $renter->nid) }}" height="80px"
                                            width="100px" /></td>

                                    <th width="20%">Trade License: </th>
                                    <td><img src="{{ asset('upload_images/trade_license/' . $renter->trade_license) }}"
                                            height="80px" width="100px" /></td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Phone: </th>
                                    <td>{{ $renter->phone }}</td>

                                    <th width="20%">Email: </th>
                                    <td>{{ $renter->email }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Agreement Document: </th>
                                    <td><img src="{{ asset('upload_images/agreement_document/' . $renter->agreement_document) }}"
                                            height="80px" width="100px" /></td>

                                    <th width="20%">Agreement Period: </th>
                                    <td>{{ $renter->agreement_period }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Rent Start Date: </th>
                                    <td>{{ $renter->rent_start_date }}</td>

                                    <th width="20%">Rent End Date: </th>
                                    <td>{{ $renter->rent_end_date }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Starting Month: </th>
                                    <td>{{ $renter->starting_month }}</td>

                                    <th width="20%">Rent Duration: </th>
                                    <td>{{ $renter->rent_duration }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Advance: </th>
                                    <td>{{ $renter->advance }} Tk.</td>
                                </tr>
                                <tr>
                                    <th width="20%">Advance Left: </th>
                                    <td>{{ $renter->advance_left }} Tk.</td>
                                </tr>
                            </table>

                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Agreement End Notice: </th>
                                    <td>
                                        {!! $renter->agreement_end_notice !!}</td>
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
    $(document).ready(function() {
        // When Property is selected
        $('#property_id').on('change', function() {
            let propertyId = $(this).val();
            if (propertyId) {
                $.ajax({
                    url: '/get-floors-by-property/' + propertyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#floor_id').empty().append(
                            '<option value="">Select floor</option>');
                        $.each(data.floors, function(key, value) {
                            $('#floor_id').append('<option value="' + value.id +
                                '">' + value.floor_name + '</option>');
                        });
                    }
                });
            } else {
                $('#floor_id').empty().append('<option value="">Select floor</option>');
                $('#unit_id').empty().append('<option value="">Select Unit</option>');
            }
        });

        // When Floor is selected
        $('#floor_id').on('change', function() {
            let floorId = $(this).val();
            let propertyId = $('#property_id').val();
            if (floorId) {
                $.ajax({
                    url: '/get-units-by-floor/' + floorId + '/' + propertyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#unit_id').empty().append(
                            '<option value="">Select Unit</option>');
                        $.each(data.units, function(key, value) {
                            $('#unit_id').append('<option value="' + value.id +
                                '">' + value.unit_name + '</option>');
                        });
                    }
                });
            } else {
                $('#unit_id').empty().append('<option value="">Select Unit</option>');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        // When Property is selected
        $('[id^="property_id_"]').on('change', function() {
            let propertyId = $(this).val();
            let renterId = $(this).attr('id').split('_')[2];
            if (propertyId) {
                $.ajax({
                    url: '/get-floors-by-property/' + propertyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#floor_id_' + renterId).empty().append(
                            '<option value="">Select floor</option>');
                        $.each(data.floors, function(key, value) {
                            $('#floor_id_' + renterId).append('<option value="' +
                                value.id + '">' + value.floor_name + '</option>'
                                );
                        });
                        $('#unit_id_' + renterId).empty().append(
                            '<option value="">Select Unit</option>');
                    }
                });
            } else {
                $('#floor_id_' + renterId).empty().append('<option value="">Select floor</option>');
                $('#unit_id_' + renterId).empty().append('<option value="">Select Unit</option>');
            }
        });

        // When Floor is selected
        $('[id^="floor_id_"]').on('change', function() {
            let floorId = $(this).val();
            let renterId = $(this).attr('id').split('_')[2];
            let propertyId = $('#property_id_' + renterId).val();
            if (floorId) {
                $.ajax({
                    url: '/get-units-by-floor/' + floorId + '/' + propertyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#unit_id_' + renterId).empty().append(
                            '<option value="">Select Unit</option>');
                        $.each(data.units, function(key, value) {
                            $('#unit_id_' + renterId).append('<option value="' +
                                value.id + '">' + value.unit_name + '</option>');
                        });
                    }
                });
            } else {
                $('#unit_id_' + renterId).empty().append('<option value="">Select Unit</option>');
            }
        });
    });
</script>
