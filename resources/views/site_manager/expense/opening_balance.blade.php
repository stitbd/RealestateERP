@extends('layouts.app')

@section('content')
    <div class="row p-3">
        <div class="col-md-6 mt-4">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h4 class="text-center">
                        Add Opening Balance
                    </h4>
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="{{route('site-opening-balance-store')}}" method="POST">
                        @csrf
                    <div class="row p-4">
                        <div class="col-md-12">
                            <label for="project">Balance Amount</label>
                            <input type="number" name="amount" id="" class=" form-control" placeholder="Enter Amount" required>
                        </div>
                        <div class="col-md-12">
                            <label for="project">Date</label>
                            <input type="date" name="date" id="" class=" form-control" placeholder="" required>
                        </div>
                        <div class="col-lg-6 pt-3">
                            <button class="btn btn-success btn-block" id="submit" @if(count($opening_balance) == 1) disabled @endif ><i class="fa fa-check"></i> Save</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h4 class="text-center">
                        Opening Balance
                    </h4>
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-info text-center">
                                <th>Sl No.</th>
                                <th>Date</th>
                                <th>Opening Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($opening_balance as $v_balance)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{$v_balance->date}}</td>
                                <td>{{ $v_balance->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                      </table>
                      <div class="row pt-3">
                        <div class="col-lg-12">
                            {{-- {{$income->links();}} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_js')


@endpush