<style>
    .question {
        min-height: auto !important;
    }
</style>

<div class="result">
    @php
        $submits = $result->submits ? json_decode($result->submits, true) : [];
        $correct_answers = $result->correct_answer ? json_decode($result->correct_answer, true) : [];
        $wrong_answers = $result->wrong_answer ? json_decode($result->wrong_answer, true) : [];
        $mark_per_question = $questions->count() ? $quiz->total_mark / $questions->count() : 0;
        $obtainedMarks = count($correct_answers) * $mark_per_question;
        $passed = $obtainedMarks >= $quiz->pass_mark;
        $scorePercentage = $quiz->total_mark ? (int) round(($obtainedMarks / $quiz->total_mark) * 100) : 0;
        @endphp

    <div class="pf-result-summary {{ $passed ? 'is-passed' : 'is-failed' }}">
        <div class="pf-result-score"><span>Seu resultado</span><strong>{{ $scorePercentage }}%</strong><small>{{ number_format($obtainedMarks, 0, ',', '.') }} de {{ $quiz->total_mark }} pontos</small></div>
        <div><span>Acertos</span><strong>{{ count($correct_answers) }}</strong><small>de {{ $questions->count() }} questões</small></div>
        <div><span>Erros</span><strong>{{ count($wrong_answers) }}</strong><small>revise abaixo</small></div>
        <div class="pf-result-status"><span>Desempenho</span><strong>{{ $passed ? 'Aprovado' : 'Em desenvolvimento' }}</strong><small>mínimo de {{ $quiz->pass_mark }} pontos</small></div>
    </div>

    @foreach ($questions as $key => $question)
        @php
            $given_answer = $question->type == 'true_false' ? $question->answer : implode(', ', json_decode($question->answer, true));
            $user_answers = array_key_exists($question->id, $submits) ? $submits[$question->id] : [];
        @endphp

        <div class="result-question pf-result-question mb-4">
            <div class="mb-1 d-flex align-items-center gap-3">
                <span class="serial">{{ ++$key }}</span>
                <div>{!! $question->title !!}</div>

                @if (in_array($question->id, $correct_answers))
                    <i class="fi fi-br-check text-success"></i>
                @elseif(in_array($question->id, $wrong_answers))
                    <i class="fi fi-br-cross-small text-danger"></i>
                @endif
            </div>

            <div class="pf-result-options">
                @if ($question->type == 'mcq')
                    @php $options = json_decode($question->options, true) ?? []; @endphp
                    @foreach ($options as $index => $option)
                        @php $val = $user_answers ? array_search($option, $user_answers) : ''; @endphp
                        <div class="pf-result-option">
                            <input class="form-check-input" type="checkbox" value="{{ $option }}" @if (is_numeric($val)) checked @endif disabled>
                            <label class="form-check-label"><b>{{ chr(65 + $index) }}</b><span>{{ $option }}</span></label>
                        </div>
                    @endforeach
                @elseif($question->type == 'fill_blanks')
                    <input type="text" class="form-control tagify" data-role="tagsinput" value="{{ json_encode($user_answers) }}" disabled>
                @elseif($question->type == 'true_false')
                    <div class="col-sm-2">
                        <input class="form-check-input" type="radio" disabled @if ($user_answers == 'true') checked @endif>
                        <label class="form-check-label">{{ get_phrase('True') }}</label>
                    </div>
                    <div class="col-sm-2">
                        <input class="form-check-input" type="radio" disabled @if ($user_answers == 'false') checked @endif>
                        <label class="form-check-label">{{ get_phrase('False') }}</label>
                    </div>
                @endif
                <p class="pf-result-correct">
                    Resposta correta: {{ $given_answer }}
                </p>
            </div>
        </div>
    @endforeach

    <div class="row">
        <div class="col-12 d-flex gap-3 justify-content-center">
            <button type="button" class="pf-question-nav pf-question-prev mb-4" id="backBtn" onclick="back()"><i class="fi fi-rr-angle-small-left fs-5"></i>Voltar ao simulado</button>
        </div>
    </div>
</div>

<script>
    // back to main
    function back() {
        description.classList.remove('d-none');
        starterContainer.classList.remove('d-none');
        document.querySelector('.result').remove();
    }

    $('.result .tagify:not(.inited)').each(function(index, element) {
        var tagify = new Tagify(element, {
            placeholder: '{{ get_phrase('Enter your keywords') }}'
        });
        $(element).addClass('inited');
    });
</script>
