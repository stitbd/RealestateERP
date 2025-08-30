@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Project Ledger List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    {{-- <form action="{{ route('ledger-list') }}" method="get"> --}}
                    <div class="row pb-3">
                        <div class="col-lg-3">
                            <label for="start_date">Select Project</label>
                            <select name="project_id" id="project_id" class="form-control" onchange="filterSubProject()">
                                <option value="">Select One</option>
                                @foreach($projects as $project)
                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!--<div class="col-lg-3">-->
                        <!--    <label for="start_date">Select Sub Project</label>-->
                        <!--    <select name="sub_project_id" id="sub_project_id" class="form-control">-->
                        <!--        <option value="">Select One</option>-->
                        <!--        @foreach($sub_projects as $sub_project)-->
                        <!--            <option value="{{$sub_project->id}}">{{$sub_project->name}}</option>-->
                        <!--        @endforeach-->
                        <!--    </select>-->
                        <!--</div>-->
                        <div class="col-lg-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date"/>
                        </div>
                        <div class="col-lg-3">
                            <label for="end_date">End Date</label>
                            <input type="date" class="form-control" name="end_date" id="end_date"/>
                        </div>
                        
                        <div class="col-lg-3 pt-3">
                            <button class="btn btn-success btn-block" onclick="viewProjectLedger();">
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
                    {{-- <tbody>
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
                    </tbody> --}}
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
     function viewProjectLedger(){
        $('#main-table').hide();
        $('.btn-warning').hide();
        var project_id        = document.getElementById('project_id').value;
        // var sub_project_id    = document.getElementById('sub_project_id').value;
        var start_date        = document.getElementById('start_date').value;
        var end_date          = document.getElementById('end_date').value;

        var url = "{{route('project-ledger-list')}}"

        $.ajax({
            type:'GET',
            url:url,
            data:{start_date,end_date,project_id},
            success:function(data){
                $('#wrapper').html(data);
            }
        });
    }


    function filterSubProject(){
        var project_id = document.getElementById('project_id').value;
            var url = "{{route('filter-subproject')}}";
            $.ajax({
                type:"GET",
                url:url,
                data:{project_id},
                success:function(data){
                    console.log(data);
                    $('#sub_project_id').find('option').remove();
                    $('#sub_project_id').html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#sub_project_id').append('<option value="' + value.id + '">' + value.name +
                            '</option>');
                    });
                    // $('#sub_project_id').trigger("chosen:updated");
                },
        });
    }
</script>