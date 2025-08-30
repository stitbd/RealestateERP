@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Employee Salary List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="{{route('employee-salary-list')}}" method="get">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Month</label>
                                    <input type="month" value="{{date('Y-m')}}" class="form-control" name="month">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Action</label> <br/>
                                    <button class="btn btn-info"><i></i>Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                  <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info text-center">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Month</th>
                            <th>Date</th>
                            <th>Gross Salary</th>
                            <th>Allowance</th>
                            <th>Deduction</th>
                            <th>Total Salary</th>
                            <th>Remarks</th>
                            <th>Paid Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salary_data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->employee->name }}</td> 
                            <td>{{ $item->month }}</td> 
                            <td>{{ date('d/m/Y',strtotime($item->start_date)).' to '.date('d/m/Y',strtotime($item->end_date)) }}</td>                       
                            <td>{{ $item->gross_salary }}</td> 
                            <td>{{ $item->addition }}</td>
                            <td>{{ $item->deduction }}</td>
                            <td>{{ $item->total_salary }}</td>
                            <td>{{ $item->remakrs }}</td>
                            <td>{!! ($item->paid_status=='1')?'<span class="btn btn-sm btn-outline-success btn-block btn-flat">Paid</span>':'<span class="btn btn-sm btn-outline-danger btn-block btn-flat">Unpaid</span>'!!}</td>
                            <td>
                                @if ($item->paid_status=='0')
                                    <a href="{{route('employee-salary-paid',$item->id)}}" class="btn btn-sm btn-outline-success btn-block btn-flat">Paid</a>
                                @endif
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
@endsection