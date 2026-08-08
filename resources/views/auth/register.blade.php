@extends('layouts.' . get_frontend_settings('theme'))

@push('title', get_phrase('Criar conta'))

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/registration_profile_fields.css') }}?v=20260808-1">
@endpush

@section('content')
    <section class="pf-auth pf-auth-register" aria-labelledby="auth-title">
        <div class="container pf-auth-container">
            <div class="pf-auth-shell">
                <aside class="pf-auth-visual" aria-label="Preparação para certificações financeiras">
                    <div class="pf-auth-visual-content">
                        <a href="{{ route('home') }}" class="pf-auth-home-link">
                            <span aria-hidden="true">←</span> {{ get_phrase('Voltar para a página inicial') }}
                        </a>
                        <div class="pf-auth-visual-copy">
                            <span class="pf-auth-kicker"><span></span>{{ get_phrase('Comece hoje') }}</span>
                            <h2>{{ get_phrase('Seu plano de aprovação começa aqui.') }}</h2>
                            <p>{{ get_phrase('Crie sua conta e tenha uma preparação estruturada para a sua próxima certificação.') }}</p>
                        </div>
                        <div class="pf-auth-proof">
                            <span class="pf-auth-proof-icon" aria-hidden="true">✓</span>
                            <span>{{ get_phrase('Estude no seu ritmo, com foco no que realmente importa') }}</span>
                        </div>
                    </div>
                </aside>

                <div class="pf-auth-form-column">
                    <form action="{{ route('register') }}" class="pf-auth-form" id="register-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="pf-auth-heading">
                            <span class="pf-auth-kicker pf-auth-kicker-dark"><span></span>{{ get_phrase('Criar conta') }}</span>
                            <h1 id="auth-title">{{ get_phrase('Prepare-se para chegar mais longe.') }}</h1>
                            <p>{{ get_phrase('Leva menos de um minuto para começar.') }}</p>
                        </div>

                        <div class="pf-auth-field-row">
                            <div class="pf-auth-field">
                                <label for="name">Nome</label>
                                <div class="pf-auth-input">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.5"></circle><path d="M4.5 20c.8-3.6 3.3-5.5 7.5-5.5s6.7 1.9 7.5 5.5"></path></svg>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Seu nome" required autocomplete="given-name">
                                </div>
                                @error('name')<span class="pf-auth-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="pf-auth-field">
                                <label for="last_name">Sobrenome</label>
                                <div class="pf-auth-input">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.5"></circle><path d="M4.5 20c.8-3.6 3.3-5.5 7.5-5.5s6.7 1.9 7.5 5.5"></path></svg>
                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Seu sobrenome" required autocomplete="family-name">
                                </div>
                                @error('last_name')<span class="pf-auth-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="pf-auth-field-row">
                            <div class="pf-auth-field">
                                <label for="cpf">CPF</label>
                                <div class="pf-auth-input">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M7 10h4M7 14h7"></path></svg>
                                    <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00" required inputmode="numeric" maxlength="14" autocomplete="off">
                                </div>
                                @error('cpf')<span class="pf-auth-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="pf-auth-field">
                                <label for="phone">Celular</label>
                                <div class="pf-auth-input">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M8 3h8a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path><path d="M10 18h4"></path></svg>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="(00) 00000-0000" required autocomplete="tel" inputmode="tel" maxlength="15">
                                </div>
                                @error('phone')<span class="pf-auth-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="pf-auth-field">
                            <label for="email">{{ get_phrase('E-mail') }}</label>
                            <div class="pf-auth-input">
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="{{ get_phrase('seu@email.com') }}" required autocomplete="email">
                            </div>
                            @error('email')<span class="pf-auth-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="pf-auth-field">
                            <label for="password">{{ get_phrase('Crie uma senha') }}</label>
                            <div class="pf-auth-input">
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg>
                                <input type="password" id="password" name="password" placeholder="{{ get_phrase('Mínimo de 8 caracteres') }}" required autocomplete="new-password">
                                <button type="button" class="pf-auth-password-toggle" data-password-toggle="password" aria-label="{{ get_phrase('Mostrar senha') }}">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg>
                                </button>
                            </div>
                            @error('password')<span class="pf-auth-error">{{ $message }}</span>@enderror
                        </div>

                        @if (get_settings('allow_instructor'))
                            <label class="pf-auth-check pf-auth-check-instructor" for="instructor">
                                <input id="instructor" type="checkbox" name="instructor">
                                <span>{{ get_phrase('Quero me candidatar como instrutor') }}</span>
                            </label>

                            <div id="become-instructor-fields" class="pf-auth-instructor-fields d-none">
                                <div class="pf-auth-field">
                                    <label for="document">{{ get_phrase('Comprovante de qualificação') }}</label>
                                    <div class="pf-auth-file"><input id="document" type="file" name="document" accept=".doc,.docx,.pdf,.txt,.png,.jpg,.jpeg"></div>
                                    <small>{{ get_phrase('Formatos aceitos: doc, docx, pdf, txt, png, jpg e jpeg.') }}</small>
                                </div>
                                <div class="pf-auth-field">
                                    <label for="description">{{ get_phrase('Conte-nos sobre sua experiência') }}</label>
                                    <textarea id="description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                        @endif

                        @if (get_frontend_settings('recaptcha_status'))
                            <button class="pf-auth-submit g-recaptcha" data-sitekey="{{ get_frontend_settings('recaptcha_sitekey') }}" data-callback="onRegisterSubmit" data-action="submit">{{ get_phrase('Criar minha conta') }} <span aria-hidden="true">→</span></button>
                        @else
                            <button type="submit" class="pf-auth-submit">{{ get_phrase('Criar minha conta') }} <span aria-hidden="true">→</span></button>
                        @endif

                        <p class="pf-auth-switch">{{ get_phrase('Já tem uma conta?') }} <a href="{{ route('login') }}">{{ get_phrase('Entrar') }}</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        function onRegisterSubmit() {
            document.getElementById('register-form').submit();
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var input = document.getElementById(this.dataset.passwordToggle);
                    input.type = input.type === 'password' ? 'text' : 'password';
                    this.setAttribute('aria-label', input.type === 'password' ? '{{ get_phrase('Mostrar senha') }}' : '{{ get_phrase('Ocultar senha') }}');
                });
            });

            var instructor = document.getElementById('instructor');
            if (instructor) {
                instructor.addEventListener('change', function () {
                    document.getElementById('become-instructor-fields').classList.toggle('d-none', !this.checked);
                    document.getElementById('document').required = this.checked;
                });
            }

            var cpf = document.getElementById('cpf');
            cpf.addEventListener('input', function () {
                var digits = this.value.replace(/\D/g, '').slice(0, 11);
                this.value = digits.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            });

            var phone = document.getElementById('phone');
            phone.addEventListener('input', function () {
                var digits = this.value.replace(/\D/g, '').slice(0, 11);
                this.value = digits.replace(/^(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
            });
        });
    </script>
@endpush
