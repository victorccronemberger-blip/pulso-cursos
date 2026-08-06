@extends('layouts.instructor')
@push('title', get_phrase('Course Manager'))
@section('content')

<div class="ol-card">
    <div class="ol-card-body">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Manage Courses') }}
            </h4>

            <a href="{{ route('instructor.course.create') }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-plus"></span>
                <span>{{ get_phrase('Add New Course') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="row g-2 g-sm-3 row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-lg-4 row-cols-xl-5">

    <div class="col">
        <a href="{{ route('instructor.courses', ['status' => 'active']) }}" class="d-block text-decoration-none">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                            <p class="sub-title fs-14px fw-semibold" style="margin: 0;">{{ $active_courses }}</p>
                            <h6 class="title fs-12px" style="margin: 0;">{{ get_phrase('Active courses') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="{{ route('instructor.courses', ['status' => 'pending']) }}" class="d-block text-decoration-none">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background: linear-gradient(135deg, #fef9c3, #fef08a); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                            <p class="sub-title fs-14px fw-semibold" style="margin: 0;">{{ $pending_courses }}</p>
                            <h6 class="title fs-12px" style="margin: 0;">{{ get_phrase('Pending courses') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="{{ route('instructor.courses', ['status' => 'upcoming']) }}" class="d-block text-decoration-none">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                                <line x1="12" y1="14" x2="12" y2="18" />
                                <line x1="10" y1="16" x2="14" y2="16" />
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                            <p class="sub-title fs-14px fw-semibold" style="margin: 0;">{{ $upcoming_courses }}</p>
                            <h6 class="title fs-12px" style="margin: 0;">{{ get_phrase('Upcoming courses') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="{{ route('instructor.courses', ['price' => 'free']) }}" class="d-block text-decoration-none">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background: linear-gradient(135deg, #fdf4ff, #f3e8ff); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23" />
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                            <p class="sub-title fs-14px fw-semibold" style="margin: 0;">{{ $free_courses }}</p>
                            <h6 class="title fs-12px" style="margin: 0;">{{ get_phrase('Free courses') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="{{ route('instructor.courses', ['price' => 'paid']) }}" class="d-block text-decoration-none">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background: linear-gradient(135deg, #fff1f2, #ffe4e6); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                                <line x1="1" y1="10" x2="23" y2="10" />
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                            <p class="sub-title fs-14px fw-semibold" style="margin: 0;">{{ $paid_courses }}</p>
                            <h6 class="title fs-12px" style="margin: 0;">{{ get_phrase('Paid courses') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

<!-- Start Instructor area -->
<div class="row">
    <div class="col-12">
        <div class="ol-card">
            <div class="ol-card-body">
                <div class="row mt-3 mb-4">
                    <div class="col-md-6 d-flex align-items-center gap-3">
                        <div class="custom-dropdown ms-2">
                            <button class="dropdown-header btn ol-btn-light">
                                {{ get_phrase('Export') }}
                                <i class="fi-rr-file-export ms-2"></i>
                            </button>
                            <ul class="dropdown-list">
                                <li>
                                    <a class="dropdown-item export-btn" href="#" onclick="downloadPDF('.print-table', 'course-list')"><i class="fi-rr-file-pdf"></i> {{ get_phrase('PDF') }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item export-btn" href="#" onclick="window.print();"><i class="fi-rr-print"></i> {{ get_phrase('Print') }}</a>
                                </li>
                            </ul>
                        </div>

                        @php
                        $filterCount = 0;
                        if (request('category') && request('category') != 'all') $filterCount++;
                        if (request('status') && request('status') != 'all') $filterCount++;
                        if (request('instructor') && request('instructor') != 'all') $filterCount++;
                        if (request('price') && request('price') != 'all') $filterCount++;
                        @endphp

                        <div class="custom-dropdown dropdown-filter">
                            <button class="dropdown-header btn ol-btn-light">
                                <i class="fi-rr-filter me-2"></i>
                                {{ get_phrase('Filter') }}
                                @if($filterCount > 0)
                                <span class="text-12px"> ({{ $filterCount }})</span>
                                @endif
                            </button>
                            <ul class="dropdown-list w-250px">
                                <li>
                                    <form id="filter-dropdown" action="{{ route('instructor.courses') }}" method="get">
                                        <div class="filter-option d-flex flex-column gap-3">
                                            <div>
                                                <label for="eDataList" class="form-label ol-form-label">{{ get_phrase('Category') }}</label>
                                                <select class="form-control ol-form-control neu-select" data-toggle="select2" name="category" data-placeholder="Type to search...">
                                                    <option value="all">{{ get_phrase('All') }}</option>
                                                    @foreach (App\Models\Category::where('parent_id', 0)->orderBy('title', 'desc')->get() as $category)
                                                    <option value="{{ $category->slug }}" @if (isset($parent_cat) && $parent_cat==$category->slug) selected @endif>
                                                        {{ $category->title }}
                                                    </option>
                                                    @foreach ($category->childs as $sub_category)
                                                    <option value="{{ $sub_category->slug }}" @if (isset($child_cat) && $child_cat==$sub_category->slug) selected @endif>
                                                        --{{ $sub_category->title }}
                                                    </option>
                                                    @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="eDataList" class="form-label ol-form-label">{{ get_phrase('Status') }}</label>
                                                <select class="form-control ol-form-control neu-select" data-toggle="select2" name="status" data-placeholder="Type to search...">
                                                    <option value="all">{{ get_phrase('All') }}</option>
                                                    <option value="active" @if (isset($status) && $status=='active' ) selected @endif>{{ get_phrase('Active') }}</option>
                                                    <option value="inactive" @if (isset($status) && $status=='inactive' ) selected @endif>{{ get_phrase('Inactive') }}</option>
                                                    <option value="pending" @if (isset($status) && $status=='pending' ) selected @endif>{{ get_phrase('Pending') }}</option>
                                                    <option value="upcoming" @if (isset($status) && $status=='upcoming' ) selected @endif>{{ get_phrase('Upcoming') }}</option>
                                                    <option value="private" @if (isset($status) && $status=='private' ) selected @endif>{{ get_phrase('Private') }}</option>
                                                    <option value="draft" @if (isset($status) && $status=='draft' ) selected @endif>{{ get_phrase('Draft') }}</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="eDataList" class="form-label ol-form-label">{{ get_phrase('Instructor') }}</label>
                                                <select class="form-control ol-form-control neu-select" data-toggle="select2" name="instructor" data-placeholder="Type to search...">
                                                    <option value="all">{{ get_phrase('All') }}</option>
                                                    @foreach (App\Models\Course::select('user_id')->distinct()->get() as $course)
                                                    <option value="{{ $course->user_id }}" @if (isset($instructor) && $instructor==$course->user_id) selected @endif>
                                                        {{ ucfirst(get_user_info($course->user_id)->name) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="eDataList" class="form-label ol-form-label">{{ get_phrase('Price') }}</label>
                                                <select class="form-control ol-form-control neu-select" data-toggle="select2" name="price" data-placeholder="Type to search...">
                                                    <option value="all">{{ get_phrase('All') }}</option>
                                                    <option value="free" @if (isset($price) && $price=='free' ) selected @endif>{{ get_phrase('Free') }}</option>
                                                    <option value="paid" @if (isset($price) && $price=='paid' ) selected @endif>{{ get_phrase('Paid') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="filter-button d-flex justify-content-end align-items-center mt-3">
                                            <button type="submit" class="ol-btn-primary">{{ get_phrase('Apply') }}</button>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        @if (isset($_GET) && count($_GET) > 0)
                        <a href="{{ route('instructor.courses') }}" class="me-2" data-bs-toggle="tooltip" title="{{ get_phrase('Clear') }}"><i class="fi-rr-cross-circle text-dark"></i></a>
                        @endif
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <form action="{{ route('instructor.courses') }}" method="get">
                            <div class="row row-gap-3">
                                <div class="col-md-9">
                                    <div class="search-input flex-grow-1">
                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ get_phrase('Search Title') }}" class="ol-form-control form-control" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn ol-btn-primary w-100" id="submit-button">{{ get_phrase('Search') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @if ($courses->count() > 0)
                        <div class="admin-tInfo-pagi d-flex justify-content-between align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($courses) . ' ' . get_phrase('of') . ' ' . $courses->total() . ' ' . get_phrase('data') }}
                            </p>
                        </div>
                        <div class="table-responsive overflow-auto course_list" id="course_list">
                            <table class="table eTable eTable-2 print-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{ get_phrase('Title') }}</th>
                                        <th scope="col">{{ get_phrase('Category') }}</th>
                                        <th scope="col">{{ get_phrase('Lesson & Section') }}</th>
                                        <th scope="col">{{ get_phrase('Enrolled Student') }}</th>
                                        <th scope="col" class="print-d-none">{{ get_phrase('Status') }}</th>
                                        <th scope="col">{{ get_phrase('Price') }}</th>
                                        <th scope="col" class="print-d-none">{{ get_phrase('Options') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $key => $row)
                                    @php
                                    $query = App\Models\Watch_history::where('course_id', $row->id)
                                    ->where('student_id', auth()->user()->id)
                                    ->first();

                                    $query1 = App\Models\Lesson::where('course_id', $row->id)
                                    ->orderBy('sort', 'asc')
                                    ->first();

                                    if (isset($query->watching_lesson_id) && $query->watching_lesson_id != '') {
                                    $watching_lesson_id = $query->watching_lesson_id;
                                    } elseif (isset($query1->id)) {
                                    $watching_lesson_id = $query1->id;
                                    }
                                    @endphp
                                    <tr>
                                        <th scope="row">
                                            <p class="row-number">{{ ++$key }}</p>
                                        </th>
                                        <td>
                                            <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                <div class="dAdmin_profile_name">
                                                    <h4 class="title fs-14px">
                                                        <a href="{{ route('instructor.course.edit', [$row->id, 'tab' => 'curriculum']) }}">{{ ucfirst($row->title) }}</a>
                                                    </h4>
                                                    <a href="{{ route('instructor.courses', ['instructor' => $row->user_id]) }}">
                                                        <p class="sub-title2 text-12px">{{ get_phrase('Instructor') }}: {{ get_user_info($row->user_id)->name }}</p>
                                                        <p class="sub-title2 text-12px">{{ get_phrase('Email') }}: {{ get_user_info($row->user_id)->email }}</p>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sub-title2 text-12px">
                                                <a href="{{ route('instructor.courses', ['category' => $row->category->slug ?? '']) }}">{{ category_by_course($row->category_id)->title }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sub-title2 text-12px">
                                                <a href="{{ route('instructor.course.edit', [$row->id, 'tab' => 'curriculum']) }}">
                                                    <p>{{ get_phrase('Lesson') }}: {{ lesson_count($row->id) }}</p>
                                                    <p>{{ get_phrase('Section') }}: {{ section_count($row->id) }}</p>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sub-title2 text-12px">
                                                <p>{{ course_enrollments($row->id) . ' ' . get_phrase('students') }}</p>
                                            </div>
                                        </td>
                                        <td class="print-d-none">
                                            <span class="badge bg-{{ $row->status }}">{{ get_phrase(ucfirst($row->status)) }}</span>
                                        </td>
                                        <td>
                                            <div class="dAdmin_info_name min-w-150px">
                                                @if ($row->is_paid == 0)
                                                <p class="eBadge ebg-soft-success">{{ get_phrase('Free') }}</p>
                                                @elseif($row->discount_flag == 1)
                                                <p>{{ currency($row->discounted_price) }} <del>{{ currency($row->price) }}</del></p>
                                                @else
                                                <p>{{ currency($row->price) }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="print-d-none">
                                            <div class="dropdown ol-icon-dropdown ol-icon-dropdown-transparent">
                                                <button class="btn ol-btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="fi-rr-menu-dots-vertical"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" target="_blank" href="{{ route('course.details', $row->slug) }}">{{ get_phrase('View Course On Frontend') }}</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" target="_blank" href="{{ route('course.player', ['slug' => $row->slug]) }}">{{ get_phrase('Go To Course Playing Page') }}</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('instructor.course.edit', [$row->id, 'tab' => 'basic']) }}">{{ get_phrase('Edit Course') }}</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" onclick="confirmModal('{{ route('instructor.course.duplicate', $row->id) }}')" href="javascript:void(0)">{{ get_phrase('Duplicate Course') }}</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" onclick="confirmModal('{{ route('instructor.course.delete', $row->id) }}')" href="javascript:void(0)">{{ get_phrase('Delete Course') }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="admin-tInfo-pagi d-flex justify-content-between align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($courses) . ' ' . get_phrase('of') . ' ' . $courses->total() . ' ' . get_phrase('data') }}
                            </p>
                            {{ $courses->links() }}
                        </div>
                        @else
                        @include('instructor.no_data')
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- End Instructor area -->

@endsection