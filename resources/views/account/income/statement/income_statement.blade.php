@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Income Statement
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="head_id">Account Head</label>
                                    <select name="head_id" id="head_id" class="chosen-select form-control">
                                        <option value="" selected>Select Head</option>
                                        @foreach ($ac_heads as $ac_head)
                                            <option value="{{ $ac_head->id }}">{{ $ac_head->head_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date"  id="start_date"/>
                                </div>
                                <div class="col-lg-2">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" />
                                </div>

                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block" onclick="incomeStatementLedger();">
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

        function incomeStatementLedger() {
            $('#main-table').hide();
            $('.btn-warning').hide();
            // var fund_id = document.getElementById('fund_id').value;
            var head_id = document.getElementById('head_id').value;
            var start_date = document.getElementById('start_date').value;
            var end_date = document.getElementById('end_date').value;
            var url = "{{ route('income-statement-list-view') }}"

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    start_date,
                    end_date,
                    head_id,
                },
                success: function(data) {
                    $('#wrapper').html(data);
                }
            });
        }
    </script>
@endpush
