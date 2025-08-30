@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Property List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Property
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Property Name</th>
                                    <th>Type</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($property_data as $property)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $property->name ?? '' }}</td>
                                        <td class="text-center">{{ $property->type ?? '' }}</td>
                                        <td class="text-center"><img
                                                src="{{ asset('/upload_images/property_image/' . $property->property_image) }}"
                                                height="80px" width="100px" /></td>
                                        <td class="text-center">
                                            @if ($property->status == 1)
                                               <span style="background-color: #28c76f; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Active</span>
                                            @else
                                               <span style="background-color: #ea5455; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $property->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-success  mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a data-toggle="modal" data-target=".update-modal-{{ $property->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if ($property->status == 1)
                                                <a href="{{ route('property_status', $property->id) }}" style="padding: 2px;"
                                                    class="delete btn btn-xs btn-danger  mr-1">
                                                    <i class="fa fa-arrow-down"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('property_status', $property->id) }}" style="padding: 2px;"
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
                    <h4 class="modal-title">Add Property</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_property') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-12">
                                <label for="type">Property Type<i class="text-danger">*</i></label>
                                <select name="type" id="type" class="form-control form-select" required>
                                    <option value="" selected>Select Property Type</option>
                                    <option value="Apartment">Apartment</option>
                                    <option value="Commercial">Commercial</option>
                                    <option value="House">House</option>
                                    <option value="Industrial">Industrial</option>
                                    <option value="Land">Land</option>
                                    <option value="Office">Office</option>
                                    <option value="Residential">Residential</option>
                                </select>
                            </div>
                            <div class="col-lg-12 p-2">
                                <div class="mb-3">
                                    <label class="form-label">Property Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="name" placeholder="Property Name"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="property_image">Property Image</label>
                                        <input type="file" name="property_image" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="phone" placeholder="Phone Number"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Address<i class="text-danger">*</i></label>
                                    <textarea type="text" cols="2" class="form-control" name="address" placeholder="Address..." required></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Description<i class="text-danger">*</i></label>
                                    <textarea type="text" cols="3" class="form-control" name="description" placeholder="Description..."
                                        required></textarea>
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
    @foreach ($property_data as $property)
        {{-- @dd($property) --}}
        <div class="modal fade update update-modal-{{ $property->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Property</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_property', $property->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="type">Property Type<i class="text-danger">*</i></label>
                                    <select name="type" class="form-control form-select" required>
                                        <option value="" {{ $property->type === '' ? 'selected' : '' }}>Select
                                            Property Type</option>
                                        <option value="Apartment" {{ $property->type === 'Apartment' ? 'selected' : '' }}>
                                            Apartment</option>
                                        <option value="Commercial"
                                            {{ $property->type === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                                        <option value="House" {{ $property->type === 'House' ? 'selected' : '' }}>House
                                        </option>
                                        <option value="Industrial"
                                            {{ $property->type === 'Industrial' ? 'selected' : '' }}>Industrial</option>
                                        <option value="Land" {{ $property->type === 'Land' ? 'selected' : '' }}>Land
                                        </option>
                                        <option value="Office" {{ $property->type === 'Office' ? 'selected' : '' }}>Office
                                        </option>
                                        <option value="Residential"
                                            {{ $property->type === 'Residential' ? 'selected' : '' }}>Residential</option>
                                    </select>
                                </div>
                                <div class="col-lg-12 p-2">
                                    <div class="mb-3">
                                        <label class="form-label">Property Name<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Property Name" value="{{ $property->name }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="property_image">Property Image</label>
                                            <input type="file" name="property_image" class="form-control" />
                                        </div>
                                        <img src="{{ asset('/upload_images/property_image/' . $property->property_image) }}" alt="property_image"
                                            style="height: 100px">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control" name="phone" value="{{ $property->phone }}"
                                            placeholder="Phone Number" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address<i class="text-danger">*</i></label>
                                        <textarea type="text" cols="2" class="form-control" name="address" placeholder="Address..." required>{{ $property->address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description<i class="text-danger">*</i></label>
                                        <textarea type="text" cols="3" class="form-control" name="description" placeholder="Description..."
                                            required>{{ $property->description }}</textarea>
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
    @foreach ($property_data as $property)
        <div class="modal fade view-modal-{{ $property->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Property Details</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-sm">
                                <tr>
                                    <th width="1%"></th>
                                    @if ($property->property_image)
                                        <td class="text-center">
                                            <img src="{{ asset('upload_images/property_image/' . $property->property_image) }}" height="80px"
                                                width="100px" />
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <img src="{{ asset('upload_images/asset/default_image.jpg') }}"
                                                alt="Property Image" style="height: 80px; width: 100px;">
                                        </td>
                                    @endif
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Property Name: </th>
                                    <td>{{ $property->name }}</td>

                                    <th width="20%">Property Type: </th>
                                    <td>{{ $property->type ?? '' }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Phone: </th>
                                    <td>{{ $property->phone }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Address: </th>
                                    <td>
                                        {{ $property->address }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Description: </th>
                                    <td>
                                        {!! $property->description !!}</td>
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
