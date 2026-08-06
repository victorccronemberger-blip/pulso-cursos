@extends('layouts.default')
@push('title', get_phrase('Frequently asked questions'))
@push('meta')@endpush
@section('content')

<!-- Hero Section -->
<section class="faq-hero">
    <div class="container">
        <div class="faq-breadcrumb">
            <a href="{{ route('home') }}">{{ get_phrase('Home') }}</a>
            <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                    <path d="M8.91 19.92L15.43 13.4C16.2 12.63 16.2 11.37 15.43 10.6L8.91 4.08" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <span class="current">{{ get_phrase('FAQ') }}</span>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="faq-hero-title">
                    {{ get_phrase('Frequently') }} <span>{{ get_phrase('Asked Questions') }}</span>
                </h1>
                <p class="faq-hero-subtitle">
                    {{ get_phrase('Find quick answers to common questions about our platform, courses, and services.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="faq-content-section">
    <div class="container">
        <div class="faq-content-inner">

            <div class="faq-section-label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9.09 9C9.33 8.33 9.82 7.77 10.46 7.42C11.11 7.08 11.85 6.97 12.57 7.11C13.29 7.25 13.94 7.63 14.41 8.19C14.88 8.74 15.13 9.45 15.13 10.18C15.13 12 12.38 12.92 12.38 12.92" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 17H12.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ get_phrase('Common Questions') }}
            </div>

            <h2 class="faq-section-title">{{ get_phrase('Everything You Need to Know') }}</h2>
            <p class="faq-section-subtitle">{{ get_phrase('FAQ provides quick answers to common inquiries, helping users resolve doubts efficiently.') }}</p>

            @php
            $faqs = json_decode(get_frontend_settings('website_faqs'), true);
            $faqs = count($faqs) > 0 ? $faqs : [['question' => '', 'answer' => '']];
            @endphp

            <div class="faq-accordion" id="faqAccordion">
                @foreach ($faqs as $key => $faq)
                <div class="faq-accordion-item {{ $key == 0 ? 'open' : '' }}" data-faq="{{ $key }}">
                    <div class="faq-accordion-header">
                        <p class="faq-accordion-question">{{ $faq['question'] }}</p>
                        <div class="faq-accordion-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="faq-accordion-body">
                        <p class="faq-accordion-answer">{{ $faq['answer'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="faq-cta">
    <div class="container">
        <h2 class="faq-cta-title">{{ get_phrase('Still Have Questions?') }}</h2>
        <p class="faq-cta-text">{{ get_phrase("Can't find the answer you're looking for? Feel free to reach out to our support team.") }}</p>
        <a href="{{ route('contact.us') }}" class="eBtn gradient">
          
            {{ get_phrase('Contact Support') }}
        </a>
    </div>
</section>

@endsection
@push('js')
<script>
    "use strict";
    document.querySelectorAll('.faq-accordion-item').forEach(function(item) {
        item.querySelector('.faq-accordion-header').addEventListener('click', function() {
            const isOpen = item.classList.contains('open');

            // Close all
            document.querySelectorAll('.faq-accordion-item').forEach(function(el) {
                el.classList.remove('open');
            });

            // Open clicked if it was closed
            if (!isOpen) {
                item.classList.add('open');
            }
        });
    });
</script>
@endpush