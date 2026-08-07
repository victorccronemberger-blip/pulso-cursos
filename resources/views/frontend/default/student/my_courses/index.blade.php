@extends('layouts.default')
@push('title', get_phrase('Minha preparação'))
@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
@endpush
@section('content')
@php
    $student_name = trim(explode(' ', auth()->user()->name)[0]);
    $enrolled_count = $my_courses->total();
@endphp
<main class="pf-student-shell">
    <section class="pf-student-hero">
        <div class="container">
            <p class="pf-student-kicker"><span></span>{{ get_phrase('Central de preparação') }}</p>
            <div class="pf-student-hero-grid">
                <div>
                    <h1>{{ get_phrase('Olá,') }} {{ $student_name }}.</h1>
                    <p>{{ get_phrase('Seu próximo passo está aqui. Retome seus cursos, acompanhe seu avanço e mantenha o ritmo até a aprovação.') }}</p>
                </div>
                <div class="pf-student-hero-metrics" aria-label="{{ get_phrase('Resumo da sua preparação') }}">
                    <div><strong>{{ $enrolled_count }}</strong><span>{{ get_phrase('cursos na sua trilha') }}</span></div>
                    <div><strong>{{ $enrolled_count ? get_phrase('Ativa') : get_phrase('Pronta') }}</strong><span>{{ get_phrase('sua preparação') }}</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="pf-student-content">
        <div class="container">
            <div class="row g-4">
                @include('frontend.default.student.left_sidebar')
                <div class="col-lg-9 col-md-8">
                    <div class="pf-student-section-heading">
                        <div><p>{{ get_phrase('Minha biblioteca') }}</p><h2>{{ get_phrase('Cursos em andamento') }}</h2></div>
                        <a href="{{ route('courses') }}" class="pf-student-browse">{{ get_phrase('Explorar cursos') }} <span aria-hidden="true">→</span></a>
                    </div>

                    @forelse ($my_courses as $course)
                        @php
                            $course_progress = progress_bar($course->course_id);
                            $watch_history = App\Models\Watch_history::where('course_id', $course->course_id)->where('student_id', auth()->id())->first();
                            $lesson = App\Models\Lesson::where('course_id', $course->course_id)->orderBy('section_id')->orderBy('sort')->first();
                            $lesson_id = $watch_history?->watching_lesson_id ?? $lesson?->id;
                            $player_url = $lesson_id ? route('course.player', ['slug' => $course->slug, 'id' => $lesson_id]) : route('course.details', $course->slug);
                            $is_expired = $course->expiry_date > 0 && $course->expiry_date < time();
                        @endphp
                        <article class="pf-learning-card">
                            <a class="pf-learning-art" href="{{ route('course.details', $course->slug) }}">
                                <img src="{{ get_image($course->thumbnail) }}" alt="{{ $course->title }}">
                            </a>
                            <div class="pf-learning-body">
                                <p class="pf-learning-label">{{ get_phrase('Sua trilha') }}</p>
                                <h3>{{ $course->title }}</h3>
                                <p class="pf-learning-instructor">{{ get_phrase('Com') }} {{ $course->user_name }}</p>
                                <div class="pf-learning-progress">
                                    <div><span>{{ get_phrase('Seu progresso') }}</span><strong>{{ number_format($course_progress, 0) }}%</strong></div>
                                    <div class="pf-learning-track" role="progressbar" aria-label="{{ get_phrase('Progresso do curso') }}" aria-valuenow="{{ $course_progress }}" aria-valuemin="0" aria-valuemax="100"><i style="width: {{ $course_progress }}%"></i></div>
                                </div>
                                @if ($is_expired)
                                    <a href="{{ route('purchase.course', ['course_id' => $course->course_id]) }}" class="pf-learning-action">{{ get_phrase('Renovar acesso') }} <span aria-hidden="true">→</span></a>
                                @else
                                    <a href="{{ $player_url }}" class="pf-learning-action">{{ $course_progress > 0 ? get_phrase('Continuar estudando') : get_phrase('Começar curso') }} <span aria-hidden="true">→</span></a>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="pf-student-empty">
                            <div class="pf-student-empty-orbit" aria-hidden="true"><i></i></div>
                            <p class="pf-student-kicker"><span></span>{{ get_phrase('Sua jornada começa aqui') }}</p>
                            <h3>{{ get_phrase('Ainda não há cursos na sua biblioteca.') }}</h3>
                            <p>{{ get_phrase('Escolha a certificação que você quer conquistar e transforme este painel no seu plano de preparação.') }}</p>
                            <a href="{{ route('courses') }}" class="pf-student-empty-action">{{ get_phrase('Encontrar minha trilha') }} <span aria-hidden="true">→</span></a>
                        </div>
                    @endforelse

                    @if ($my_courses->hasPages())
                        <div class="pf-student-pagination">{{ $my_courses->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
