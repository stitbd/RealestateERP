@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Bank Entry
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('save-bank-info') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            
                            <div class="col-lg-6">
                                <label for="name">Bank Name</label>
                                <input type="text" name="bank_name" required class="form-control">
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