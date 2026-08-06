@extends('layouts.default')
@push('title', $course_details->title)
@push('meta')@endpush
@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/frontend/default/css/course_detail.css') }}">
@endpush

@php
// Extrair código da certificação do título
$cert_code = '';
$cert_patterns = ['ANCORD', 'CFP', 'CFA', 'CPA', 'CNPI', 'CFG', 'C-PRO'];
foreach ($cert_patterns as $pattern) {
    if (stripos($course_details->title, $pattern) !== false) {
        $cert_code = $pattern;
        break;
    }
}

$instructor_review = App\Models\Instructor_review::where('instructor_id', get_course_creator_id($course_details->id)->id)
->orderBy('id', 'DESC')
->get();

$review = App\Models\Review::where('course_id', $course_details->id)->orderBy('id', 'DESC')->get();

$total = $review->count();
$rating = array_sum(array_column($review->toArray(), 'rating'));

$average_rating = 0;
if ($total != 0) {
$average_rating = $rating / $total;
}
@endphp

@section('content')
<!------------------- Hero Area Start  ------>
<section class="course-detail-hero">
    <div class="container">
        {{-- Breadcrumb --}}
        <div class="course-detail-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator">/</span>
            <a href="{{ route('courses') }}">Cursos</a>
            <span class="separator">/</span>
            <span class="current">{{ $course_details->title }}</span>
        </div>

        {{-- Hero Grid --}}
        <div class="course-detail-hero-grid">
            {{-- Coluna principal --}}
            <div class="course-detail-main">
                @if($cert_code)
                <div class="course-cert-code">{{ $cert_code }}</div>
                @endif
                
                <h1 class="course-detail-title">{{ $course_details->title }}</h1>
                
                <p class="course-detail-description">
                    {{ $course_details->short_description }}
                </p>

                {{-- Stats --}}
                <div class="course-detail-stats">
                    <div class="course-stat">
                        <span class="course-stat-value">{{ total_enroll($course_details->id) }}</span>
                        <span class="course-stat-label">Alunos</span>
                    </div>
                    <div class="course-stat">
                        <span class="course-stat-value">{{ total_durations($course_details->id) }}</span>
                        <span class="course-stat-label">Duração</span>
                    </div>
                    <div class="course-stat">
                        <span class="course-stat-value">{{ lesson_count($course_details->id) }}</span>
                        <span class="course-stat-label">Aulas</span>
                    </div>
                    <div class="course-stat">
                        <span class="course-stat-value">{{ ucfirst($course_details->language) }}</span>
                        <span class="course-stat-label">Idioma</span>
                    </div>
                </div>
            </div>

            {{-- Pricing Card (Sidebar) --}}
            <div class="course-pricing-card">
                @if($course_details->banner)
                <div class="course-pricing-image">
                    <img src="{{ get_image($course_details->banner) }}" alt="{{ $course_details->title }}">
                    @if($course_details->preview)
                    <div class="course-pricing-play" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                    @endif
                </div>
                @endif

                <div class="course-pricing-price">
                    @if($course_details->is_paid == 0)
                        <span class="course-pricing-free">Grátis</span>
                    @elseif($course_details->discount_flag == 1)
                        <span class="course-pricing-current">R$ {{ number_format($course_details->discounted_price, 2, ',', '.') }}</span>
                        <span class="course-pricing-original">R$ {{ number_format($course_details->price, 2, ',', '.') }}</span>
                    @else
                        <span class="course-pricing-current">R$ {{ number_format($course_details->price, 2, ',', '.') }}</span>
                    @endif
                </div>

                @php
                if (isset(auth()->user()->id)) {
                    $is_enrolled = DB::table('enrollments')
                        ->where('user_id', auth()->user()->id)
                        ->where('course_id', $course_details->id)
                        ->where(function ($query) {
                            $query->where('expiry_date', '>', now()->timestamp)->orWhereNull('expiry_date');
                        })
                        ->exists();
                } else {
                    $is_enrolled = false;
                }
                @endphp

                @if($is_enrolled)
                    <a href="{{ route('my.courses') }}" class="course-pricing-cta">Acessar Curso</a>
                @else
                    <a href="{{ route('purchase.course', $course_details->id) }}" class="course-pricing-cta">
                        {{ $course_details->is_paid ? 'Comprar Agora' : 'Inscrever-se' }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
<!------------------- Hero Area End  --------->

<section class="course-detail-content">
    <div class="container">
        {{-- Tabs Navigation --}}
        <div class="course-tabs">
            <button class="course-tab active" data-tab="overview">Visão Geral</button>
            <button class="course-tab" data-tab="curriculum">Conteúdo</button>
            <button class="course-tab" data-tab="instructor">Instrutor</button>
            <button class="course-tab" data-tab="reviews">Avaliações</button>
        </div>

        {{-- Tab Panels --}}
        <div class="course-tab-panel active" id="tab-overview">
            @include('frontend.default.course.overview_area')
        </div>
        <div class="course-tab-panel" id="tab-curriculum">
            @include('frontend.default.course.content_area')
        </div>
        <div class="course-tab-panel" id="tab-instructor">
            @if ($course_details->creator->id > 0)
                @include('frontend.default.course.instructor_area')
            @endif
        </div>
        <div class="course-tab-panel" id="tab-reviews">
            @include('frontend.default.course.review_area')
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade-in-effect" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body bg-dark">
                <link rel="stylesheet" href="{{ asset('assets/global/plyr/plyr.css') }}">
                @php
                $preview_video_type = str_contains($course_details->preview, 'youtu') ? 'youtube' : '';
                $preview_video_type = str_contains($course_details->preview, 'vimeo') && $preview_video_type == '' ? 'vimeo' : $preview_video_type;
                $preview_video_type = str_contains($course_details->preview, 'http') && $preview_video_type == '' ? 'html5' : $preview_video_type;
                @endphp

                @if ($preview_video_type == 'youtube')
                <div class="plyr__video-embed" id="promoPlayer">
                    <iframe height="500" src="{{ $course_details->preview }}?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>
                @elseif ($preview_video_type == 'vimeo')
                <div class="plyr__video-embed" id="promoPlayer">
                    <iframe height="500" src="https://player.vimeo.com/video/{{ $course_details->preview }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>
                @elseif($preview_video_type == 'html5')
                <video id="promoPlayer" playsinline controls>
                    <source src="{{ $course_details->preview }}" type="video/mp4">
                </video>
                @else
                <video id="promoPlayer" playsinline controls>
                    <source src="{{ asset($course_details->preview) }}" type="video/mp4">
                </video>
                @endif

                <script src="{{ asset('assets/global/plyr/plyr.js') }}"></script>
                <script>
                    "use strict";
                    const promoPlayer = new Plyr('#promoPlayer');
                </script>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";
    const myModalElement = document.getElementById('exampleModal')
    myModalElement.addEventListener('hidden.bs.modal', event => {
        promoPlayer.pause();
        $('#exampleModal').toggleClass('in');
    });
    myModalElement.addEventListener('shown.bs.modal', event => {
        promoPlayer.play();
        $('#exampleModal').toggleClass('in');
    });
</script>

@endsection

@push('js')
<script>
    "use strict";

    // Tab switching
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.course-tab');
        const panels = document.querySelectorAll('.course-tab-panel');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetId = this.getAttribute('data-tab');
                
                // Remove active from all tabs and panels
                tabs.forEach(t => t.classList.remove('active'));
                panels.forEach(p => p.classList.remove('active'));
                
                // Add active to clicked tab and corresponding panel
                this.classList.add('active');
                document.getElementById('tab-' + targetId).classList.add('active');
            });
        });
        
        // More/Less description toggle
        const moreBtn = document.getElementById('more_description');
        if (moreBtn) {
            moreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const description = document.querySelector('.description');
                if (description) {
                    description.classList.toggle('expanded');
                    if (description.classList.contains('expanded')) {
                        this.innerHTML = 'Ver menos <i class="fa-solid fa-angle-up ms-2"></i>';
                    } else {
                        this.innerHTML = 'Ver mais <i class="fa-solid fa-angle-right ms-2"></i>';
                    }
                }
            });
        }
    });
</script>
@endpush