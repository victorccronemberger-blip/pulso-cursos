@php $courseProgress = (int) round((float) progress_bar($course_details->id)); @endphp
<div class="pf-player-header">
    <a class="pf-player-brand" href="{{ route('home') }}" aria-label="Voltar para a página inicial">
        <img src="{{ asset(get_frontend_settings('light_logo')) }}" alt="{{ config('app.name') }}">
    </a>

    <div class="pf-player-course">
        <span class="pf-player-course-kicker">Sua trilha de aprovação</span>
        <strong>{{ ucfirst($course_details->title) }}</strong>
    </div>

    <div class="pf-player-progress" aria-label="{{ $courseProgress }}% do curso concluído">
        <div class="pf-player-progress-copy">
            <span>Progresso</span>
            <b>{{ $courseProgress }}%</b>
        </div>
        <div class="pf-player-progress-track"><i style="width: {{ $courseProgress }}%"></i></div>
    </div>

    <div class="pf-player-header-actions">
        <button type="button" class="pf-player-icon-button" id="fullscreen" aria-label="Tela cheia" title="Tela cheia">
            <i class="fi fi-rr-expand"></i>
        </button>
        @if(is_course_instructor($course_details->id) || auth()->user()->role == 'admin')
            <a class="pf-player-exit" href="{{ route(auth()->user()->role.'.course.edit', ['id' => $course_details->id, 'tab' => 'curriculum']) }}">Gerenciar curso</a>
        @else
            <a class="pf-player-exit" href="{{ route('my.courses') }}">Meus cursos</a>
        @endif
    </div>
</div>
