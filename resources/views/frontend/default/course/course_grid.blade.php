@php
    // Espelha a lógica de preço do card da homepage (hero_banner.blade.php)
    $price = $course->discount_flag == 1 ? $course->discounted_price : $course->price;
    $price = $price > 0 ? $price : 0;
    $monthly = round($price / 12, 2);
    $monthly_int = floor($monthly);
    $monthly_dec = str_pad((string) round(($monthly - $monthly_int) * 100), 2, '0', STR_PAD_LEFT);
@endphp
<div class="col-lg-4 col-md-6 col-sm-6 mb-30">
    <div class="card-curso-toro card--integral">
        <a href="{{ route('course.details', $course->slug) }}" class="toro-card-image" aria-label="{{ $course->title }}">
            @if ($course->thumbnail)
                <img src="{{ get_image($course->thumbnail) }}" alt="{{ $course->title }}">
            @endif
        </a>

        <div class="toro-card-title-first-line">{{ get_phrase('Curso Completo + Simulados') }}</div>

        @if ($course->is_paid == 1)
            <div class="toro-card-price-12x">
                <span class="small">12x R$</span>
                <span class="large">{{ $monthly_int }}</span>
                <span class="small">,{{ $monthly_dec }}</span>
            </div>
            <div class="toro-card-title-second-line">
                {{ get_phrase('Ou') }} <b>R$ {{ number_format($price, 2, ',', '.') }}</b> {{ get_phrase('à vista') }}
                <span class="toro-icon-pix">PIX</span>
            </div>
        @else
            <div class="toro-card-price-12x">
                <span class="large">{{ get_phrase('GRÁTIS') }}</span>
            </div>
        @endif

        <div class="toro-card-btn-comprar">
            @if ($course->is_paid == 1)
                <a href="{{ route('purchase.course', $course->id) }}"><span>{{ get_phrase('Comprar Agora') }}</span></a>
            @else
                <a href="{{ route('purchase.course', $course->id) }}"><span>{{ get_phrase('Inscrever-se') }}</span></a>
            @endif
        </div>
    </div>
</div>
