@extends('layouts.default')
@push('title', get_phrase('Become an instructor'))
@push('meta')@endpush
@push('css')@endpush
@section('content')

@include('frontend.default.student.page_header', [
    'title' => 'Quero ensinar',
    'current' => 'Quero ensinar',
    'description' => 'Envie seus dados para análise da equipe acadêmica.',
])

<!-------------- List Item Start   --------------->
<div class="eNtery-item">
    <div class="container">
        <div class="row">
            @include('frontend.default.student.left_sidebar')

            <div class="col-lg-9 col-md-8">

                <div class="my-panel message-panel edit_profile pf-form-panel pf-application-panel">
                    <div class="pf-panel-intro">
                        <h2>Informações da candidatura</h2>
                        <p>Apresente sua experiência e envie um documento que ajude nossa equipe a avaliar seu perfil.</p>
                    </div>
                    <form action="{{ route('become.instructor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label for="phone" class="form-label">{{ get_phrase('Phone Number') }}</label>
                                    <input type="tel"
                                        class="form-control @error('phone') border border-danger @enderror"
                                        name="phone"
                                        id="phone"
                                        placeholder="{{ get_phrase('+0 (123) 456 - 7890') }}">
                                </div>
                            </div>

                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label for="document" class="form-label">{{ get_phrase('Document') }}</label>
                                    <input type="file"
                                        class="form-control @error('document') border border-danger @enderror"
                                        name="document"
                                        id="document">
                                    <small class="pf-form-help">Envie um documento de qualificação com até 5 MB nos formatos DOC, DOCX, PDF, TXT, PNG, JPG ou JPEG.</small>
                                </div>
                            </div>

                            <div class="col-lg-12 mb-20">
                                <div class="form-group">
                                    <label for="description" class="form-label">{{ get_phrase('Description') }}</label>
                                    <textarea name="description"
                                        class="form-control @error('description') border border-danger @enderror"
                                        id="description"
                                        cols="30"
                                        rows="5"
                                        placeholder="{{ get_phrase('Your description here...') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        <button class="eBtn btn gradient mt-10">{{ get_phrase('Apply for instructor') }}</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<!-------------- List Item End  --------------->

@endsection
@push('js')@endpush
