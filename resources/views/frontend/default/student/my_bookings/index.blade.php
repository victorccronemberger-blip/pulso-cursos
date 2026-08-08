@extends('layouts.default')
@push('title', get_phrase('Booked schedules'))
@push('meta')@endpush
@section('content')

<!------------------- Breadcum Area Start  ------>
<section class="breadcum-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="eNtry-breadcum">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('my.courses') }}">Área do aluno</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('Booked schedules') }}</li>
                        </ol>
                    </nav>

                    <div class="row row-gap-3 align-items-center mt-4">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mb-0">{{ get_phrase('Booked schedules') }}</h3>
                        </div>
                        <div class="col-auto ms-auto">
                            <div class="booking-tabs-wrap" id="bookingTabsWrap">
                                <div class="booking-tabs-glider" id="bookingTabsGlider"></div>
                                <a href="{{ route('my_bookings', ['tab' => 'live-upcoming']) }}"
                                    class="booking-tab-btn {{ request('tab') === 'live-upcoming' ? 'active' : '' }}">
                                    {{ get_phrase('Live & Upcoming') }}
                                </a>
                                <a href="{{ route('my_bookings', ['tab' => 'archive']) }}"
                                    class="booking-tab-btn {{ request('tab') === 'archive' ? 'active' : '' }}">
                                    {{ get_phrase('Archive') }}
                                </a>
                            </div>
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
            @include('frontend.default.student.left_sidebar')

            <div class="col-lg-9 col-md-8">

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade {{ request('tab') === 'live-upcoming' ? 'show active' : '' }}" id="pills-live" role="tabpanel">
                        @include('frontend.default.student.my_bookings.live_and_upcoming')
                    </div>
                    <div class="tab-pane fade {{ request('tab') === 'archive' ? 'show active' : '' }}" id="pills-archive" role="tabpanel">
                        @include('frontend.default.student.my_bookings.archive')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-------------- List Item End  --------------->

@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var wrap = document.getElementById('bookingTabsWrap');
        var glider = document.getElementById('bookingTabsGlider');
        var tabs = wrap ? Array.prototype.slice.call(wrap.querySelectorAll('.booking-tab-btn')) : [];

        if (!wrap || !glider || !tabs.length) return;

        var active = wrap.querySelector('.booking-tab-btn.active');

        function moveGlider(el) {
            glider.style.left = el.offsetLeft + 'px';
            glider.style.width = el.offsetWidth + 'px';
        }

        function enableTransition() {
            glider.style.transition = 'left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        }

        /* wait for full paint+layout before snapping — fixes first-load bug */
        if (active) {
            glider.style.transition = 'none';
            setTimeout(function() {
                moveGlider(active);
                setTimeout(enableTransition, 50);
            }, 50);
        }

        /* click — slide then navigate */
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                var href = tab.getAttribute('href');

                tabs.forEach(function(t) {
                    t.classList.remove('active');
                });
                tab.classList.add('active');
                active = tab;

                moveGlider(tab);

                setTimeout(function() {
                    window.location.href = href;
                }, 320);
            });
        });
    });
</script>
@endpush
