@extends('layouts.default')
@push('title', get_phrase('My Ebooks'))
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
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('My Ebooks') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('My Ebooks') }}</h3>
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
                <div class="row">

                    @foreach ($my_ebooks as $ebook)
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-30">
                        <div class="card Ecard g-card c-card">
                            <div class="card-head">
                                <img src="{{ get_image($ebook->thumbnail) }}" alt="ebook-thumbnail">
                            </div>
                            <div class="card-body entry-details">

                                <div class="info-card mb-15">
                                    <div class="creator">
                                        <img src="{{ get_image($ebook->user_photo) }}" alt="author-image">
                                        <h5>{{ $ebook->user_name }}</h5>
                                    </div>
                                </div>

                                <div class="entry-title">
                                    <a href="{{ route('ebook.details', $ebook->slug) }}">
                                        <h3 class="w-100 ellipsis-line-2">{{ ucfirst($ebook->title) }}</h3>
                                    </a>
                                </div>

                                <a href="{{ route('my.ebooks.read', $ebook->slug) }}" class="eBtn learn-btn w-100 text-center mt-20 f-500">
                                    {{ get_phrase('Read Now') }}
                                </a>

                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if (count($my_ebooks) == 0)
                    <div class="col-12 bg-white radius-10 py-5 shadow-lg">
                        @include('frontend.default.empty')
                    </div>
                    @endif

                </div>

                <!-- Pagination -->
                @if (count($my_ebooks) > 0)
                <div class="entry-pagination">
                    <nav aria-label="Page navigation example">
                        {{ $my_ebooks->links() }}
                    </nav>
                </div>
                @endif
                <!-- Pagination -->

            </div>
        </div>
    </div>
</div>
<!-------------- List Item End  --------------->

@endsection