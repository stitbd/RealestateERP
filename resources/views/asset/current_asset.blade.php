@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                           Current Asset List
                        </h3>
                            <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add Asset
                            </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Asset Code</th>
                                    <th>Asset Name</th>
                                    <th>Location</th>
                                    <th>Owner</th>
                                    <th>Date Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($asset as $v_asset)
                                    <tr>
                                        <td>{{++$serial}}</td>
                                        <td>{{$v_asset->code_no}}</td>
                                        <td>{{$v_asset->name}}</td>
                                        <td>{{$v_asset->location}}</td>
                                        <td>{{$v_asset->owner}}</td>
                                        <td>{{$v_asset->date}}</td>
                                        <td>
                                            <button data-toggle="modal" onclick="load_edit_body('{{$v_asset->id}}','{{$v_asset->name}}','{{$v_asset->code_no}}','{{$v_asset->location}}','{{$v_asset->owner}}','{{$v_asset->date}}')" data-target="#modal-edit" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
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
                    <h4 class="modal-title">Add Current Asset </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('current_asset_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Type a brief description or name of asset." required oninput="assetcode()">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Asset Code No.<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="code_no" id="code_no" placeholder="Type asset Code" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Location.(If applicable)</label>
                                    <input type="text" class="form-control" name="location" placeholder="Type physical location of the asset">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Owner/Responsible Dept.<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="owner" placeholder="Type Owner Name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date.</label>
                                    <input type="date" class="form-control" name="date" placeholder="" required>
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
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Update Asset</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('update_asset_category') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="asset_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="name" id="edit_name" placeholder="Type a brief description or name of asset." required oninput="assetcode()">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Asset Code No.<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="code_no" id="edit_code_no" placeholder="Type asset Code" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Location.(If applicable)</label>
                                    <input type="text" class="form-control" name="location" id="location" placeholder="Type physical location of the asset">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Owner/Responsible Dept.<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="owner" id="owner" placeholder="Type Owner Name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date.</label>
                                    <input type="date" class="form-control" name="date" id="date" placeholder="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <script>
        function load_edit_body(id,name,code_no,location,owner,date) {
            $('#asset_id').val(id);
            $('#edit_name').val(name);
            $('#edit_code_no').val(code_no);
            $('#location').val(location);
            $('#owner').val(owner);
            $('#date').val(date);
        }

        function assetcode(){
            var name = document.getElementById('name').value;
            if (name) {
            var lastId = {{$asset_id ?? 0}}; 
            var nextId = lastId + 1;
            var code = 'ASSET-00' + nextId;
            document.getElementById('code_no').value = code;
            } else {
                document.getElementById('code_no').value = ''; 
            }
        }
    </script>
@endsection
