@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Receivable Loan List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('receivable-loan-list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-2">
                                    <label for="loan_id">Employee</label>
                                    <select name="loan_id" class="form-control">
                                        <option value="">Select employee </option>
                                        @foreach ($loans as $loan)
                                            <option value="{{ $loan->id }}">{{ $loan->loanee_name }}</option>
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
                                <a href="{{ url('receivable-loan-print?loan_id=' . request()->get('loan_id')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                {{-- <a href="{{ url('loan-pdf?fund_id=' . request()->get('fund_id')  . '&bank_id=' . request()->get('bank_id')) }}"
                                    target="_blank" class="btn  btn-danger float-end m-2">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                                </a> --}}
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Employee</th>
                                    <th class="text-center">Loan Date</th>
                                    <th class="text-center">Loan Amount</th>
                                    <th class="text-center">Receivable Amount</th>
                                    <th class="text-center">Due Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($receivable_loans) --}}
                                @foreach ($receivable_loans as $item)

                                @php $collected_amount = $item->loancollection->sum('collect_amount');  @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->loan->loanee_name ?? '' }} ({{ $item->loan->designation ?? '' }})</td>
                                        <td class="text-center">{{ date('d/m/Y',strtotime($item->loan->loan_date)) }}</td>
                                        <td class="text-center">{{ $item->loan->amount }} Tk.</td>
                                        <td class="text-center">{{ $collected_amount }} Tk.</td>
                                        <td class="text-center">{{ $item->current_amount }} Tk.</td>
                                        <td class="text-center">
                                            <a class="btn btn-info" href="{{url('receivable-loan-log/'.$item->loan_id.'/')}}">View Log</a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $receivable_loans->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
