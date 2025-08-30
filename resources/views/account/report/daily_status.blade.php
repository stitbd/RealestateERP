@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Daily Status
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('daily-status') }}" method="get">
                    <div class="row pb-3">
                        <div class="col-lg-2">
                            <label for="Project">Type</label>
                            <select name="type" class="form-control">
                                <option value="">Select One</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Check">Check</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="Project">Fund</label>
                            <select name="fund_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($fund_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" name="start_date"/>
                        </div>
                        <div class="col-lg-2">
                            <label for="start_date">End Date</label>
                            <input type="date" class="form-control" name="end_date"/>
                        </div>
                        
                        <div class="col-lg-2">
                            <label for="action">Action</label> <br/>
                            <button class="btn btn-success btn-block">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    </form>

                    
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('daily-status-print?type='.request()->get('type').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('daily-status-pdf?type='.request()->get('type').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>


                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Fund</th>
                                <th>Payment Type</th>
                                <th>Particulars</th>
                                <th>Details</th>
                                <th>Deposit</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_diposit = 0;
                                $total_payment = 0;
                            @endphp
                            @foreach ($fund_log as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ date('d/m/Y',strtotime($item->transection_date)) }}</td>
                                <td>{{ $item->fund->name }}</td>
                                <td>{{ $item->payment_type }}</td>
                                <td>{{ $item->transection_type }}</td>
                                <td>
                                    @if($item->transection_type == 'supplier_payment')
                                        {{$item->sapplier_payment->supplier->name}} <br/>
                                        ({{$item->sapplier_payment->project->name}})
                                    @elseif($item->transection_type == 'vendor_payment')
                                        {{$item->vendor_payment->vendor->name}} <br/>
                                        ({{$item->vendor_payment->project->name}})
                                    @elseif($item->transection_type == 'salary_payment')
                                        {{$item->salary_payment->employee->name}}
                                    @elseif($item->transection_type == 'requisition_payment')
                                        {{$item->requisition_payment->project->name}}
                                    @elseif($item->transection_type == 'deposit')
                                        {{$item->diposit->payment_type}}
                                    @elseif($item->transection_type == 'expense')
                                            {{$item->expense->particulars}}
                                    @endif
                                    
                                </td>
                                <td class="text-right">
                                    Tk. {{ ($item->type==1)?$item->amount:'-' }}
                                    @if($item->type==1)
                                    @php
                                        $total_diposit += $item->amount;
                                    @endphp
                                    @endif
                                </td>
                                <td class="text-right">
                                    Tk. {{ ($item->type==2)?$item->amount:'-' }}
                                    @if($item->type==2)
                                    @php
                                        $total_payment += $item->amount;
                                    @endphp
                                    @endif
                                </td>
                            </tr>
                            
                            
                            @endforeach
                            <tr>
                                <th colspan="6">Total</th>
                                <th class="text-right">Tk. {{$total_diposit}}</th>
                                <th class="text-right">Tk. {{$total_payment}}</th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$fund_log->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection