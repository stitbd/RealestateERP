<!-- resources/views/systemsetting/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <h2 class="card-title">System Settings</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('systemsetting.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Company Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $setting ? $setting->name : '') }}">
                            </div>

                            <div class="form-group">
                                <label for="email">Company Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $setting ? $setting->email : '') }}">
                            </div>

                            <div class="form-group">
                                <label for="address">Company Address</label>
                                <textarea class="form-control" id="address" name="address">{{ old('address', $setting ? $setting->address : '') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="about">About</label>
                                <textarea class="form-control" id="about" name="about">{{ old('about', $setting ? $setting->about : '') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone', $setting ? $setting->phone : '') }}">
                            </div>

                            <div class="form-group">
                                <label for="copyright_text">Copyright Text</label>
                                <input type="text" class="form-control" id="copyright_text" name="copyright_text"
                                    value="{{ old('copyright_text', $setting ? $setting->copyright_text : '') }}">
                            </div>

                            <div class="form-group">
                                <label for="develop_by">Developed By</label>
                                <input type="text" class="form-control" id="develop_by" name="develop_by"
                                    value="{{ old('develop_by', $setting ? $setting->develop_by : '') }}">
                            </div>

                            <div class="form-group">
                                <label for="logo">Logo</label>
                                <input type="file" class="form-control-file" id="logo" name="logo">
                                @if ($setting && $setting->logo)
                                    <img src="{{ asset($setting->logo) }}" alt="Logo" width="100">
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="fav_icon">Favicon</label>
                                <input type="file" class="form-control-file" id="fav_icon" name="fav_icon">
                                @if ($setting && $setting->fav_icon)
                                    <img src="{{ asset($setting->fav_icon) }}" alt="Logo" width="100">
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
