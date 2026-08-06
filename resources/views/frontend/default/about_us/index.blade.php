@extends('layouts.default')
@push('title', get_phrase('About Us'))
@push('meta')@endpush
@push('css')
@endpush
@section('content')

<!-- Hero Section -->
<section class="about-hero">
    <div class="container">
        <div class="about-breadcrumb">
            <a href="{{ route('home') }}">
                {{ get_phrase('Home') }}
            </a>
            <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                    <path d="M8.91 19.92L15.43 13.4C16.2 12.63 16.2 11.37 15.43 10.6L8.91 4.08" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span class="current">{{ get_phrase('About Us') }}</span>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="about-hero-title">
                    {{ get_phrase('Who') }} {{ get_phrase('We') }} <span>{{ get_phrase('Are') }}</span>
                </h1>
                <p class="about-hero-subtitle">
                    {{ get_phrase('We are passionate about transforming education and empowering learners worldwide with world-class knowledge and skills.') }}
                </p>

                <div class="about-stats-bar">
                    <div class="about-stat-item">
                        <span class="about-stat-number">500+</span>
                        <span class="about-stat-label">{{ get_phrase('Courses') }}</span>
                    </div>
                    <div class="about-stat-divider"></div>
                    <div class="about-stat-item">
                        <span class="about-stat-number">10K+</span>
                        <span class="about-stat-label">{{ get_phrase('Students') }}</span>
                    </div>
                    <div class="about-stat-divider"></div>
                    <div class="about-stat-item">
                        <span class="about-stat-number">100+</span>
                        <span class="about-stat-label">{{ get_phrase('Instructors') }}</span>
                    </div>
                    <div class="about-stat-divider"></div>
                    <div class="about-stat-item">
                        <span class="about-stat-number">50+</span>
                        <span class="about-stat-label">{{ get_phrase('Countries') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feature Cards -->
<section class="about-features">
    <div class="container">
        <div class="text-center">
            <div class="about-section-label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ get_phrase('Why Choose Us') }}
            </div>
            <h2 class="about-section-title">{{ get_phrase('Built for Learners, By Educators') }}</h2>
            <p class="about-section-subtitle mx-auto">{{ get_phrase('Everything we do is designed to make your learning journey seamless, effective, and enjoyable.') }}</p>
        </div>

        <div class="about-features-grid">
            <div class="about-feature-card">
                <div class="about-feature-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M22 16.74V4.67C22 3.47 21.02 2.58 19.83 2.68H19.77C17.67 2.86 14.48 3.93 12.7 5.05L12.53 5.16C12.24 5.34 11.76 5.34 11.47 5.16L11.22 5.01C9.44 3.9 6.26 2.84 4.16 2.67C2.97 2.57 2 3.47 2 4.66V16.74C2 17.7 2.78 18.6 3.74 18.72L4.03 18.76C6.2 19.05 9.55 20.15 11.47 21.2L11.51 21.22C11.78 21.37 12.21 21.37 12.47 21.22C14.39 20.16 17.75 19.05 19.93 18.76L20.26 18.72C21.22 18.6 22 17.7 22 16.74Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 5.49V20.49" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="about-feature-title">{{ get_phrase('Expert-Led Courses') }}</h4>
                <p class="about-feature-text">{{ get_phrase('Learn from industry professionals with real-world experience and proven expertise in their fields.') }}</p>
            </div>

            <div class="about-feature-card">
                <div class="about-feature-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7.75 12L10.58 14.83L16.25 9.17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="about-feature-title">{{ get_phrase('Certified Learning') }}</h4>
                <p class="about-feature-text">{{ get_phrase('Earn recognized certificates upon course completion to boost your career and professional profile.') }}</p>
            </div>

            <div class="about-feature-card">
                <div class="about-feature-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M17 3V9L19 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M17 9L15 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="about-feature-title">{{ get_phrase('Learn at Your Pace') }}</h4>
                <p class="about-feature-text">{{ get_phrase('Access course materials anytime, anywhere. Study on your own schedule with lifetime access.') }}</p>
            </div>

            <div class="about-feature-card">
                <div class="about-feature-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M17 21V19C17 17.93 16.58 16.9 15.83 16.17C15.08 15.42 14.07 15 13 15H5C3.93 15 2.9 15.42 2.17 16.17C1.42 16.9 1 17.93 1 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9 11C11.21 11 13 9.21 13 7C13 4.79 11.21 3 9 3C6.79 3 5 4.79 5 7C5 9.21 6.79 11 9 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M23 21V19C23 18.11 22.7 17.26 22.15 16.57C21.6 15.88 20.83 15.4 19.97 15.18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 3.13C16.87 3.35 17.64 3.83 18.19 4.52C18.74 5.21 19.04 6.07 19.04 6.96C19.04 7.85 18.74 8.71 18.19 9.4C17.64 10.09 16.87 10.57 16 10.79" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="about-feature-title">{{ get_phrase('Vibrant Community') }}</h4>
                <p class="about-feature-text">{{ get_phrase('Join thousands of learners and instructors in a supportive, engaging learning community.') }}</p>
            </div>

            <div class="about-feature-card">
                <div class="about-feature-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 12H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 8.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M7 15.5H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="about-feature-title">{{ get_phrase('Rich Course Content') }}</h4>
                <p class="about-feature-text">{{ get_phrase('Dive into comprehensive curricula with videos, quizzes, assignments and hands-on projects.') }}</p>
            </div>

            <div class="about-feature-card">
                <div class="about-feature-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="about-feature-title">{{ get_phrase('Top Rated Platform') }}</h4>
                <p class="about-feature-text">{{ get_phrase('Consistently rated as one of the best e-learning platforms by students and educators worldwide.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="about-content-section">
    <div class="container">
        <div class="about-content-inner">
            <div class="about-section-label mb-5">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="currentColor" stroke-width="1.5" />
                    <path d="M12 8V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M11.9945 16H12.0035" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                {{ get_phrase('Our Story') }}
            </div>
            <div class="about-description-style">
                {!! htmlspecialchars_decode(removeScripts(get_frontend_settings('about_us'))) !!}
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="about-cta">
    <div class="container position-relative text-center">

        <h2 class="about-cta-title">
            {{ get_phrase('Ready to Start Learning?') }}
        </h2>

        <p class="about-cta-text">
            {{ get_phrase('Join thousands of students already learning on our platform today.') }}
        </p>

        <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">

            <a href="{{ route('courses') }}" class="eBtn gradient">
                {{ get_phrase('Explore Courses') }}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M22 16.74V4.67C22 3.47 21.02 2.58 19.83 2.68H19.77C17.67 2.86 14.48 3.93 12.7 5.05L12.53 5.16C12.24 5.34 11.76 5.34 11.47 5.16L11.22 5.01C9.44 3.9 6.26 2.84 4.16 2.67C2.97 2.57 2 3.47 2 4.66V16.74C2 17.7 2.78 18.6 3.74 18.72L4.03 18.76C6.2 19.05 9.55 20.15 11.47 21.2L11.51 21.22C11.78 21.37 12.21 21.37 12.47 21.22C14.39 20.16 17.75 19.05 19.93 18.76L20.26 18.72C21.22 18.6 22 17.7 22 16.74Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 5.49V20.49" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <a href="{{ route('register') }}" class="btn-light">
                {{ get_phrase('Get Started Free') }}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M8.91 19.92L15.43 13.4C16.2 12.63 16.2 11.37 15.43 10.6L8.91 4.08" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

        </div>
    </div>
</section>


@endsection
@push('js')@endpush