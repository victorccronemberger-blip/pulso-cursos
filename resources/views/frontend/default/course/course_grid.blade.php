@php
    $price = $course->discount_flag == 1 ? $course->discounted_price : $course->price;
    $price = max(0, (float) $price);
    $monthly = $price ? $price / 12 : 0;
@endphp
<article class="col-lg-4 col-md-6 mb-30 pf-course-column">
    <div class="pf-course-card">
        <a href="{{ route('course.details', $course->slug) }}" class="pf-course-media" aria-label="{{ $course->title }}">
            @if ($course->thumbnail)
                <img src="{{ get_image($course->thumbnail) }}" alt="{{ $course->title }}" loading="lazy">
            @endif
            <span class="pf-course-media-badge">{{ $course->is_paid ? get_phrase('Curso completo') : get_phrase('Acesso gratuito') }}</span>
        </a>
        <div class="pf-course-body">
            <p class="pf-course-eyebrow">{{ $course->category->title ?? get_phrase('Certificação financeira') }}</p>
            <h2><a href="{{ route('course.details', $course->slug) }}">{{ $course->title }}</a></h2>
            <p class="pf-course-description">{{ \Illuminate\Support\Str::limit(strip_tags($course->short_description), 108) }}</p>
            <div class="pf-course-signals" aria-label="Dados do curso">
                <span>{{ lesson_count($course->id) }} {{ get_phrase('aulas') }}</span>
                <span>{{ total_durations($course->id) }}</span>
            </div>
            <div class="pf-course-footer">
                <div class="pf-course-price">
                    @if ($price > 0)
                        <small>{{ get_phrase('a partir de') }}</small>
                        <strong>12x R$ {{ number_format($monthly, 2, ',', '.') }}</strong>
                        <span>R$ {{ number_format($price, 2, ',', '.') }} {{ get_phrase('à vista') }}</span>
                    @else
                        <small>{{ get_phrase('Comece agora') }}</small>
                        <strong>{{ get_phrase('Grátis') }}</strong>
                    @endif
                </div>
                <a class="pf-course-action" href="{{ route('course.details', $course->slug) }}">{{ get_phrase('Ver curso') }} <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </div>
</article>
