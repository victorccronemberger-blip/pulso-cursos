@extends('layouts.default')
@push('title', get_phrase('Cookie Policy'))
@push('meta')@endpush
@section('content')

<!-- Hero Section -->
<section class="cookie-hero">
    <div class="container">
        <div class="cookie-breadcrumb">
            <a href="{{ route('home') }}">{{ get_phrase('Home') }}</a>
            <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                    <path d="M8.91 19.92L15.43 13.4C16.2 12.63 16.2 11.37 15.43 10.6L8.91 4.08" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span class="current">{{ get_phrase('Cookie Policy') }}</span>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="cookie-hero-title">
                    {{ get_phrase('Cookie') }} <span>{{ get_phrase('Policy') }}</span>
                </h1>
                <p class="cookie-hero-subtitle">
                    {{ get_phrase('Learn how we use cookies to improve your experience on our platform and how you can manage your preferences.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Info Cards -->
<section class="cookie-content-section">
    <div class="container">
        <div class="cookie-content-inner">

            <div class="cookie-info-cards">
                <div class="cookie-info-card">
                    <div class="cookie-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 8V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M11.9945 16H12.0035" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h4 class="cookie-info-title">{{ get_phrase('What Are Cookies') }}</h4>
                    <p class="cookie-info-text">{{ get_phrase('Small text files stored on your device to help us remember your preferences and improve your experience.') }}</p>
                </div>

                <div class="cookie-info-card">
                    <div class="cookie-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7 12H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7 8.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7 15.5H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h4 class="cookie-info-title">{{ get_phrase('How We Use Them') }}</h4>
                    <p class="cookie-info-text">{{ get_phrase('We use cookies to analyze traffic, personalize content, and enhance your overall learning experience.') }}</p>
                </div>

                <div class="cookie-info-card">
                    <div class="cookie-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.75 12L10.58 14.83L16.25 9.17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h4 class="cookie-info-title">{{ get_phrase('Your Control') }}</h4>
                    <p class="cookie-info-text">{{ get_phrase('You can manage or disable cookies at any time through your browser settings without affecting core functionality.') }}</p>
                </div>
            </div>

            <div class="cookie-section-label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M21 7V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V7C3 4 4.5 2 8 2H16C19.5 2 21 4 21 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14.5 4.5V6.5C14.5 7.6 15.4 8.5 16.5 8.5H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M8 13H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M8 17H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ get_phrase('Full Policy') }}
            </div>

            <div class="cookie-description-style">
                {!! htmlspecialchars_decode(removeScripts(get_frontend_settings('cookie_policy'))) !!}
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="cookie-cta">
    <div class="container">
        <h2 class="cookie-cta-title">{{ get_phrase('Have Questions About Our Cookie Policy?') }}</h2>
        <p class="cookie-cta-text">{{ get_phrase('Feel free to reach out to us if you have any questions or concerns about how we use cookies.') }}</p>
        <a href="{{ route('contact.us') }}" class="eBtn gradient">
           
            {{ get_phrase('Contact Us') }}
        </a>
    </div>
</section>

@endsection
@push('js')@endpush