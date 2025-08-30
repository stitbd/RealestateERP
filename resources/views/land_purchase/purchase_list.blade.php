@extends('layouts.app')


@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Land Purchase List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3 table-responsive">
                        {{-- <form action="{{ route('purchase') }}" method="get">
                    <div class="row pb-3">
                        <div class="col-lg-3">
                            <label for="Project">Project</label>
                            <select name="project_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($project_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="Project">Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($supplier_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" name="start_date"/>
                        </div>
                        <div class="col-lg-2">
                            <label for="start_date">End Date</label>
                            <input type="date" class="form-control" name="end_date"/>
                        </div>

                        <div class="col-lg-2">
                            <label for="action">Action</label> <br/>
                            <button class="btn btn-success btn-block">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    </form> --}}

                        {{-- <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('purchase-print?supplier_id='.request()->get('supplier_id').'&project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print
                            </a>
                            <a href="{{url('purchase-pdf?supplier_id='.request()->get('supplier_id').'&project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                            </a>
                        </div>
                    </div> --}}

                        <table class="table table-bordered">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>Project</th>
                                    <th>Purchase Date</th>
                                    <th>Dolil No (দলিল নং)</th>
                                    <th>Rs Dag No (আর এস দাগ নং)</th>
                                    <th>Khatian No (খতিয়ান নং)</th>
                                    <th>Khazna No (খাজনা নং)</th>
                                    <th>Percentage (শতাংশ)</th>
                                    <th>Amount Per Percentage (প্রতি শতাংশের দাম)</th>
                                    <th>Amount</th>
                                    <th>Middle-man Amount</th>
                                    <th>Type</th>
                                    <th>Attachments</th>
                                    <th>Total Land (শতক)</th>
                                    <th>Total Amount (মোট টাকা)</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($landPurchase as $purchase)
                                    @php
                                        $detailsCount = $purchase->landPurchaseDetails->count();
                                        $total_land = $purchase->landPurchaseDetails->sum('shotangso');
                                        $total_amount = $purchase->landPurchaseDetails->sum('total_amount');
                                    @endphp
                                    <tr>
                                        <td rowspan="{{ $detailsCount }}">{{ $loop->iteration }}</td>
                                        <td rowspan="{{ $detailsCount }}">{{ $purchase->projectInformation->name }}</td>
                                        <td rowspan="{{ $detailsCount }}">
                                            {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>

                                        @if ($detailsCount > 0)
                                            @php
                                                $firstDetail = $purchase->landPurchaseDetails->first();
                                            @endphp
                                            <td>{{ $firstDetail->dolil_no }}</td>
                                            <td>{{ $firstDetail->rs_dag_no }}</td>
                                            <td>{{ $firstDetail->khatian_no }}</td>
                                            <td>{{ $firstDetail->khazna_no }}</td>
                                            <td>{{ $firstDetail->shotangso }}</td>
                                            <td>{{ $firstDetail->per_shotangso_rate }}</td>
                                            <td>{{ $firstDetail->total_amount }}</td>
                                            <td>{{ $firstDetail->medium_amount }}</td>
                                            <td>
                                                @if ($firstDetail->type == 1)
                                                    পাওয়ার
                                                @elseif($firstDetail->type == 2)
                                                    সাফ কাওলা
                                                @else
                                                    নামজারি
                                                @endif
                                            </td>
                                            <td>
                                                @if ($firstDetail->documents)
                                                    @foreach ($firstDetail->documents as $document)
                                                        <a href="{{ asset('land_purchase/' . $document->land_documents) }}"
                                                            target="_blank" class="btn btn-info btn-sm w-60">
                                                            <i class="fa fa-download"></i>
                                                            {{ $document->land_documents_type_name }}
                                                        </a>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td rowspan="{{ $detailsCount }}">{{ $total_land }}</td>
                                            <td rowspan="{{ $detailsCount }}">{{ $total_amount }}</td>
                                            <td class="text-center" rowspan="{{ $detailsCount }}">
                                                <a data-toggle="modal" data-target=".view-modal-{{ $purchase->id }}"><i
                                                        class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                                <a href="{{route('land_stock_edit',$purchase->id)}}"><i class="fa fa-edit"
                                                        style="color: rgb(28, 145, 199)"></i></a>
                                                <a href=""
                                                    onclick="return confirm('Are you sure you want to delete this item?');"><i
                                                        class="fa fa-trash text-danger"></i></a>
                                            </td>
                                        @endif
                                    </tr>

                                    <!-- Additional Detail Rows -->
                                    @foreach ($purchase->landPurchaseDetails->skip(1) as $detail)
                                        <tr>
                                            <td>{{ $detail->dolil_no }}</td>
                                            <td>{{ $detail->rs_dag_no }}</td>
                                            <td>{{ $detail->khatian_no }}</td>
                                            <td>{{ $detail->khazna_no }}</td>
                                            <td>{{ $detail->shotangso }}</td>
                                            <td>{{ $detail->per_shotangso_rate }}</td>
                                            <td>{{ $detail->total_amount }}</td>
                                            <td>{{ $detail->medium_amount }}</td>
                                            <td>
                                                @if ($detail->type == 1)
                                                    পাওয়ার
                                                @elseif($detail->type == 2)
                                                    সাফ কাওলা
                                                @else
                                                    নামজারি
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>



                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $landPurchase->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .card-header-bg {
            background-color: green;
            color: white;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    <!-- View Modal -->
    @foreach ($landPurchase as $item)
        <div class="modal fade view-modal-{{ $item->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center d-flex justify-content-between align-items-center">
                        <h5>Land Purchase Details</h5>
                        <div>
                            <button type="button" class="btn btn-secondary"
                                onclick="printPage({{ $item->id }}, '{{ Session::get('company_name') }}')">
                                <i class="fa fa-print"></i> Print
                            </button>
                            <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body" id="print_body_{{ $item->id }}">
                        <div class="row ">
                            <!-- General Information -->
                            <div class="col-sm-12 mb-4">
                                <div class="card border-success">
                                    <div class="card-header text-center card-header-bg">
                                        <strong>Land Purchase Details</strong>
                                        <br>
                                        <h5>Date:
                                            {{ \Carbon\Carbon::parse($item->purchase_date)->format('d/m/Y') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <strong>Project:</strong> {{ $item->projectInformation->name ?? 'N/A' }}
                                            </div>
                                            <div class="col-sm-4">
                                                <strong>Total Land (শতক):</strong>
                                                {{ $item->total_purchase_land ?? 'N/A' }}
                                            </div>
                                            <div class="col-sm-4">
                                                <strong>Total Amount:</strong> {{ $item->total_amount ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Purchase Details -->
                            <div class="col-12">
                                <h5 class="text-success mb-3">Land Details</h5>

                                @foreach ($item->landPurchaseDetails as $detail)
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <strong>Detail #{{ $loop->iteration }}</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <strong>Donor Name:</strong>
                                                    <br>
                                                    @if ($detail->donors->isNotEmpty())
                                                        @foreach ($detail->donors as $donor)
                                                            {{ $donor->donor_name }}<br>
                                                        @endforeach
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                                <div class="col-sm-4">
                                                    <strong>Dolil No (দলিল নং):</strong> {{ $detail->dolil_no ?? 'N/A' }}
                                                </div>
                                                <div class="col-sm-4">
                                                    <strong>Rs Dag No (আর এস দাগ নং):</strong>
                                                    {{ $detail->rs_dag_no ?? 'N/A' }}
                                                </div>
                                                <div class="col-sm-4 mt-2">
                                                    <strong>Khatian No (খতিয়ান নং):</strong>
                                                    {{ $detail->khatian_no ?? 'N/A' }}
                                                </div>
                                                <div class="col-sm-4 mt-2">
                                                    <strong>Khazna No (খাজনা নং):</strong>
                                                    {{ $detail->khazna_no ?? 'N/A' }}
                                                </div>
                                                <div class="col-sm-4 mt-2">
                                                    <strong>Percentage (শতাংশ):</strong> {{ $detail->shotangso ?? 'N/A' }}
                                                </div>
                                                <div class="col-sm-4 mt-2">
                                                    <strong>Amount Per Percentage (প্রতি শতাংশের দাম):</strong>
                                                    {{ $detail->per_shotangso_rate ?? 'N/A' }}
                                                </div>
                                                <div class="col-sm-4 mt-2">
                                                    <strong>Total Amount:</strong> {{ $detail->total_amount ?? 'N/A' }}
                                                </div>
                                                <div class="col-sm-4 mt-2">
                                                    <strong>Middle-man Amount:</strong>
                                                    {{ $detail->medium_amount ?? 'N/A' }}
                                                </div>
                                                <div class="col-sm-4 mt-2">
                                                    <strong>Type:</strong>
                                                    @if ($detail->type == 1)
                                                        পাওয়ার
                                                    @elseif ($detail->type == 2)
                                                        সাফ কাওলা
                                                    @else
                                                        নামজারি
                                                    @endif
                                                </div>
                                                <div class="col-md-12 mt-2 no-print">
                                                    <strong>Documents:</strong>
                                                    <br>
                                                    @if ($detail->documents)
                                                        @foreach ($detail->documents as $document)
                                                            <a href="{{ asset('land_purchase/' . $document->land_documents) }}"
                                                                target="_blank" class="btn btn-sm btn-info">
                                                                <i class="fa fa-download"></i>
                                                                {{ $document->land_documents_type_name }}
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- /.modal -->

    <script>
        function view_invoice(purchase_id) {
            url = '{{ route('purchaseInvoice', ':purchase_id') }}';
            url = url.replace(':purchase_id', purchase_id);
            //alert(url);
            $.ajax({
                cache: false,
                type: "GET",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                url: url,
                success: function(response) {
                    $('#invoice_details').html(response);
                }
            })
        }

        function generatePayment(purchase_id) {
            url = '{{ route('generatePayment', ':purchase_id') }}';
            url = url.replace(':purchase_id', purchase_id);
            //alert(url);
            $.ajax({
                cache: false,
                type: "GET",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                url: url,
                success: function(response) {
                    $('#payment_create').html(response);
                }
            })
        }

        function viewPaymentDetails(purchase_id) {
            url = '{{ route('payment-details-view', ':purchase_id') }}';
            url = url.replace(':purchase_id', purchase_id);
            //alert(url);
            $.ajax({
                cache: false,
                type: "GET",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                url: url,
                success: function(response) {
                    $('#payment_details').html(response);
                }
            })
        }
    </script>
    <script>
        function printPage(itemId, companyName) {
            var printBody = document.getElementById('print_body_' + itemId);

            var noPrintElements = printBody.querySelectorAll('.no-print');
            noPrintElements.forEach(function(el) {
                el.style.display = 'none';
            });

            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printBody.innerHTML;

            window.print();

            document.body.innerHTML = originalContents;

            noPrintElements.forEach(function(el) {
                el.style.display = '';
            });
            location.reload();

        }
    </script>

@endsection
