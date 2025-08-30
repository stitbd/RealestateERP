@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 mt-4">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h4 class="text-center">
                        Add Head Wise Opening/Previous Balance
                    </h4>
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="{{ route('account-opening-balance-store') }}" method="POST">
                        @csrf
                        <div class="row p-4">

                            <div class="col-md-12">
                                <label for="category_id">Category</label>
                                <select name="category" id="category-1" class="form-control category chosen-select"
                                    onchange="filterHead(this); newCategoryAdd(this);" required>
                                    <option value="">Select Category..</option>
                                    <option value="new_category">New Category</option>
                                    @foreach ($categories as $category)
                                        @php $incomes = json_decode($category->category_type)  @endphp
                                        @if ($incomes && in_array('Income', $incomes))
                                            <option value="{{ $category->id }}">{{ $category->category_name }}
                                            </option>
                                        @endif
                                    @endforeach
                                    <option value=""></option>
                                </select>
                                <div id="categoryAdd-1">

                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="head_id">Head</label>
                                <select name="head" id="head-1" class="form-control chosen-select" required
                                    onchange="newHeadAdd(this);">
                                    <option value="">Select Head...</option>
                                    <option value="new_head">New Head</option>
                                    @foreach ($head as $v_head)
                                        <option value="{{ $v_head->id }}">{{ $v_head->head_name }}</option>
                                    @endforeach
                                </select>
                                <div class="col-lg-12" id="headAdd-1">

                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="amount">Opening/Previous Balance</label>
                                <input type="number" name="amount" id="" class=" form-control"
                                    placeholder="Enter Amount" required>
                            </div>
                            <div class="col-md-12">
                                <label for="date">Date</label>
                                <input type="date" name="date" id="" class=" form-control" placeholder=""
                                    required>
                            </div>
                            <div class="col-md-12">
                                <label for="remarks">Remarks</label>
                                <input type="text" name="remarks" id="" class=" form-control"
                                    placeholder="Remarks" required>
                            </div>
                            <div class="col-lg-6 pt-3">
                                <button class="btn btn-success btn-block" id="submit"><i class="fa fa-check"></i>
                                    Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12 mt-4 p-5">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h4 class="text-center">
                        Head Wise Opening/Previous Balance List
                    </h4>
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-info text-center">
                                <th>Sl No.</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Head</th>
                                <th>Opening Balance</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($balance as $b)
                                <tr>
                                    <td class="text-center">{{ ++$i }}</td>
                                    <td class="text-center">{{ $b->date }}</td>
                                    <td class="text-center">{{ $b->category->category_name ?? '' }}</td>
                                    <td class="text-center">{{ $b->head->head_name ?? '' }}</td>
                                    <td class="text-center">{{ $b->amount }}</td>
                                    <td class="text-center">{{ $b->remarks }}</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#exampleModal-{{ $b->id }}"
                                            style="padding:2px; color:white" class="btn btn-xs btn-info mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{ $balance->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($balance as $b)
        <div class="modal fade" id="exampleModal-{{ $b->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Head Wise Opening/Previous Balance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('account-opening-balance-update', $b->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row p-4">
                                <div class="col-md-12">
                                    <label for="category_id">Category</label>
                                    <select name="category" id="category_edit-{{ $b->id }}"
                                        class="form-control chosen-select" onchange="filterHeadEdit(this);" required>
                                        <option value="">Select Category..</option>
                                        @foreach ($categories as $category)
                                            @php $incomes = json_decode($category->category_type)  @endphp
                                            @if ($incomes && in_array('Income', $incomes))
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id == $b->category_id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="head_id">Head</label>
                                    <select name="head" id="head_edit-{{ $b->id }}"
                                        class="form-control chosen-select" required>
                                        <option value="">Select Head...</option>
                                        @foreach ($head as $v_head)
                                            <option value="{{ $v_head->id }}"
                                                {{ $v_head->id == $b->head_id ? 'selected' : '' }}>
                                                {{ $v_head->head_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="amount">Opening/Previous Balance</label>
                                    <input type="number" name="amount" class="form-control"
                                        value="{{ $b->amount }}" placeholder="Enter Amount" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" class="form-control"
                                        value="{{ $b->date }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" name="remarks" class="form-control"
                                        value="{{ $b->remarks }}" placeholder="Remarks" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(".chosen-select").chosen({
            width: "100%"
        });

        function filterHead(e) {
            var text = e.id;
            var id = text.replace('category-', '');
            // console.log(id);

            var category_id = document.getElementById('category-' + id).value;
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
                        $('#head-' + id).find('option').remove();
                        $('#head-' + id).html('');
                        $('#head-' + id).append('<option value="">Choose Head</option>');
                        $('#head-' + id).append('<option value="new_head">New Head</option>');
                        $.each(data, function(key, value) {
                            $('#head-' + id).append('<option value="' + value.id + '">' + value
                                .head_name +
                                '</option>');
                        });

                        $('#head-' + id).trigger("chosen:updated");
                    },
                });
            }
        }

        function filterHeadEdit(e) {
            var text = e.id;
            var id = text.replace('category_edit-', '');
            var category_id = document.getElementById('category_edit-' + id).value;
            // console.log(category_id);

            var url = "{{ route('filter-head-data') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    category_id: category_id
                },
                success: function(data) {
                    var $headDropdown = $('#head_edit-' + id);
                    $headDropdown.empty()
                        .append('<option value="">Choose Head</option>')

                    $.each(data, function(key, value) {
                        $headDropdown.append('<option value="' + value.id + '">' + value.head_name +
                            '</option>');
                    });

                    $headDropdown.trigger('chosen:updated');
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred:", status, error);
                }
            });
        }

        function newCategoryAdd(e) {
            var text = e.id;
            var id = text.replace('category-', '');
            var category = document.getElementById('category-' + id).value;
            if (category == 'new_category') {
                $('#categoryAdd-' + id).append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="category_name" class="form-control" id="" placeholder="Enter Category Name"/>
                                        <input type="hidden" name="category_type[][]" class="form-control" id="" value="Income"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

                $('#categoryAdd-' + id).on('click', '.remove', function() {
                    $(this).parent().remove();
                });
            } else {
                $('#categoryAdd-' + id).hide();
            }

        }

        function newHeadAdd(e) {
            var text = e.id;
            var id = text.replace('head-', '');
            var head = document.getElementById('head-' + id).value;
            if (head == 'new_head') {
                $('#headAdd-' + id).empty().show().append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="head_name" class="form-control" id="" placeholder="Enter Head Name"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm  " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

                $('#headAdd-' + id).on('click', '.remove', function() {
                    $(this).parent().remove();
                });
            } else {
                $('#headAdd-' + id).hide();
            }

        }
    </script>
@endpush
