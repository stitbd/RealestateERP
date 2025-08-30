@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Consumer Investors List
                        </h3>
                        <a href="{{route('create_investment')}}"><button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </button></a>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('investment_list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-2">
                                    <label for="consumer_name">Consumer Investor</label>
                                    <input type="text" name="consumer_name" class="form-control">
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

                        {{-- <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ url('investment-print?consumer_name=' . request()->get('consumer_name') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                <a href="{{ url('investment-pdf?consumer_name=' . request()->get('consumer_name') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn  btn-danger float-end m-2">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                                </a>
                            </div>
                        </div> --}}

                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>Invest Code</th>
                                    <th>Consumer Investor</th>
                                    <th>Tentative Collection Date</th>
                                    <th>Monthly Invest Amount</th>
                                    <th>Investment Length</th>
                                    <th>Total Collected Amount</th>
                                    <th>Check Attachment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($investments) --}}
                                @foreach ($investments as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->invest_code }}</td>
                                        <td class="text-center">{{ $item->consumer_name }}</td>
                                        <td class="text-center">{{ date('d F Y', strtotime($item->tentative_collection_date)) ?? '' }}</td>
                                        <td class="text-center">{{ $item->invest_amount ?? '' }} Tk.</td>
                                        <td class="text-center">{{  date('d F Y', strtotime($item->invest_length)) ?? '' }}</td>
                                        <td class="text-center">{{ $item->total_collected_amount }}</td>
                                        <td class="text-center">
                                            @if ($item->attachment != null)
                                                <a href="{{ asset('attachment/' . $item->attachment) }}" target="_blank"
                                                    class="btn btn-info">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $item->id }}"><i
                                                class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>

                                            <a href="{{route('edit-investment', $item->id)}}"><i class="fa fa-edit pr-2 pl-2"
                                                style="color: rgb(28, 145,199)"></i></a>

                                            {{-- <a data-toggle="modal"
                                                    data-target=".invest-payment-modal-{{ $item->id }}"
                                                    style="padding:2px; color:white" class="btn btn-xs btn-primary  mr-1">
                                                    <i class="fa fa-credit-card"></i>
                                                </a> --}}

                                            @if ($item->status == 1)
                                                <a href="{{ route('updateConsumerStatus', $item->id) }}">
                                                    <i class="fas fa-arrow-up" style="color: green"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('updateConsumerStatus', $item->id) }}">
                                                    <i class="fas fa-arrow-down" style="color: red"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $investments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($investments as $model)
    {{-- @dd($model) --}}
    <div class="modal fade update view-modal-{{ $model->id }}" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-center">
                    <h5>View Consumer Investor Details</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <table class="table table-sm">
                            {{-- <thead>
                                <tr>
                                    <th colspan="6" style="text-align: center; color: green;">Applicant Details
                                    </th>
                                </tr>
                            </thead> --}}
                            <tbody>
                                <tr>
                                    <th width="20%">C.I Code: </th>
                                    <td>{{ $model->invest_code ?? '' }}</td>

                                    <th width="20%">Consumer Investor Name: </th>
                                    <td>{{ $model->consumer_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Investment Date: </th>
                                    <td>{{ $model->invest_date ?? '' }}</td>

                                    <th width="20%">Tentative Collection Date: </th>
                                    <td>{{ $model->tentative_collection_date ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Category: </th>
                                    <td>{{ $model->category->category_name ?? '' }}</td>

                                    <th width="20%">Account Head: </th>
                                    <td>{{ $model->head->head_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Address: </th>
                                    <td>{{ $model->address ?? '' }}</td>

                                </tr>
                                <tr>
                                    <th width="20%">Phone No.: </th>
                                    <td>{{ $model->phone ?? '' }}</td>

                                    <th width="20%">Email: </th>
                                    <td>{{ $model->email ?? '' }}</td>
                                </tr>
                                
                                <tr>
                                    <th width="20%">NID: </th>
                                    <td class="text-center">
                                        @if (!empty($model->nid))
                                            <img src="{{ asset('nid/' . $model->nid) }}"
                                                height="80px" width="100px" />
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">Attachment: </th>
                                    <td class="text-center">
                                        @if (!empty($model->attachment))
                                            <img src="{{ asset('attachment/' . $model->attachment) }}"
                                                height="80px" width="100px" />
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <th width="20%">Invest Amount: </th>
                                    <td>{{ $model->invest_amount ?? '' }}</td>

                                    <th width="20%">Invest Length: </th>
                                    <td>{{ $model->invest_length ?? '' }}</td>
                                </tr>

                                <tr>
                                    <th width="20%">Note: </th>
                                    <td>{{ $model->note ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
