@extends('layouts.app')
@section('content')
    <!-- Session Message Modal বা Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border border-success border-2 rounded-3 px-4 py-3 mt-3"
            role="alert" style="background: #03ad33;">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-4 text-success"></i>
                <div class="flex-grow-1">
                    <strong></strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="container mt-2">
        <div class="card card-info card-outline">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Land Share List</h3>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add" style="margin-left: 800px;">
                    <i class="fa fa-plus"></i> Add Land Share
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="landshareTable">
                    <thead class="bg-info text-center">
                        <tr>
                            <th>Project</th>
                            <th>Share Qty</th>
                            <th>Percentage (শতাংশ)</th>
                            <th>Square Feet</th>
                            {{-- <th>Image</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($landshares as $item)
                            <tr class="text-center">
                                <td>{{ $item->project->name ?? '' }}</td>
                                <td>{{ $item->shareqty }}</td>
                                <td>{{ $item->sotangsho }}</td>
                                <td>{{ $item->size }}</td>
                                {{-- <td>
                                    @if ($item->image)
                                        @php
                                            $extension = pathinfo($item->image, PATHINFO_EXTENSION);
                                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                        @endphp

                                        @if (in_array(strtolower($extension), $imageExtensions))
                                            <img src="{{ asset($item->image) }}" alt="Uploaded Image" width="100"
                                                class="img-thumbnail">
                                        @else
                                            <a href="{{ asset($item->image) }}" class="btn btn-primary" target="_blank">View
                                                File</a>
                                        @endif
                                    @else
                                        <p class="text-muted">No Image</p>
                                    @endif
                                </td> --}}
                                <td>
                                    <button data-toggle="modal" data-target="#edit-modal-{{ $item->id }}"
                                        class="btn btn-sm btn-info"><i class="fas fa-edit"></i>
                                    </button>
                                    {{--
                                    <form action="{{ route('landsharedestroy', $item->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you want to delete the land share?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                    --}}
                                    {{--
                                    <a href="{{ route('landshareshow', $item->id) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                    --}}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('landsharestore') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Add Landshare</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6">
                        <label>Project</label>
                        <select name="project_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Share Qty</label>
                        <input type="text" class="form-control" name="shareqty" required>
                    </div>
                    <div class="col-md-6">
                        <label>Percentage (শতাংশ)</label>
                        <input type="text" class="form-control" name="sotangsho">
                    </div>
                    <div class="col-md-6">
                        <label>Square Feet</label>
                        <input type="text" class="form-control" name="size">
                    </div>
                    {{-- <div class="col-md-6">
                    <label>Sector</label>
                    <input type="text" class="form-control" name="sector">
                </div>
                <div class="col-md-6">
                    <label>Road</label>
                    <input type="text" class="form-control" name="road">
                </div>
                <div class="col-md-6">
                    <label>Block</label>
                    <input type="text" class="form-control" name="block">
                </div>
                <div class="col-md-6">
                    <label>Facing</label>
                    <input type="text" class="form-control" name="facing">
                </div>
                <div class="col-md-12">
                    <label>Note</label>
                    <textarea class="form-control" name="note"></textarea>
                </div>
                <div class="col-md-12">
                    <label>Image</label>
                    <input type="file" class="form-control" name="image">
                </div> --}}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach ($landshares as $item)
        <div class="modal fade" id="edit-modal-{{ $item->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('landshareupdate', $item->id) }}" method="POST" enctype="multipart/form-data"
                    class="modal-content">
                    @csrf
                    <div class="modal-header bg-info">
                        <h5 class="modal-title">Edit Landshare</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-md-6">
                            <label>Project</label>
                            <select name="project_id" class="form-control" required>
                                <option value="">Select</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ $item->project_id == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Share Qty</label>
                            <input type="text" class="form-control" name="shareqty" value="{{ $item->shareqty }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label>Percentage (শতাংশ)</label>
                            <input type="text" class="form-control" name="sotangsho" value="{{ $item->sotangsho }}">
                        </div>
                        <div class="col-md-6">
                            <label>Square Feet</label>
                            <input type="text" class="form-control" name="size" value="{{ $item->size }}">
                        </div>
                        {{-- <div class="col-md-6">
                    <label>Sector</label>
                    <input type="text" class="form-control" name="sector" value="{{ $item->sector }}">
                </div>
                <div class="col-md-6">
                    <label>Road</label>
                    <input type="text" class="form-control" name="road" value="{{ $item->road }}">
                </div>
                <div class="col-md-6">
                    <label>Block</label>
                    <input type="text" class="form-control" name="block" value="{{ $item->block }}">
                </div>
                <div class="col-md-6">
                    <label>Facing</label>
                    <input type="text" class="form-control" name="facing" value="{{ $item->facing }}">
                </div>
                <div class="col-md-12">
                    <label>Note</label>
                    <textarea class="form-control" name="note">{{ $item->note }}</textarea>
                </div>
                <div class="col-md-12">
                    <label>Image</label>
                    <input type="file" class="form-control" name="image">
                    @if ($item->image)
                        <div class="pt-2">
                            <img src="{{ asset($item->image) }}" width="100" class="img-thumbnail">
                        </div>
                    @endif
                </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('script_js')
    <script>
        $(document).ready(function() {
            $('#landshareTable').DataTable({
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                }
            });
        });
    </script>
@endpush
