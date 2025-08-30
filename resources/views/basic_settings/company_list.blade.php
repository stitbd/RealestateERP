@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Company List
                        <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Company
                        </button>
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info text-center">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Logo</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($company_data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td><img src="{{ asset('upload_images/company_logo/'.$item->logo) }}"  width="100px"/></td>
                            <td>{{ ($item->address) }}</td> 
                            <td>{{ ($item->email) }}</td> 
                            <td>{{ ($item->phone) }}</td> 
                            <td>
                                @if ($item->status == '1')
                                    <a href="{{ route('change-company-status',['0',$item->id]) }}" class="text-success text-bold">Active</a>
                                @else
                                    <a href="{{ route('change-company-status',['1',$item->id]) }}" class="text-danger text-bold">Inactive</a>
                                @endif
                            </td>
                            <td>
                                <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->name}}','{{$item->email}}','{{$item->address}}','{{$item->phone}}')" data-target="#modal-edit" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</button>
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
          <h4 class="modal-title">Add Company</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('save-company') }}" method="post" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name"
                            placeholder="Your Company name">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email"
                            placeholder="Your email">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone"
                            placeholder="Your phone">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address"
                            placeholder="Your address">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo"/>
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
          <h4 class="modal-title">Update Company</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('update-company') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id"  id="company_id">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" name="name"
                            placeholder="Your name">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"  id="email" class="form-control" name="email"
                            placeholder="Your email">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            placeholder="Your phone">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                            placeholder="Your address">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo"/>
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
    function load_edit_body(company_id,name,email,address,phone){
        $('#company_id').val(company_id);
        $('#name').val(name);
        $('#email').val(email);
        $('#address').val(address);
        $('#phone').val(phone);
    }
</script>
@endsection

