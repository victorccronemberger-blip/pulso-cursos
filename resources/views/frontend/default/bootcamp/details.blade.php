@extends('layouts.default')
@push('title', get_phrase('Bootcamp Details'))
@push('meta')@endpush
@push('css')

@endpush
@section('content')
<!------------------- Breadcum Area Start  ------>
<section class="breadcum-area playing-breadcum">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 px-4">
                <div class="eNtry-breadcum mt-5">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ get_phrase('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $bootcamp_details->title }}</li>
                        </ol>
                    </nav>
                </div>

                <div class="course-details pe-auto pe-lg-5">
                    <h2 class="g-title ellipsis-line-2">{{ $bootcamp_details->title }}</h2>
                    <p class="g-text text-white ellipsis-line-2">
                        {{ $bootcamp_details->short_description }}
                    </p>

                    @php
                    $user = get_user_info($bootcamp_details->user_id);
                    @endphp

                    <ul class="course-motion-top">
                        <li>
                            <a class="d-flex align-items-center text-white" href="{{ route('instructor.details', ['name' => slugify($user->name), 'id' => $user->id]) }}">
                                <img class="pro-32" src="{{ get_image($user->photo) }}" alt="instructor-image">
                                {{ $user->name }}
                            </a>
                        </li>
                        <li class="text-white">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.33 10C18.33 14.6 14.6 18.33 10 18.33C5.4 18.33 1.67 14.6 1.67 10C1.67 5.4 5.4 1.67 10 1.67C14.6 1.67 18.33 5.4 18.33 10Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.09 12.65L10.5 11.11C10.05 10.84 9.69 10.2 9.69 9.67V6.26" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ date('d-M-Y', $bootcamp_details->publish_date) }}
                        </li>
                        <li class="text-white">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 5.5C2 3.57 3.57 2 5.5 2H14.5C16.43 2 18 3.57 18 5.5V14.5C18 16.43 16.43 18 14.5 18H13.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.08 9.76C6.93 10.25 9.75 13.08 10.25 16.93" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2.19 12.56C5.01 12.92 7.09 15 7.45 17.83" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M1.65 15.72C3.06 15.9 4.1 16.93 4.29 18.35" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ count_bootcamp_modules($bootcamp_details->id) }} {{ get_phrase(count_bootcamp_modules($bootcamp_details->id) > 1 ? 'Modules' : 'Module') }}
                        </li>
                        <li class="text-white">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.67 7.5V6.67C1.67 4.17 3.34 2.5 5.84 2.5H14.17C16.67 2.5 18.34 4.17 18.34 6.67V13.33C18.34 15.83 16.67 17.5 14.17 17.5H13.34" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.08 9.76C6.93 10.25 9.75 13.08 10.25 16.93" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2.19 12.56C5.01 12.92 7.09 15 7.45 17.83" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M1.65 15.72C3.06 15.9 4.1 16.93 4.29 18.35" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ count_bootcamp_classes($bootcamp_details->id) }} {{ get_phrase(count_bootcamp_classes($bootcamp_details->id) > 1 ? 'Classes' : 'Class') }}
                        </li>
                        <li class="text-white">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 21V19C17 17.93 16.58 16.9 15.83 16.17C15.08 15.42 14.07 15 13 15H5C3.93 15 2.9 15.42 2.17 16.17C1.42 16.9 1 17.93 1 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9 11C11.21 11 13 9.21 13 7C13 4.79 11.21 3 9 3C6.79 3 5 4.79 5 7C5 9.21 6.79 11 9 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M23 21V19C23 18.11 22.7 17.26 22.15 16.57C21.6 15.88 20.83 15.4 19.97 15.18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16 3.13C16.87 3.35 17.64 3.83 18.19 4.52C18.74 5.21 19.04 6.07 19.04 6.96C19.04 7.85 18.74 8.71 18.19 9.4C17.64 10.09 16.87 10.57 16 10.79" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ total_enroll($bootcamp_details->id) }} {{ get_phrase('Students') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!------------------- Breadcum Area End  --------->

<!-- Modal -->
<div class="modal eModal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="g-title">{{ ucfirst($bootcamp_details->title) }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="player-body">
                    <div class="plyr__video-embed" id="player">
                        <video width="100%" height="440" poster="" id="videoPlayer" playsinline controls>
                            <source src="{{ asset($bootcamp_details->preview) }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!------------------- Player Feature Area Start  --------->
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 px-4">
                <div class="hero-details pe-auto pe-lg-5">
                    <img src="{{ get_image($bootcamp_details->thumbnail) }}" alt="...">
                </div>

                <div class="row">
                    <div class="col-lg-12 pe-auto pe-lg-5">

                        <!-- Tab Track -->
                        <div class="zPill-wrapper mt-5">
                            <ul class="zPill-track" id="bootcampPillTab">
                                <li class="zPill-item">
                                    <button class="zPill-btn zPill-active" data-zpill-target="bc-overview" type="button">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M7 12H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M7 8.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M7 15.5H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{ get_phrase('Overview') }}
                                    </button>
                                </li>
                                <li class="zPill-item">
                                    <button class="zPill-btn" data-zpill-target="bc-course-content" type="button">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M22 16.74V4.67C22 3.47 21.02 2.58 19.83 2.68H19.77C17.67 2.86 14.48 3.93 12.7 5.05L12.53 5.16C12.24 5.34 11.76 5.34 11.47 5.16L11.22 5.01C9.44 3.9 6.26 2.84 4.16 2.67C2.97 2.57 2 3.47 2 4.66V16.74C2 17.7 2.78 18.6 3.74 18.72L4.03 18.76C6.2 19.05 9.55 20.15 11.47 21.2L11.51 21.22C11.78 21.37 12.21 21.37 12.47 21.22C14.39 20.16 17.75 19.05 19.93 18.76L20.26 18.72C21.22 18.6 22 17.7 22 16.74Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 5.49V20.49" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{ get_phrase('Course Content') }}
                                    </button>
                                </li>
                                <li class="zPill-item">
                                    <button class="zPill-btn" data-zpill-target="bc-details" type="button">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21 7V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V7C3 4 4.5 2 8 2H16C19.5 2 21 4 21 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M14.5 4.5V6.5C14.5 7.6 15.4 8.5 16.5 8.5H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M8 13H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M8 17H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{ get_phrase('Details') }}
                                    </button>
                                </li>
                                <li class="zPill-item">
                                    <button class="zPill-btn" data-zpill-target="bc-instructor" type="button">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M20.5899 22C20.5899 18.13 16.7399 15 11.9999 15C7.25991 15 3.40991 18.13 3.40991 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{ get_phrase('Instructor') }}
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Tab Panels -->
                        <div class="zPill-content">
                            <div class="zPill-panel zPill-panel-active" id="bc-overview">
                                @include('frontend.default.bootcamp.overview_area')
                            </div>
                            <div class="zPill-panel" id="bc-course-content">
                                @include('frontend.default.bootcamp.content_area')
                            </div>
                            <div class="zPill-panel" id="bc-details">
                                @include('frontend.default.bootcamp.requirement_outcome_area')
                            </div>
                            <div class="zPill-panel" id="bc-instructor">
                                @include('frontend.default.bootcamp.instructor_area')
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 px-4">
                @include('frontend.default.bootcamp.pricing_card')
            </div>
        </div>
    </div>
</section>
<!------------------- Player Feature Area End  --------->
@endsection

@push('js')
<script>
    "use strict";

    // See more / See less
    jQuery(function($) {
        $('#more_description').click(function(e) {
            e.preventDefault();
            let ellipsis = $('.description').attr('id');
            $('.description').toggleClass(ellipsis);
            $(this).toggleClass('active');
            if ($(this).hasClass('active')) {
                $(this).text('See less');
            } else {
                $(this).html('See more <i class="fa-solid fa-angle-right me-2"></i>');
            }
        });

        $('#exampleModal').on('hidden.bs.modal', function() {
            $('#videoPlayer').get(0).pause();
        });
    });

    /* ════════════════════════════════════════════════════════
       zPill — bootcamp tab engine (zero Bootstrap)
    ════════════════════════════════════════════════════════ */
    (function() {

        function init() {
            var track = document.getElementById('bootcampPillTab');
            if (!track) return;

            var buttons = Array.from(track.querySelectorAll('button.zPill-btn'));
            var panels = Array.from(document.querySelectorAll('.zPill-panel'));
            if (!buttons.length || !panels.length) return;

            /* ── Create glider ── */
            var glider = document.createElement('div');
            glider.className = 'zPill-glider';
            track.appendChild(glider);

            /* ── Snap glider (no animation) ── */
            function snapGlider(btn) {
                var tr = track.getBoundingClientRect();
                var br = btn.getBoundingClientRect();
                glider.style.transition = 'none';
                glider.style.left = (br.left - tr.left) + 'px';
                glider.style.width = br.width + 'px';
                glider.offsetHeight; /* force reflow */
                glider.style.transition = '';
            }

            /* ── Slide glider (with animation) ── */
            function slideGlider(btn) {
                var tr = track.getBoundingClientRect();
                var br = btn.getBoundingClientRect();
                glider.style.left = (br.left - tr.left) + 'px';
                glider.style.width = br.width + 'px';
            }

            /* ── Switch tab ── */
            function switchTo(btn, animate) {
                /* Update buttons */
                buttons.forEach(function(b) {
                    b.classList.remove('zPill-active');
                });
                btn.classList.add('zPill-active');

                /* Move glider */
                animate ? slideGlider(btn) : snapGlider(btn);

                /* Scroll into view on mobile */
                btn.scrollIntoView({
                    block: 'nearest',
                    inline: 'nearest',
                    behavior: 'smooth'
                });

                /* Show panel */
                var targetId = btn.getAttribute('data-zpill-target');
                panels.forEach(function(p) {
                    if (p.id === targetId) {
                        p.classList.add('zPill-panel-active');
                    } else {
                        p.classList.remove('zPill-panel-active');
                    }
                });
            }

            /* ── Click listeners ── */
            buttons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    switchTo(btn, true);
                });
            });

            /* ── Initial active tab ── */
            var initBtn = track.querySelector('button.zPill-active') || buttons[0];
            requestAnimationFrame(function() {
                switchTo(initBtn, false);
            });

            /* ── Recalculate on resize ── */
            window.addEventListener('resize', function() {
                var active = track.querySelector('button.zPill-active') || buttons[0];
                snapGlider(active);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

    })();
</script>
@endpush