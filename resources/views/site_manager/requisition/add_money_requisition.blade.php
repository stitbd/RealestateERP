@extends('layouts.app')
@section('content')

<div class="container mt-2">

     <style>
            .profile-tabs .nav-pills .nav-link {
                border-bottom: 2px solid transparent;
                color: black;
                background-color: transparent;
                padding: 10px 10px;
                border-radius: 0;
                position: relative;
                display: inline-block;
            }


            .profile-tabs .nav-pills .nav-link.active {
                border-bottom: 2px solid #0077ff;
            }

            .profile-tabs .nav-pills .nav-link i {
                margin-right: 4px;
            }
        </style>
        <div class="row" style="background: #dbeaf0; margin-left:2px; margin-right:2px;">
            <div class="col-md-12 pl-2 pr-2">

                <div class="profile-tabs">
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('money-requisition-list') ? 'active' : '' }}"
                                href="{{ route('money-requisition-list') }}" style="background: none; color: rgb(50, 53, 52); font-size:16.5px;">
                                <i class="fas fa-list" style="color:rgb(8, 103, 148)"></i>
                                <span class="d-none d-md-inline text-bold">All Requisition</span>
                            </a>
                        </li>

                        <!-- New Requisition Tab -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('money-requisition-entry') ? 'active' : '' }}"
                                href="{{ route('money-requisition-entry') }}" style="background: none; color: black; font-size:16.5px;">
                                <i class="fas fa-plus-circle" style="color:#4242f3"></i>
                                <span class="d-none d-md-inline text-bold">New Requisition</span>
                            </a>
                        </li>

                        <!-- Approve Requisition Tab -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reject-requisition-list') ? 'active' : '' }}"
                                href="{{ route('reject-requisition-list') }}" style="background: none; color: black; font-size:16.5px;">
                                <i class="fas fa-check-circle" style="color:green"></i>
                                <span class="d-none d-md-inline text-bold">Approve Requisition</span>
                            </a>
                        </li>

                        <!-- Reject Requisition Tab -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reject-requisition-list') ? 'active' : '' }}"
                                href="{{ route('reject-requisition-list') }}" style="background: none; color: black; font-size:16.5px;">
                                <i class="fas fa-times-circle" style="color:rgb(150, 10, 10)"></i>
                                <span class="d-none d-md-inline text-bold">Reject Requisition</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    <div class="row justify-content-center mt-2">
   
        <div class="col-md-12">
            <div class="card card-outline" style="border-top:3px solid #426bf3">
                <div class="card-header " >
                    <div class="row">
                        <div class="col-sm-12" style="font-size:16px;">
                            <p class="text-bold h6" >
                                Create Money Requisition
                            </p> 
                        </div>
                    </div>
                  
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('money-requisition-store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="date">Requisition Date</label>
                                <input type="date" name="requisition_date" required class="form-control" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="date">Requested Amount</label>
                                <input type="number" name="amount" required class="form-control" placeholder="Amount" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="date">Purpose of Requisition</label>
                                <input type="text" name="purpose" required class="form-control" placeholder="Purpose" required>
                            </div>
                            <div class="col-lg-12 pt-3" style="text-align: right">
                                <button class="btn col-lg-2" style="background:#426bf3; color:white" ><i class="fa fa-check"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function add_more(){
        var row_data = $('.row_tr').html();
        row_data = '<tr>'+row_data+'</tr>';

        $("table tbody").append(row_data);
    }
    
</script>




@endsection