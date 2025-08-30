@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<style>
    .director-container,
    .coordinator-container {
        display: none;
    }
</style>

<script>
    function printPage(itemId, companyName) {
        var printContents = document.getElementById('print_body_' + itemId).innerHTML;
        var today = new Date();
        var formattedDate = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();

        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
        printWindow.document.write('.header { text-align: center; margin-bottom: 20px; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write('<div class="header">');
        printWindow.document.write('<h1>' + companyName + '</h1>');
        printWindow.document.write('<p>Date: ' + formattedDate + '</p>');
        printWindow.document.write('</div>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Director/ Co-ordinator/ Shareholder/ Outsider List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body table-responsive">
                        <form action="{{ route('land_sale_employee') }}" method="get">
                            <div class="row">

                                <div class="col-lg-3">
                                    <label for="employee_name">Select One</label>
                                    <select name="employee_name" class="form-control chosen-select">
                                        <option value="" selected>--Select One Director/Co-ordinator/Shareholder/Outsider--</option>
                                        @foreach ($sale_emp as $item)
                                            <option value="{{ $item->id }}">{{ $item->employee_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="head_id">Head</label>
                                    <select name="head_id" class="form-control chosen-select">
                                        <option value="" selected>--Select One--</option>
                                        @foreach ($head as $h)
                                            <option value="{{ $h->id }}">{{ $h->head_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="designation">Designation</label>
                                    <input type="text" class="form-control" name="designation" />
                                </div>
                                <div class="col-lg-3">
                                    <label for="employee_code">Employee Code</label>
                                    <input type="text" class="form-control" name="employee_code" />
                                </div>

                                <div class="col-lg-3">
                                    <label for="start_date">From</label>
                                    <input type="date" class="form-control" name="start_date"/>
                                </div>
                                <div class="col-lg-3">
                                    <label for="start_date">To</label>
                                    <input type="date" class="form-control" name="end_date"/>
                                </div>

                                <div class="col-lg-3">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>

                                <div class="col-lg-3">
                                    <label for="action">Director Wise Filter</label> <br />
                                    <a href="{{ route('director_wise_employees') }}" class="btn btn-primary btn-block">
                                        <i class="fa fa-users"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Account Head</th>
                                    <th>Employee Type</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    {{-- <th>Mobile No</th> --}}
                                    <th>Address</th>
                                    <th>Photo</th>
                                    <th>Director</th>
                                    <th>Co-ordinator</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($sale_employees as $item)
                                    <tr id="row-{{ $item->id }}" data-id="{{ $item->id }}">
                                        <td class="text-center">
                                            @php
                                                $i = ($sale_employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                                    ? ($loop->iteration + ($sale_employees->perPage() * ($sale_employees->currentPage() - 1)))
                                                    : ++$i;
                                            @endphp
                                            {{ $i }}
                                        </td>
                                        <td class="text-center">{{ $item->employee_name ?? '' }}</td>
                                        <td class="text-center">{{ $item->employee_code ?? '' }}</td>
                                        <td class="text-center">{{ $item->head->head_name ?? '' }}</td>
                                        <td class="text-center">{{ $item->userType->type ?? '' }}</td>
                                        <td class="text-center">{{ $item->designation ?? '' }} </td>
                                        <td class="text-center">{{ $item->email ?? '' }}</td>
                                        <td class="text-center">{{ $item->address ?? '' }}</td>
                                        <td class="text-center">
                                            <img src="{{ asset('upload_images/employee_photo/' . $item->employee_photo) }}" height="70px" width="90px" />
                                        </td>
                                        <td class="text-center">{{ $item->director->employee_name ?? 0 }}</td>
                                        <td class="text-center">{{ $item->coordinator->employee_name ?? 0 }}</td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $item->id }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a data-toggle="modal" data-target=".update-modal-{{ $item->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if ($item->status == 1)
                                                <a class=" btn btn-xs btn-success  mr-1"
                                                    href="{{ route('employee_status', $item->id) }}"
                                                    style="padding: 2px; color: white;">
                                                    <i class="fas fa-arrow-up"></i>
                                                </a>
                                            @else
                                                <a class=" btn btn-xs btn-danger mr-1"
                                                    href="{{ route('employee_status', $item->id) }}"
                                                    style="padding: 2px; color: white;">
                                                    <i class="fas fa-arrow-down"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $sale_employees->appends(request()->except('page'))->links() }}

                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>


    <!-- Add Modal -->
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Director/ Co-ordinator/ Shareholder/ Outsider</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('land_sale_employee_store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="category_id">Account Main Head<i class="text-danger">*</i></label>
                                <select name="category_id"  class="form-control category chosen-select" id="filterHead" required>
                                    <option value="">Select Category..</option>
                                    @foreach ($categories as $category)
                                       @php $incomes = json_decode($category->category_type)  @endphp
                                       @if($incomes && in_array('Income',$incomes))
                                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                                       @endif
                                    @endforeach
                                     <option value=""></option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label for="head">Account Sub Head<i class="text-danger">*</i></label>
                                <select class=" chosen-select form-control head" name="head" required
                                    id="head" onchange="newHeadAdd()">
                                    <option value="">Choose Sub Head...</option>
                                    @foreach ($head as $v_head)
                                        <option value="{{ $v_head->id }}">{{ $v_head->head_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label for="user_type_id">User Type<i class="text-danger">*</i></label>
                                <select name="user_type_id" id="user_type_id" class="form-control" required>
                                    <option value="" selected>Select Type</option>
                                    @foreach ($user_type as $type)
                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12 director-container" style="display: none;">
                                <label for="director_id">Director<i class="text-danger">*</i></label>
                                <select name="director_id" id="director_id" class="form-control chosen-select">
                                    <option value="0">Select One</option>
                                    @foreach ($land_sale_director as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->employee_name }},
                                            ({{ $employee->userType->type }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12 coordinator-container" style="display: none;">
                                <label for="coordinator_id">Co-ordinator</label>
                                <select name="coordinator_id" id="coordinator_id" class="form-control chosen-select">
                                    <option value="0">Select One</option>
                                    @foreach ($land_sale_coordinator as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->employee_name }},
                                            ({{ $employee->userType->type }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-lg-5">
                                <label for="employee_name">Name<i class="text-danger">*</i></label>
                                <input type="text" class="form-control" name="employee_name"
                                    placeholder="Director/ Co-ordinator/ Shareholder Name" required>
                            </div>
                            <div class="col-lg-3">
                                <label for="employee_code">Code<i class="text-danger">*</i></label>
                                <input type="text" class="form-control" name="employee_code"
                                    placeholder="Employee Code" required>
                            </div>

                            <div class="col-lg-4">
                                <label for="designation">Designation<i class="text-danger">*</i></label>
                                <input type="text" class="form-control" name="designation" required />
                            </div>
                            <div class="col-lg-3">
                                <label for="email">Email<i class="text-danger">*</i></label>
                                <input type="email" class="form-control" name="email" required />
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Mobile No<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="mobile_no" placeholder="Mobile No"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Date Of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="blood_group">Blood Group</label>
                                <select name="blood_group" id="blood_group" class="form-control">
                                    <option value="">--Select--</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Address<i class="text-danger">*</i></label>
                                    <textarea type="text" class="form-control" name="address" cols="4" rows="4" placeholder="Address"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="nid"> NID</label>
                                    <input type="file" name="nid" class="form-control" />
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="employee_photo"> Employee Photo</label>
                                    <input type="file" name="employee_photo" class="form-control" />
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <!-- Land Sale Employee Edit Modal -->
    @foreach ($sale_employees as $item)
        <div class="modal fade update update-modal-{{ $item->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Director/ Co-ordinator/ Shareholder</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_land_sale_employee', ['id' => $item->id, 'page' => request()->input('page')]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="category">Account Main Head<i class="text-danger">*</i></label>
                                    <select name="category_id"  class="form-control category chosen-select filterHead" id="filterHead_{{$item->id}}" required>
                                        <option value="">Select Category..</option>
                                        @foreach ($categories as $category)
                                           @php $incomes = json_decode($category->category_type)  @endphp
                                           @if($incomes && in_array('Income',$incomes))
                                                <option {{ $category->id == $item->category_id ? 'selected' : '' }} value="{{$category->id}}">{{$category->category_name}}</option>
                                           @endif
                                        @endforeach
                                         <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <label for="head">Account Sub Head<i class="text-danger">*</i></label>
                                    <select class="chosen-select form-control head" name="head" required
                                        id="head_{{$item->id}}">
                                        <option value="">Choose Sub Head...</option>
                                        @foreach ($head as $v_head)
                                            <option {{ $v_head->id == $item->head_id ? 'selected' : '' }} value="{{ $v_head->id }}">{{ $v_head->head_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <label for="user_type_id">User Type<i class="text-danger">*</i></label>
                                    <select name="user_type_id" id="user_type_id_edit_{{ $item->id }}" class="form-control">
                                        <option value="" selected>Select Type</option>
                                        @foreach ($user_type as $type)
                                            <option value="{{ $type->id }}" @if ($type->id == $item->user_type_id) selected @endif>
                                                {{ $type->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-12 director-container-{{ $item->id }}" style="display: none;">
                                    <label for="director_id">Director<i class="text-danger">*</i></label>
                                    <select name="director_id" id="director_id_edit_{{ $item->id }}" class="form-control chosen-select">
                                        <option value="0">Select One</option>
                                        @foreach ($land_sale_director as $employee)
                                            <option value="{{ $employee->id }}" @if ($employee->id == $item->director_id) selected @endif>
                                                {{ $employee->employee_name }}, ({{ $employee->userType->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-12 coordinator-container-{{ $item->id }}" style="display: none;">
                                    <label for="coordinator_id">Co-ordinator</label>
                                    <select name="coordinator_id" id="coordinator_id_edit_{{ $item->id }}" class="form-control chosen-select">
                                        <option value="0">Select One</option>
                                        @foreach ($land_sale_coordinator as $employee)
                                            <option value="{{ $employee->id }}" @if ($employee->id == $item->coordinator_id) selected @endif>
                                                {{ $employee->employee_name }}, ({{ $employee->userType->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-5">
                                    <label for="employee_name">Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="employee_name"
                                        value="{{ $item->employee_name }}"
                                        placeholder="Director/ Co-ordinator/ Shareholder Name" required>
                                </div>
                                <div class="col-lg-3">
                                    <label for="employee_code">Code<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="employee_code"
                                        value="{{ $item->employee_code }}"
                                        placeholder="Employee Code" required>
                                </div>

                                <div class="col-lg-4">
                                    <label for="designation">Designation<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="designation"
                                        value="{{ $item->designation }}" required />
                                </div>
                                <div class="col-lg-3">
                                    <label for="email">Email<i class="text-danger">*</i></label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ $item->email }}" required />
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile No<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control" name="mobile_no"
                                            value="{{ $item->mobile_no }}" placeholder="Mobile No" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Date Of Birth</label>
                                        <input type="date" value="{{ $item->date_of_birth }}" class="form-control"
                                            name="date_of_birth">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="blood_group">Blood Group</label>
                                    <select name="blood_group" id="blood_group" class="form-control">
                                        <option value="" {{ $item->blood_group === '' ? 'selected' : '' }}>
                                            --Select--</option>
                                        <option value="A+" {{ $item->blood_group === 'A+' ? 'selected' : '' }}>A+
                                        </option>
                                        <option value="A-" {{ $item->blood_group === 'A-' ? 'selected' : '' }}>A-
                                        </option>
                                        <option value="B+" {{ $item->blood_group === 'B+' ? 'selected' : '' }}>B+
                                        </option>
                                        <option value="B-" {{ $item->blood_group === 'B-' ? 'selected' : '' }}>B-
                                        </option>
                                        <option value="AB+" {{ $item->blood_group === 'AB+' ? 'selected' : '' }}>AB+
                                        </option>
                                        <option value="AB-" {{ $item->blood_group === 'AB-' ? 'selected' : '' }}>AB-
                                        </option>
                                        <option value="O+" {{ $item->blood_group === 'O+' ? 'selected' : '' }}>O+
                                        </option>
                                        <option value="O-" {{ $item->blood_group === 'O-' ? 'selected' : '' }}>O-
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address<i class="text-danger">*</i></label>
                                        <textarea type="text" class="form-control" name="address" cols="4" rows="4" placeholder="Address">{{ $item->address }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="nid"> NID </label>
                                        <input type="file" name="nid" class="form-control" />

                                        @if ($item->nid)
                                            @php
                                                $filePath = 'upload_images/land_sale_employee_nid/' . $item->nid;
                                                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                            @endphp

                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ asset($filePath) }}" height="100px" width="100px" />
                                            @elseif ($extension == 'pdf')
                                                <embed src="{{ asset($filePath) }}" type="application/pdf"
                                                    width="100px" height="100px" />
                                                <p><a href="{{ asset($filePath) }}" target="_blank">View PDF</a></p>
                                            @elseif (in_array($extension, ['doc', 'docx']))
                                                <p><a href="{{ asset($filePath) }}" target="_blank">Download Word
                                                        Document</a></p>
                                            @else
                                                <p><a href="{{ asset($filePath) }}" target="_blank">Download File</a></p>
                                            @endif
                                        @endif
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="employee_photo"> Employee Photo</label>
                                        <input type="file" name="employee_photo" class="form-control" />
                                        @if ($item->employee_photo)
                                            <img src="{{ asset('upload_images/employee_photo/' . $item->employee_photo) }}"
                                                height="70px" width="90px" />
                                        @endif
                                    </div>
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
    <!-- /.modal -->


    <!-- Land Sale Employee View Modal -->
    @foreach ($sale_employees as $item)
        <div class="modal fade view-modal-{{ $item->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center d-flex justify-content-between align-items-center">
                        <h5>View Sales Related Employee Details</h5>
                        <div>
                            <button type="button"
                                class="btn btn-secondary"onclick="printPage({{ $item->id }}, '{{ Session::get('company_name') }}')">
                                <i class="fa fa-print"></i> Print
                            </button>
                            <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body" id="print_body_{{ $item->id }}">
                        <div class="row">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="6" style="text-align: center; color: green;"> Sales Related
                                            Employee
                                            Details
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">Account Category: </th>
                                        <td>{{ $item->category->category_name ?? '' }}</td>

                                        <th width="20%">Account Head: </th>
                                        <td>{{ $item->head->head_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="20%">Employee Name: </th>
                                        <td>{{ $item->employee_name ?? '' }}</td>

                                        <th width="20%">Employee Code: </th>
                                        <td>{{ $item->employee_code ?? '' }}</td>

                                        @if ($item->director_id)
                                            <th width="20%">Director: </th>
                                            <td>{{ $item->head->employee_name ?? 0 }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th width="20%">Employee Type: </th>
                                        <td>{{ $item->userType->type ?? '' }}</td>

                                        <th width="20%">Designation: </th>
                                        <td>{{ $item->designation ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="20%">Email: </th>
                                        <td>{{ $item->email ?? '' }}</td>

                                        <th width="20%">Mobile No: </th>
                                        <td>{{ $item->mobile_no ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="20%">Date Of Birth: </th>
                                        <td>{{ $item->date_of_birth ?? '' }}</td>

                                        <th width="20%">Blood Group: </th>
                                        <td>{{ $item->blood_group ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="20%">Address: </th>
                                        <td>{{ $item->address ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="20%">NID: </th>
                                        <td class="text-center">
                                            @if ($item->nid)
                                                @php
                                                    $filePath = 'upload_images/land_sale_employee_nid/' . $item->nid;
                                                    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                @endphp

                                                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                    <img src="{{ asset($filePath) }}" height="100px" width="100px" />
                                                @elseif ($extension == 'pdf')
                                                    <embed src="{{ asset($filePath) }}" type="application/pdf"
                                                        width="100px" height="100px" />
                                                    <p><a href="{{ asset($filePath) }}" target="_blank">View PDF</a></p>
                                                @elseif (in_array($extension, ['doc', 'docx']))
                                                    <p><a href="{{ asset($filePath) }}" target="_blank">Download Word
                                                            Document</a></p>
                                                @else
                                                    <p><a href="{{ asset($filePath) }}" target="_blank">Download File</a>
                                                    </p>
                                                @endif
                                            @endif
                                        </td>

                                        <th width="20%">Employee Photo: </th>
                                        <td class="text-center">
                                            <img src="{{ asset('/upload_images/employee_photo/' . $item->employee_photo) }}"
                                                height="80px" width="100px" />
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- /.modal -->
@endsection
@push('script_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
       $(".chosen-select").chosen({width: "100%"});

        $(document).ready(function() {
            $('.director-container, .coordinator-container').hide();

            function showInfo() {
                var user_type_id = $('#user_type_id').val();

                console.log("User type selected:", user_type_id);
                $('.director-container, .coordinator-container').hide();
                $('#director_id').removeAttr('required');

                if (user_type_id == "1") {
                    console.log("User type 1 selected - no container shown");
                    // No container shown for user type 1
                } else if (user_type_id == "2") {
                    console.log("User type 2 selected - director container shown");
                    $('.director-container').show();
                    $('#director_id').attr('required', 'required');
                } else {
                    console.log("Other user type selected - both containers shown");
                    $('.director-container, .coordinator-container').show();
                    $('#director_id').attr('required', 'required');
                }
            }

            // Update visibility when user type changes
            $('#user_type_id').change(function() {
                showInfo();
            });

            // Form validation on submit
            $('form').on('submit', function(e) {
                let valid = true;

                if ($('.director-container').is(':visible') && $('#director_id').val() == '0') {
                    alert('Please select a Director.');
                    valid = false;
                }
                // if ($('.coordinator-container').is(':visible') && $('#coordinator_id').val() == '0') {
                //     alert('Please select a Co-ordinator.');
                //     valid = false;
                // }

                if (!valid) {
                    e.preventDefault();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function showInfo(itemId) {
                var user_type_id = $('#user_type_id_edit_' + itemId).val();

                $('.director-container-' + itemId + ', .coordinator-container-' + itemId).hide();
                $('#director_id_edit_' + itemId + ', #coordinator_id_edit_' + itemId).removeAttr('required');

                if (user_type_id == "1") {
                } else if (user_type_id == "2") {
                    $('.director-container-' + itemId).show();
                    $('#director_id_edit_' + itemId).attr('required', 'required');
                } else {
                    $('.director-container-' + itemId + ', .coordinator-container-' + itemId).show();
                    $('#director_id_edit_' + itemId).attr('required', 'required');
                    $('#coordinator_id_edit_' + itemId).attr('required', 'required');
                }
            }

            $('select[id^="user_type_id_edit_"]').each(function() {
                var itemId = $(this).attr('id').split('_').pop();
                showInfo(itemId);

                $(this).change(function() {
                    showInfo(itemId);
                });
            });

            $('form').on('submit', function(e) {
                let valid = true;

                $('select[id^="user_type_id_edit_"]').each(function() {
                    var itemId = $(this).attr('id').split('_').pop();

                    if ($('.director-container-' + itemId).is(':visible') && $('#director_id_edit_' + itemId).val() == '0') {
                        alert('Please select a Director for item ' + itemId + '.');
                        valid = false;
                    }
                    // if ($('.coordinator-container-' + itemId).is(':visible') && $('#coordinator_id_edit_' + itemId).val() == '0') {
                    //     alert('Please select a Co-ordinator for item ' + itemId + '.');
                    //     valid = false;
                    // }
                });

                if (!valid) {
                    e.preventDefault();
                }
            });
        });
        $("#filterHead").on("change",function(e){
            e.preventDefault();
            var category_id = $(this).val();
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
                            `<option value="" disabled selected>Choose Head</option>`
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


        });
        $(document).ready(function () {
            $(".filterHead").on("change", function (e) {
                e.preventDefault();
                var category_id = $(this).val();
                var itemId = $(this).attr('id').split('_')[1];
                var url = "{{ route('filter-head') }}";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        category_id: category_id
                    },
                    success: function (data) {
                        $('#head_' + itemId).find('option').remove();
                        $('#head_' + itemId).html('');
                        $('#head_' + itemId).append(
                            `<option value="" disabled selected>Choose Head</option>`
                        );
                        $.each(data, function (key, value) {
                            $('#head_' + itemId).append(`
                                <option value="` + value.id + `">` + value.head_name + `</option>`
                            );
                        });
                        $('#head_' + itemId).trigger("chosen:updated");
                    }
                });
            });

            $(".chosen-select").chosen();
        });
    </script>
@endpush
