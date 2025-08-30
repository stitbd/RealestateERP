@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Add Plot</h4>
                    </div>
                    <form action="{{ route('save_plot') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Project<i class="text-danger">*</i></label>
                                        <select name="project_id" id="" class="form-control"
                                            onchange="updateSectorFields(this.value); updatePlotTypes(this.value)">
                                            <option value="">Select Project</option>
                                            @foreach ($project_data as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Type of Plot in Katha(কাঠার ধরন)<i
                                                class="text-danger">*</i></label>
                                        <select name="type_id" id="plot-type" class="form-control"
                                            onchange="getPlotSize(this);">
                                            <option value="">Select Plot Katha Type</option>
                                            @foreach ($plot_types as $type)
                                                <option value="{{ $type->id }}" data-plot-size = {{ $type->percentage }}>
                                                    {{ $type->plot_type }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                 <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Plot Size (Percentage)<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control plot-size" name="plot_size"
                                            placeholder="Plot Size" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row border m-2 p-2" style="border-color: green !important">
                                <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                    Plot Information
                                </h6>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Plot No<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="plot_no[]" placeholder="Plot No"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Sector No.<i class="text-danger">*</i></label>
                                        <select name="sector_id[]" id="sector-select1"
                                            class="form-control form-select sector-select" required
                                            onchange="sectorIdWiseRoad(this)">
                                            <option value="" selected>Select Sector</option>
                                            @foreach ($sectors as $sector)
                                                <option value="{{ $sector->id }}">{{ $sector->sector_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label for="road_id">Road No.<i class="text-danger">*</i></label>
                                        <select name="road_id[]" id="road_id1"
                                            class="form-control form-select road-dropdown" required>
                                            <option value="" selected>Select Road</option>
                                            @foreach ($roads as $road)
                                                <option value="{{ $road->id }}">{{ $road->road_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Block No.<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="block_no[]" placeholder="Block No">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Measurement<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="measurement[]"
                                            placeholder="Measurement">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Facing<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="facing[]" placeholder="Facing">
                                    </div>
                                </div>

                                {{-- <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Plot Size (Percentage)<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control plot-size" name="plot_size[]"
                                            placeholder="Plot Size" readonly>
                                    </div>
                                </div> --}}

                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Plot Type<i class="text-danger">*</i></label>
                                        <select name="plot_type[]" id="plot_type" class="form-control form-select" required>
                                            <option value="" selected>Select Type</option>
                                            <option value="residential">Residential Plot</option>
                                            <option value="commercial">Commercial Plot</option>
                                            <option value="industrial">Industrial Plot</option>
                                            <option value="agricultural">Agricultural Plot</option>
                                            <option value="mixed-Use">Mixed-Use Plot</option>
                                            <option value="recreational">Recreational Plot</option>
                                            <option value="institutional">Institutional Plot</option>
                                            <option value="green_belt">Green Belt Plot</option>
                                            <option value="development">Development Plot</option>
                                            <option value="heritage">Heritage Plot</option>
                                            <option value="environmentally_sensitive">Environmentally Sensitive Plot
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="wrapper">

                            </div>
                            <div class="row">

                                <div class="col-lg-6 pt-3">
                                    <button type="button" class="btn btn-success" onclick="add_more()"><i
                                            class="fa fa-plus"></i> Add More</button>
                                </div>

                                <div class="col-lg-6 pt-3 text-right">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i>
                                        Save</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_js')
    <script>
        $(document).ready(function() {
            console.log("ready!");
        });
        var i = 1;
        var j = 0;

        const sectors = @json($sectors);

        function updateSectorFields(projectId) {
            const sectorDropdowns = document.querySelectorAll("[id^='sector-select']");

            sectorDropdowns.forEach((dropdown) => {
                dropdown.innerHTML = '<option value="" selected>Select Sector</option>';

                sectors.forEach((sector) => {
                    if (sector.project_id == projectId) {
                        const option = document.createElement("option");
                        option.value = sector.id;
                        option.textContent = `${sector.sector_name}`;
                        dropdown.appendChild(option);
                    }
                });
            });
        }


        let currentPlotSize = '';

        function getPlotSize() {
            const plotType       = document.getElementById('plot-type');
            const selectOption   = plotType.options[plotType.selectedIndex];
            currentPlotSize        = selectOption.getAttribute('data-plot-size');
            const plotSizeInputs = document.querySelectorAll('.plot-size');
             console.log(plotSizeInputs);
            plotSizeInputs.forEach((sizeValue) => {
                sizeValue.value = currentPlotSize;
            });
        }



        function add_more() {
            ++i;
            ++j;
            $('#wrapper').append(`
                        <div class="row border m-2 p-2" style="border-color: green !important">

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Plot No<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="plot_no[]" placeholder="Plot No"
                                        required>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Sector No.<i class="text-danger">*</i></label>
                                    <select name="sector_id[]" id="sector-select${i}" class="form-control form-select sector-select" required
                                        onchange="sectorIdWiseRoad(this)">
                                        <option value="" selected>Select Sector</option>
                                        @foreach ($sectors as $sector)
                                            <option value="{{ $sector->id }}">{{ $sector->sector_name }}

                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="road_id">Road No.<i class="text-danger">*</i></label>
                                    <select name="road_id[]" id="road_id" class="form-control form-select road-dropdown" required>
                                        <option value="" selected>Select Road</option>
                                        @foreach ($roads as $road)
                                            <option value="{{ $road->id }}">{{ $road->road_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Block No.<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="block_no[]" placeholder="Block No">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Measurement<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="measurement[]"
                                        placeholder="Measurement">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Facing<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="facing[]" placeholder="Facing">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Plot Type<i class="text-danger">*</i></label>
                                    <select name="plot_type[]" id="plot_type" class="form-control form-select" required>
                                        <option value="" selected>Select Type</option>
                                        <option value="residential">Residential Plot</option>
                                        <option value="commercial">Commercial Plot</option>
                                        <option value="industrial">Industrial Plot</option>
                                        <option value="agricultural">Agricultural Plot</option>
                                        <option value="mixed-Use">Mixed-Use Plot</option>
                                        <option value="recreational">Recreational Plot</option>
                                        <option value="institutional">Institutional Plot</option>
                                        <option value="green_belt">Green Belt Plot</option>
                                        <option value="development">Development Plot</option>
                                        <option value="heritage">Heritage Plot</option>
                                        <option value="environmentally_sensitive">Environmentally Sensitive Plot
                                        </option>
                                    </select>
                                </div>
                            </div>

                            `);
            const projectId = document.querySelector("[name='project_id']").value;
            const newDropdown = document.getElementById(`sector-select${i}`);
            populateSectorsForProject(projectId, newDropdown);
        }

        document.getElementById('plot-type').addEventListener('change', getPlotSize);

        function populateSectorsForProject(projectId, dropdown) {
            dropdown.innerHTML = '<option value="" selected>Select Sector</option>';

            sectors.forEach((sector) => {
                if (sector.project_id == projectId) {
                    const option = document.createElement("option");
                    option.value = sector.id;
                    option.textContent = sector.sector_name;
                    dropdown.appendChild(option);
                }
            });
        }

        function removeRow(rowId) {
            $(`#row-${rowId}`).remove();
        }

        const roads = @json($roads);

        function sectorIdWiseRoad(sectorDropdown) {
            const roadDropdown = sectorDropdown.closest(".row").querySelector(".road-dropdown");
            const sectorId = sectorDropdown.value;
            if (!roadDropdown) return;
            roadDropdown.innerHTML = '<option value="" selected>Select Road</option>';

            roads.forEach((road) => {
                if (road.sector_id == sectorId) {
                    const option = document.createElement("option");
                    option.value = road.id;
                    option.textContent = road.road_name;
                    roadDropdown.appendChild(option);
                }
            });
        }

       const plotTypes = @json($plot_types);

        function updatePlotTypes(projectId) {
            const plotTypeDropdown = document.getElementById('plot-type');

            plotTypeDropdown.innerHTML = '<option value="">Select Plot Katha Type</option>';

            const filteredPlotTypes = plotTypes.filter(type => type.project_id == projectId);

            filteredPlotTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type.id;
                option.textContent = type.plot_type;
                option.setAttribute('data-plot-size', type.percentage);
                plotTypeDropdown.appendChild(option);
            });
        }





    </script>
@endpush
