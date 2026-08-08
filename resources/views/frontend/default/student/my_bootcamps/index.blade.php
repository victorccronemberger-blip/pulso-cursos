@extends('layouts.default')
@push('title', get_phrase('My Bootcamps'))
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
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('My Bootcamps') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('My Bootcamps') }}</h3>
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

                    @foreach ($my_bootcamps as $bootcamp)
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-30">
                        <div class="card Ecard g-card c-card">
                            <div class="card-head">
                                <img src="{{ get_image($bootcamp->thumbnail) }}" alt="bootcamp-thumbnail">
                            </div>
                            <div class="card-body entry-details">

                                <div class="entry-title">
                                    <a href="{{ route('my.bootcamp.details', $bootcamp->slug) }}">
                                        <h3 class="w-100 ellipsis-line-2">{{ ucfirst($bootcamp->title) }}</h3>
                                    </a>
                                </div>

                                <div class="class-details pt-3">
                                    <div class="d-flex gap-3 justify-content-between">
                                        <div class="class-status">
                                            <span class="text-capitalize">{{ get_phrase('Published') }}:</span>
                                        </div>
                                        <div class="class-status">
                                            <span class="badge bg-success text-capitalize">{{ date('d M, Y', $bootcamp->publish_date) }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3 justify-content-between mt-2">
                                        <div class="class-status">
                                            <span class="text-capitalize">{{ get_phrase('Live Classes') }}:</span>
                                        </div>
                                        <div class="class-status">
                                            <span class="badge bg-success text-capitalize">{{ count_bootcamp_classes($bootcamp->id) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('my.bootcamp.details', $bootcamp->slug) }}" class="eBtn learn-btn w-100 text-center mt-20 f-500">
                                    {{ get_phrase('View Bootcamp') }}
                                </a>

                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if (count($my_bootcamps) == 0)
                    <div class="col-12 bg-white radius-10 py-5 shadow-lg">
                        @include('frontend.default.empty')
                    </div>
                    @endif

                </div>

                <!-- Pagination -->
                @if (count($my_bootcamps) > 0)
                <div class="entry-pagination">
                    <nav aria-label="Page navigation example">
                        {{ $my_bootcamps->links() }}
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
