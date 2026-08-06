@extends('layouts.default')

@push('title', get_phrase('My Exams'))
@push('meta')@endpush
@push('css')@endpush

@section('content')

<!------------------- Breadcum Area Start  ------>
<section class="breadcum-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="eNtry-breadcum">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">{{ get_phrase('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ get_phrase('My Exams') }}
                            </li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('My Exams') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!------------------- Breadcum Area End  --------->


<!-------------- List Item Start   --------------->
<div class="eNtery-item">
    <div class="container">
        <div class="row">

            {{-- Left Sidebar --}}
            @include('frontend.default.student.left_sidebar')

            {{-- Main Content --}}
            <div class="col-lg-9 col-md-8">
                <div class="row">

                    @forelse ($exams as $exam)

                    @php
                    $now = now();
                    $hasStarted = $exam->start_at && $now >= \Carbon\Carbon::parse($exam->start_at);
                    $hasExpired = $exam->end_at && $now > \Carbon\Carbon::parse($exam->end_at);
                    $submission = $exam->mySubmission;
                    $isEvaluated = $submission && (isset($submission->obtained_marks) || isset($submission->annotated_pdf));
                    @endphp

                    <div class="col-lg-12 col-md-12 col-sm-6 mb-30">
                        <div class="single-feature w-100 position-relative">

                            {{-- Badge --}}
                            @if($isEvaluated)
                            <span class="badge bg-success position-absolute" style="top:12px; right:12px; z-index:1; font-size:11px; padding:5px 10px;">
                                {{ get_phrase('Evaluated') }}
                            </span>
                            @elseif($hasExpired)
                            <span class="badge bg-danger position-absolute" style="top:12px; right:12px; z-index:1; font-size:11px; padding:5px 10px;">
                                {{ get_phrase('Expired') }}
                            </span>
                            @endif

                            <div class="row align-items-center">

                                {{-- Thumbnail --}}
                                <div class="col-lg-4 col-md-4">
                                    <div class="courses-img">
                                        <img src="{{ get_image($exam->course->thumbnail) }}" alt="course-thumbnail">
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="col-lg-8 col-md-8">
                                    <div class="entry-details">

                                        <div class="entry-title en-title">
                                            <h3 class="ellipsis-2">{{ $exam->title }}</h3>
                                        </div>

                                        <ul>
                                            <li>
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6B7385" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                    <polyline points="14 2 14 8 20 8" />
                                                </svg>
                                                {{ $exam->marks }} {{ get_phrase('Marks') }}
                                            </li>
                                            <li>
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6B7385" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <polyline points="12 6 12 12 16 14" />
                                                </svg>
                                                {{ $exam->duration }} {{ get_phrase('min') }}
                                            </li>
                                        </ul>


                                        <div class="mb-5">
                                            <h3 class="ellipsis-2">
                                                {{ $exam->course->title }}
                                            </h3>

                                        </div>

                                        <div class="learn-creator">
                                            <div class="creator">
                                                <img src="{{ get_image($exam->creator->photo ?? null) }}" alt="instructor">
                                                <p><span>{{ $exam->creator->name }}</span></p>
                                            </div>
                                            <div>
                                                @if(!$hasStarted)
                                                <span class="badge bg-warning text-dark" style="padding:8px 14px; font-size:12px;">
                                                    {{ get_phrase('Not Started') }}
                                                </span>
                                                @else
                                                <a href="{{ route('my.exam.details', $exam->id) }}" class="learn-more">
                                                    {{ get_phrase('View Details') }} <i class="fa-solid fa-arrow-right-long ms-2"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    @empty

                    <div class="col-12 bg-white radius-10 py-5 shadow-lg">
                        @include('frontend.default.empty', [
                        'message' => get_phrase('No exams found.')
                        ])
                    </div>

                    @endforelse

                </div>
            </div>

            <!-- Pagination -->
            @if ($exams->count() > 0)
            <div class="entry-pagination">
                <nav aria-label="Page navigation example">
                    {{ $exams->links() }}
                </nav>
            </div>
            @endif

        </div>
    </div>
</div>
<!-------------- List Item End  --------------->

@endsection
@push('js')@endpush