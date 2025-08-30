@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Supplier List
                        <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Supplier
                        </button>
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('supplier-print')}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('supplier-pdf')}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-info text-center">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Mobile</th>
                                <th>Contact Person Name</th>
                                <th>Contact Person Mobile</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Other Details</th>
                                <th>Note</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supplier_data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->company->name }}</td>
                                <td>{{ $item->mobile }}</td>
                                <td>{{ $item->contact_person_name }}</td>
                                <td>{{ $item->contact_person_mobile }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->address }}</td>
                                <td>{{ $item->other_details }}</td>
                                <td>{{ $item->note }}</td>
                                <td>
                                    @if ($item->status == '1')
                                        <a href="{{ route('change-supplier-status',['0',$item->id]) }}" class="text-success text-bold">Active</a>
                                    @else
                                        <a href="{{ route('change-supplier-status',['1',$item->id]) }}" class="text-danger text-bold">Inactive</a>
                                    @endif
                                </td>
                                <td>
                                    <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->name}}','{{$item->mobile}}','{{$item->email}}','{{$item->address}}','{{$item->other_details}}','{{$item->contact_person_name}}','{{$item->contact_person_mobile}}','{{$item->note}}')" data-target="#modal-edit" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</button>
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
          <h4 class="modal-title">Add Supplier</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('save-supplier') }}" method="post" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Name <i
                            class="text-danger">*</i></label>
                        <input type="text" class="form-control" name="name"
                            placeholder="Supplier name" required>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Mobile <i
                            class="text-danger">*</i></label>
                        <input type="number" class="form-control" name="mobile"
                            placeholder="Mobile no" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email"
                            placeholder="Email">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address"
                            placeholder="Address">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Other Details</label>
                        <input type="text" class="form-control" name="other_details"
                            placeholder="Other details">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Contact Person Name<i
                            class="text-danger">*</i></label>
                        <input type="text" class="form-control" name="contact_person_name"
                            placeholder="Contact Person Name" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Contact Person Mobile <i
                            class="text-danger">*</i></label>
                        <input type="number" class="form-control" name="contact_person_mobile"
                            placeholder="Contact Person Mobile" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea type="text" cols="3" rows="3" class="form-control" name="note"
                            placeholder="Note"></textarea>
                    </div>
                </div>
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
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Update Supplier</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('update-supplier') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id"  id="supplier_id">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Name<i
                            class="text-danger">*</i></label>
                        <input type="text" id="name" class="form-control" name="name"
                            placeholder="Supplier name" required>
                    </div>
                </div>
                
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Mobile<i
                            class="text-danger">*</i></label>
                        <input type="number" class="form-control" name="mobile"
                            placeholder="Mobile no" id="mobile" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email"
                            placeholder="Email">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address"
                            placeholder="Address" id="address">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Other Details</label>
                        <input type="text" class="form-control" name="other_details"
                            placeholder="Your other details" id="other_details">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Contact Person Name<i
                            class="text-danger">*</i></label>
                        <input type="text" class="form-control" name="contact_person_name" id="contact_person_name"
                            placeholder="Contact Person Name" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Contact Person Mobile<i
                            class="text-danger">*</i></label>
                        <input type="number" class="form-control" name="contact_person_mobile" id="contact_person_mobile"
                            placeholder="Contact Person Mobile" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea id="note" type="text" cols="3" rows="3" class="form-control" name="note"
                            placeholder="Note"></textarea>
                    </div>
                </div>
                
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
  
  <script>
    function load_edit_body(supplier_id, name, mobile, email, address, other_details, contact_person_name, contact_person_mobile, note) {
        $('#supplier_id').val(supplier_id);
        $('#name').val(name);
        $('#mobile').val(mobile);
        $('#email').val(email);
        $('#address').val(address);
        $('#other_details').val(other_details);
        $('#contact_person_name').val(contact_person_name);
        $('#contact_person_mobile').val(contact_person_mobile);
        $('#note').val(note);
    }
</script>

@endsection

