@php
    $quiz = App\Models\Lesson::where('id', request()->route()->parameter('id'))->firstOrNew();
    $questions = DB::table('questions')->where('quiz_id', $quiz->id)->get();
    $submits = DB::table('quiz_submissions')->where('quiz_id', $quiz->id)->where('user_id', auth()->user()->id)->get();
    $duration = explode(':', $quiz->duration);
    $durationMinutes = ((int) ($duration[0] ?? 0) * 60) + (int) ($duration[1] ?? 0);
    $passPercentage = $quiz->total_mark ? (int) round(($quiz->pass_mark / $quiz->total_mark) * 100) : 0;
@endphp

<div class="pf-quiz-shell">
    <div class="pf-quiz-intro">
        <span class="pf-quiz-kicker">Prática dirigida</span>
        <h2>{{ $quiz->title }}</h2>
        <p class="description">{{ strip_tags($quiz->description) }}</p>
    </div>

    <div class="timer-container pf-quiz-timer d-none" aria-live="polite">
        <i class="fi fi-rr-clock-five"></i>
        <span>Tempo restante</span>
        <strong id="quizTimer"></strong>
    </div>

    <div class="pf-quiz-starter quiz-starter">
        <div class="pf-quiz-metrics">
            <div><span>Questões</span><strong>{{ $questions->count() }}</strong><small>múltipla escolha</small></div>
            <div><span>Tempo estimado</span><strong>{{ $durationMinutes }} min</strong><small>cronômetro automático</small></div>
            <div><span>Nota mínima</span><strong>{{ $passPercentage }}%</strong><small>{{ $quiz->pass_mark }} de {{ $quiz->total_mark }} pontos</small></div>
            <div><span>Tentativas</span><strong>{{ $submits->count() }}/{{ $quiz->retake }}</strong><small>resultado imediato</small></div>
        </div>

        <div class="pf-quiz-guidance">
            <div><i class="fi fi-rr-lightbulb-on"></i><span><b>Antes de começar</b> Reserve este tempo sem interrupções. Você poderá revisar o resultado ao final.</span></div>
            <div class="pf-quiz-actions">
                @foreach ($submits as $key => $submit)
                    <button type="button" class="pf-quiz-result result-btn" onclick="getResult(this)" id="{{ $submit->id }}">
                        Ver resultado {{ $loop->iteration }}
                    </button>
                @endforeach
                @if ($submits->count() < $quiz->retake)
                    <button type="button" class="pf-quiz-start" id="starterBtn">Iniciar simulado <i class="fi fi-rr-arrow-small-right"></i></button>
                @else
                    <span class="pf-quiz-limit">Todas as tentativas foram utilizadas.</span>
                @endif
            </div>
        </div>
    </div>

    <div class="load-content pf-quiz-content"></div>
</div>

<script>
    const starterContainer = document.querySelector('.quiz-starter');
    const starterBtn = document.querySelector('#starterBtn');
    const quizTimer = document.querySelector('#quizTimer');
    const description = document.querySelector('.description');

    if (starterBtn) {
        starterBtn.addEventListener('click', function() {
            starterContainer.classList.add('d-none');
            description.classList.add('d-none');
            $.ajax({
                type: "get",
                url: "{{ route('load.quiz.questions') }}",
                data: { quiz_id: "{{ $quiz->id }}" },
                success: function(response) {
                    $('.load-content').html(response);
                    startTimer();
                }
            });
        });
    }

    function startTimer() {
        document.querySelector('.timer-container').classList.remove('d-none');
        const durationArr = "{{ $quiz->duration }}".split(":");
        let hour = parseInt(durationArr[0]);
        let minute = parseInt(durationArr[1]);
        let second = parseInt(durationArr[2]);

        const renderTimer = () => {
            quizTimer.innerHTML = (hour < 10 ? '0' + hour : hour) + ':' +
                (minute < 10 ? '0' + minute : minute) + ':' +
                (second < 10 ? '0' + second : second);
        };
        renderTimer();

        const timerInterval = setInterval(() => {
            if (hour === 0 && minute === 0 && second === 0) {
                clearInterval(timerInterval);
                endQuiz();
                return;
            }
            if (second === 0) {
                if (minute === 0) { hour--; minute = 59; } else { minute--; }
                second = 59;
            } else { second--; }
            renderTimer();
        }, 1000);
    }

    function getResult(elem) {
        description.classList.add('d-none');
        starterContainer.classList.add('d-none');
        $.ajax({
            type: "get",
            url: "{{ route('load.quiz.result') }}",
            data: { submit_id: $(elem).attr('id'), quiz_id: "{{ $quiz->id }}" },
            success: function(response) { $('.load-content').html(response); }
        });
    }

    function endQuiz() { submitQuiz(); }
</script>
