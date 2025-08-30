@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Installment Statement
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="customer_name">Customer</label>
                                    <select name="customer_name" id="customer_id" class="chosen-select form-control">
                                        <option value="" selected>Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}-{{$customer->customer_code}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="col-lg-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date"  id="start_date"/>
                                </div>
                                <div class="col-lg-2">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" />
                                </div> --}}

                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block"  onclick="statementLedger();">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                    </div>

                </div>
            </div>
        </div>

    <div id="wrapper">

    </div>
    </div>

@endsection
@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen();
        });

        function statementLedger(){
            $('.btn-warning').hide();
            var customer_id        = document.getElementById('customer_id').value;
            // var start_date        = document.getElementById('start_date').value;
            // var end_date          = document.getElementById('end_date').value;

            var url = "{{route('customer-statement-list')}}"

            $.ajax({
                type:'GET',
                url:url,
                data:{customer_id},
                success:function(data){
                    $('#wrapper').html(data);
                }
            });
        }
    </script>
@endpush
