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
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Property</th>
                                    <th>Floor</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($floor_data as $floor)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $floor->property->name ?? '' }}</td>
                                        <td class="text-center">{{ $floor->floor_name ?? '' }}</td>
                                        <td class="text-center">{!! $floor->description !!}</td>
                                        <td class="text-center">
                                            @if ($floor->status == 1)
                                                <span
                                                    style="background-color: #28c76f; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Active</span>
                                            @else
                                                <span
                                                    style="background-color: #ea5455; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".update-modal-{{ $floor->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if ($floor->status == 1)
                                                <a href="{{ route('floor_status', $floor->id) }}" style="padding: 2px;"
                                                    class="delete btn btn-xs btn-danger  mr-1">
                                                    <i class="fa fa-arrow-down"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('floor_status', $floor->id) }}" style="padding: 2px;"
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
                    <h4 class="modal-title">Add Floor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_floor') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="property_id">Property<i class="text-danger">*</i></label>
                                <select name="property_id" id="property_id" class="form-control form-select" required>
                                    <option value="">Select property</option>
                                    @foreach ($properties as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Floor Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="floor_name" placeholder="Floor Name"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Description<i class="text-danger">*</i></label>
                                    <textarea type="text" cols="3" class="form-control" name="description" placeholder="Description..." required></textarea>
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
    @foreach ($floor_data as $floor)
        {{-- @dd($property) --}}
        <div class="modal fade update update-modal-{{ $floor->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Floor</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_floor', $floor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">

                                <div class="col-lg-6">
                                    <label for="property_id">Property<i class="text-danger">*</i></label>
                                    <select name="property_id" id="property_id" class="form-control form-select" required>
                                        <option value="">Select property</option>
                                        @foreach ($properties as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == $floor->property_id) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Floor Name<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="floor_name" value="{{$floor->floor_name}}"
                                            placeholder="Floor Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description<i class="text-danger">*</i></label>
                                        <textarea type="text" cols="3" class="form-control" name="description" placeholder="Description..."
                                            required>{{$floor->description}}</textarea>
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
@endsection
