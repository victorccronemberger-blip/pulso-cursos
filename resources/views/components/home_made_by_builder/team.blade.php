@php
$team_members = App\Models\TeamMember::where('status', 1)
->orderBy('sort_order', 'asc')
->orderBy('id', 'asc')
->get();
@endphp

<section class="xt-team-section">
    <div class="container">

        <div class="xt-team-head">
            <h2 class="xt-team-title builder-editable" builder-identity="1">{{ get_phrase('Nossa equipe de especialistas') }}</h2>
            <a class="xt-team-viewall btn-gradient builder-editable" builder-identity="3">
                {{ get_phrase('Ver todos') }}
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                    <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </div>

        <div class="swiper xt-swiper">
            <div class="swiper-wrapper">
                @foreach ($team_members as $member)
                <div class="swiper-slide">
                    <div class="xt-card">
                        <div class="xt-card-img">
                            <img src="{{ get_image($member->photo) }}" alt="{{ $member->name }}">
                            <div class="xt-card-overlay">
                                <h4 class="xt-card-name">{{ $member->name }}</h4>
                                <p class="xt-card-role">{{ $member->designation }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="xt-swiper-pagination"></div>
        </div>

    </div>
</section>

@push('js')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.xt-swiper', {
            slidesPerView: 4,
            spaceBetween: 20,
            loop: true,
            grabCursor: true,
            speed: 500,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.xt-swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 14
                },
                576: {
                    slidesPerView: 2,
                    spaceBetween: 16
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 18
                },
                992: {
                    slidesPerView: 4,
                    spaceBetween: 20
                },
            },
        });
    });
</script>
@endpush