@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Item List
                        <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Item
                        </button>
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('item-print')}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('item-pdf')}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-info text-center">
                                <th>SL</th>
                                <th>Code No.</th>
                                <th>Company</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Size/Type</th>
                                <th>Unit</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($item_data as $item)
                            <tr>
                                <td> @php
                                    $i = ($item_data instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($loop->iteration + ($item_data->perPage() * ($item_data->currentPage() - 1)))  : ++$i;
                                @endphp {{$i}}
                                </td>
                                <td>{{ $item->code_no }}</td>
                                <td>{{ $item->company->name ?? ''}}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->category->category_name??'' }}</td>
                                <td>{{ $item->size_type }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>
                                    @if ($item->status == '1')
                                        <a href="{{ route('change-item-status',['0',$item->id]) }}" class="text-success text-bold">Active</a>
                                    @else
                                        <a href="{{ route('change-item-status',['1',$item->id]) }}" class="text-danger text-bold">Inactive</a>
                                    @endif
                                </td>
                                <td>
                                    <a data-toggle="modal" data-target=".edit-modal-{{ $item->id }}"
                                        style="padding:2px; color:white" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row pt-3">
                        <div class="col-lg-12">
                            @if($item_data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $item_data->links() }}
                            @endif
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
          <h4 class="modal-title">Add Item</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('save-item') }}" method="post" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Item Name/Description<i
                            class="text-danger">*</i></label>
                        <input type="text" class="form-control" name="name" required
                            placeholder="Enter item name">
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Item Category<i
                            class="text-danger">*</i></label>
                        <select name="category_id" id="category" class="form-control" onchange="generateCode()" required>
                            <option value="">Select a Category</option>
                            @foreach($item_category as $v_category)
                                <option value="{{$v_category->id}}"> {{$v_category->category_name}} </option>
                            @endforeach
                            {{-- <option value="Electronics">Electronics</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Office Supplies">Office Supplies</option>
                            <option value="Tools and Equipment">Tools and Equipment</option>
                            <option value="Consumables">Consumables</option> --}}
                        </select>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Item Code</label>
                        <input type="text" class="form-control" name="code_no" id="code_no"
                            placeholder="generate the item code">
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Item Size/Type<i
                            class="text-danger">*</i></label>
                        <input type="text" class="form-control" name="size_type" required
                            placeholder="Item size/type">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Unit Of Measure<i
                            class="text-danger">*</i></label>
                        <select name="unit" class="form-control" id="" required>
                            <option value="">Select Unit</option>
                            <option value="Kilogram">Kilogram</option>
                            <option value="Meter">Meter</option>
                            <option value="Peice">Peice</option>
                            <option value="Cft">Cft</option>
                            <option value="Bag">Bag</option>
                        </select>
                    </div>
                </div>
                {{-- <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Asset Type</label>
                        <select name="asset_type" class="form-control">
                            <option value="Current">Current</option>
                            <option value="Fixed">Fixed</option>
                        </select>
                    </div>
                </div> --}}
                
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
  <!-- /.modal -->


  
<!-- Edit Modal -->
@foreach ($item_data as $item)
<div class="modal fade update edit-modal-{{ $item->id }}" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Update Item</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('update-item', $item->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
            <div class="row">
               <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Item Name/Description<i class="text-danger">*</i></label> 
                        <input type="text" class="form-control" name="name" id="name" required value="{{$item->name}}"
                            placeholder="Enter item name">
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Item Category<i class="text-danger">*</i></label>
                        <select name="category" id="category_id" class="form-control" required onchange="generateCode()">
                            <option value="">Select a Category</option>
                            @foreach($item_category as $v_category)
                                <option value="{{$v_category->id}}" @if ($v_category->id == $item->category_id) selected @endif> {{$v_category->category_name}} </option>
                            @endforeach
                            {{-- <option value="Electronics">Electronics</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Office Supplies">Office Supplies</option>
                            <option value="Tools and Equipment">Tools and Equipment</option>
                            <option value="Consumables">Consumables</option> --}}
                        </select>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Item Code</label>
                        <input type="text" class="form-control" name="code_no" id="code_no_edit"
                            placeholder="generate the item code" value="{{$item->code_no}}" readonly>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Item Size/Type<i class="text-danger">*</i></label> 
                        <input type="text" class="form-control" name="size_type" required
                            placeholder="Item size/type" id="size_type" value="{{$item->size_type}}">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Unit Of Measure <i class="text-danger">*</i></label>
                        <select name="unit" class="form-control" id="unit" required>
                            <option value="" {{ $item->unit === '' ? 'selected' : '' }}>Select Unit</option>
                            <option value="Kilogram" {{ $item->unit === 'Kilogram' ? 'selected' : '' }}>Kilogram</option>
                            <option value="Meter" {{ $item->unit === 'Meter' ? 'selected' : '' }}>Meter</option>
                            <option value="Piece" {{ $item->unit === 'Piece' ? 'selected' : '' }}>Piece</option>
                            <option value="Cft" {{ $item->unit === 'Cft' ? 'selected' : '' }}>Cft</option>
                            <option value="Bag" {{ $item->unit === 'Bag' ? 'selected' : '' }}>Bag</option>
                        </select>
                    </div>
                </div>
                {{-- <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Asset Type</label>
                        <select name="asset_type" class="form-control">
                            <option value="Current">Current</option>
                            <option value="Fixed">Fixed</option>
                        </select>
                    </div>
                </div> --}}
                
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endforeach
  <!-- /.modal -->
@endsection

@push('script_js')
<script>
    function generateCode() {
        var category_id = document.getElementById('category').value;
            if (category_id) {
            var lastId = {{$item_code ?? 0}}; // Set lastItem Id to 0 if it's not available
            // console.log(lastId);
            var nextId = lastId + 1; // Increment last Item ID or start from 1 if no previous item
            var code = '#ITEM00-' + nextId;
            document.getElementById('code_no').value = code;
            } else {
                document.getElementById('code_no').value = ''; // Reset if no category selected
            }
        }
</script>
@endpush

