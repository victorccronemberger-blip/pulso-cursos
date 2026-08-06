<link rel="stylesheet" href="{{ asset('assets/backend/css/neu-select.css') }}">

<form class="ajaxForm" action="{{ route('admin.course.question.store') }}" method="post">@csrf

    <input type="hidden" name="quiz_id" value="{{ $id }}">
    <div class="row">
        <div class="col-sm-12">
            <div class="mb-3">
                <label class="form-label ol-form-label">
                    {{ get_phrase('Question Type') }}
                    <span class="text-danger ms-1">*</span>
                </label>
                <select class="form-control ol-form-control neu-select" id="question_type_select" name="type" onchange="getOptionType(this)">
                    <option value="">{{ get_phrase('Select an option') }}</option>
                    <option value="mcq">{{ get_phrase('Multiple Choice') }}</option>
                    <option value="fill_blanks">{{ get_phrase('Fill in the blanks') }}</option>
                    <option value="true_false">{{ get_phrase('True or False') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="title" class="form-label ol-form-label">
            {{ get_phrase('Write question') }}
            <span class="text-danger ms-1">*</span>
        </label>
        <textarea name="title" rows="5" class="form-control ol-form-control nEditor" id="question_title"></textarea>
    </div>

    <div class="load-question-type"></div>

    <div class="d-flex gap-3">
        <a href="#" class="btn ol-btn-primary" id="questionBackBtn"
            onclick="ajaxModal('{{ route('modal', ['admin.questions.index', 'id' => $id]) }}', '{{ get_phrase('Questions') }}', 'modal-lg')">
            <i class="fi-rr-angle-small-left"></i> {{ get_phrase('Back') }}
        </a>
        <div>
            <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Add Question') }}</button>
        </div>
    </div>
</form>

<script>
    'use strict';

    if (typeof NeuSelect !== 'undefined') {
        new NeuSelect('#question_type_select');
    }

    if (typeof nEditor !== 'undefined') {
        nEditor.boot();
    }

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
                    type: type
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