@extends('layouts.default')
@push('title', get_phrase('Bootcamps'))
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
                            <li class="breadcrumb-item"><a href="#">{{ get_phrase('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('All Bootcamps') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('All Bootcamps') }}</h3>
                        </div>
                        <div class="col-auto col-md-4 col-lg-4">
                            <span class="showing-text mt-4 d-inline-block">{{ get_phrase('Showing') . ' ' . count($bootcamps) . ' ' . get_phrase('of') . ' ' . $bootcamps->total() . ' ' . get_phrase('data') }}</span>
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
            <div class="col-lg-3 col-md-4">
                @include('frontend.default.bootcamp.filter')
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="row">
                    @foreach ($bootcamps as $bootcamp)
                    @include('frontend.default.bootcamp.bootcamp_grid')
                    @endforeach
                    @if ($bootcamps->count() == 0)
                    <div class="col-12 bg-white radius-10 py-5 shadow-lg">
                         @include('frontend.default.empty')
                        @include('frontend.default.empty')
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if (count($bootcamps) > 0)
        <div class="entry-pagination">
            <nav aria-label="Page navigation example">
                {{ $bootcamps->links() }}
            </nav>
        </div>
        @endif
        <!-- Pagination -->
    </div>
</div>
<!-------------- List Item End  --------------->
@endsection
@push('js')@endpush