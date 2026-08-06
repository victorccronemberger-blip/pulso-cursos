@extends('layouts.default')
@push('title', get_phrase('Become an instructor'))
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
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ get_phrase('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('Become an instructor') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('Become an instructor') }}</h3>
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

                <div class="my-panel message-panel edit_profile">
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
                                    <small class="ps-3 text-secondary">{{ get_phrase('Documents of qualification. Max-size : 5MB (DOC, DOCX, PDF, TXT, PNG, JPG, JPEG)') }}</small>
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