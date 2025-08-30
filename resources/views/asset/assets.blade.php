@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Asset List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Asset
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <form action="{{ route('asset_list') }}" method="get">
                            <div class="row pb-3">
                                {{-- <div class="col-lg-3">
                                    <label for="name">Asset Category</label>
                                    <select name="name" class="form-control">
                                        <option value="" selected>Select Category</option>
                                        @foreach ($asset_category as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-lg-3">
                                    <label for="group_id">Asset Group</label>
                                    <select name="group_id" class="form-control">
                                        <option value="" selected>Select Group</option>
                                        @foreach ($asset_groups as $asset_group)
                                            <option value="{{ $asset_group->id }}">{{ $asset_group->name }} ({{$asset_group->asset_category->name}})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="name">Asset</label>
                                    <input type="text" class="form-control" name="name" placeholder="Asset Name">
                                </div>
                                {{-- <div class="col-lg-3">
                                    <label class="form-label">Asset Type</label>
                                    <select name="asset_type" class="form-control">
                                        <option value="">Select Asset Type</option>
                                        <option value="Fixed"> Fixed </option>
                                        <option value="Usable"> Usable </option>
                                        <option value="Current"> Current </option>
                                    </select>
                                </div>  --}}
                                <div class="col-lg-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>

                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center" >
                                    <th style="padding: 7px;">SL</th>
                                    <th style="padding: 7px;">Asset Code</th>
                                    <th style="padding: 7px;">Asset Name</th>
                                    <th style="padding: 7px;">Image</th>
                                    <th style="padding: 7px;">Series/Model</th>
                                    <th style="padding: 7px;">Asset Group</th>
                                    <th style="padding: 7px;">Date of Purchase</th>
                                    <th style="padding: 7px;">Quantity</th>
                                    <th style="padding: 7px;">Unit Price</th>
                                    <th style="padding: 7px;">Total Price</th>
                                    <th style="padding: 7px;">Suppplier Name</th>
                                    <th style="padding: 7px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($assets as $asset)
                                    <tr>
                                        <td class="text-center" style="padding: 7px;">{{ ++$serial }}</td>
                                        <td class="text-center" style="padding: 7px;">{{ $asset->asset_code}}</td>
                                        <td class="text-center" style="padding: 7px;">{{ $asset->name ?? '' }}</td>
                                        @if ($asset->image)
                                        <td class="text-center">
                                            <img src="{{ asset('upload_images/asset/' . $asset->image) }}"
                                                height="70px" width="90px" />
                                        </td>
                                        @else
                                            <td class="text-center">
                                                <img src="{{ asset('upload_images/asset/default_image.jpg') }}"
                                                    alt="Asset Image" style="height: 70px; width: 90px;">
                                            </td>
                                        @endif
                                        <td class="text-center" style="padding: 7px;">{{ $asset->asset_series ?? '' }}</td>
                                        <td class="text-center" style="padding: 7px;">{{ $asset->asset_group->name ?? '' }} </td>
                                        <td class="text-center" style="padding: 7px;">{{ $asset->purchase_date ?? '' }}</td>
                                        <td class="text-center" style="padding: 7px;">{{ $asset->quantity ?? '' }}</td>
                                        <td class="text-center" style="padding: 7px;">{{ $asset->unit_price ?? '' }}</td>
                                        <td class="text-center" style="padding: 7px;">{{ $asset->total_price ?? '' }}</td>
                                        <td class="text-center" style="padding: 7px;">{{ $asset->supplier->name ?? '' }}</td>
                                        {{-- @if ($asset->life_time)
                                            <td class="text-center">{{ $asset->life_time ?? '' }} Years</td>
                                        @else
                                            <td class="text-center">{{ $asset->life_time ?? '' }}</td>
                                        @endif --}}

                                        {{-- @dd($asset->image) --}}
                                      

                                        {{-- <td class="text-center"
                                            style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $asset->description }}
                                        </td> --}}
                                        <td class="text-center" style="padding: 7px;">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                </button>
                                                <div class="dropdown-menu actionmenu" style="font-size: 14.5px">
                                                {{-- <div class="dropdown-divider"></div> --}}
                                                <a class="dropdown-item" href="#" id="btncheckout" customdata="431"  data-toggle="modal" data-target=".view-modal-{{ $asset->id }}"><i class="fa fa-eye pr-1" style="color: rgb(78, 151, 78)"></i>View</a>
                                                <a class="dropdown-item" href="#" id="btnedit" customdata="431" data-toggle="modal" data-target=".update-modal-{{ $asset->id }}"><i class="fa fa-edit" style="color: rgb(38, 137, 145)"></i> Edit</a>
                                                <a class="dropdown-item" href="#" id="btnedit" customdata="431" data-toggle="modal" data-target=".payment-modal-{{ $asset->id }}"><i class="fa fa-file" style="color: rgb(38, 137, 145)"></i> Generate Payment</a>
                                                <a class="dropdown-item" href="{{ route('asset_delete', $asset->id) }}" id="btnedit" onclick="return confirm('Are you sure you want to delete?');"
                                                    ><i class="fa fa-trash" style="color:rgb(138, 34, 34)"></i> Delete</a>
                                                </div>

                                            </div>
                                        </td>
                                        {{-- <td >
                                            <a ></a>
                                            <a 
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a 
                                                >
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $assets->links() }}
                            </div>
                        </div>
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
                    <h4 class="modal-title">Add Asset</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_asset') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            {{-- <div class="col-lg-12">
                                <label for="category_id">Asset Category<i class="text-danger">*</i></label>
                                <select name="category_id" id="category_id" class="form-control form-select" required>
                                    <option value="" selected>Select Category</option>
                                    @foreach ($asset_category as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                           
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Asset Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Asset Name"
                                    required oninput="assetcode();">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Asset Code<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="code" id="code_no" placeholder="Asset Code"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Series/Model</label>
                                    <input type="text" class="form-control" name="series" placeholder="Series/Model">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="group_id">Asset Group<i class="text-danger">*</i></label>
                                <select name="group_id" id="group_id" class="form-control form-select" required>
                                    <option value="" selected>Select Group</option>
                                    {{-- @dd($asset_groups) --}}
                                    @foreach ($asset_groups as $asset_group)
                                        <option value="{{ $asset_group->id }}">{{ $asset_group->name }}
                                            ({{ $asset_group->asset_category->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Asset Quantity<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Quantity"
                                        required oninput="calculatePrice();">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Unit<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="unit"  placeholder="Ex: Piece" >
                                </div>
                            </div>
                          
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Unit Price<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="unit_price" id="price" placeholder="Unit Price"
                                        required oninput="calculatePrice();">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Total Price<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="total_price" placeholder="Total Price" id="total_price"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Date of purchase <i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" name="date" placeholder=""
                                        required>
                                </div>
                            </div>
                            {{-- <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Asset Type<i class="text-danger">*</i></label>
                                    <select name="asset_type" id="asset_type" class="form-control" required>
                                        <option value="">Select Asset Type</option>
                                        <option value="Fixed"> Fixed </option>
                                        <option value="Usable"> Usable </option>
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-lg-6" id="lifeTimeSection">
                                <div class="mb-3">
                                    <label class="form-label">Warranty period(Years)</label>
                                    <input type="number" class="form-control" name="warranty_yr" placeholder="Warranty in years">
                                </div>
                            </div> --}}
                            <div class="col-lg-6" id="lifeTimeSection">
                                <div class="mb-3">
                                    <label class="form-label">Warranty period(Month)</label>
                                    <input type="number" class="form-control" name="warranty_mnth" placeholder="Warranty in months">
                                </div>
                            </div>
                            <div class="col-lg-6" id="lifeTimeSection">
                                <div class="mb-3">
                                    <label class="form-label">Depreciation (Month)</label>
                                    <input type="number" class="form-control" name="life_time" placeholder="Depriciation in months">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="image"> Asset Image</label>
                                    <input type="file" name="image" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="supplier">Supplier <i class="text-danger">*</i></label>
                                    <select name="supplier_id" id="" class="form-control">
                                        <option value="">Select Supplier</option>
                                        @foreach ($supplier as $v_supplier)
                                            <option value="{{$v_supplier->id}}">{{$v_supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea type="text" class="form-control" name="description" cols="4" rows="4"
                                        placeholder="Asset Description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->



    <!-- Edit Modal -->
    @foreach ($assets as $asset)
        <div class="modal fade update update-modal-{{ $asset->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Asset</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_asset', $asset->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Asset Name<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" value="{{ $asset->name }}"
                                            id="name" name="name" placeholder="Asset Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Asset Code<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" value="{{ $asset->asset_code }}"
                                            id="asset_code" name="asset_code" placeholder="Asset Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Series / Model</label>
                                        <input type="text" class="form-control" value="{{ $asset->asset_series }}"
                                            id="asset_series" name="asset_series" placeholder="Series/Model Name" >
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="group_id">Asset Group<i class="text-danger">*</i></label>
                                    <select name="group_id" class="form-control form-select" required>
                                        <option value="" selected>Select Group</option>
                                        @foreach ($asset_groups as $asset_group)
                                            <option value="{{ $asset_group->id }}"
                                                @if ($asset_group->id == $asset->group_id) selected @endif>
                                                {{ $asset_group->name }} ({{ $asset_group->asset_category->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Quantity<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" value="{{ $asset->quantity }}"
                                            id="e_quantity" name="quantity" placeholder="Quantity" required  oninput="editPrice();">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Unit</label>
                                        <input type="text" class="form-control" value="{{ $asset->unit }}"
                                            id="unit" name="unit" placeholder="Unit" >
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Unit Price<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" value="{{ $asset->unit_price }}"
                                            id="unit_price" name="unit_price" placeholder="Unit Price" required  oninput="editPrice();">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Total Price<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" value="{{ $asset->total_price }}"
                                            id="e_total_price" name="total_price" placeholder="Total Price" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Purchase Date<i class="text-danger">*</i></label>
                                        <input type="date" class="form-control" value="{{ $asset->purchase_date }}"
                                            id="purchase_date" name="purchase_date" placeholder="Purchase Date" required>
                                    </div>
                                </div>
                            
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Warranty(In Month)<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" value="{{ $asset->warranty_mnth }}"
                                            id="warranty_mnth" name="warranty_mnth" placeholder="Purchase Date" required>
                                    </div>
                                </div>

                                @if ($asset->life_time)
                                    <div class="col-lg-6" id="lifeTimeSectionUpdate_{{ $asset->id }}">
                                        <div class="mb-3">
                                            <label class="form-label">Life Time (In month)</label>
                                            <input type="number" class="form-control" value="{{ $asset->life_time }}"
                                                name="life_time" placeholder="Life Time" min="0" required>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="image"> Asset Image </label>
                                        <input type="file" name="image" class="form-control" />
                                        @if ($asset->image)
                                            <img src="{{ asset('upload_images/asset/' . $asset->image) }}" height="70px"
                                                width="90px" />
                                        @else
                                            <img src="{{ asset('upload_images/asset/default_image.jpg') }}"
                                                height="70px" width="90px" />
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="supplier_id">Supplier<i class="text-danger">*</i></label>
                                    <select name="supplier_id" class="form-control form-select" required>
                                        <option value="" selected>Select Supplier</option>
                                        @foreach ($supplier as $v_supplier)
                                            <option value="{{ $v_supplier->id }}"
                                                @if ($v_supplier->id == $asset->supplier_id) selected @endif>
                                                {{ $v_supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea type="text" class="form-control" cols="4" rows="4"
                                            name="description" id="description" placeholder="Asset Description">{{ $asset->description }}</textarea>
                                    </div>
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
    <!-- /.modal -->


    <!-- View Modal -->
    @foreach ($assets as $asset)
        <div class="modal fade view-modal-{{ $asset->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Asset Details</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row p-3">
                            <table class="table table-sm">
                                <tr>
                                    <th width="1%"></th>
                                    @if ($asset->image)
                                        <td class="text-center">
                                            <img src="{{ asset('upload_images/asset/' . $asset->image) }}" height="80px"
                                                width="100px" />
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <img src="{{ asset('upload_images/asset/default_image.jpg') }}"
                                                alt="Asset Image" style="height: 80px; width: 100px;">
                                        </td>
                                    @endif
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Asset Code </th>
                                    <th width="">: </th>
                                    <td>{{ $asset->asset_code }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Asset Name</th>
                                    <th>:</th>
                                    <td>{{ $asset->name }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Asset Series/Model </th>
                                    <th>:</th>
                                    <td>{{ $asset->asset_series }}</td>
                                </tr>
                              
                                <tr>

                                    <th width="20%">Asset Category </th>
                                    <th>:</th>
                                    <td>{{ $asset->asset_group->asset_category->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Asset Group </th>
                                    <th>:</th>
                                    <td>{{ $asset->asset_group->name }}</td>
                                </tr>
                                <tr>
                                    @if ($asset->life_time)
                                        <th width="20%">Life Time</th>
                                        <th>:</th>
                                        <td>{{ $asset->life_time }} Months</td>
                                    @endif
                                </tr>

                                <tr>
                                    @if ($asset->warranty_mnth)
                                        <th width="20%">Warranty</th>
                                        <th>:</th>
                                        <td>{{ $asset->warranty_mnth }} Months</td>
                                    @endif
                                </tr>
                                
                            
                                <tr>
                                    <th width="20%">Asset Quantity</th>
                                    <th>:</th>
                                    <td>{{ $asset->quantity ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Unit</th>
                                    <th>:</th>
                                    <td>{{ $asset->unit }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Unit Price </th>
                                    <th>:</th>
                                    <td>{{ $asset->unit_price }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Total Price </th>
                                    <th>:</th>
                                    <td>{{ $asset->total_price }}</td>
                                </tr>
                                
                           
                                <tr>
                                    <th width="20%">Description</th>
                                    <th>:</th>
                                    <td>
                                        {{ $asset->description }}</td>
                                </tr>
                          
                                <tr>
                                    <th width="20%">Supplier Name </th>
                                    <th>:</th>
                                    <td>
                                        {{ $asset->supplier->name }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Purchase Date </th>
                                    <th>:</th>
                                    <td>
                                        {{ date('d/m/Y', strtotime($asset->purchase_date)) }}</td>
                                </tr>
                            </table>
                          
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    <!-- /.modal -->

    <!-- Payment Modal -->
    @foreach ($assets as $asset)
        <div class="modal fade payment-modal-{{ $asset->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Asset Details</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row p-3">
                            <form action="save-payment" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="purchase_id" value="{{$asset->id}}">
                                <input type="hidden" name="supplier_id" value="{{$asset->supplier_id}}">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Date<i
                                                class="text-danger">*</i></label>
                                            <input type="date" class="form-control" name="date" id="date" required value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                        
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Purchase Amount<i
                                                class="text-danger">*</i></label>
                                            <input type="number" class="form-control" name="purchase_amount"  readonly id=""
                                                value="" >
                                        </div>
                                    </div>
                        
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Amount<i
                                                class="text-danger">*</i></label>
                                            <input type="number" class="form-control" name="amount"  required id="amount"
                                                placeholder="Enter Payment Amount" oninput="generatePaymentCode();">
                                        </div>
                                    </div>
                        
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Code<i
                                                class="text-danger">*</i></label>
                                            <input type="text" class="form-control" name="code_no"  required id="code_no"
                                                placeholder="">
                                        </div>
                                    </div>
                        
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Fund<i
                                                class="text-danger">*</i></label>
                                            <select name="fund_id" id="fund_id" class="form-control" required  onchange="checkPaymentType();checkType();">
                                                <option value="">Select a Fund</option>
                                                @foreach($fund as $v_fund)
                                                  <option value="{{$v_fund->id}}">{{$v_fund->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                        
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Type<i
                                                class="text-danger">*</i></label>
                                            <select name="payment_type" class="form-control" id="payment_type" required>
                                                <option value="">Select One</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Bank">Bank</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                        
                                    <div class="col-lg-6 bank">
                                        <label for="mobile_no">Bank <i
                                        class="text-danger">*</i></label>
                                        <select name="bank_id" id="bank_id" class="form-control form-select chosen-select" onchange="filterAccount()">
                                            <option value="" selected>Select Bank</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                        
                                    <div class="col-lg-6 bank">
                                        <label for="mobile_no">Bank Account<i
                                        class="text-danger">*</i></label>
                                        <select name="account_id" id="account" class="form-control form-select chosen-select">
                                            <option value="" selected>Select a Bank Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}">{{$account->account_no}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                        
                                    <div class="col-6 bank">
                                        <div class="mb-3">
                                            <label class="form-label">Cheque(If any)</label>
                                            <input type="text" class="form-control" name="cheque_no" id="date"
                                            placeholder="Enter Your Cheque Number">
                                        </div>
                                    </div>
                        
                                    <div class="col-6 bank">
                                        <div class="mb-3">
                                            <label class="form-label">Cheque Issue Date(If any)</label>
                                            <input type="date" class="form-control" name="cheque_issue_date" id="date"
                                            placeholder="Enter Your Cheque Number">
                                        </div>
                                    </div>
                        
                                    <div class="col-lg-12 mobile">
                                        <label for="mobile_no">Mobile No.<i
                                            class="text-danger">*</i></label>
                                        <input type="text" name="mobile_no" class="form-control "/>
                                    </div>
                        
                                    <div class="col-12" >
                                        <div class="mb-3">
                                            <label class="form-label">Please provide any details <i
                                                class="text-danger">*</i></label>
                                            <textarea name="remarks" id="" cols="10" rows="2" required class="form-control"></textarea>
                                        </div>
                                    </div>
                        
                                    <div class="col-12" >
                                        <div class="mb-3">
                                            <label class="form-label">Include Attachment(If Any) </label>
                                                <input type="file" name="attachment">
                                        </div>
                                    </div>
                        
                                    <div class="col-3 pt-3">
                                        <button class="btn btn-success btn-block"><i class="fa fa-check"></i> Save</button>
                                    </div>
                                </div>
                            </form>
                            
                          
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
        $('[id^="edit_asset_type_"]').change(function() {
            var selectedasset_type = $(this).val();
            var assetId = $(this).attr('id').split('_')[3];
            $.ajax({
                url: '/check-asset-type',
                type: 'POST',
                data: {
                    asset_type: selectedasset_type
                },
                dataType: 'json',
                success: function(response) {
                    var assetType = response.asset_type; 
                    if (assetType === 'Fixed') {
                        $('#lifeTimeSectionUpdate_' + assetId).show();
                    } else {
                        $('#lifeTimeSectionUpdate_' + assetId).hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
        $('#asset_type').change(function() {
            var selectedasset_type = $(this).val();
            // console.log(selectedasset_type);
            $.ajax({
                url: '/check-asset-type',
                type: 'POST',
                data: {
                    asset_type: selectedasset_type
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    var assetType = response.asset_type; 
                    if (assetType === 'Fixed') {
                        $('#lifeTimeSection').show();
                    } else {
                        $('#lifeTimeSection').hide();
                    }
                },

                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });

    function assetcode(){
            var name = document.getElementById('name').value;
            let firstThreeLetters = name.substring(0,3);
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            if (name) {
            var lastId = {{$asset_id ?? 0}}; 
            var nextId = lastId + 1;
            var code = 'AST-'+ currentYear + nextId;
            document.getElementById('code_no').value = code;
            } else {
                document.getElementById('code_no').value = ''; 
            }
        }

        function calculatePrice(){
            let quantity = document.getElementById('quantity').value;
            let price = document.getElementById('price').value;
            let total = quantity * price;
            document.getElementById('total_price').value = total;
        }

        function editPrice(){
            let quantity = document.getElementById('e_quantity').value;
            let price = document.getElementById('unit_price').value;
            let total = quantity * price;
            document.getElementById('e_total_price').value = total;
        }

       
</script>

<script>
    $( document ).ready(function() {
       $('.bank').hide();
       $('.cheque').hide();
       $('.mobile').hide();
   });

   function checkPaymentType(){
       var fund_id = document.getElementById('fund_id').value;
       if(fund_id == 2){
           $('.bank').hide().removeAttr('required', true);
           $('.cheque').hide();
           $('.mobile').hide();

       }
       if(fund_id == 1){
           $('.bank').show().prop('required', true);
           $('.cheque').show();
           $('.mobile').hide();

       }
       if(fund_id == 3){
           $('.bank').hide().removeAttr('required', true);
           $('.cheque').hide();
           $('.mobile').show();
       }
       if(fund_id == 4){
           $('.bank').hide().removeAttr('required', true);
           $('.cheque').hide();
           $('.mobile').hide();
       }
      }


      function filterAccount(){
           var bank_id = document.getElementById('bank_id').value;
           var url = "{{route('filter-account')}}";
           $.ajax({
               type:"GET",
               url:url,
               data:{bank_id},
               success:function(data){
                   $('#account').find('option').remove();
                   $('#account').html('<option value="">Select One</option>');
                   $.each(data, function(key, value) {
                       $('#account').append('<option value="' + value.id + '">' + value.account_no +
                           '</option>');
                   });
                   $('#account').trigger("chosen:updated");
               },
           });
      }

      function generatePaymentCode() {
       var amount = document.getElementById('amount').value;
           if (amount) {
           var lastId = {{$payment_id ?? 0}};
           var nextId = lastId + 1; 
           let date = new Date();
           let year = date.getFullYear();
           console.log(year);
           var paymentCode = 'P-'+year+ nextId;
           document.getElementById('code_no').value = paymentCode;
           } else {
               document.getElementById('code_no').value = ''; 
           }
       }
      
</script>
