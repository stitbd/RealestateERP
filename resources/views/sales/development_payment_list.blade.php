@extends('layouts.app')

@section('content')
    <div class="p-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-info shadow">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">📋 Development Payment List</h4>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="developmentTable"
                                class="table table-bordered table-striped table-hover text-center align-middle">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th width="10%">TDC</th>
                                        <th width="10%">Project</th>
                                        <th width="8%">Type</th>
                                        <th width="12%">Customer Name</th>
                                        <th>Amount (Tk)</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Note</th>
                                        <th>File</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($development_payments as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>

                                            {{-- TDC --}}
                                            <td>{{ $item->land_sale->customer->customer_code ?? '-' }}</td>

                                            {{-- Project --}}
                                            <td>{{ $item->land_sale->customer->project->name ?? '-' }}</td>

                                            {{-- Type --}}
                                            <td>{{ $item->land_sale->type ?? '-' }}</td>

                                            {{-- Customer Name --}}
                                            <td>{{ $item->land_sale->customer->customer_name ?? '-' }}</td>

                                            {{-- Amount --}}
                                            <td>{{ number_format($item->amount, 2) }}</td>

                                            {{-- Dates --}}
                                            <td>{{ $item->start_date ?? '-' }}</td>
                                            <td>{{ $item->end_date ?? '-' }}</td>

                                            {{-- Note --}}
                                            <td>{!! $item->note ?? '-' !!}</td>

                                            {{-- File/Image --}}
                                            <td>
                                                @if ($item->image)
                                                    @php
                                                        $extension = pathinfo($item->image, PATHINFO_EXTENSION);
                                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                                    @endphp

                                                    @if (in_array(strtolower($extension), $imageExtensions))
                                                        <img src="{{ asset('upload_images/development_images/' . $item->image) }}"
                                                            alt="Uploaded Image" class="img-thumbnail" width="70">
                                                    @else
                                                        <a href="{{ asset('upload_images/development_images/' . $item->image) }}"
                                                            class="btn btn-sm btn-primary" target="_blank">View File</a>
                                                    @endif
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function() {
            var table = $('#developmentTable').DataTable({
                paging: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                searching: true,
                responsive: true,
                order: [
                    [5, 'asc']
                ], // Amount ascending
                columnDefs: [{
                        orderable: false,
                        targets: 0
                    }, // Disable ordering for SL
                    {
                        orderable: true,
                        targets: 5
                    } // Allow ordering for Amount
                ],
                language: {
                    search: "🔍 Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No records found",
                    paginate: {
                        previous: "‹ Prev",
                        next: "Next ›"
                    }
                }
            });

            // Update Serial Number
            table.on('order.dt search.dt', function() {
                table.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes()
                    .each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
        });
    </script>
@endsection
