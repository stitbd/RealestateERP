@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Headwise Ledger
                        </h3>
                    </div> <!-- /.card-body -->

                    <div class="card-body p-3">
                        {{-- <form action="{{ route('ledger-list') }}" method="get"> --}}
                        <div class="row pb-3">
                            <div class="col-lg-3">
                                <label for="head">Main Head</label>
                                <select name="category_id" id="category-id" class="form-control chosen-select"
                                    onchange="filterHead();">
                                    <option value="">Select Head</option>
                                    @foreach ($heads as $data)
                                        <option value="{{ $data->id }}">{{ $data->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="head">Sub Head</label>
                                <select name="head_id" id="head-id" class="form-control chosen-select">
                                    <option value="">Select Head</option>
                                    @foreach ($sub_heads as $v_data)
                                        <option value="{{ $v_data->id }}">{{ $v_data->head_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" name="start_date" id="start_date"
                                    value="@php echo date('Y-m-d') @endphp" />
                            </div>
                            <div class="col-lg-3">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" name="end_date" id="end_date"
                                    value="@php echo date('Y-m-d') @endphp" />
                            </div>

                            <div class="col-lg-3">
                                <label for="action">Action</label> <br />
                                <button class="btn btn-success btn-block" onclick="viewLedger();">
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
@endsection

@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(".chosen-select").chosen();
    </script>

    <script>
        function viewLedger() {
            $('#main-table').hide();
            $('.btn-warning').hide();
            var head_id = document.getElementById('head-id').value;
            var start_date = document.getElementById('start_date').value;
            var end_date = document.getElementById('end_date').value;
            var url = "{{ route('head-wise-ledger-list') }}"

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    start_date,
                    end_date,
                    head_id
                },
                success: function(data) {
                    $('#wrapper').html(data);
                }
            });
        }

        function filterHead() {
            // var text = e.id;
            // var id = text.replace('category-', '');
            var category_id = document.getElementById('category-id').value;
            if (category_id == 'new_category') {
                console.log(category_id);
                $('#head-' + id).empty().append(
                    '<option value="" selected>Choose Head</option><option value="new_head">New Head</option>').trigger(
                    "chosen:updated");
            } else {
                var url = "{{ route('filter-head-data') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        category_id
                    },
                    success: function(data) {
                        $('#head-id').find('option').remove();
                        $('#head-id').html('');
                        $('#head-id').append('<option value="">Choose Head</option>');
                        $.each(data, function(key, value) {
                            $('#head-id').append('<option value="' + value.id + '">' + value
                                .head_name +
                                '</option>');
                        });

                        $('#head-id').trigger("chosen:updated");
                    },
                });
            }
        }
    </script>
@endpush
