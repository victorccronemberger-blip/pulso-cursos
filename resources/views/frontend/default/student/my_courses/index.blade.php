@extends('layouts.default')
@push('title', 'Minha preparação')
@section('content')
@php
    $studentName = trim(explode(' ', auth()->user()->name)[0]);
    $enrolledCount = $my_courses->total();
@endphp

<section class="pf-student-shell pf-student-content">
    <div class="container">
        <header class="pf-student-pagehead">
            <div>
                <p>Central de preparação</p>
                <h1>Olá, {{ $studentName }}. Continue de onde parou.</h1>
            </div>
            <span>{{ $enrolledCount }} {{ $enrolledCount === 1 ? 'curso na sua trilha' : 'cursos na sua trilha' }}</span>
        </header>

        <div class="row g-4">
            @include('frontend.default.student.left_sidebar')
            <div class="col-lg-9 col-md-8">
                <div class="pf-student-section-heading">
                    <div><p>Minha biblioteca</p><h2>Cursos em andamento</h2></div>
                    <a href="{{ route('courses') }}" class="pf-student-browse">Explorar cursos <span aria-hidden="true">→</span></a>
                </div>

                @forelse ($my_courses as $course)
                    @php
                        $courseProgress = progress_bar($course->course_id);
                        $watchHistory = App\Models\Watch_history::where('course_id', $course->course_id)->where('student_id', auth()->id())->first();
                        $lesson = App\Models\Lesson::where('course_id', $course->course_id)->orderBy('section_id')->orderBy('sort')->first();
                        $lessonId = $watchHistory?->watching_lesson_id ?? $lesson?->id;
                        $playerUrl = $lessonId ? route('course.player', ['slug' => $course->slug, 'id' => $lessonId]) : route('course.details', $course->slug);
                        $isExpired = $course->expiry_date > 0 && $course->expiry_date < time();
                    @endphp
                    <article class="pf-learning-card">
                        <a class="pf-learning-art" href="{{ route('course.details', $course->slug) }}">
                            <img src="{{ get_image($course->thumbnail) }}" alt="{{ $course->title }}">
                        </a>
                        <div class="pf-learning-body">
                            <p class="pf-learning-label">Sua trilha</p>
                            <h3>{{ $course->title }}</h3>
                            <p class="pf-learning-instructor">Com {{ $course->user_name }}</p>
                            <div class="pf-learning-progress">
                                <div><span>Seu progresso</span><strong>{{ number_format($courseProgress, 0) }}%</strong></div>
                                <div class="pf-learning-track" role="progressbar" aria-label="Progresso do curso" aria-valuenow="{{ $courseProgress }}" aria-valuemin="0" aria-valuemax="100"><i style="width: {{ $courseProgress }}%"></i></div>
                            </div>
                            @if ($isExpired)
                                <a href="{{ route('purchase.course', ['course_id' => $course->course_id]) }}" class="pf-learning-action">Renovar acesso</a>
                            @else
                                <a href="{{ $playerUrl }}" class="pf-learning-action">{{ $courseProgress > 0 ? 'Continuar estudando' : 'Começar curso' }}</a>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="pf-student-empty">
                        <p class="pf-student-kicker">Sua jornada começa aqui</p>
                        <h3>Ainda não há cursos na sua biblioteca.</h3>
                        <p>Escolha a certificação que deseja conquistar e transforme este painel no seu plano de preparação.</p>
                        <a href="{{ route('courses') }}" class="pf-student-empty-action">Encontrar minha trilha</a>
                    </div>
                @endforelse

                @if ($my_courses->hasPages())
                    <div class="pf-student-pagination">{{ $my_courses->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
