<div class="ps-box p-0 shadow-none">
    <h4 class="g-title text-dark">Visão geral do curso</h4>
    <div class="editor-content description ellipsis-4" id="ellipsis-4">
        @if (filled($course_details->description))
            {!! removeScripts($course_details->description) !!}
        @else
            <p>{{ $course_details->short_description }}</p>
            <p>Avance por uma trilha organizada de videoaulas, materiais em PDF e simulados vinculados aos respectivos módulos.</p>
        @endif
    </div>
    @if (filled($course_details->description))
        <a href="#" class="s_stext" id="more_description">
            Ver descrição completa <i class="fa-solid fa-angle-right me-2"></i>
        </a>
    @endif
</div>
@include('frontend.default.course.faq_area')
