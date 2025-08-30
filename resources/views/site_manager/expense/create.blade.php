@extends('layouts.app')
<style>
    .form-control {
        border: 0.8px solid rgb(180, 179, 179) !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Expense Entry
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('site-expense-store') }}" method="post" enctype="multipart/form-data"
                            id="myForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 ">
                                    <div class="row border m-2 p-2" style="border-color: green !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                            Expense Required Information
                                        </h6>
                                        <div class="col-lg-12">
                                            <label for="project">Project<i
                                                class="text-danger pt-1" style="font-size:16px">*</i> </label>
                                            <select class="form-control chosen-select" name="project" id="project">
                                                @foreach ($project as $v_project)
                                                    @if($v_project->id == auth()->user()->project_id)
                                                        <option value="{{ $v_project->id }}">{{ $v_project->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                       
                                        <div class="col-lg-6">
                                            <label for="category">Account Head<i
                                                class="text-danger pt-1" style="font-size:16px">*</i> </label>
                                            <select class="form-control chosen-select" name="category" id="category"
                                                onchange="filterHead(this); generateExpenseCode()">
                                                <option value="0">Choose Category..</option>
                                                @foreach ($categories as $category)
                                                    @php $expenses = json_decode($category->category_type)  @endphp
                                                    @if ($expenses && in_array('Expense', $expenses))
                                                        <option value="{{ $category->id }}">{{ $category->category_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                        <div class="col-lg-6">
                                            <label for="head">Account Sub Head<i
                                                class="text-danger pt-1" style="font-size:16px">*</i> </label>
                                            <select class=" chosen-select form-control head" name="head" id="head">
                                                <option value="">Choose Head...</option>
                                                @foreach ($head as $v_head)
                                                    <option value="{{ $v_head->id }}">{{ $v_head->head_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- <div class="col-lg-1 mt-4">
                                        <button type="button" class="btn btn-success btn-md add-btn" onclick="addMore()">+</button>
                                    </div> --}}
                    
                                        <div class="col-lg-6">
                                            <label for="date">Date<i
                                                class="text-danger pt-1" style="font-size:16px">*</i> </label>
                                            <input type="date" name="payment_date" required class="form-control">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Code No">Code No<i
                                                class="text-danger pt-1" style="font-size:16px">*</i> </label>
                                            <input type="text" class="form-control" name="code_no" id="code_no"
                                                placeholder="Enter an Exp. Code No.">
                                            <small style="color:red">Code No. Should Be Unique</small>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="date">Expense Amount<i
                                                class="text-danger pt-1" style="font-size:16px">*</i> </label>
                                            <input type="number" name="amount" id="amount" class="form-control amount"
                                                placeholder="Enter Amount" onkeyup="totalAmount(this)">
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="remarks">Particulars<i
                                                class="text-danger pt-1" style="font-size:16px">*</i> </label>
                                            <input type="text" name="remarks" class="form-control" id="remarks" placeholder="Particular" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 ">

                                    <div class="row border m-2 p-2" style="border-color: rgb(9, 155, 212) !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-info text-center">
                                            Expense Optional Information
                                        </h6>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 expenser" name="expenser_name"
                                                placeholder="Type Expernser Name">
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 expenser" name="designation"
                                                placeholder="Type Designation">
                                        </div>
                                        
                                        <div class="col-lg-12 mt-3">
                                            <label for="Supplier">Attachment</label>
                                            <input type="file" name="attachment" />
                                        </div>
                                        <div class="col-lg-12 pt-3">
                                            <button class="btn btn-success btn-block" id="submit"><i
                                                    class="fa fa-check"></i> Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                $('.cheque').hide();
                $('.mobile').hide();
                $('.headAdd').hide();
                $('#advance-expense').hide();
                $('#advance-cheque').hide();

                console.log("ready!");
            });
            var i = 1;

        //=============================== Advance Cheque ====================//      

        function filterHead() {
            var category_id = document.getElementById('category').value;
            if(category_id == 'new_category'){
                console.log(category_id);
                 $('#head').empty().append('<option value="" disabled selected>Choose Head</option><option value="new_head">New Head</option>').trigger("chosen:updated");
            }else{
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
                    $('#head').append(`<option value="" disabled selected>Choose Head</option>`);
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

        function generateExpenseCode() {
            var category_id = document.getElementById('category').value;
            var projectName = @json($project_name); 
            var projectId = @json($project_id);
            var lastExpenseId = @json($lastExpenseId ?? 0); 
            if (category_id) {
                var nextExpenseId = lastExpenseId + 1;
                function getInitials(name) {
                    var words = name.split(' ');
                    return words.map(function(word) {
                        return word.charAt(0).toUpperCase();
                    }).join('');
                }

                var initials = getInitials(projectName);

                var expenseCode = `${initials}-EXP-${nextExpenseId}`;

                document.getElementById('code_no').value = expenseCode;
            } else {
                document.getElementById('code_no').value = '';
            }
        }
    </script>
@endpush
