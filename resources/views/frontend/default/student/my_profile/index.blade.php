@extends('layouts.default')
@push('title', get_phrase('My profile'))
@push('meta')@endpush
@push('css')@endpush
@section('content')

@include('frontend.default.student.page_header', [
    'title' => 'Meu perfil',
    'current' => 'Meu perfil',
    'description' => 'Mantenha seus dados e sua senha atualizados.',
])

<!-------------- List Item Start   --------------->
<div class="eNtery-item">
    <div class="container">
        <div class="row">
            @include('frontend.default.student.left_sidebar')

            <div class="col-lg-9 col-md-8">

                <!-- Personal Information -->
                <div class="my-panel message-panel edit_profile pf-form-panel mb-4">
                    <div class="pf-panel-intro">
                        <h2>Dados pessoais</h2>
                        <p>Atualize as informações usadas na sua conta e na comunicação com a equipe acadêmica.</p>
                    </div>
                    <form action="{{ route('update.profile', $user_details->id) }}" method="POST">@csrf
                        <div class="row">
                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ get_phrase('Full Name') }}</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user_details->name }}" id="name">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="email" class="form-label">{{ get_phrase('Email Address') }}</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user_details->email }}" id="email">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="phone" class="form-label">{{ get_phrase('Phone Number') }}</label>
                                    <input type="tel" class="form-control" name="phone" value="{{ $user_details->phone }}" id="phone">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="website" class="form-label">{{ get_phrase('Website') }}</label>
                                    <input type="text" class="form-control" name="website" value="{{ $user_details->website }}" id="website">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="facebook" class="form-label">{{ get_phrase('Facebook') }}</label>
                                    <input type="text" class="form-control" name="facebook" value="{{ $user_details->facebook }}" id="facebook">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="twitter" class="form-label">{{ get_phrase('Twitter') }}</label>
                                    <input type="text" class="form-control" name="twitter" value="{{ $user_details->twitter }}" id="twitter">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <div class="form-group">
                                    <label for="linkedin" class="form-label">{{ get_phrase('Linkedin') }}</label>
                                    <input type="text" class="form-control" name="linkedin" value="{{ $user_details->linkedin }}" id="linkedin">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label for="skills" class="form-label">{{ get_phrase('Skills') }}</label>
                                    <input type="text" class="form-control tagify" name="skills" data-role="tagsinput" value="{{ $user_details->skills }}" id="skills">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label for="biography" class="form-label">{{ get_phrase('Biography') }}</label>
                                    <textarea name="biography" class="form-control" id="biography" cols="30" rows="5">{{ $user_details->biography }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button class="eBtn btn gradient mt-10">{{ get_phrase('Save Changes') }}</button>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="my-panel message-panel edit_profile pf-form-panel pf-security-panel">
                    <div class="pf-panel-intro">
                        <h2>Segurança da conta</h2>
                        <p>Use uma senha exclusiva para proteger seus cursos, certificados e dados pessoais.</p>
                    </div>
                    <form action="{{ route('password.change') }}" method="POST">@csrf
                        <div class="row">
                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label class="form-label">{{ get_phrase('Current password') }}</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label class="form-label">{{ get_phrase('New password') }}</label>
                                    <input type="password" class="form-control" name="new_password" required>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label class="form-label">{{ get_phrase('Confirm password') }}</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                            </div>
                        </div>
                        <button class="eBtn btn gradient mt-10">{{ get_phrase('Update password') }}</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<!-------------- List Item End  --------------->

@endsection
