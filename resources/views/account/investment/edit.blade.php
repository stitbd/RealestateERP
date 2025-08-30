@extends('layouts.app')
<style type="text/css">
    fieldset {
        min-width: 0px;
        padding: 15px;
        margin: 7px;
        border: 2px solid #000000;
    }

    legend {
        float: none;
        background-image: linear-gradient(to bottom right, #0e0e0e, #000000);
        padding: 4px;
        width: 50%;
        color: rgb(255, 255, 255);
        border-radius: 7px;
        font-size: 17px;
        font-weight: 700;
        text-align: center;
    }

    label {
        font-weight: 700;
    }

    .line {
        border: 1px solid #000000;
    }


    /* date css End  */
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Consumer Investor Edit
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('update-investment',$consumer->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend style="color: hsl(0, 0%, 100%);"> Investment Information </legend>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="date">Investment Start Date<i class="text-danger">*</i></label>
                                                <input type="date" name="invest_date" required value="{{ $consumer->invest_date }}" class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="date">Tentative Collection Date<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" name="tentative_collection_date" required value="{{ $consumer->tentative_collection_date }}"
                                                    class="form-control">
                                            </div>

                                            <div class="col-lg-12">
                                                <label for="category">Account Main Head<i class="text-danger">*</i></label>
                                                <select class="form-control chosen-select" name="category" id="category"  onchange="filterHead(this); generateInvestCode();" required>
                                                    <option value="">Select Category..</option>
                                                    @foreach ($categories as $category)
                                                       @php $incomes = json_decode($category->category_type)  @endphp
                                                       @if($incomes && in_array('Income',$incomes))
                                                            <option value="{{$category->id}}" @if ($category->id == $consumer->category_id) selected @endif>{{$category->category_name}}</option>
                                                       @endif
                                                    @endforeach
                                                     <option value=""></option> 
                                                </select>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="head">Account Sub Head<i class="text-danger">*</i></label>
                                                <select class=" chosen-select form-control head" name="head" required
                                                    id="head">
                                                    <option value="">Choose Sub Head...</option>
                                                    {{-- <option value="new_head">New Sub Head</option> --}}
                                                    @foreach ($head as $v_head)
                                                        <option value="{{ $v_head->id }}" @if ($v_head->id == $consumer->head_id) selected @endif>{{ $v_head->head_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="invest_code">Invest Code</label>
                                                <input type="text" class="form-control" name="invest_code"
                                                    id="invest_code" readonly placeholder=" Invest Code No." value="{{ $consumer->invest_code }}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="invest_amount">Monthly Invest Amount<i class="text-danger">*</i></label>
                                                <input type="number" required name="invest_amount" class="form-control" value="{{ $consumer->invest_amount }}"/>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="attachment">Check Attachment</label>
                                                <br />
                                                <input type="file" name="attachment" />
                                                <div id="preview_file" style="margin-top: 10px;">
                                                    @if ($consumer->attachment != null)
                                                        @php
                                                            $extension = pathinfo($consumer->attachment, PATHINFO_EXTENSION);
                                                        @endphp
                                                        @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                                            <img src="{{ asset('attachment/' . $consumer->attachment) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="User">
                                                        @elseif (strtolower($extension) === 'pdf')
                                                            <iframe src="{{ asset('attachment/' . $consumer->attachment) }}" style="width:100%; height:100px;" frameborder="0"></iframe>
                                                            <!-- Optionally, add a download link -->
                                                            <a href="{{ asset('attachment/' . $consumer->attachment) }}" target="_blank">View PDF</a>
                                                        @else
                                                            <p>Unsupported file type</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label for="invest_length">Investment Length<i class="text-danger">*</i></label>
                                                <input type="date" name="invest_length" class="form-control" required value="{{ $consumer->invest_length }}">
                                            </div>
                                            <div class="col-lg-12 mb-3">
                                                <label for="note">Note (If Any)</label>
                                                <textarea type="text" name="note" cols="2" class="form-control" placeholder="Note...">{{ $consumer->note }}</textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-md-6">
                                    <fieldset>
                                        <legend style="color: hsl(0, 0%, 100%);"> Investor's Information </legend>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="consumer_name">Name<i class="text-danger">*</i></label>
                                                <input type="text" name="consumer_name" required class="form-control" value="{{ $consumer->consumer_name }}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="phone">Mobile no.<i class="text-danger">*</i></label>
                                                <input type="number" name="phone" class="form-control" required value="{{ $consumer->phone }}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" class="form-control" placeholder="email" value="{{ $consumer->email }}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="nid">NID<i class="text-danger">*</i></label> <br />
                                                <input type="file" name="nid" />
                                                <div id="preview_file" style="margin-top: 10px;">
                                                    @if ($consumer->nid != null)
                                                        @php
                                                            $extension = pathinfo($consumer->nid, PATHINFO_EXTENSION);
                                                        @endphp
                                                        @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                                            <img src="{{ asset('nid/' . $consumer->nid) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="User">
                                                        @elseif (strtolower($extension) === 'pdf')
                                                            <iframe src="{{ asset('nid/' . $consumer->nid) }}" style="width:100%; height:100px;" frameborder="0"></iframe>
                                                            <!-- Optionally, add a download link -->
                                                            <a href="{{ asset('nid/' . $consumer->nid) }}" target="_blank">View PDF</a>
                                                        @else
                                                            <p>Unsupported file type</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-3">
                                                <label for="address">Address<i class="text-danger">*</i></label>
                                                <textarea name="address" class="form-control" required placeholder="Address..">{{ $consumer->address }}</textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="col-lg-12 btn btn-success text-center"><i
                                                class="fa fa-check"></i>
                                            Save</button>
                                    </fieldset>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-lg-12 text-center">
                                    
                                </div>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(".chosen-select").chosen();
        $(document).ready(function() {
            $('.bank').hide();
            $('.headAdd').hide();
        });

        function showBankInfo() {
            var fund_id = document.getElementById('fund').value;
            console.log(fund_id);
            if (fund_id == 1) {
                $('.bank').show();
                $('.bank_info').prop('required', true);
            } else {
                $('.bank').hide();
                $('.bank_info').hide();
            }

        }

        function filterAccount() {
            var bank_id = document.getElementById('bank_id').value;
            var url = "{{ route('filter-bank-fund') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id
                },
                success: function(data) {
                    $('#account_id').html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#account_id').append('<option value="' + value.id + '">' + value.account_no +
                            '</option>');
                    });
                },
            });
        }

        var j = 1;

        function filterHead() {
            var category_id = document.getElementById('category').value;
            if (category_id == 'new_category') {
                console.log(category_id);
                $('#head').empty().append(
                        '<option value="" disabled selected>Choose Head</option><option value="new_head">New Head</option>')
                    .trigger("chosen:updated");
            } else {
                var url = "{{ route('filter-head') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        category_id
                    },
                    success: function(data) {
                        $('#head').find('option').remove();
                        $('#head').html('');
                        $('#head').append(
                            `<option value="" disabled selected>Choose Head</option> <option value="new_head">New Head</option>`
                        );
                        $.each(data, function(key, value) {
                            $('#head').append(`
                        <option value="` + value.id + `">` + value.head_name +
                                `</option>`);
                        });
                        $('#head').trigger("chosen:updated");
                    },
                });
                $(".chosen-select").chosen();
            }

        }

        function generateInvestCode() {
            var category_id = document.getElementById('category').value;
            console.log(category_id);
            if (category_id) {
                var lastInvestId = {{ $lastInvestId ?? 0 }};
                var nextInvestId = lastInvestId + 1;
                var investCode = 'CI-' + nextInvestId;

                document.getElementById('invest_code').value = investCode;
            } else {
                document.getElementById('invest_code').value = '';
            }
        }

        function newCategoryAdd() {
            var category = document.getElementById('category').value;
            if (category == 'new_category') {
                ++j
                $('.categoryAdd').append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="category_name" class="form-control" id="" placeholder="Enter Category Name"/>
                                        <input type="hidden" name="category_type[]" class="form-control" id="" value="Invest"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

                $('.categoryAdd').on('click', '.remove', function() {
                    $(this).parent().remove();
                });
            } else {
                $('.categoryAdd').hide();
            }

        }

        function newHeadAdd() {
            var head = document.getElementById('head').value;
            if (head == 'new_head') {
                $('.headAdd').empty().show().append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="head_name" class="form-control" id="" placeholder="Enter Head Name"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm  " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

                $('.headAdd').on('click', '.remove', function() {
                    $(this).parent().remove();
                });
            } else {
                $('.headAdd').hide();
            }

        }
    </script>
@endpush
