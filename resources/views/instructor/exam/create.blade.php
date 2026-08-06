@extends('layouts.instructor')
@push('title', get_phrase('Create Exam'))

@section('content')
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="ol-card   mb-3">
                <div class="ol-card-body  ">
                    <h4 class="title fs-16px">
                        <i class="fi-rr-settings-sliders me-2"></i>
                        {{ get_phrase('Add New Exam') }}
                    </h4>
                </div>
            </div>

            <div class="ol-card p-3">
                <div class="ol-card-body">
                    <form action="{{ route('instructor.exam.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-md-6 pb-2">
                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label" for="title">{{ get_phrase('Exam Title') }} <span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="title" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter Exam Title') }}" required>
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label" for="description">{{ get_phrase('Description') }}</label>
                                    <textarea name="description" class="form-control ol-form-control" rows="5" placeholder="{{ get_phrase('Enter Exam Description') }}"></textarea>
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label" for="marks">{{ get_phrase('Marks') }} <span class="text-danger ms-1">*</span></label>
                                    <input type="number" name="marks" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter Total Marks') }}" required min="1">
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label" for="duration">{{ get_phrase('Duration (minutes)') }} <span class="text-danger ms-1">*</span></label>
                                    <input type="number" name="duration" class="form-control ol-form-control" placeholder="{{ get_phrase('Enter Exam Duration in Minutes') }}" required min="1">
                                </div>
                            </div>

                            <div class="col-md-6 pb-2">
                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label" for="course_id">{{ get_phrase('Select Course') }} <span class="text-danger ms-1">*</span></label>
                                    <select name="course_id" class="form-control ol-form-control neu-select " required>
                                        <option value="">{{ get_phrase('Select a Course') }}</option>
                                        @foreach(App\Models\Course::all() as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label" for="exam_mode">{{ get_phrase('Exam Mode') }}</label>
                                    <select name="exam_mode" class="form-control ol-form-control neu-select " required>
                                        <option value="online">{{ get_phrase('Online') }}</option>
                                        <option value="offline">{{ get_phrase('Offline') }}</option>
                                    </select>
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label" for="question_paper_pdf">{{ get_phrase('Upload Question PDF') }}</label>
                                    <input type="file" name="question_paper_pdf" class="form-control ol-form-control" accept="application/pdf">
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label" for="start_at">{{ get_phrase('Start Date & Time') }}</label>
                                    <input type="datetime-local" name="start_at" class="form-control ol-form-control">
                                </div>

                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label" for="end_at">{{ get_phrase('End Date & Time') }}</label>
                                    <input type="datetime-local" name="end_at" class="form-control ol-form-control">
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn ol-btn-primary float-end">{{ get_phrase('Create Exam') }}</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
