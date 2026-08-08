@extends('layouts.default')

@push('title', 'Simulados e provas')
@push('meta')@endpush
@push('css')@endpush

@section('content')
@include('frontend.default.student.page_header', [
    'title' => 'Simulados e provas',
    'current' => 'Simulados e provas',
    'description' => 'Pratique por módulo, acompanhe suas tentativas e acesse avaliações dos seus cursos.',
])

<div class="eNtery-item">
    <div class="container">
        <div class="row">
            @include('frontend.default.student.left_sidebar')

            <main class="col-lg-9 col-md-8">
                @if ($quizCourses->isNotEmpty() || $exams->isNotEmpty())
                    <section class="pf-assessment-overview" aria-label="Resumo dos simulados">
                        <div><span>Cursos com prática</span><strong>{{ $summary['courses'] }}</strong></div>
                        <div><span>Simulados no total</span><strong>{{ $summary['quizzes'] }}</strong></div>
                        <div><span>Já realizados</span><strong>{{ $summary['attempted'] }}</strong></div>
                        <div><span>Aprovados neste curso</span><strong>{{ $summary['active_passed'] }}</strong></div>
                    </section>

                    @if ($quizCourses->isNotEmpty())
                        <section class="pf-assessment-section" aria-labelledby="interactive-quizzes-title">
                            <div class="pf-assessment-heading">
                                <div><span>Central de avaliação</span><h2 id="interactive-quizzes-title">Simulados por curso</h2></div>
                                <p>Escolha um curso para consultar os módulos e continuar sua preparação.</p>
                            </div>

                            @php
                                $activeProgress = $activeQuizCourse['quiz_count'] > 0
                                    ? (int) round(($activeQuizCourse['attempted_count'] / $activeQuizCourse['quiz_count']) * 100)
                                    : 0;
                            @endphp
                            <form class="pf-course-switcher" action="{{ route('my.exams') }}" method="GET">
                                <div class="pf-course-switcher-preview">
                                    <img src="{{ get_image($activeQuizCourse['thumbnail']) }}" alt="">
                                </div>
                                <div class="pf-course-switcher-control">
                                    <label for="assessment-course">Curso em exibição</label>
                                    <div class="pf-course-select-row">
                                        <select id="assessment-course" name="course" data-course-switcher>
                                            @foreach ($quizCourses as $courseOption)
                                                <option value="{{ $courseOption['id'] }}" @selected($courseOption['id'] === $activeCourseId)>
                                                    {{ $courseOption['title'] }} — {{ $courseOption['quiz_count'] }} simulados
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit">Carregar</button>
                                    </div>
                                    <small>{{ $quizCourses->count() }} {{ $quizCourses->count() === 1 ? 'curso disponível' : 'cursos disponíveis' }} com simulados</small>
                                </div>
                                <div class="pf-course-switcher-progress">
                                    <div><span>Progresso no curso</span><strong>{{ $activeQuizCourse['attempted_count'] }}/{{ $activeQuizCourse['quiz_count'] }}</strong></div>
                                    <div class="pf-course-progress-track" aria-label="{{ $activeProgress }}% dos simulados realizados"><span style="width: {{ $activeProgress }}%"></span></div>
                                    <small>{{ $activeProgress }}% realizados</small>
                                </div>
                            </form>

                            <article class="pf-quiz-course">
                                <header class="pf-quiz-course-head">
                                    <div class="pf-quiz-course-copy">
                                        <span>Curso selecionado</span>
                                        <h3>{{ $activeQuizCourse['title'] }}</h3>
                                        <p>{{ $activeQuizCourse['modules']->count() }} módulos · {{ $activeQuizCourse['quiz_count'] }} simulados disponíveis</p>
                                    </div>
                                    <a href="{{ route('course.player', ['slug' => $activeQuizCourse['slug']]) }}" class="pf-quiz-course-link">Abrir curso <i class="fi fi-rr-arrow-small-right"></i></a>
                                </header>

                                <div class="pf-quiz-modules">
                                    @foreach ($activeQuizCourse['modules'] as $module)
                                        <details class="pf-quiz-module" @if($loop->first) open @endif>
                                            <summary>
                                                <span class="pf-quiz-module-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                                <span class="pf-quiz-module-title"><strong>{{ $module['title'] }}</strong><small>{{ $module['quizzes']->count() }} simulados</small></span>
                                                <i class="fi fi-rr-angle-small-down" aria-hidden="true"></i>
                                            </summary>

                                            <div class="pf-quiz-list">
                                                @foreach ($module['quizzes'] as $quiz)
                                                    @php
                                                        $kindLabel = match($quiz->context_kind) {
                                                            'final' => 'Revisão final',
                                                            'module' => 'Simulado do módulo',
                                                            default => 'Prática do conteúdo',
                                                        };
                                                        $statusLabel = match($quiz->status_key) {
                                                            'passed' => 'Aprovado',
                                                            'in_progress' => 'Em andamento',
                                                            'finished' => 'Tentativas concluídas',
                                                            default => 'Não iniciado',
                                                        };
                                                        $durationParts = array_map('intval', explode(':', (string) $quiz->duration));
                                                        [$durationHours, $durationMinutes, $durationSeconds] = count($durationParts) === 3
                                                            ? $durationParts
                                                            : [0, $durationParts[0] ?? 0, $durationParts[1] ?? 0];
                                                        $durationLabel = collect([
                                                            $durationHours > 0 ? $durationHours . 'h' : null,
                                                            $durationMinutes > 0 ? $durationMinutes . 'min' : null,
                                                            $durationHours === 0 && $durationMinutes === 0 && $durationSeconds > 0 ? $durationSeconds . 's' : null,
                                                        ])->filter()->implode(' ') ?: 'Tempo livre';
                                                    @endphp
                                                    <div class="pf-quiz-row">
                                                        <div class="pf-quiz-kind pf-quiz-kind-{{ $quiz->context_kind }}"><i class="fi fi-rr-document"></i><span>{{ $kindLabel }}</span></div>
                                                        <div class="pf-quiz-main">
                                                            <h4>{{ $quiz->title }}</h4>
                                                            <div class="pf-quiz-meta">
                                                                <span>{{ $quiz->question_count }} questões</span>
                                                                <span>{{ $durationLabel }}</span>
                                                                <span>Aprovação: {{ $quiz->pass_percentage }}%</span>
                                                                <span>Tentativas: {{ $quiz->attempt_count }}/{{ $quiz->retake }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="pf-quiz-result">
                                                            <span class="pf-quiz-status pf-quiz-status-{{ $quiz->status_key }}">{{ $statusLabel }}</span>
                                                            @if($quiz->best_score !== null)<small>Melhor nota: {{ $quiz->best_score }}%</small>@else<small>{{ $quiz->remaining_attempts }} tentativas disponíveis</small>@endif
                                                        </div>
                                                        <a class="pf-quiz-open" href="{{ route('course.player', ['slug' => $activeQuizCourse['slug'], 'id' => $quiz->id]) }}">{{ $quiz->attempt_count ? 'Abrir' : 'Iniciar' }} <i class="fi fi-rr-arrow-small-right"></i></a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endforeach
                                </div>
                            </article>
                        </section>
                    @endif

                    @if ($exams->isNotEmpty())
                        <section class="pf-assessment-section" aria-labelledby="submitted-exams-title">
                            <div class="pf-assessment-heading">
                                <div><span>Avaliação formal</span><h2 id="submitted-exams-title">Provas com entrega</h2></div>
                                <p>Arquivos enviados para correção e acompanhamento da equipe acadêmica.</p>
                            </div>
                            <div class="pf-formal-exam-list">
                                @foreach ($exams as $exam)
                                    @php
                                        $now = now();
                                        $hasStarted = !$exam->start_at || $now >= $exam->start_at;
                                        $hasExpired = $exam->end_at && $now > $exam->end_at;
                                        $submission = $exam->mySubmission;
                                    @endphp
                                    <article class="pf-formal-exam">
                                        <div><span>{{ $exam->course->title }}</span><h3>{{ $exam->title }}</h3><p>{{ $exam->marks }} pontos · {{ $exam->duration }} minutos</p></div>
                                        <div class="pf-formal-exam-action">
                                            @if($submission)<span class="pf-quiz-status pf-quiz-status-in_progress">Entregue</span>@elseif($hasExpired)<span class="pf-quiz-status pf-quiz-status-finished">Encerrada</span>@elseif(!$hasStarted)<span class="pf-quiz-status">Agendada</span>@endif
                                            <a href="{{ route('my.exam.details', $exam->id) }}">Ver detalhes <i class="fi fi-rr-arrow-small-right"></i></a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                            @if($exams->hasPages())<div class="entry-pagination">{{ $exams->links() }}</div>@endif
                        </section>
                    @endif
                @else
                    @include('frontend.default.student.empty_state', [
                        'icon' => 'fi-rr-document',
                        'title' => 'Nenhum simulado disponível.',
                        'message' => 'Os simulados vinculados aos seus cursos aparecerão aqui automaticamente.',
                        'actionUrl' => route('my.courses'),
                        'actionLabel' => 'Voltar aos meus cursos',
                    ])
                @endif
            </main>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const courseSwitcher = document.querySelector('[data-course-switcher]');
        courseSwitcher?.form.classList.add('is-enhanced');
        courseSwitcher?.addEventListener('change', function () {
            this.form.requestSubmit();
        });
    });
</script>
@endpush
