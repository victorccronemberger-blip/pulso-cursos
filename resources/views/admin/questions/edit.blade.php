<link rel="stylesheet" href="{{ asset('assets/backend/css/neu-select.css') }}">

@php
$question = App\Models\Question::where('id', $id)->first();
@endphp

<form class="ajaxForm" action="{{ route('admin.course.question.update', $id) }}" method="post">@csrf

    <input type="hidden" name="quiz_id" value="{{ $question->quiz_id }}">

    <div class="mb-3">
        <label class="form-label ol-form-label">
            {{ get_phrase('Question Type') }}
            <span class="text-danger ms-1">*</span>
        </label>
        <select class="form-control ol-form-control neu-select" id="question_edit_type_select" name="type" onchange="getOptionType(this)">
            <option value="">{{ get_phrase('Select an option') }}</option>
            <option @if ($question->type == 'mcq') selected @endif value="mcq">{{ get_phrase('Multiple Choice') }}</option>
            <option @if ($question->type == 'fill_blanks') selected @endif value="fill_blanks">{{ get_phrase('Fill in the blanks') }}</option>
            <option @if ($question->type == 'true_false') selected @endif value="true_false">{{ get_phrase('True or False') }}</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="title" class="form-label ol-form-label">
            {{ get_phrase('Write question') }}
            <span class="text-danger ms-1">*</span>
        </label>
        <textarea name="title" rows="5" class="form-control ol-form-control nEditor" id="question_edit_title">{!! $question->title !!}</textarea>
    </div>

    <div class="load-question-type"></div>

    <div class="d-flex gap-3">
        <a href="#" class="btn ol-btn-primary" id="questionBackBtn"
            onclick="ajaxModal('{{ route('modal', ['admin.questions.index', 'id' => $question->quiz_id]) }}', '{{ get_phrase('Questions') }}', 'modal-lg')">
            <i class="fi-rr-angle-small-left"></i> {{ get_phrase('Back') }}
        </a>
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Question') }}</button>
    </div>
</form>

<script>
    'use strict';

    if (typeof NeuSelect !== 'undefined') {
        new NeuSelect('#question_edit_type_select');
    }

    if (typeof nEditor !== 'undefined') {
        nEditor.boot();
    }

    setupQuestion("{{ $question->type }}");

    function getOptionType(elem) {
        let type = elem.value;
        setupQuestion(type);
    }

    function setupQuestion(type) {
        if (type) {
            $.ajax({
                type: "get",
                url: "{{ route('admin.load.question.type') }}",
                data: {
                    id: "{{ $question->id }}",
                    type: type,
                },
                success: function(response) {
                    $('.load-question-type').empty().append(response);
                }
            });
        }
    }

    function responseBack() {
        document.querySelector('#questionBackBtn').click();
    }
</script>

@include('admin.init')