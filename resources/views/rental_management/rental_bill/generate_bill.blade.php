@extends('layouts.app')
<style>
    .contain {
        max-width: 900px !important;
    }
</style>

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Rental Bill Generate
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('generate_rental_bill') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-4">
                                    <label for="property_id">Property<i class="text-danger">*</i></label>
                                    <select name="property_id" id="property_id" class="form-control form-select" required>
                                        <option value="">Select property</option>
                                        @foreach ($properties as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4">
                                    <label for="floor_id">Floor<i class="text-danger">*</i></label>
                                    <select name="floor_id" id="floor_id" class="form-control form-select" required>
                                        <option value="">Select floor</option>
                                    </select>
                                </div>

                                <div class="col-lg-4">
                                    <label for="unit_id">Unit<i class="text-danger">*</i></label>
                                    <select name="unit_id" id="unit_id" class="form-control form-select" required>
                                        <option value="">Select Unit</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 p-3">
                                    <label for="type">Bill type<i class="text-danger">*</i></label>
                                    <select class="form-control" name="type" required>
                                        <option value="">Select Bill Type</option>
                                        <option value="Monthly Rent">Monthly Rent</option>
                                        <option value="Electricity Bill">Electricity Bill</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 p-3">
                                    <label for="month">Month</label>
                                    <input type="month" class="form-control" name="month" value="{{ date('Y-m') }}" />
                                </div>

                                <div class="col-lg-2 p-3">
                                    <label for="action">Action</label>
                                    <button type="submit" class="btn btn-success btn-block">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($billType) && $billType == 'Electricity Bill')
        <div class="container contain">
            <div class="card mt-4">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Electricity Bill</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('save_electricity_bill') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="renter_id" value="{{ $renter->id }}">
                            <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                            <input type="hidden" name="type" value="{{ $billType }}">

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Property</label>
                                    <input type="text" class="form-control" name="name" placeholder="Property"
                                        value="{{ $renter->property->name }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Floor</label>
                                    <input type="text" class="form-control" name="floor_name" placeholder="Floor"
                                        value="{{ $renter->floor->floor_name ?? '' }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Unit</label>
                                    <input type="text" class="form-control" name="unit_name" placeholder="Unit"
                                        value="{{ $renter->unit->unit_name ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Month</label>
                                    <input type="month" class="form-control" name="month" placeholder="Month"
                                        value="{{ $month }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label">Meter Holder Name</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Meter Holder Name" value="{{ $renter->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Meter No.</label>
                                    <input type="text" class="form-control" name="meter_number"
                                        placeholder="Meter No." value="{{ $meter->meter_number ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Last Reading Date<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" name="last_reading_date" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Last Reading<i class="text-danger">*</i></label>
                                    <input type="number" min="0" step="0.01" class="form-control"
                                        id="last_reading" name="last_reading" placeholder="Last Reading" required
                                        oninput="calculateAmount()">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Current Reading Date<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" name="current_reading_date" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Current Reading<i class="text-danger">*</i></label>
                                    <input type="number" min="0" step="0.01" class="form-control"
                                        id="current_reading" placeholder="Current Reading" name="current_reading"
                                        required oninput="calculateAmount()">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Electricity Unit Rate<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" placeholder="Electricity Unit Rate"
                                        id="e_rate_per_unit" value="{{ $unit->e_rate_per_unit }}" name="e_rate_per_unit"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Usage Amount<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="usage_amount"
                                        placeholder="Usage Amount" value="" name="usage_amount" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Total Amount<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="total_amount"
                                        placeholder="Total Amount" value="" name="total_amount" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Bill Date<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" id="bill_date" placeholder="Bill Date"
                                        value="{{ date('Y-m-d') }}" name="bill_date">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Due Date<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" id="due_date" placeholder="Due Date" name="due_date">
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Note<i class="text-danger">*</i></label>
                                    <textarea type="text" cols="3" class="form-control" name="note" placeholder="Note..." required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mb-3" style="width: 100%;">
                                <i class="fa fa-check"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif


    @if (isset($billType) && $billType == 'Monthly Rent')
        <div class="container contain">
            <div class="card mt-4">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Monthly Rent</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('save_rental_bill') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="renter_id" value="{{ $renter->id }}">
                            <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                            <input type="hidden" name="type" value="{{ $billType }}">
                            {{-- @dd($renter) --}}

                            <div class="mt-3 ml-2 col-lg-11">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="advance_deduction_check"
                                        name="advance_deduction">
                                    <label class="form-check-label" for="advance_deduction_check"><b>Advance
                                            Deduction</b></label>
                                </div>
                                <div style="height: 2px; background-color: rgb(65, 62, 62); margin: 20px 0;"></div>
                            </div>

                            <!-- Advance Deduction Amount (Hidden by Default) -->
                            <div class="col-lg-12" id="advance_deduction_amount_div" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Advance Deduction Amount</label>
                                    <input type="number" class="form-control" id="advance_deduction_amount"
                                        name="advance_deduction" max="{{ $renter->advance_left }}" placeholder="Advance Deduction Amount">
                                    <small id="advance_error_message" class="text-danger" style="display: none;"></small>
                                </div>
                            </div>                            
                            


                            <div class="mt-3 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Property</label>
                                    <input type="text" class="form-control" name="name" placeholder="Property"
                                        value="{{ $renter->property->name }}" readonly>
                                </div>
                            </div>

                            <div class="mt-3 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Floor</label>
                                    <input type="text" class="form-control" name="floor_name" placeholder="Floor"
                                        value="{{ $renter->floor->floor_name ?? '' }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Unit</label>
                                    <input type="text" class="form-control" name="unit_name" placeholder="Unit"
                                        value="{{ $renter->unit->unit_name ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Month</label>
                                    <input type="month" class="form-control" name="month" placeholder="Month"
                                        value="{{ $month }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label">Renter Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Renter Name"
                                        value="{{ $renter->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Rent Amount</label>
                                    <input type="text" class="form-control" name="rent_amount" id="rent_amount"
                                        placeholder="Rent Amount" value="{{ $unit->rent_amount ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Service/Maintenance Charge<i
                                            class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="service_charge" id="service_charge"
                                        value="{{ $unit->service_charge ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Water Bill<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="water_bill" id="water_bill"
                                        value="{{ $unit->water_bill ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Gas Bill<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="gas_bill" id="gas_bill"
                                        value="{{ $unit->gas_bill ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Trash Bill<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="trash_bill" id="trash_bill"
                                        value="{{ $unit->trash_bill ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Security Bill<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="security_bill" id="security_bill"
                                        value="{{ $unit->security_bill ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Total Amount<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="total_amount"
                                        placeholder="Total Amount"
                                        value="{{ ($unit->rent_amount ?? 0) + ($unit->service_charge ?? 0) + ($unit->water_bill ?? 0) + ($unit->gas_bill ?? 0) + ($unit->trash_bill ?? 0) + $unit->security_bill }}"
                                        name="total_amount" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6" id="bill_date_div">
                                <div class="mb-3">
                                    <label class="form-label">Bill Date<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" id="bill_date" placeholder="Bill Date"
                                        value="{{ date('Y-m-d') }}" name="bill_date">
                                </div>
                            </div>
                            <div class="col-lg-6" id="due_date_div">
                                <div class="mb-3">
                                    <label class="form-label">Due Date<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" id="due_date" placeholder="Due Date"
                                       name="due_date">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Note<i class="text-danger">*</i></label>
                                    <textarea type="text" cols="3" class="form-control" name="note" placeholder="Note..." required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mb-3" style="width: 100%;">
                                <i class="fa fa-check"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#property_id').on('change', function() {
            let propertyId = $(this).val();
            if (propertyId) {
                $.ajax({
                    url: '/get-floors-by-property/' + propertyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#floor_id').empty().append(
                            '<option value="">Select floor</option>');
                        $.each(data.floors, function(key, value) {
                            $('#floor_id').append('<option value="' + value.id +
                                '">' + value.floor_name + '</option>');
                        });
                    }
                });
            } else {
                $('#floor_id').empty().append('<option value="">Select floor</option>');
                $('#unit_id').empty().append('<option value="">Select Unit</option>');
            }
        });

        $('#floor_id').on('change', function() {
            let floorId = $(this).val();
            let propertyId = $('#property_id').val();
            if (floorId) {
                $.ajax({
                    url: '/get-units-floor-wise/' + floorId + '/' + propertyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#unit_id').empty().append(
                            '<option value="">Select Unit</option>');
                        $.each(data.units, function(key, value) {
                            $('#unit_id').append('<option value="' + value.id +
                                '">' + value.unit_name + '</option>');
                        });
                    }
                });
            } else {
                $('#unit_id').empty().append('<option value="">Select Unit</option>');
            }
        });
    });
</script>
<script>
    function calculateAmount() {
        let lastReading = parseFloat(document.getElementById('last_reading').value) || 0;
        let currentReading = parseFloat(document.getElementById('current_reading').value) || 0;
        let ratePerUnit = parseFloat(document.getElementById('e_rate_per_unit').value) || 0;

        let usageAmount = currentReading - lastReading;

        if (usageAmount < 0) {
            usageAmount = 0;
        }

        document.getElementById('usage_amount').value = usageAmount.toFixed(2);

        let totalAmount = usageAmount * ratePerUnit;

        document.getElementById('total_amount').value = totalAmount.toFixed(2);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('advance_deduction_check').addEventListener('change', function() {
            var advanceDeductionDiv = document.getElementById('advance_deduction_amount_div');
            var advanceDeductionInput = document.getElementById('advance_deduction_amount');

            var billDateDiv = document.getElementById('bill_date_div');
            var dueDateDiv = document.getElementById('due_date_div');

            if (!billDateDiv || !dueDateDiv) {
                console.error(
                    'Bill Date or Due Date div not found. Please check the HTML structure and element IDs.'
                    );
                return;
            }

            var rentAmount = parseFloat(document.getElementById('rent_amount').value) || 0;
            var serviceCharge = parseFloat(document.getElementById('service_charge').value) || 0;
            var waterBill = parseFloat(document.getElementById('water_bill').value) || 0;
            var gasBill = parseFloat(document.getElementById('gas_bill').value) || 0;
            var trashBill = parseFloat(document.getElementById('trash_bill').value) || 0;
            var securityBill = parseFloat(document.getElementById('security_bill').value) || 0;
            var originalTotalAmount = rentAmount + serviceCharge + waterBill + gasBill + trashBill +
                securityBill;

            if (this.checked) {
                advanceDeductionDiv.style.display = 'block';
                advanceDeductionInput.value = originalTotalAmount;

                billDateDiv.style.display = 'none';
                dueDateDiv.style.display = 'none';
            } else {
                advanceDeductionDiv.style.display = 'none';

                billDateDiv.style.display = 'block';
                dueDateDiv.style.display = 'block';
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var rentDuration = {{ $renter->rent_duration ?? 0 }};

        var today = new Date();
        var firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        var dueDate = new Date(firstDayOfMonth);
        dueDate.setDate(dueDate.getDate() + rentDuration);
        var formattedDueDate = dueDate.toISOString().split('T')[0];
        document.getElementById('due_date').value = formattedDueDate;
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var advanceDeductionInput = document.getElementById('advance_deduction_amount');
    var advanceErrorMessage = document.getElementById('advance_error_message');

    advanceDeductionInput.addEventListener('input', function() {
        var advanceLeft = parseFloat(this.getAttribute('max'));
        var currentValue = parseFloat(this.value);

        if (currentValue > advanceLeft) {
            advanceErrorMessage.textContent = 'Advance Deduction Amount cannot exceed ' + advanceLeft;
            advanceErrorMessage.style.display = 'block';
            this.value = advanceLeft; 
        } else {
            advanceErrorMessage.style.display = 'none';
        }
    });
});

</script>
