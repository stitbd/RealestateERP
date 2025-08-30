@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Revoke List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <form action="{{ route('revoke_list') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="employee_id">Employee</label>
                                    <select name="employee_id" class="form-control">
                                        <option value="" selected>Select Employee</option>
                                        {{-- @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}
                                                ({{ $employee->department->name }}, {{ $employee->designation->name }})
                                            </option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>

                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ url('print_revoke_list?employee_id=' . request()->get('employee_id') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                {{-- <a href="{{url('project-received-pdf?project_id='.request()->get('project_id').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                        <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                                    </a> --}}
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th> Revoke Date </th>
                                    <th> Asset Code </th>
                                    <th> Asset Group </th>
                                    <th> Asset </th>
                                    <th> Asset Category </th>
                                    <th> Asset Type </th>
                                    <th> Employee </th>
                                    <th> Action </th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($revokes as $revoke)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $revoke->revoke_date ?? '' }}</td>
                                        <td class="text-center">{{ $revoke->asset->asset_code ?? '' }}</td>
                                        <td class="text-center">{{ $revoke->asset->asset_group->name ?? '' }}</td>
                                        <td class="text-center">{{ $revoke->asset->name ?? '' }}</td>
                                        <td class="text-center">
                                            {{ $revoke->asset->asset_group->asset_category->name ?? '' }}</td>
                                        <td class="text-center">{{ $revoke->asset->asset_type ?? '' }}</td>
                                        {{-- <td class="text-center"><b>{{ $revoke->employee->name ?? '' }}</b> --}}
                                            ({{ $revoke->employee->department->name ?? '' }},
                                            {{ $revoke->employee->designation->name ?? '' }})
                                        </td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $revoke->id }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $revokes->links() }}
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    @foreach ($revokes as $revoke)
        {{-- @dd($asset_d) --}}
        <div class="modal fade view-modal-{{ $revoke->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Revoke</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Revoke Date: </th>
                                    <td>{{ $revoke->revoke_date }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Employee: </th>
                                    {{-- <td><b>{{ $revoke->employee->name }}</b>
                                        ({{ $revoke->employee->department->name }},
                                        {{ $revoke->employee->designation->name }})
                                    </td> --}}
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Asset Code: </th>
                                    <td>{{ $revoke->asset->asset_code ?? '' }}</td>

                                    <th width="20%">Asset Group: </th>
                                    <td>{{ $revoke->asset->asset_group->name ?? '' }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Asset: </th>
                                    <td>{{ $revoke->asset->name ?? '' }}</td>
                                </tr>
                            </table>

                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Asset Category: </th>
                                    <td>{{ $revoke->asset->asset_group->asset_category->name ?? '' }}</td>

                                    <th width="20%">Asset Type: </th>
                                    <td>{{ $revoke->asset->asset_type ?? '' }}</td>
                                </tr>

                            </table>
                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Reason: </th>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ $revoke->reason }}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    <!-- /.modal -->
@endsection
