@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Receivable Invest List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('receivable-invest-list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-2">
                                    <label for="invest">Client / Company</label>
                                    <select name="invest_id" class="form-control">
                                        <option value="">Select client/company </option>
                                        @foreach ($invests as $invest)
                                            <option value="{{ $invest->id }}">{{ $invest->client_name }} ({{ $invest->invest_code }})</option>
                                        @endforeach
                                    </select>
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
                                <a href="{{ url('receivable-investment-print?invest_id=' . request()->get('invest_id')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                {{-- <a href="{{ url('invest-pdf?fund_id=' . request()->get('fund_id')  . '&bank_id=' . request()->get('bank_id')) }}"
                                    target="_blank" class="btn  btn-danger float-end m-2">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                                </a> --}}
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Client / Company</th>
                                    <th class="text-center">Invest Date</th>
                                    <th class="text-center">Invest Amount</th>
                                    <th class="text-center">Due Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($receivable_invests) --}}
                                @foreach ($receivable_invests as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->invest->client_name ?? '' }} ({{ $item->invest->invest_code }})</td>
                                        <td class="text-center">{{ date('d/m/Y',strtotime($item->invest->invest_date)) }}</td>
                                        <td class="text-center">{{ $item->invest->amount ?? '' }}</td>
                                        <td class="text-center">{{ $item->current_amount }} Tk.</td>
                                        <td class="text-center">
                                            <a class="btn btn-info" href="{{url('receivable-invest-log/'.$item->invest_id.'/')}}">View Log</a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $receivable_invests->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
