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
                            Update Unit
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('update_unit', $unit->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <fieldset>
                                <legend style="color: hsl(0, 0%, 100%);"> Unit Information </legend>
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Unit Name<i class="text-danger">*</i></label>
                                            <input type="text" class="form-control" name="unit_name"
                                                placeholder="Unit Name" value="{{ $unit->unit_name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="property_id">Property<i class="text-danger">*</i></label>
                                        <select name="property_id" id="property_id" class="form-control form-select"
                                            required>
                                            <option value="">Select property</option>
                                            @foreach ($properties as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($item->id == $unit->property_id) selected @endif>{{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="floor_id">Floor<i class="text-danger">*</i></label>
                                        <select name="floor_id" id="floor_id" class="form-control form-select" required>
                                            <option value="">Select floor</option>
                                            @foreach ($floors as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($item->id == $unit->floor_id) selected @endif>
                                                    {{ $item->floor_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <div class="form-group">
                                                <label for="size">Size<i class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="size"
                                                    placeholder="Size (Sqft)" value="{{ $unit->size }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Rent<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control" name="rent_amount"
                                                placeholder="Unit Rent" value="{{ $unit->rent_amount }}" required>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Rent Duration<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="rent_duration" placeholder="Day of Month Between 1 to 30" value="{{$unit->rent_duration}}" required>
                                    </div>
                                </div> --}}
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Notes</label>
                                            <textarea type="text" cols="3" class="form-control" name="note" placeholder="Notes...">{{ $unit->note }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="current_status">Current Status<i class="text-danger">*</i></label>
                                            <select name="current_status" id="current_status" class="form-control form-select" required>
                                                <option value="" {{ $unit->current_status === null ? 'selected' : '' }}>
                                                    Select Current Status</option>
                                                <option value="1" {{ $unit->current_status == 1 ? 'selected' : '' }}>
                                                    Available</option>
                                                <option value="2" {{ $unit->current_status == 2 ? 'selected' : '' }}>
                                                    Rented</option>
                                                <option value="3" {{ $unit->current_status == 3 ? 'selected' : '' }}>
                                                    Under Maintenance</option>
                                            </select>
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
                                            <input type="number" min="0" step="0.01" class="form-control" name="water_bill"
                                                placeholder="Water Bill" required value="{{ $unit->water_bill }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Gas Bill<i class="text-danger">*</i></label>
                                            <input type="number" min="0" step="0.01" class="form-control" name="gas_bill"
                                                placeholder="Gas Bill" required value="{{ $unit->gas_bill }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Trash Bill<i class="text-danger">*</i></label>
                                            <input type="number" min="0" step="0.01" class="form-control" name="trash_bill"
                                                placeholder="Trash Bill" required value="{{ $unit->trash_bill }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Security Bill<i class="text-danger">*</i></label>
                                            <input type="number" min="0" step="0.01" class="form-control" name="security_bill"
                                                placeholder="Security Bill" required value="{{ $unit->security_bill }}">
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
                                                    <option value="{{ $item->id }}" @if ($item->id == $unit->meter_id) selected @endif>{{ $item->meter_number }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Rate Per Unit<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control" name="e_rate_per_unit" min="0" step="0.01"
                                                placeholder="Electricity Rate Per Unit" required value="{{ $unit->e_rate_per_unit }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Service Charge<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control" name="service_charge" min="0" step="0.01"
                                                placeholder="Service Charge" required value="{{ $unit->service_charge }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success  text-center col-md-12 mb-3"><i
                                        class="fa fa-check"></i>
                                    Update</button>
                            </fieldset>

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
    </script>
@endpush
