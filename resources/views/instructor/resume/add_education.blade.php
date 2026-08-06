@php
$countries = App\Models\Country::all();
@endphp

<form class="ajaxFormSubmission" action="{{ route('instructor.manage.education_add') }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="form-group mb-3">
        <label class="form-label ol-form-label">{{ get_phrase('Title') }}</label>
        <input type="text" name="title" class="form-control ol-form-control" required>
    </div>

    <div class="form-group mb-3">
        <label class="form-label ol-form-label">{{ get_phrase('Institute') }}</label>
        <input type="text" name="institute" class="form-control ol-form-control" required>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label class="form-label ol-form-label">{{ get_phrase('Country') }}</label>
            <select class="form-control ol-form-control neu-select" id="country_select" name="country" required>
                <option value="">{{ get_phrase('Select a country') }}</option>
                @foreach ($countries as $country)
                <option value="{{ $country->name }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 form-group mb-3">
            <label class="form-label ol-form-label">{{ get_phrase('City') }}</label>
            <input type="text" name="city" class="form-control ol-form-control" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label class="form-label ol-form-label" for="start_date">{{ get_phrase('Start Date') }}</label>
            <input type="date" name="start_date" id="start_date" class="form-control ol-form-control">
        </div>

        <div class="col-md-6 form-group mb-3">
            <label class="form-label ol-form-label" for="end_date">{{ get_phrase('End Date') }}</label>
            <input type="date" name="end_date" id="end_date" class="form-control ol-form-control">
        </div>
    </div>

    <div class="form-group mb-3">
        <input type="checkbox" name="status" id="status" value="ongoing" class="form-check-input">
        <label for="status" class="form-label ol-form-label">{{ get_phrase('This degree/course is currently ongoing') }}</label>
    </div>

    <div class="form-group mb-3">
        <label class="form-label ol-form-label">{{ get_phrase('Description') }}</label>
        <textarea name="description" class="form-control nEditor"></textarea>
    </div>

    <div class="text-center">
        <button class="btn ol-btn-primary ol-btn-sm w-100 formSubmissionBtn" type="submit" name="button">{{ get_phrase('Save') }}</button>
    </div>
</form>

<script src="{{ asset('assets/backend/js/neu-select.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof NeuSelect !== 'undefined') {
            new NeuSelect('#country_select');
        }
    });
</script>

@include('instructor.init')