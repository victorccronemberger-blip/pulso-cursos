@extends('layouts.default')
@push('title', get_phrase('Instructor details'))
@push('meta')@endpush
@section('content')

<!-- Hero Section -->
<section class="instructor-hero">
    <div class="container">
        <div class="instructor-breadcrumb">
            <a  href="{{ route('home') }}">{{ get_phrase('Home') }}</a>
            <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                    <path d="M8.91 19.92L15.43 13.4C16.2 12.63 16.2 11.37 15.43 10.6L8.91 4.08" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span class="current">{{ get_phrase('Instructor Details') }}</span>
        </div>

        <div class="instructor-profile-card">

            <!-- Avatar -->
            <div class="instructor-avatar-wrap">
                <img class="instructor-avatar" src="{{ get_image($instructor_details->photo) }}" alt="instructor-photo">
                <div class="instructor-avatar-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                        <path d="M7.75 12L10.58 14.83L16.25 9.17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>

            <!-- Info -->
            <div class="instructor-hero-info">
                <h1 class="instructor-hero-name">
                    {{ get_phrase('Hi, I\'m') }} <span>{{ $instructor_details->name }}</span>
                </h1>

                @if($instructor_details->skill)
                <div class="instructor-skill-badge">{{ $instructor_details->skill }}</div>
                @endif

                @if($instructor_details->boigraphy)
                <p class="instructor-hero-bio">{{ $instructor_details->boigraphy }}</p>
                @endif

                <!-- Stats -->
                <div class="instructor-stats-row">
                    <div class="instructor-stat">
                        <span class="instructor-stat-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M17 21V19C17 17.93 16.58 16.9 15.83 16.17C15.08 15.42 14.07 15 13 15H5C3.93 15 2.9 15.42 2.17 16.17C1.42 16.9 1 17.93 1 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9 11C11.21 11 13 9.21 13 7C13 4.79 11.21 3 9 3C6.79 3 5 4.79 5 7C5 9.21 6.79 11 9 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="instructor-stat-text">
                            <strong>{{ count_student_by_instructor($instructor_details->id) }}</strong>
                        </span>
                    </div>
                    <div class="instructor-stat">
                        <span class="instructor-stat-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M22 16.74V4.67C22 3.47 21.02 2.58 19.83 2.68H19.77C17.67 2.86 14.48 3.93 12.7 5.05L12.53 5.16C12.24 5.34 11.76 5.34 11.47 5.16L11.22 5.01C9.44 3.9 6.26 2.84 4.16 2.67C2.97 2.57 2 3.47 2 4.66V16.74C2 17.7 2.78 18.6 3.74 18.72L4.03 18.76C6.2 19.05 9.55 20.15 11.47 21.2L11.51 21.22C11.78 21.37 12.21 21.37 12.47 21.22C14.39 20.16 17.75 19.05 19.93 18.76L20.26 18.72C21.22 18.6 22 17.7 22 16.74Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 5.49V20.49" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="instructor-stat-text">
                            <strong>{{ count_course_by_instructor($instructor_details->id) }}</strong>
                        </span>
                    </div>
                    <div class="instructor-stat">
                        <span class="instructor-stat-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M17 3V9L19 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M17 9L15 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="instructor-stat-text">
                            <strong>{{ instructor_experience($instructor_details->id) }}</strong>
                        </span>
                    </div>
                </div>

                <!-- Meta -->
                <ul class="instructor-meta-list">
                    @if($instructor_details->email)
                    <li class="instructor-meta-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <path d="M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M17 9L13.87 11.5C12.84 12.32 11.15 12.32 10.12 11.5L7 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <strong>{{ get_phrase('Email') }}:</strong> {{ $instructor_details->email }}
                    </li>
                    @endif
                    @if($instructor_details->phone)
                    <li class="instructor-meta-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <path d="M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M6 8.5L9.5 11C10.59 11.82 13.41 11.82 14.5 11L18 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <strong>{{ get_phrase('Phone') }}:</strong> {{ $instructor_details->phone }}
                    </li>
                    @endif
                    @if($instructor_details->details)
                    <li class="instructor-meta-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <path d="M12 13.43C13.7231 13.43 15.12 12.0331 15.12 10.31C15.12 8.58687 13.7231 7.19 12 7.19C10.2769 7.19 8.88 8.58687 8.88 10.31C8.88 12.0331 10.2769 13.43 12 13.43Z" stroke="currentColor" stroke-width="1.5" />
                            <path d="M3.62 8.49C5.59 -0.169998 18.42 -0.159997 20.38 8.5C21.53 13.58 18.37 17.88 15.6 20.54C13.59 22.48 10.41 22.48 8.39 20.54C5.63 17.88 2.47 13.57 3.62 8.49Z" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <strong>{{ get_phrase('Location') }}:</strong> {{ $instructor_details->details }}
                    </li>
                    @endif
                </ul>

                <!-- Socials -->
                <ul class="instructor-socials">
                    <li>
                        <a href="{{ $instructor_details->twitter ?? 'javascript: void(0);' }}" title="Twitter/X">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $instructor_details->facebook ?? 'javascript: void(0);' }}" title="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $instructor_details->linkedin ?? 'javascript: void(0);' }}" title="LinkedIn">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

@if (count($instructor_courses) > 0)
<section class="instructor-courses-section">
    <div class="container">
        <div class="instructor-courses-header">
            <div>
                <div class="instructor-courses-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M22 16.74V4.67C22 3.47 21.02 2.58 19.83 2.68H19.77C17.67 2.86 14.48 3.93 12.7 5.05L12.53 5.16C12.24 5.34 11.76 5.34 11.47 5.16L11.22 5.01C9.44 3.9 6.26 2.84 4.16 2.67C2.97 2.57 2 3.47 2 4.66V16.74C2 17.7 2.78 18.6 3.74 18.72L4.03 18.76C6.2 19.05 9.55 20.15 11.47 21.2L11.51 21.22C11.78 21.37 12.21 21.37 12.47 21.22C14.39 20.16 17.75 19.05 19.93 18.76L20.26 18.72C21.22 18.6 22 17.7 22 16.74Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 5.49V20.49" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ get_phrase('Courses') }}
                </div>
                <h2 class="instructor-courses-title">{{ get_phrase('My Courses') }}</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            @foreach ($instructor_courses as $course)
            @include('frontend.default.course.course_grid', ['course' => $course])
            @endforeach
        </div>

        <div class="entry-pagination mt-4">
            <nav aria-label="Page navigation">
                {{ $instructor_courses->links() }}
            </nav>
        </div>
    </div>
</section>
@else
@include('frontend.default.empty')
@endif

@endsection