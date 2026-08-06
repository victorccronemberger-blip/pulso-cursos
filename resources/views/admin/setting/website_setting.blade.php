@extends('layouts.admin')
@push('title', get_phrase('Website settings'))
@push('meta')@endpush

@section('content')

<!-- Page header -->
<div class="ol-card">
    <div class="ol-card-body">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Website Settings') }}
            </h4>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="ol-card p-4">
            <div class="ol-card-body">
                <div class="col-md-12 pb-3">

                    <!-- Tab Track -->
                    <div class="zPill-wrapper">
                        <ul class="zPill-track" id="zPillTab">

                            <li class="zPill-item">
                                <button class="zPill-btn zPill-active" data-zpill-target="cHome" type="button">
                                    {{ get_phrase('Frontend Settings') }}
                                </button>
                            </li>
                            <li class="zPill-item">
                                <button class="zPill-btn" data-zpill-target="cMessage" type="button">
                                    {{ get_phrase('Motivational Speech') }}
                                </button>
                            </li>
                            <li class="zPill-item">
                                <button class="zPill-btn" data-zpill-target="cSettings" type="button">
                                    {{ get_phrase('Website FAQS') }}
                                </button>
                            </li>
                            <li class="zPill-item">
                                <button class="zPill-btn" data-zpill-target="contact_information" type="button">
                                    {{ get_phrase('Contact Information') }}
                                </button>
                            </li>
                            <li class="zPill-item">
                                <button class="zPill-btn" data-zpill-target="recaptcha" type="button">
                                    {{ get_phrase('Recaptcha') }}
                                </button>
                            </li>
                            <li class="zPill-item">
                                <button class="zPill-btn" data-zpill-target="reviews-tab" type="button">
                                    {{ get_phrase('User Reviews') }}
                                </button>
                            </li>
                            <li class="zPill-item">
                                <button class="zPill-btn" data-zpill-target="logo_images" type="button">
                                    {{ get_phrase('Logo & Images') }}
                                </button>
                            </li>

                        </ul>
                    </div><!-- /.zPill-wrapper -->

                    <!-- Tab Panels -->
                    <div class="zPill-content">

                        <div class="zPill-panel zPill-panel-active" id="cHome">
                            @include('admin.setting.frontend_setting')
                        </div>

                        <div class="zPill-panel" id="cMessage">
                            @include('admin.setting.motivational')
                        </div>

                        <div class="zPill-panel" id="cSettings">
                            @include('admin.setting.webfaqs')
                        </div>

                        <div class="zPill-panel" id="contact_information">
                            @include('admin.setting.contact_information')
                        </div>

                        <div class="zPill-panel" id="recaptcha">
                            @include('admin.setting.recaptcha')
                        </div>

                        <div class="zPill-panel" id="reviews-tab">
                            @include('admin.setting.user_review_list')
                        </div>

                        <div class="zPill-panel" id="logo_images">
                            @include('admin.setting.logo_image')
                        </div>

                    </div><!-- /.zPill-content -->

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    "use strict";

    /* ── FAQ / Speech helpers ── */
    var blank_faq, blank_motivational_speech;

    jQuery(function($) {
        blank_faq = $('#blank_faq_field').html();
        blank_motivational_speech = $('#blank_motivational_speech_field').html();
        $('#blank_faq_field').hide();
        $('#blank_motivational_speech_field').hide();
    });

    function appendFaq() {
        jQuery('#faq_area').append(blank_faq);
    }

    function removeFaq(el) {
        jQuery(el).closest('tr, .faq-row').remove();
    }

    function appendMotivational_speech() {
        jQuery('#motivational_speech_area').append(blank_motivational_speech);
    }

    function removeMotivational_speech(el) {
        jQuery(el).closest('tr, .speech-row').remove();
    }

    /* ════════════════════════════════════════════════════════
       zPill — pure vanilla tab engine (zero Bootstrap)
    ════════════════════════════════════════════════════════ */
    (function() {

        function init() {
            var track = document.getElementById('zPillTab');
            if (!track) return;

            var buttons = Array.from(track.querySelectorAll('button.zPill-btn'));
            var panels = Array.from(document.querySelectorAll('.zPill-panel'));
            if (!buttons.length || !panels.length) return;

            /* ── Create and append glider ── */
            var glider = document.createElement('div');
            glider.className = 'zPill-glider';
            track.appendChild(glider);

            /* ── Position glider with no animation (on init / resize) ── */
            function snapGlider(btn) {
                var tr = track.getBoundingClientRect();
                var br = btn.getBoundingClientRect();
                glider.style.transition = 'none';
                glider.style.left = (br.left - tr.left) + 'px';
                glider.style.width = br.width + 'px';
                glider.offsetHeight; /* force reflow to flush transition:none */
                glider.style.transition = '';
            }

            /* ── Slide glider with animation (on click) ── */
            function slideGlider(btn) {
                var tr = track.getBoundingClientRect();
                var br = btn.getBoundingClientRect();
                glider.style.left = (br.left - tr.left) + 'px';
                glider.style.width = br.width + 'px';
            }

            /* ── Activate a tab ── */
            function switchTo(btn, animate) {
                /* 1. Update button states */
                buttons.forEach(function(b) {
                    b.classList.remove('zPill-active');
                });
                btn.classList.add('zPill-active');

                /* 2. Move glider */
                if (animate) {
                    slideGlider(btn);
                } else {
                    snapGlider(btn);
                }

                /* 3. Scroll tab into view on mobile */
                btn.scrollIntoView({
                    block: 'nearest',
                    inline: 'nearest',
                    behavior: 'smooth'
                });

                /* 4. Show correct panel */
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

            /* ── Resolve initial active tab ── */
            var initBtn = null;

            /* Check ?tab= URL param */
            var tabParam = new URLSearchParams(window.location.search).get('tab');
            if (tabParam) {
                buttons.forEach(function(b) {
                    if (b.getAttribute('data-zpill-target') === tabParam) initBtn = b;
                });
            }

            /* Fall back to .zPill-active in HTML, then first button */
            if (!initBtn) initBtn = track.querySelector('button.zPill-active') || buttons[0];

            /* ── Paint initial state after first frame ── */
            requestAnimationFrame(function() {
                switchTo(initBtn, false);
            });

            /* ── Recalculate glider on window resize ── */
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