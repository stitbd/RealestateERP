@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title col-sm-11">
                        Account Sub Head List
                    </h3>
                    <button class="text-end col-sm-1 btn btn-success btn-sm"  data-toggle="modal"
                    data-target="#exampleModal" >+Add</button>
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('account-head') }}" method="get">
                        <div class="row pb-3">
                            <div class="col-lg-6">
                                <label for="search">Search For</label>
                                <input type="text" name="search" class="form-control">
                            </div>
                            <div class="col-lg-3">
                                <label for="action">Action</label> <br />
                                <button class="btn btn-success btn-block">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Sub Head Name</th>
                                <th>Main Head Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                          @foreach($head as $v_head)
                          <tr>
                            <td>@php
                                $i = ($head instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($loop->iteration + ($head->perPage() * ($head->currentPage() - 1)))  : ++$i;
                            @endphp {{$i}}</td>
                            <td>{{$v_head->head_name}}</td>
                            <td >{{$v_head->category? $v_head->category->category_name : ''}}</td>
                            <td>
                                <a data-toggle="modal"
                                    data-target=".update-modal-{{$v_head->id}}"
                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                   <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{route('head-delete',$v_head->id)}}"
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
                           {{$head->links()}}
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
                <h5 >Add Account Sub Head</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('head-store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row pt-3">
                        <label for="category" class="col-sm-3 col-form-label">Category</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <select name="category" id="" class="form-control">
                                <option value="">Select a Type</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                   <div class="form-group row pt-3">
                        <label for="head_name" class="col-sm-3 col-form-label">Head Name</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="head_name" type="text" class="form-control" placeholder="Type Head Name............">
                        </div>
                    </div>

                    <!--<div class="form-group row pt-3">-->
                    <!--    <div class="col-sm-12 text-right">-->
                    <!--        <input class="form-check-input mt-2" type="checkbox" name="only_head_office"-->
                    <!--            id="yes" value="1" onclick="">-->
                    <!--        <label class="form-check-label ml-1" style="font-size: 18px" for="yes"><b>Only For Head Office</b></label>-->
                    <!--        <input type="hidden" name="only_head_office" value="0">-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($head as $c_head)
<div class="modal fade update update-modal-{{$c_head->id}}" id="exampleModal"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-center">
                <h5 >Update Account Sub Head</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('head-update',$c_head->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                   <div class="form-group row pt-3">
                        <label for="head_name" class="col-sm-3 col-form-label">Head Name</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="head_name" type="text" class="form-control" value="{{$c_head->head_name}}" placeholder="Type Head Name............">
                        </div>
                    </div>
                    <div class="form-group row pt-3">
                        <label for="category" class="col-sm-3 col-form-label">Category </label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <select name="category" id="" class="form-control">
                                @foreach($categories as $t_category)
                                <option value="{{$t_category->id}}" @if($t_category->id == $c_head->category_id) selected @endif>{{$t_category->category_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--<div class="form-group row pt-3">-->
                    <!--    <div class="col-sm-12 text-right">-->
                    <!--        <input class="form-check-input mt-2" type="checkbox" name="only_head_office"-->
                    <!--            id="yes" value="1" onclick="updateValue()" @if($c_head->only_head_office == 1) checked @endif>-->
                    <!--            <label class="form-check-label ml-1" style="font-size: 18px" for="yes"><b>Only For Head Office</b></label>-->

                    <!--    </div>-->
                    <!--</div>-->
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
<script>
function updateValue() {
    let checkbox = document.getElementById('yes');
    checkbox.value = checkbox.checked ? 1 : 0;
}
</script>
