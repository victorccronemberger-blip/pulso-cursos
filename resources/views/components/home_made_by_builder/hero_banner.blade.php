{{-- Hero V2: Ticker + Painel de certificações + Carrossel de cursos --}}
@php
use App\Models\Course;
use App\Models\Category;

$toro_categories = Category::where('parent_id', 0)->orderBy('id')->get();
$toro_courses = Course::with(['features', 'reviews'])
    ->where('status', 'active')
    ->latest('id')
    ->get();
$toro_filters = [
    'CFP'    => 'CFP®',
    'CFA'    => 'CFA',
    'CPA'    => 'CPA',
    'C-PRO'  => 'C-PRO',
    'CFG'    => 'CFG',
    'CNPI'   => 'CNPI',
    'ANCORD' => 'ANCORD',
];
$cert_names = [
    'CFP' => 'Certified Financial Planner',
    'CFA' => 'Chartered Financial Analyst',
    'CPA' => 'Certificação Profissional ANBIMA',
    'CNPI' => 'Analista CNPI',
    'ANCORD' => 'Ancord',
    'CFG' => 'Consultor FG',
    'C-PRO' => 'C-PRO',
];
$total_students = $toro_courses->sum(function($c) { return $c->enrollments_count ?? 0; });
$avg_rating = $toro_courses->avg(function($c) { return $c->reviews->avg('rating') ?? 0; }) ?? 0;
@endphp

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/frontend/default/css/toro_home_v2.css') }}">
@endpush

{{-- Ticker de certificações (elemento assinatura) --}}
<div class="pf-ticker">
    <div class="pf-ticker-track">
        @foreach(array_merge(array_values($toro_filters), array_values($toro_filters)) as $cert)
        <span class="pf-ticker-item">{{ $cert }}</span>
        @endforeach
    </div>
</div>

{{-- Hero V2: Banner com slideshow --}}
<section class="pf-hero-v2">
    <div class="container">
        <div class="pf-hero-slideshow">
            <img class="pf-hero-banner-img active" src="{{ asset('assets/frontend/default/img/banner-full-hero.png') }}?v={{ time() }}"
                alt="Certificações do mercado financeiro" fetchpriority="high">
            <img class="pf-hero-banner-img" src="{{ asset('assets/frontend/default/img/banner-full-herov2.png') }}?v={{ time() }}"
                alt="Certificações do mercado financeiro" fetchpriority="high">
            <img class="pf-hero-banner-img" src="{{ asset('assets/frontend/default/img/banner-full-herov3.png') }}?v={{ time() }}"
                alt="Certificações do mercado financeiro" fetchpriority="high">
            <div class="pf-hero-dots">
                <span class="pf-hero-dot active" data-index="0"></span>
                <span class="pf-hero-dot" data-index="1"></span>
                <span class="pf-hero-dot" data-index="2"></span>
            </div>
        </div>
    </div>
</section>

{{-- Seção de cursos V2 --}}
<section class="pf-cursos-v2" id="pf-cursos">
    <div class="container">
        {{-- Filtros V2 --}}
        <div class="pf-filters">
            <button type="button" class="pf-filter-btn active" data-tipo="todos">Todas</button>
            @foreach($toro_filters as $key => $label)
            <button type="button" class="pf-filter-btn" data-cat="{{ $key }}">{{ $label }}</button>
            @endforeach
        </div>

        {{-- Carrossel de cards (design original) --}}
        <div class="toro-cards-wrapper">
            <button type="button" class="toro-nav-arrow toro-nav-prev" aria-label="Anterior">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="toro-cards-carrossel" id="toro-cards-carrossel">
                @forelse ($toro_courses as $course)
                    @php
                    $avg_rating = $course->reviews->avg('rating') ?? 0;
                    $highlights = $course->features->take(2);
                    $price = $course->discount_flag == 1 ? $course->discounted_price : $course->price;
                    $price = $price > 0 ? $price : 0;
                    $monthly = round($price / 12, 2);
                    $monthly_int = floor($monthly);
                    $monthly_dec = str_pad(round(($monthly - $monthly_int) * 100), 2, '0', STR_PAD_LEFT);
                    @endphp
                    <div class="card-curso-toro card--integral" data-categorias="{{ strtoupper($course->category->title ?? '') }} {{ strtoupper($course->title ?? '') }}">
                        <div class="toro-card-image">
                            @if ($course->thumbnail)
                            <img src="{{ get_image($course->thumbnail) }}" alt="{{ $course->title }}">
                            @endif
                        </div>
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
                            <span class="large">GRÁTIS</span>
                        </div>
                        @endif
                        <div class="toro-card-btn-comprar">
                            @if ($course->is_paid == 1)
                            <a href="{{ route('purchase.course', $course->id) }}">{{ get_phrase('Comprar Agora') }}</a>
                            @else
                            <a href="{{ route('purchase.course', $course->id) }}">{{ get_phrase('Inscrever-se') }}</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="toro-carrossel-empty">
                        {{ get_phrase('Nossos cursos estão chegando! Em breve você encontrará aqui os preparatórios para as principais certificações do mercado financeiro.') }}
                    </div>
                @endforelse
            </div>
            <button type="button" class="toro-nav-arrow toro-nav-next" aria-label="Próximo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</section>

<script>
    "use strict";
    (function() {
        var filterBtns = document.querySelectorAll('.pf-filter-btn');
        var cards = document.querySelectorAll('.card-curso-toro');
        var carrossel = document.getElementById('toro-cards-carrossel');
        var prevBtn = document.querySelector('.toro-nav-prev');
        var nextBtn = document.querySelector('.toro-nav-next');

        function applyFilter() {
            var activeBtn = document.querySelector('.pf-filter-btn.active');
            if (!activeBtn) return;
            
            var tipo = activeBtn.getAttribute('data-tipo') || 'todos';
            var cat = activeBtn.getAttribute('data-cat') || '';

            cards.forEach(function(card) {
                var meta = (card.getAttribute('data-categorias') || '').toUpperCase();
                var show = true;
                
                if (tipo === 'livres') {
                    show = meta.indexOf('LIVRE') >= 0 || meta.indexOf('GRÁTIS') >= 0;
                }
                if (show && cat) {
                    show = meta.indexOf(cat.toUpperCase()) >= 0;
                }
                card.style.display = show ? '' : 'none';
            });
            
            setTimeout(updateArrowsState, 50);
        }

        // Navegação por setas
        function getScrollAmount() {
            var card = carrossel.querySelector('.card-curso-toro:not([style*="display: none"])');
            if (!card) return 348;
            var style = getComputedStyle(carrossel);
            var gap = parseInt(style.gap) || 28;
            return card.offsetWidth + gap;
        }

        function updateArrowsState() {
            if (!carrossel) return;
            var scrollLeft = carrossel.scrollLeft;
            var maxScroll = carrossel.scrollWidth - carrossel.clientWidth;
            if (prevBtn) prevBtn.disabled = scrollLeft <= 5;
            if (nextBtn) nextBtn.disabled = scrollLeft >= maxScroll - 5;
        }

        if (prevBtn && carrossel) {
            prevBtn.addEventListener('click', function() {
                carrossel.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
            });
        }

        if (nextBtn && carrossel) {
            nextBtn.addEventListener('click', function() {
                carrossel.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            });
        }

        if (carrossel) {
            carrossel.addEventListener('scroll', updateArrowsState);
            window.addEventListener('resize', updateArrowsState);
            setTimeout(updateArrowsState, 100);
        }

        filterBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterBtns.forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');
                applyFilter();
            });
        });

        // Slideshow do hero
        var heroImages = document.querySelectorAll('.pf-hero-banner-img');
        var heroDots = document.querySelectorAll('.pf-hero-dot');
        var currentSlide = 0;
        var slideshowInterval;
        
        function goToSlide(index) {
            heroImages[currentSlide].classList.remove('active');
            heroDots[currentSlide].classList.remove('active');
            currentSlide = index;
            heroImages[currentSlide].classList.add('active');
            heroDots[currentSlide].classList.add('active');
        }
        
        function nextSlide() {
            goToSlide((currentSlide + 1) % heroImages.length);
        }
        
        function startSlideshow() {
            slideshowInterval = setInterval(nextSlide, 8000);
        }
        
        function resetSlideshow() {
            clearInterval(slideshowInterval);
            startSlideshow();
        }
        
        // Navegação manual pelos dots
        heroDots.forEach(function(dot, index) {
            dot.addEventListener('click', function() {
                if (index !== currentSlide) {
                    goToSlide(index);
                    resetSlideshow();
                }
            });
        });
        
        // Iniciar slideshow automático
        if (heroImages.length > 1) {
            startSlideshow();
        }
    })();
</script>
