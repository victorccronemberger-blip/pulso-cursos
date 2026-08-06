@extends('layouts.instructor')
@push('title', get_phrase('Exam Submissions'))
@section('content')

<!-- Header Card -->
<div class="ol-card  ">
    <div class="ol-card-body   ">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Manage Exam Submissions') }}
            </h4>

            <a href="{{ route('instructor.exams') }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi fi-rr-arrow-left"></span>
                <span>{{ get_phrase('Back') }}</span>
            </a>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="row">
    <div class="col-12">
        <div class="ol-card">
            <div class="ol-card-body p-3 mb-5">

                <!-- Toolbar: Export, Filter, Search -->
                <div class="row mt-3 mb-4">
                    <div class="col-md-6 d-flex align-items-center gap-3">
                        <!-- Export Dropdown -->
                        <div class="custom-dropdown ms-2">
                            <button class="dropdown-header btn ol-btn-light">
                                {{ get_phrase('Export') }}
                                <i class="fi-rr-file-export ms-2"></i>
                            </button>
                            <ul class="dropdown-list">
                                <li>
                                    <a class="dropdown-item export-btn" href="#" onclick="downloadPDF('.print-table', 'submission-list')"><i class="fi-rr-file-pdf"></i> {{ get_phrase('PDF') }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item export-btn" href="#" onclick="window.print();"><i class="fi-rr-print"></i> {{ get_phrase('Print') }}</a>
                                </li>
                            </ul>
                        </div>

                        <!-- Filter Dropdown -->
                        <div class="custom-dropdown dropdown-filter @if (!isset($_GET) || (isset($_GET) && count($_GET) == 0))  @endif">
                            <button class="dropdown-header btn ol-btn-light">
                                <i class="fi-rr-filter me-2"></i>
                                {{ get_phrase('Filter') }}

                                @if (isset($_GET) && count($_GET))
                                <span class="text-12px">
                                    ({{count($_GET)}})
                                </span>
                                @endif
                            </button>
                            <ul class="dropdown-list w-250px">
                                <li>
                                    <form id="filter-dropdown" action="{{ route('instructor.exam.submissions', $exam_id ?? request()->route('id')) }}" method="get">
                                        <div class="filter-option d-flex flex-column gap-3">
                                            <div>
                                                <label for="statusFilter" class="form-label ol-form-label">{{ get_phrase('Status') }}</label>
                                                <select class="form-control ol-form-control neu-select" data-toggle="select2" name="status" data-placeholder="Type to search...">
                                                    <option value="all">{{ get_phrase('All') }}</option>
                                                    <option value="pending" @if(isset($status) && $status=='pending' ) selected @endif>{{ get_phrase('Pending') }}</option>
                                                    <option value="checking" @if(isset($status) && $status=='checking' ) selected @endif>{{ get_phrase('Checking') }}</option>
                                                    <option value="checked" @if(isset($status) && $status=='checked' ) selected @endif>{{ get_phrase('Evaluated') }}</option>
                                                    <option value="published" @if(isset($status) && $status=='published' ) selected @endif>{{ get_phrase('Published') }}</option>
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
                        <a href="{{ route('instructor.exam.submissions', $exam_id ?? request()->route('id')) }}" class="me-2" data-bs-toggle="tooltip" title="{{ get_phrase('Clear') }}"><i class="fi-rr-cross-circle text-dark"></i></a>
                        @endif
                    </div>

                    <!-- Search Box -->
                    <div class="col-md-6 mt-3 mt-md-0">
                        <form action="{{ route('instructor.exam.submissions', $exam_id ?? request()->route('id')) }}" method="get">
                            <div class="row row-gap-3">
                                <div class="col-md-9">
                                    <div class="search-input flex-grow-1">
                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ get_phrase('Search Student Name or Email') }}" class="ol-form-control form-control" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn ol-btn-primary w-100" id="submit-button">{{ get_phrase('Search') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="row">
                    <div class="col-md-12">
                        @if($submissions->count() > 0)
                        <div class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($submissions) . ' ' . get_phrase('of') . ' ' . $submissions->total() . ' ' . get_phrase('data') }}
                            </p>
                        </div>

                        <div class="table-responsive overflow-auto">
                            <table class="table eTable eTable-2 print-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ get_phrase('Student Name') }}</th>
                                        <th>{{ get_phrase('Submitted At') }}</th>
                                        <th>{{ get_phrase('Status') }}</th>
                                        <th>{{ get_phrase('Marks') }}</th>
                                        <th>{{ get_phrase('Remarks') }}</th>
                                        <th class="print-d-none">{{ get_phrase('Options') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $key => $submission)
                                    <tr>
                                        <th scope="row">{{ ++$key }}</th>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ get_image(optional($submission->student)->photo) }}"
                                                    alt="student"
                                                    style="width:36px; height:36px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                                                <div>
                                                    <a href="{{ route('admin.submission.details', $submission->id) }}"
                                                        class="fw-bold text-decoration-none d-block">
                                                        {{ optional($submission->student)->name ?? 'N/A' }}
                                                    </a>
                                                    <small class="text-muted d-block">
                                                        {{ optional($submission->student)->email ?? '' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($submission->created_at)
                                            <div class="fw-semibold">
                                                {{ $submission->created_at->format('d M Y') }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $submission->created_at->format('h:i A') }}
                                            </small>
                                            @else
                                            {{ get_phrase('N/A') }}
                                            @endif
                                        </td>

                                        <td>
                                            @if($submission->status === 'published')
                                            <span class="badge bg-primary" style="background-color:#dbeafe; font-weight:500; border-radius:8px; padding:6px 12px;">{{ get_phrase('Published') }}</span>
                                            @elseif($submission->status === 'checked')
                                            <span class="badge bg-success" style="background-color:#dcfce7;  font-weight:500; border-radius:8px; padding:6px 12px;">{{ get_phrase('Evaluated') }}</span>
                                            @elseif($submission->status === 'checking')
                                            <span class="badge bg-info" style="background-color:#ede9fe;  font-weight:500; border-radius:8px; padding:6px 12px;">{{ get_phrase('Checking') }}</span>
                                            @elseif($submission->status === 'pending')
                                            <span class="badge bg-warning" style="background-color:#fef9c3;  font-weight:500; border-radius:8px; padding:6px 12px;">{{ get_phrase('Pending') }}</span>
                                            @else
                                            <span class="badge bg-primary" style="background-color:#f3f4f6;  font-weight:500; border-radius:8px; padding:6px 12px;">{{ $submission->status }}</span>
                                            @endif
                                        </td>

                                        <td>{{ $submission->obtained_marks ?? '-' }}</td>
                                        <td>
                                            @if($submission->remarks)
                                            <a href="#" class="remarks-link" data-remarks="{{ $submission->remarks }}">
                                                {{ \Illuminate\Support\Str::words($submission->remarks, 5, '...') }}
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="print-d-none">
                                            <div class="dropdown ol-icon-dropdown ol-icon-dropdown-transparent">
                                                <button class="btn ol-btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="fi-rr-menu-dots-vertical"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('instructor.submission.details', $submission->id) }}">{{ get_phrase('View Details') }}</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" onclick="confirmModal('{{ route('instructor.submission.delete', $submission->id) }}')" href="javascript:void(0)">{{ get_phrase('Delete Submission') }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Remarks Modal -->
                            <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ get_phrase('Remarks') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="remarksContent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($submissions) . ' ' . get_phrase('of') . ' ' . $submissions->total() . ' ' . get_phrase('data') }}
                            </p>
                            {{ $submissions->links() }}
                        </div>
                        @else
                        @include('instructor.no_data', ['message' => get_phrase('No submissions found for this exam.')])
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JS to handle remarks click -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.remarks-link');
        const modal = new bootstrap.Modal(document.getElementById('remarksModal'));
        const content = document.getElementById('remarksContent');

        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                content.textContent = this.dataset.remarks;
                modal.show();
            });
        });
    });
</script>

@endsection