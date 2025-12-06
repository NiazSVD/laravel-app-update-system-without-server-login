@extends('backend.master')

@section('content')
    <div class="page-heading">
        <h3>Site Settings</h3>
    </div>
    <div class="page-content">
        <section class="list-group-navigation">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">

                        <!-- LEFT TAB LIST -->
                        <div class="col-12 col-sm-12 col-md-4">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="list-group" role="tablist">
                                            <a class="list-group-item list-group-item-action active" id="list-home-list"
                                                data-bs-toggle="list" href="#list-home" role="tab">General</a>

                                            <a class="list-group-item list-group-item-action" id="list-profile-list"
                                                data-bs-toggle="list" href="#list-profile" role="tab">Logo & Favicon</a>

                                            <a class="list-group-item list-group-item-action" id="list-messages-list"
                                                data-bs-toggle="list" href="#list-messages" role="tab">Contact Info</a>

                                            <a class="list-group-item list-group-item-action" id="list-settings-list"
                                                data-bs-toggle="list" href="#list-settings" role="tab">SEO Settings</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT TAB CONTENT -->
                        <div class="col-12 col-sm-12 col-md-8">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="tab-content text-justify" id="nav-tabContent">

                                            <!-- GENERAL SETTINGS -->
                                            <div class="tab-pane show active" id="list-home" role="tabpanel">
                                                <form action="{{ route('admin.settings.site.update') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="mb-3">
                                                        <label class="form-label">Site Name</label>
                                                        <input type="text" name="site_name" class="form-control"
                                                            value="{{ $setting->site_name ?? '' }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Site Description</label>
                                                        <textarea name="site_description" class="form-control" rows="3">{{ $setting->site_description ?? '' }}</textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Footer Text</label>
                                                        <input type="text" name="footer_text" class="form-control"
                                                            value="{{ $setting->footer_text ?? '' }}">
                                                    </div>

                                                    <button class="btn btn-primary mt-2" type="submit">Save
                                                        Changes</button>
                                                </form>
                                            </div>

                                            <!-- LOGO & FAVICON -->
                                            <div class="tab-pane" id="list-profile" role="tabpanel">
                                                <form action="{{ route('admin.settings.site.update') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="mb-3">
                                                        <label class="form-label">Site Logo</label>
                                                        <input type="file" name="site_logo" class="form-control">
                                                        @if ($setting && $setting->site_logo)
                                                            <img src="{{ asset($setting->site_logo) }}" alt="Logo"
                                                                class="mt-2" height="60">
                                                        @endif
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Favicon</label>
                                                        <input type="file" name="favicon" class="form-control">
                                                        @if ($setting && $setting->favicon)
                                                            <img src="{{ asset($setting->favicon) }}" alt="Favicon"
                                                                class="mt-2" height="32">
                                                        @endif
                                                    </div>

                                                    <button class="btn btn-primary mt-2" type="submit">Save
                                                        Changes</button>
                                                </form>
                                            </div>

                                            <!-- CONTACT INFO -->
                                            <div class="tab-pane" id="list-messages" role="tabpanel">
                                                <form action="{{ route('admin.settings.site.update') }}" method="POST">
                                                    @csrf

                                                    <div class="mb-3">
                                                        <label class="form-label">Email Address</label>
                                                        <input type="email" name="email_address" class="form-control"
                                                            value="{{ $setting->email_address ?? '' }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Phone Number</label>
                                                        <input type="text" name="phone_number" class="form-control"
                                                            value="{{ $setting->phone_number ?? '' }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Office Address</label>
                                                        <textarea name="office_address" class="form-control" rows="2">{{ $setting->office_address ?? '' }}</textarea>
                                                    </div>

                                                    <button class="btn btn-primary mt-2" type="submit">Save
                                                        Changes</button>
                                                </form>
                                            </div>

                                            <!-- SEO SETTINGS -->
                                            <div class="tab-pane" id="list-settings" role="tabpanel">
                                                <form action="{{ route('admin.settings.site.update') }}" method="POST">
                                                    @csrf

                                                    <div class="mb-3">
                                                        <label class="form-label">Meta Title</label>
                                                        <input type="text" name="meta_title" class="form-control"
                                                            value="{{ $setting->meta_title ?? '' }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Meta Description</label>
                                                        <textarea name="meta_description" class="form-control" rows="3">{{ $setting->meta_description ?? '' }}</textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Meta Keywords</label>
                                                        <input type="text" name="meta_keywords" class="form-control"
                                                            value="{{ $setting->meta_keywords ?? '' }}">
                                                    </div>

                                                    <button class="btn btn-primary mt-2" type="submit">Save
                                                        Changes</button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
