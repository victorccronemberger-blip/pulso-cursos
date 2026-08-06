{{-- To make a editable image or text need to be add a "builder editable" class and builder identity attribute with a unique value --}}
{{-- builder identity and builder editable --}}
{{-- builder identity value have to be unique under a single file --}}

@if (get_frontend_settings('recaptcha_status'))
@push('js')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endif

<footer class="footer-area-new mt-5">
    <div class="container">
        <div class="row gx-lg-5 gx-4">
            <!-- Left Column - Logo and Description -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="footer-brand ">
                    <div class="footer-logo-wrapper">
                        <img src="{{ get_image(get_frontend_settings('light_logo')) }}" alt="system logo" class="footer-logo">
                    </div>
                    <p class="footer-description builder-editable" builder-identity="footer-desc">
                        {{ get_phrase('Adquira habilidades de nível mundial de qualquer lugar do país e leve sua carreira a novos patamares.') }}
                    </p>
                </div>
            </div>

            <!-- Column 2 - Empresa (Company) -->
            <div class="col-lg-3 col-md-6 col-6 mb-4 mb-lg-0">
                <div class="footer-widget-new">
                    <h4 class="footer-widget-title builder-editable" builder-identity="footer-company">
                        {{ get_phrase('Empresa') }}
                    </h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('courses') }}">{{ get_phrase('Todos os cursos') }}</a></li>
                        <li><a href="{{ route('privacy.policy') }}">{{ get_phrase('Política de privacidade') }}</a></li>
                        <li><a href="{{ route('refund.policy') }}">{{ get_phrase('Política de reembolso e cancelamento') }}</a></li>
                        <li><a href="{{ route('terms.condition') }}">{{ get_phrase('Termos de uso') }}</a></li>
                        <li><a href="{{ route('contact.us') }}">{{ get_phrase('Carreiras e contato') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Column 3 - Sobre nós (About Us) -->
            <div class="col-lg-2 col-md-6 col-6 mb-4 mb-lg-0">
                <div class="footer-widget-new">
                    <h4 class="footer-widget-title builder-editable" builder-identity="footer-about">
                        {{ get_phrase('Recursos úteis') }}
                    </h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('about.us') }}">{{ get_phrase('Sobre nós') }}</a></li>
                        {{-- <li><a href="{{ route('blogs') }}">{{ get_phrase('Blog') }}</a></li> --}}
                        <li><a href="{{ route('privacy.policy') }}">{{ get_phrase('Política de privacidade') }}</a></li>
                        <li><a href="{{ route('faq') }}">{{ get_phrase('Perguntas frequentes') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Column 4 - Contato (Contact) -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="footer-widget-new">
                    <h4 class="footer-widget-title builder-editable" builder-identity="footer-contact">
                        {{ get_phrase('Fale conosco') }}
                    </h4>
                    <ul class="footer-contact-info">
                        <li class="contact-item">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z" stroke="#BCBBCA" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M17 9L13.87 11.5C12.84 12.32 11.15 12.32 10.12 11.5L7 9" stroke="#BCBBCA" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="contact-text">
                                <a href="mailto:{{ get_settings('system_email') ?? 'support@creativeitem.com' }}" class="builder-editable" builder-identity="footer-email">
                                    {{ get_settings('system_email') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom-new">
        <div class="container">
            <div class="footer-bottom-content d-flex justify-content-center align-items-center" style="min-height:20px;">
                <p class="copyright-text builder-editable text-center" builder-identity="footer-copyright">
                    {{ get_phrase('© 2024 Todos os direitos reservados.') }}
                </p>
            </div>
        </div>
    </div>
</footer>

@push('js')
<script>
    "use strict";

    function onNewslaterSubmit(token) {
        document.getElementById("newslater-form").submit();
    }
</script>
@endpush