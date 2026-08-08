@extends('layouts.default')
@push('title', 'Meu perfil')
@push('meta')@endpush
@push('css')@endpush

@section('content')
@php
    $cpfDigits = preg_replace('/\D+/', '', (string) $user_details->cpf);
    $cpfDisplay = strlen($cpfDigits) === 11
        ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpfDigits)
        : ((string) $user_details->cpf ?: 'Não cadastrado');
@endphp

@include('frontend.default.student.page_header', [
    'title' => 'Meu perfil',
    'current' => 'Meu perfil',
    'description' => 'Consulte seus dados de identificação e mantenha seus contatos atualizados.',
])

<div class="eNtery-item">
    <div class="container">
        <div class="row">
            @include('frontend.default.student.left_sidebar')

            <div class="col-lg-9 col-md-8">
                <section class="my-panel message-panel edit_profile pf-form-panel pf-profile-panel mb-4" aria-labelledby="profile-data-title">
                    <div class="pf-panel-intro">
                        <h2 id="profile-data-title">Dados cadastrais</h2>
                        <p>As informações protegidas identificam sua conta. Os demais dados podem ser atualizados quando necessário.</p>
                    </div>

                    <div class="pf-identity-grid" aria-label="Dados protegidos da conta">
                        <div class="pf-readonly-field">
                            <div class="pf-readonly-label"><span>E-mail</span><strong>Protegido</strong></div>
                            <p>{{ $user_details->email ?: 'Não cadastrado' }}</p>
                            <small>Usado para acesso e comunicações da plataforma.</small>
                        </div>
                        <div class="pf-readonly-field">
                            <div class="pf-readonly-label"><span>CPF</span><strong>Protegido</strong></div>
                            <p>{{ $cpfDisplay }}</p>
                            <small>Alterações exigem validação da equipe de suporte.</small>
                        </div>
                    </div>

                    <div class="pf-profile-editable-head">
                        <h3>Informações editáveis</h3>
                        <p>Esses dados aparecem no atendimento e nas comunicações sobre seus cursos.</p>
                    </div>

                    <form action="{{ route('update.profile', $user_details->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nome</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $first_name) }}" id="name" autocomplete="given-name" required>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="last_name" class="form-label">Sobrenome</label>
                                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $last_name) }}" id="last_name" autocomplete="family-name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Número de celular</label>
                                    <input type="tel" class="form-control" name="phone" value="{{ old('phone', $user_details->phone) }}" id="phone" autocomplete="tel" inputmode="tel" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="linkedin" class="form-label">LinkedIn</label>
                                    <input type="url" class="form-control" name="linkedin" value="{{ old('linkedin', $user_details->linkedin) }}" id="linkedin" autocomplete="url" placeholder="linkedin.com/in/seu-perfil">
                                </div>
                            </div>
                        </div>
                        <button class="eBtn btn gradient mt-10" type="submit">Salvar alterações</button>
                    </form>
                </section>

                <section class="my-panel message-panel edit_profile pf-form-panel pf-security-panel" aria-labelledby="security-title">
                    <div class="pf-panel-intro">
                        <h2 id="security-title">Segurança da conta</h2>
                        <p>Use uma senha exclusiva para proteger seus cursos, certificados e dados pessoais.</p>
                    </div>
                    <form action="{{ route('password.change') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-20"><label class="form-label" for="current_password">Senha atual</label><input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password" required></div>
                            <div class="col-lg-12 mb-20"><label class="form-label" for="new_password">Nova senha</label><input type="password" class="form-control" id="new_password" name="new_password" autocomplete="new-password" required></div>
                            <div class="col-lg-12 mb-20"><label class="form-label" for="confirm_password">Confirmar nova senha</label><input type="password" class="form-control" id="confirm_password" name="confirm_password" autocomplete="new-password" required></div>
                        </div>
                        <button class="eBtn btn gradient mt-10" type="submit">Atualizar senha</button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
