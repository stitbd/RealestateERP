@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Security Money Entry
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('save-security') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="date">Date</label>
                                <input type="date" name="security_date" required class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label for="bank_name">Bank Name</label>
                                <input type="text" name="bank_name" required class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label for="Project">Project</label>
                                <select class="form-control" name="project_id">
                                    <option value="">Select One</option>
                                    @foreach ($projects as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                            <div class="col-lg-6">
                                <label for="reference">Reference</label>
                                <input type="text" name="reference" required class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label for="amount">Amount </label>
                                <input type="text" required name="amount" class="form-control"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="amount">Bank Credit Limit </label>
                                <input type="text" required name="bank_credit_limit" class="form-control"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="valid_date">Valid Date </label>
                                <input type="date" name="valid_date" required class="form-control"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="remarks">Remarks </label>
                                <input type="text" name="remarks" class="form-control"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="attachment">Attachment </label> <br/>
                                <input type="file" name="attachment"/>
                            </div>
                            
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-success float-right"><i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection