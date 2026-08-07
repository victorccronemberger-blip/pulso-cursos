@php
    $visibleLessonQuizzes = $lesson_quizzes->where('id', '!=', $lesson_details->id);
    $currentKind = $lesson_details->lesson_type === 'quiz' ? 'Simulado' : 'Aula';
@endphp
<section class="pf-study-context" aria-labelledby="pf-current-lesson-title">
    <div class="pf-study-heading">
        <div>
            <span class="pf-study-eyebrow">{{ $currentKind }} atual <i></i> {{ $current_section?->title }}</span>
            <h1 id="pf-current-lesson-title">{{ $lesson_details->title }}</h1>
        </div>
        <form action="{{ route('set.watch.history') }}" method="post" class="pf-complete-form">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course_details->id }}">
            <input type="hidden" name="lesson_id" value="{{ $lesson_details->id }}">
            @php $isComplete = in_array($lesson_details->id, json_decode($history?->completed_lesson ?? '[]', true) ?: []); @endphp
            <button type="submit" class="pf-complete-button {{ $isComplete ? 'is-complete' : '' }}">
                <i class="fi fi-rr-{{ $isComplete ? 'check-circle' : 'circle' }}"></i>
                {{ $isComplete ? 'Concluída' : 'Marcar como concluída' }}
            </button>
        </form>
    </div>

    @if($lesson_materials->isNotEmpty() || $visibleLessonQuizzes->isNotEmpty())
        <div class="pf-context-actions">
            @foreach($lesson_materials as $material)
                <a class="pf-context-action pf-context-material" href="{{ route('course.material.download', $material) }}">
                    <span class="pf-context-icon"><i class="fi fi-rr-file-pdf"></i></span>
                    <span><small>Apostila desta aula</small><strong>{{ $material->title }}</strong><em>{{ number_format($material->size_bytes / 1048576, 1, ',', '.') }} MB · PDF</em></span>
                    <b>Baixar <i class="fi fi-rr-download"></i></b>
                </a>
            @endforeach
            @foreach($visibleLessonQuizzes as $quiz)
                <a class="pf-context-action pf-context-quiz" href="{{ route('course.player', ['slug' => $course_details->slug, 'id' => $quiz->id]) }}">
                    <span class="pf-context-icon"><i class="fi fi-rr-bullseye-arrow"></i></span>
                    <span><small>Fixe o conteúdo</small><strong>{{ $quiz->title }}</strong><em>{{ $quiz->total_mark }} questões · aprovação em 70%</em></span>
                    <b>Praticar <i class="fi fi-rr-arrow-small-right"></i></b>
                </a>
            @endforeach
        </div>
    @endif

    @if($module_simulations->isNotEmpty())
        <div class="pf-module-practice">
            <div class="pf-module-practice-copy">
                <span>Checkpoint do módulo</span>
                <strong>Teste seu domínio antes de avançar</strong>
                <p>Simulados separados por conteúdo, com resultado imediato e novas tentativas.</p>
            </div>
            <div class="pf-module-practice-list">
                @foreach($module_simulations as $simulation)
                    <a href="{{ route('course.player', ['slug' => $course_details->slug, 'id' => $simulation->id]) }}" class="{{ $simulation->id === $lesson_details->id ? 'active' : '' }}">
                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <b>{{ $simulation->title }}</b>
                        <small>{{ $simulation->total_mark }} questões</small>
                        <i class="fi fi-rr-angle-small-right"></i>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</section>
