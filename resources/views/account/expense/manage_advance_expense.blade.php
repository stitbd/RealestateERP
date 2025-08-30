@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2 class="text-right mt-4 mb-5">Total Advance Expense : <b>{{$total_advance}}</b></h2>
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card card-info card-outline">
                <div class="card-header">
                    
                    <h3 class="card-title col-sm-11">
                        Advance Expense List
                    </h3>
                    <button class="text-end col-sm-1 btn btn-success btn-sm"  data-toggle="modal"
                    data-target=".create_modal" >+Add</button> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Expense Details</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($advance_expenses as $expense)
                                <tr>
                                    <td>{{$expense->id}}</td>
                                    <td>{{$expense->details}}</td>
                                    <td>{{$expense->amount}}</td>
                                    <td> <a data-toggle="modal"
                                        data-target=".update-modal-{{$expense->id}}"
                                        style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                       <i class="fas fa-edit"></i>
                                    </a></td>
                                </tr>
                                <div class="modal fade update update-modal-{{$expense->id}}" id="exampleModal"
                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-center">
                                                <h5 >Edit Data</h5>
                                                <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="{{route('advance-expense-update',$expense->id)}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body"> 
                                                   <div class="form-group row pt-3">
                                                        <label for="details" class="col-sm-3 col-form-label">Expense Details</label>
                                                        <label for="" class="col-sm-1 col-form-label">:</label>
                                                        <div class="col-sm-8">
                                                            <input name="details" type="text" class="form-control" placeholder="Type a Note.." value="{{$expense->details}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row pt-3">
                                                        <label for="category" class="col-sm-3 col-form-label">Amount</label>
                                                        <label for="" class="col-sm-1 col-form-label">:</label>
                                                        <div class="col-sm-8">
                                                            <input type="number" name="amount" id="" class="form-control" value="{{$expense->amount}}">
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
                        </tbody>
                    </table>
                    <div class="row pt-3">
                        <div class="col-lg-12">
                          {{$advance_expenses->links();}}
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
                <h5 >Add Account Head</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('advance-expense-store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body"> 
                   <div class="form-group row pt-3">
                        <label for="details" class="col-sm-3 col-form-label">Expense Details</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="details" type="text" class="form-control" placeholder="Type a Note..">
                        </div>
                    </div>

                   <div class="form-group row pt-3">
                        <label for="details" class="col-sm-3 col-form-label">Expense Date</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input name="date" type="date" class="form-control" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-group row pt-3">
                        <label for="category" class="col-sm-3 col-form-label">Amount</label>
                        <label for="" class="col-sm-1 col-form-label">:</label>
                        <div class="col-sm-8">
                            <input type="number" name="amount" id="" class="form-control">
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

{{-- @foreach($head as $c_head)

@endforeach  --}}
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>

</script>