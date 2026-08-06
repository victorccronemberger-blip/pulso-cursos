{{-- To make a editable image or text need to be add a "builder editable" class and builder identity attribute with a unique value --}}
{{-- builder identity and builder editable --}}
{{-- builder identity value have to be unique under a single file --}}

<section class="support-section ">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            {{-- Large Card - Support Contact --}}
            <div class="col-lg-7 col-md-12 d-flex">
                <div class="support-card support-card-large">
                    <div class="support-card-content">
                        <div class="support-text">
                            <h2 class="support-title builder-editable" builder-identity="title_1">
                                {{ get_phrase('Estamos ao seu lado em qualquer problema') }}
                            </h2>
                            <p class="support-description builder-editable" builder-identity="desc_1">
                                {{ get_phrase('Dúvidas sobre cursos, ajuda com pagamento ou qualquer problema técnico — nossa equipe de suporte está sempre pronta.') }}
                            </p>
                            <div class="support-availability">
                                <div class="availability-badge">
                                    <span class="availability-text builder-editable" builder-identity="avail_1">{{ get_phrase('Suporte 24/7') }}</span>
                                </div>
                            </div>
                            <a href="javascript:void(0);"
                                class="darkBtn d-inline-flex align-items-center gap-2 w-auto align-self-start builder-editable"
                                builder-identity="btn_1">
                                {{ get_phrase('Falar com o suporte') }}
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                        <div class="support-image">
                            <img src="{{ asset('assets/frontend/default/image/support.png') }}" alt="Support Representative">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Small Cards --}}
            <div class="col-lg-5 col-md-12 d-flex">
                <div class="support-cards-small">
                    {{-- YouTube Card --}}
                    <div class="support-card support-card-small youtube" style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.4) 0%, rgba(255, 208, 208, 0.6) 50%, rgba(255, 130, 140, 0.8) 100%);">
                        <div class="support-card-small-content">
                            <div class="text-content">
                                <h3 class="support-title-small builder-editable" builder-identity="title_2">
                                    {{ get_phrase('Aprenda assistindo vídeos grátis') }}
                                </h3>
                                <a href="javascript:void(0);" class="support-btn-small builder-editable" builder-identity="btn_2">
                                    {{ get_phrase('Assistir vídeos') }}
                                </a>
                            </div>
                            <div class="support-icon">
                                <i class="fab fa-youtube"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Facebook Card --}}
                    <div class="support-card support-card-small facebook" style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.4) 0%, rgba(176, 218, 255, 0.6) 50%, rgba(56, 130, 248, 0.8) 100%);">
                        <div class="support-card-small-content">
                            <div class="text-content">
                                <h3 class="support-title-small builder-editable" builder-identity="title_3">
                                    {{ get_phrase('Junte-se à nossa comunidade') }}
                                </h3>
                                <a href="javascript:void(0);" class="support-btn-small builder-editable" builder-identity="btn_3">
                                    {{ get_phrase('Entrar no grupo') }}
                                </a>
                            </div>
                            <div class="support-icon">
                                <i class="fab fa-facebook-f"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>