@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title col-sm-11">
                        Bank List
                    </h3> 
                    <button class="text-end col-sm-1 btn btn-success btn-sm"  data-toggle="modal"
                    data-target="#exampleModal" >+Add Bank</button> 
                </div> 
                <div class="card-body p-3">  
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>#</th>
                                <th>Bank Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($banks as $bank)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$bank->name}}</td>
                                <td> <a data-toggle="modal"
                                    data-target=".update-modal-{{$bank->id}}"
                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                   <i class="fas fa-edit"></i>
                                </a></td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{-- {{$banks->links();}} --}}
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
                <h5 >Add Bank</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('save-bank-info') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body"> 
                   <div class="form-group row pt-3">
                        <label for="bank_name" class="col-sm-3 col-form-label">Bank Name</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="bank_name" type="text" class="form-control" placeholder="Type Bank Name............">
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
@foreach($banks as $v_bank)

<div class="modal fade update update-modal-{{$v_bank->id}}" id="exampleModal"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-center">
                <h5 >Edit Bank Name</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('update-bank',$v_bank->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body"> 
                   <div class="form-group row pt-3">
                        <label for="category_name" class="col-sm-3 col-form-label">Bank Name</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="bank_name" type="text" class="form-control" value="{{$v_bank->name}}" placeholder="">
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