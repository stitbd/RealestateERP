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
                            Unit Entry
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('save_unit') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <fieldset>
                                <legend style="color: hsl(0, 0%, 100%);"> Unit Information </legend>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Unit Name<i class="text-danger">*</i></label>
                                            <input type="text" class="form-control" name="unit_name"
                                                placeholder="Unit Name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="property_id">Property<i class="text-danger">*</i></label>
                                        <select name="property_id" id="property_id" class="form-control form-select"
                                            required>
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
                                            @foreach ($floors as $item)
                                                <option value="{{ $item->id }}">{{ $item->floor_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <div class="form-group">
                                                <label for="size">Size<i class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="size"
                                                    placeholder="Size (Sqft)" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Rent<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control" name="rent_amount"
                                                placeholder="Unit Rent" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Notes</label>
                                            <textarea type="text" cols="3" class="form-control" name="note" placeholder="Notes..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend style="color: hsl(0, 0%, 100%);"> Bill Information </legend>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Water Bill<i class="text-danger">*</i></label>
                                            <input type="number" min="0" step="0.01" class="form-control"
                                                name="water_bill" placeholder="Water Bill" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Gas Bill<i class="text-danger">*</i></label>
                                            <input type="number" min="0" step="0.01" class="form-control"
                                                name="gas_bill" placeholder="Gas Bill" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Trash Bill<i class="text-danger">*</i></label>
                                            <input type="number" min="0" step="0.01" class="form-control"
                                                name="trash_bill" placeholder="Trash Bill" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Security Bill<i class="text-danger">*</i></label>
                                            <input type="number" min="0" step="0.01" class="form-control"
                                                name="security_bill" placeholder="Security Bill" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Electricity (Meter No.)<i
                                                    class="text-danger">*</i></label>
                                            <select name="meter_id" id="meter_id" class="form-control form-select"
                                                required>
                                                <option value="">Select Meter No.</option>
                                                @foreach ($meters as $item)
                                                    @php
                                                        $unitExists = App\Models\Unit::where('meter_id', $item->id)
                                                            ->where(['company_id' => Session::get('company_id')])
                                                            ->where('status', 1)
                                                            ->exists();
                                                    @endphp

                                                    @if (!$unitExists)
                                                        <option value="{{ $item->id }}">{{ $item->meter_number }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Rate Per Unit<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control" name="e_rate_per_unit"
                                                min="0" step="0.01" placeholder="Electricity Rate Per Unit"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Service Charge<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control" name="service_charge"
                                                min="0" step="0.01" placeholder="Service Charge" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success  text-center col-md-12 mb-3"><i
                                        class="fa fa-check"></i>
                                    Save</button>
                            </fieldset>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(".chosen-select").chosen();

        $(document).ready(function() {
            // When Property is selected
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

        });
    </script>
@endpush
