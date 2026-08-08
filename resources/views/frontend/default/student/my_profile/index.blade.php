@extends('layouts.default')
@push('title', get_phrase('My profile'))
@push('meta')@endpush
@push('css')@endpush
@section('content')

<!------------------- Breadcum Area Start  ------>
<section class="breadcum-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="eNtry-breadcum">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('my.courses') }}">Área do aluno</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('My profile') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('My profile') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!------------------- Breadcum Area End  --------->

<!-------------- List Item Start   --------------->
<div class="eNtery-item">
    <div class="container">
        <div class="row">
            @include('frontend.default.student.left_sidebar')

            <div class="col-lg-9 col-md-8">

                <!-- Personal Information -->
                <div class="my-panel message-panel edit_profile mb-4">
                    <h4 class="g-title mb-5">{{ get_phrase('Personal Information') }}</h4>
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
                <div class="my-panel message-panel edit_profile">
                    <h4 class="g-title mb-5">{{ get_phrase('Change Password') }}</h4>
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
