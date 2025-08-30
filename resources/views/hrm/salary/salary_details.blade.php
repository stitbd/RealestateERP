<div class="row">
    <div class="col-sm-12">
        <form action="{{route('save-employee-salary')}}" method="post">
            @csrf
            <hr/>
            <h4 class="m-t-0 text-center"><b>{{$company_data->name}}</b></h4>
            <h6 class="text-center"><b>Department: {{($department_data != null)?$department_data->name:'All'}}</b></h6>
            <h6 class="text-center">Salary Month: {{date('M,Y',strtotime($month.'-01'))}}</h6>
            <h6 class="text-center">Date: {{date('d/m/Y',strtotime($start_date))}} To {{date('d/m/Y',strtotime($end_date))}}</h6>
            <button class="btn btn-success float-end"><i class="fas fa-file-invoice-dollar"></i> Generate Salary</button>
            <hr/>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-info">
                        <th>#</th>
                        <th>Employee</th>
                        <th>Gross Salary</th>
                        <th>Allowance</th>
                        <th>Deduction</th>
                        <th>Total Salary</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total=0;   
                    @endphp
                    <input type="hidden" name="month" value="{{$month}}">
                    <input type="hidden" name="department_id" value="{{$department_id}}">
                    <input type="hidden" name="start_date" value="{{$start_date}}">
                    <input type="hidden" name="end_date" value="{{$end_date}}">

                    @foreach ($employee_data as $item)
                        <input type="hidden" name="employee_id[]" value="{{$item->id}}">
                        <input type="hidden" name="gross_salary[]" value="{{$item->gross_salary}}" id="gross_salary_{{$item->id}}">
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->gross_salary}}</td>
                            <td>
                                <input type="text" value="0" onblur="salary_calculate('{{$item->id}}')"  name="addition[]" class="form-control" id="addition_{{$item->id}}">
                            </td>
                            <td>
                                <input type="text" onchange="salary_calculate('{{$item->id}}')" value="0" name="deduction[]" class="form-control" id="deduction_{{$item->id}}">
                            </td>
                            <td>
                                <input type="text" value="{{$item->gross_salary}}" readonly name="total_salary[]" class="form-control total_salary" id="total_salary_{{$item->id}}">
                            </td>
                            <td>
                                <input type="text" name="remarks[]" class="form-control">
                            </td>
                        </tr>
                        @php
                            $total+=$item->gross_salary;   
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr  class="text-center bg-success">
                        <th colspan="5">Total Salary</th>
                        <th id="total_salary">{{$total}}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>


