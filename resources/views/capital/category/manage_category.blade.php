@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display{
        padding-left: 15px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover{
        background: transparent;
        color: white;
    }
</style>
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title col-sm-11">
                        Capital Category List
                    </h3>
                    <button class="text-end col-sm-1 btn btn-success btn-sm"  data-toggle="modal"
                    data-target="#exampleModal" >+Add</button> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                          @foreach($categories as $category)
                          <tr>
                            <td>{{++$i}}</td>
                            <td>{{$category->category_name}}</td>
                            <td>
                                <a data-toggle="modal"
                                    data-target=".update-modal-{{$category->id}}"
                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                   <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{route('category-delete',$category->id)}}"
                                    onclick="return confirm('Are you sure you want to delete?');"
                                    style="padding: 2px;" class="delete btn btn-xs btn-danger  mr-1">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade create_modal" id="exampleModal"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-center">
                <h5 >Add Capital Category</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('capital-category-store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body"> 
                   <div class="form-group row pt-3">
                        <label for="category_name" class="col-sm-3 col-form-label">Category Name</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="category_name" type="text" class="form-control" placeholder="Type Category_name............" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>

@foreach($categories as $category)
<div class="modal fade update update-modal-{{$category->id}}" id="exampleModal"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-center">
                <h5 >Edit Capital Category</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('capital-category-update',$category->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body"> 
                   <div class="form-group row pt-3">
                        <label for="category_name" class="col-sm-3 col-form-label">Category Name</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="edit_category_name" type="text" class="form-control" value="{{$category->category_name}}" placeholder="Type Category_name............">
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
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<script>
    $(document).ready(function() {
        $('.create_modal').on('shown.bs.modal', function () {
            $('.js-example-basic-multiple').select2();
        });
        $('.update').on('shown.bs.modal', function () {
            $('.js-example-basic-multiple').select2();
        });
    });
</script>