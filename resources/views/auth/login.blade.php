@extends('layouts.' . get_frontend_settings('theme'))

@push('title', get_phrase('Entrar'))

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <section class="pf-auth" aria-labelledby="auth-title">
        <div class="container pf-auth-container">
            <div class="pf-auth-shell">
                <aside class="pf-auth-visual" aria-label="Preparação para certificações financeiras">
                    <div class="pf-auth-visual-content">
                        <a href="{{ route('home') }}" class="pf-auth-home-link">
                            <span aria-hidden="true">←</span> {{ get_phrase('Voltar para a página inicial') }}
                        </a>
                        <div class="pf-auth-visual-copy">
                            <span class="pf-auth-kicker"><span></span>{{ get_phrase('Sua jornada continua') }}</span>
                            <h2>{{ get_phrase('A próxima aprovação começa com um acesso.') }}</h2>
                            <p>{{ get_phrase('Retome seus estudos, simulados e materiais em um único lugar.') }}</p>
                        </div>
                        <div class="pf-auth-proof">
                            <span class="pf-auth-proof-icon" aria-hidden="true">✓</span>
                            <span>{{ get_phrase('Conteúdo focado nas principais certificações do mercado financeiro') }}</span>
                        </div>
                    </div>
                </aside>

                <div class="pf-auth-form-column">
                    <form action="{{ route('login') }}" method="POST" class="pf-auth-form" id="login-form">
                        @csrf
                        <input type="hidden" id="user_agent" name="user_agent">

                        <div class="pf-auth-heading">
                            <span class="pf-auth-kicker pf-auth-kicker-dark"><span></span>{{ get_phrase('Acesso à plataforma') }}</span>
                            <h1 id="auth-title">{{ get_phrase('Que bom ter você de volta.') }}</h1>
                            <p>{{ get_phrase('Entre para continuar sua preparação.') }}</p>
                        </div>

                        <div class="pf-auth-field">
                            <label for="email">{{ get_phrase('E-mail') }}</label>
                            <div class="pf-auth-input">
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="{{ get_phrase('seu@email.com') }}" required autofocus autocomplete="email">
                            </div>
                            @error('email')<span class="pf-auth-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="pf-auth-field">
                            <div class="pf-auth-label-row">
                                <label for="password">{{ get_phrase('Senha') }}</label>
                                <a href="{{ route('password.request') }}">{{ get_phrase('Esqueceu a senha?') }}</a>
                            </div>
                            <div class="pf-auth-input">
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg>
                                <input type="password" id="password" name="password" placeholder="{{ get_phrase('Digite sua senha') }}" required autocomplete="current-password">
                                <button type="button" class="pf-auth-password-toggle" data-password-toggle="password" aria-label="{{ get_phrase('Mostrar senha') }}">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg>
                                </button>
                            </div>
                            @error('password')<span class="pf-auth-error">{{ $message }}</span>@enderror
                        </div>

                        <label class="pf-auth-check" for="remember">
                            <input type="checkbox" name="remember" id="remember" checked>
                            <span>{{ get_phrase('Manter meu acesso neste dispositivo') }}</span>
                        </label>

                        @if (get_frontend_settings('recaptcha_status'))
                            <button class="pf-auth-submit g-recaptcha" data-sitekey="{{ get_frontend_settings('recaptcha_sitekey') }}" data-callback="onLoginSubmit" data-action="submit">{{ get_phrase('Entrar na plataforma') }} <span aria-hidden="true">→</span></button>
                        @else
                            <button type="submit" class="pf-auth-submit">{{ get_phrase('Entrar na plataforma') }} <span aria-hidden="true">→</span></button>
                        @endif

                        <p class="pf-auth-switch">{{ get_phrase('Ainda não tem uma conta?') }} <a href="{{ route('register.form') }}">{{ get_phrase('Criar minha conta') }}</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        function onLoginSubmit() {
            document.getElementById('login-form').submit();
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var input = document.getElementById(this.dataset.passwordToggle);
                    input.type = input.type === 'password' ? 'text' : 'password';
                    this.setAttribute('aria-label', input.type === 'password' ? '{{ get_phrase('Mostrar senha') }}' : '{{ get_phrase('Ocultar senha') }}');
                });
            });

            if (!localStorage.getItem('device_token')) {
                localStorage.setItem('device_token', crypto.randomUUID());
            }
            document.getElementById('user_agent').value = localStorage.getItem('device_token');
        });
    </script>
@endpush
