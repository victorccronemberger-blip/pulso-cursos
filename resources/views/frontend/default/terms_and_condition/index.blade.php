@extends('layouts.default')
@push('title', get_phrase('Terms and condition'))
@push('meta')@endpush
@push('css')@endpush

@section('content')

<!-- Hero Section -->
<section class="refund-hero">
    <div class="container">
        <div class="refund-breadcrumb">
            <a href="{{ route('home') }}">{{ get_phrase('Home') }}</a>
            <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                    <path d="M8.91 19.92L15.43 13.4C16.2 12.63 16.2 11.37 15.43 10.6L8.91 4.08" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span class="current">{{ get_phrase('Terms And Condition') }}</span>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="refund-hero-title">
                    {{ get_phrase('Terms And') }} <span>{{ get_phrase('Condition') }}</span>
                </h1>
                <p class="refund-hero-subtitle">
                    {{ get_phrase('Please read these terms and conditions carefully before using our platform. By accessing our services, you agree to be bound by these terms.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Info Cards + Content -->
<section class="refund-content-section">
    <div class="container">
        <div class="refund-content-inner">

            <div class="refund-info-cards">
                <div class="refund-info-card">
                    <div class="refund-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.75 12L10.58 14.83L16.25 9.17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h4 class="refund-info-title">{{ get_phrase('Acceptance') }}</h4>
                    <p class="refund-info-text">{{ get_phrase('By using our platform, you confirm that you have read, understood, and agree to these terms and conditions.') }}</p>
                </div>

                <div class="refund-info-card">
                    <div class="refund-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.75 12H16.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.75 8.5H16.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.75 15.5H12.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h4 class="refund-info-title">{{ get_phrase('Usage Policy') }}</h4>
                    <p class="refund-info-text">{{ get_phrase('Our terms outline the rules and guidelines for using our services responsibly and lawfully.') }}</p>
                </div>

                <div class="refund-info-card">
                    <div class="refund-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M17 9L13.87 11.5C12.84 12.32 11.15 12.32 10.12 11.5L7 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h4 class="refund-info-title">{{ get_phrase('Contact Us') }}</h4>
                    <p class="refund-info-text">{{ get_phrase('If you have any questions regarding our terms and conditions, feel free to reach out to our support team.') }}</p>
                </div>
            </div>

            <div class="refund-section-label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M21 7V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V7C3 4 4.5 2 8 2H16C19.5 2 21 4 21 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14.5 4.5V6.5C14.5 7.6 15.4 8.5 16.5 8.5H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M8 13H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M8 17H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ get_phrase('Full Terms') }}
            </div>

            <div class="refund-description-style">
                {!! htmlspecialchars_decode(removeScripts(get_frontend_settings('terms_and_condition'))) !!}
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="refund-cta">
    <div class="container">
        <h2 class="refund-cta-title">{{ get_phrase('Have Questions About Our Terms?') }}</h2>
        <p class="refund-cta-text">{{ get_phrase('If you have any questions or concerns about our terms and conditions, our support team is here to help.') }}</p>
        <a href="{{ route('contact.us') }}" class="eBtn gradient">
            {{ get_phrase('Contact Support') }}
        </a>
    </div>
</section>

@endsection
@push('js')@endpush