@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Unit List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('unit_list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-2">
                                    <label for="property_id">Property</label>
                                    <select class="form-control" name="property_id">
                                        <option value="">Select property</option>
                                        @foreach ($properties as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="floor_id">Floor</label>
                                    <select class="form-control" name="floor_id">
                                        <option value="">Select floor</option>
                                        @foreach ($floors as $item)
                                            <option value="{{ $item->id }}">{{ $item->floor_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">From</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-2">
                                    <label for="end_date">To</label>
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
                            {{-- <div class="col-12 text-right">
                                <a href="{{ url('loan-print?loanee_name=' . request()->get('loanee_name') . '&company_id=' . request()->get('company_id') . '&fund_id=' . request()->get('fund_id') . '&start_date=' . request()->get('start_date') . '&bank_id=' . request()->get('bank_id') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                <a href="{{ url('loan-pdf?loanee_name=' . request()->get('loanee_name') . '&company_id=' . request()->get('company_id') . '&fund_id=' . request()->get('fund_id') . '&start_date=' . request()->get('start_date') . '&bank_id=' . request()->get('bank_id') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn  btn-danger float-end m-2">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                                </a>
                            </div> --}}
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Unit Name</th>
                                    <th class="text-center">Rent</th>
                                    <th class="text-center">Property</th>
                                    <th class="text-center">Floor</th>
                                    <th class="text-center">Renter</th>
                                    <th class="text-center">Current Status</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($unit_data) --}}
                                @foreach ($unit_data as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->unit_name }}</td>
                                        <td class="text-center">{{ $item->rent_amount ?? '' }} Tk.</td>
                                        <td class="text-center">{{ $item->property->name ?? '' }}</td>
                                        <td class="text-center">{{ $item->floor->floor_name ?? '' }}</td>
                                        <td class="text-center">
                                            {{ $item->renter && $item->current_status !== 1 ? $item->renter->name : '-' }}
                                        </td>

                                        <td class="text-center">
                                            @if ($item->current_status == 1)
                                                <span
                                                    style="background-color: #28c76f; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Available</span>
                                            @elseif ($item->current_status == 2)
                                                <span
                                                    style="background-color: #ea5455; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Rented</span>
                                            @else
                                                <span
                                                    style="background-color: #106d59; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Under
                                                    Maintenance</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == 1)
                                                <span
                                                    style="background-color: #28c76f; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Active</span>
                                            @else
                                                <span
                                                    style="background-color: #ea5455; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Inactive</span>
                                            @endif
                                        </td>

                                        {{-- <td>
                                            <a data-toggle="modal" data-target="#view-modal-{{ $item['id'] }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a href="{{ route('print-loan-debit-voucher', $item->id) }}" target="_blank"><i
                                                    class="fa fa-print" style="color: rgb(28, 145, 199)"></i></a>
                                        </td> --}}
                                        <td class="text-center">
                                            {{-- @if ($item->created_at->format('Y-m-d') == date('Y-m-d')) --}}
                                            <a data-toggle="modal" data-target=".view-modal-{{ $item->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-success  mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('edit_unit', $item->id) }}" style="padding:2px; color:white"
                                                class="btn btn-xs btn-info  mr-1"><i class="fa fa-edit"></i></a>

                                            @if ($item->status == 1)
                                                <a href="{{ route('unit_status', $item->id) }}" style="padding: 2px;"
                                                    class="delete btn btn-xs btn-danger  mr-1">
                                                    <i class="fa fa-arrow-down"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('unit_status', $item->id) }}" style="padding: 2px;"
                                                    class="delete btn btn-xs btn-success  mr-1">
                                                    <i class="fa fa-arrow-up"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $unit_data->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- View modal start --}}
    @foreach ($unit_data as $item)
        <div class="modal fade view-modal-{{ $item->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Unit Details</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Property: </th>
                                    <td>{{ $item->property->name ?? '' }}
                                        ({{ $item->floor->floor_name ?? '' }})
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Unit Name: </th>
                                    <td>{{ $item->unit_name }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Size: </th>
                                    <td>
                                        {{ $item->size }} sqft</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Rent Amount: </th>
                                    <td>
                                        {{ $item->rent_amount }} Tk.</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Meter No.: </th>
                                    <td>
                                        {{ $item->meter->meter_number ?? '' }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Rate Per Unit: </th>
                                    <td>
                                        {{ $item->e_rate_per_unit }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Renter: </th>
                                    <td>
                                        {{ $item->renter->name ?? '' }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Gas Bill: </th>
                                    <td>
                                        {{ $item->gas_bill }} Tk.</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Water Bill: </th>
                                    <td>
                                        {{ $item->water_bill }} Tk.</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Trash Bill: </th>
                                    <td>
                                        {{ $item->trash_bill }} Tk.</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Security Bill: </th>
                                    <td>
                                        {{ $item->security_bill }} Tk.</td>
                                </tr>
                            </table>

                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Note: </th>
                                    <td>
                                        {!! $item->note !!}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    {{-- View modal end --}}
@endsection
