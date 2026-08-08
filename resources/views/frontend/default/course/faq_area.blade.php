@php
    $faqs = $course_details->faqs ? json_decode($course_details->faqs, true) : [];
@endphp
@if (count($faqs) > 0)
<div class="ps-box p-0 shadow-none">
    <h4 class="g-title text-dark mb-15">Perguntas frequentes</h4>
    <div class="faq p-0">
        <div class="accordion" id="courseFaqAccordion">
            @foreach ($faqs as $key => $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#course_faq_{{ $course_details->id }}_{{ $key }}" aria-expanded="false"
                            aria-controls="course_faq_{{ $course_details->id }}_{{ $key }}">{{ ucfirst($faq['title'] ?? '') }}
                        </button>
                    </h2>
                    <div id="course_faq_{{ $course_details->id }}_{{ $key }}" class="accordion-collapse collapse"
                        data-bs-parent="#courseFaqAccordion">
                        <div class="accordion-body">
                            {!! nl2br(removeScripts(ucfirst($faq['description'] ?? '-'))) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
