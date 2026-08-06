@extends('layouts.admin')
@push('title', get_phrase('Edit Team Member'))

@section('content')
<div class="row mb-5">
    <div class="col-lg-12">
        <div class="ol-card">
            <div class="ol-card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="title fs-16px">
                        <i class="fi-rr-settings-sliders me-2"></i>
                        {{ get_phrase('Edit Team Member') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="ol-card p-3">
            <div class="ol-card-body">
                <form action="{{ route('admin.team.update', $team->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <!-- Left Column -->
                        <div class="col-md-6">

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">
                                    {{ get_phrase('Name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" value="{{ $team->name }}" class="form-control ol-form-control" required>
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">
                                    {{ get_phrase('Designation') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="designation" value="{{ $team->designation }}" class="form-control ol-form-control" required>
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">
                                    {{ get_phrase('Bio') }}
                                </label>
                                <textarea name="bio" class="form-control ol-form-control" rows="5">{{ $team->bio }}</textarea>
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">
                                    {{ get_phrase('Sort Order') }}
                                </label>
                                <input type="number" name="sort_order" value="{{ $team->sort_order }}" class="form-control ol-form-control">
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">
                                    {{ get_phrase('Status') }} <span class="text-danger">*</span>
                                </label>

                                <div class="eRadios">
                                    <div class="form-check">
                                        <input type="radio" name="status" value="1" class="form-check-input eRadioSuccess"
                                            {{ $team->status == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ get_phrase('Active') }}</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="radio" name="status" value="0" class="form-check-input eRadioDanger"
                                            {{ $team->status == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ get_phrase('Inactive') }}</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">
                                    {{ get_phrase('Photo') }}
                                </label>
                                <input type="file" name="photo" class="form-control ol-form-control" accept="image/*">
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">
                                    {{ get_phrase('LinkedIn URL') }}
                                </label>
                                <input type="url" name="linkedin_url" value="{{ $team->linkedin_url }}" class="form-control ol-form-control">
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">
                                    {{ get_phrase('Facebook URL') }}
                                </label>
                                <input type="url" name="facebook_url" value="{{ $team->facebook_url }}" class="form-control ol-form-control">
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label">
                                    {{ get_phrase('Twitter URL') }}
                                </label>
                                <input type="url" name="twitter_url" value="{{ $team->twitter_url }}" class="form-control ol-form-control">
                            </div>

                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn ol-btn-primary float-end">
                                {{ get_phrase('Update') }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection