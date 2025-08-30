<table class="table table-bordered">
    <thead class="bg-info">
        <tr>
            <th colspan="12">Previous Data</th>
        </tr>
        <tr>
            <th>Work Nature</th>
            <th>Vendor</th>
            <th>Today Manpower</th>
            <th>Per Manpower Cost</th>
            <th>Description</th>
            <th>Previous Done Work (%)</th>
            <th>Today Work (%)</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @php
            $check = 0;
        @endphp
        @foreach ($data as $item)
        @php
            $check = 1;
        @endphp
        <tr>
            <td>
                <input type="hidden" name="work_status_id[]"  value="{{ $item->id }}"/>
                <input type="hidden" name="work_nature[]" value="{{ $item->work_nature }}" class="form-control">
                {{ $item->work_nature }}
            </td>
            <td>
                <input type="hidden" name="vendor_id[]" value="{{ $item->vendor_id }}" class="form-control">
                {{ $item->vendor->name }}
            </td>
            <td>
                <input type="text" name="today_manpower[]" class="form-control">
            </td>
            <td>
                <input type="text" name="per_manpower_cost[]" class="form-control">
            </td>
            <td>
                <input type="text" name="description[]" class="form-control">
            </td>
            <td>
                <input type="text" readonly value="{{ $item->complete_work }}" name="previous_work[]" class="form-control">
            </td>
            <td>
                <input type="text" name="today_work[]" class="form-control">
            </td>
            <td>
                <input type="text" name="remarks[]" class="form-control">
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if ($check == 0)
<table class="table table-bordered" id="new_data">
    <thead class="bg-info">
        <tr>
            <th colspan="12">New Data</th>
        </tr>
        <tr>
            <th>Work Nature</th>
            <th>Vendor</th>
            <th>Today Manpower</th>
            <th>Per Manpower Cost</th>
            <th>Description</th>
            <th>Previous Done Work (%)</th>
            <th>Today Work (%)</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <tr id="row_1" class="row_tr">
            <td>
                <input type="hidden" name="work_status_id[]"  value="new"/>
                <input type="text" name="work_nature[]" class="form-control">
            </td>
            <td>
                <select name="vendor_id[]" id="vendor_id" class="form-control">
                    <option value="">Select One</option>
                    @foreach ($vendor_data as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="today_manpower[]" class="form-control">
            </td>
            <td>
                <input type="text" name="per_manpower_cost[]" class="form-control">
            </td>
            <td>
                <input type="text" name="description[]" class="form-control">
            </td>
            <td>
                <input type="text" name="previous_work[]" class="form-control">
            </td>
            <td>
                <input type="text" name="today_work[]" class="form-control">
            </td>
            <td>
                <input type="text" name="remarks[]" class="form-control">
            </td>
        </tr>
    </tbody>
</table>
@endif