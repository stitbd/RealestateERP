@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Fund Ledger List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    {{-- <form action="{{ route('ledger-list') }}" method="get"> --}}
                    <div class="row pb-3">
                        <div class="col-lg-3">
                            <label for="start_date">Select Fund</label>
                            <select name="fund_id" id="fund_id" class="form-control">
                                @foreach($funds as $fund)
                                    <option value="{{$fund->id}}">{{$fund->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="@php echo date('Y-m-d') @endphp"/>
                        </div>
                        <div class="col-lg-3">
                            <label for="end_date">End Date</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="@php echo date('Y-m-d') @endphp"/>
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="action">Action</label> <br/>
                            <button class="btn btn-success btn-block" onclick="viewLedger();">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div id="wrapper">

</div>
    <div class="container pt-5">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" id="main-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Fund</th>
                            <th>Transaction For</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($logs as $log)
                            <tr>
                                <td>{{date('d/m/Y',strtotime($log->transection_date))}}</td>
                                <td>{{$log->fund->name}}</td>
                                <td>{{$log->transection_type}}</td>
                                <td>{{$log->type == 2 ? $log->amount : ''}}</td>
                                <td>{{$log->type == 1 ? $log->amount : ''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="row pt-3">
                    <div class="col-lg-12">
                        {{$logs->links();}}
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection

<script>
     function viewLedger(){
        $('#main-table').hide();
        $('.btn-warning').hide();
        var fund_id = document.getElementById('fund_id').value;
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var url = "{{route('fund-ledger-list')}}"

        $.ajax({
            type:'GET',
            url:url,
            data:{start_date,end_date,fund_id},
            success:function(data){
                $('#wrapper').html(data);
            }
        });
    }
</script>