@extends('layouts.default')
@push('title', get_phrase('Wishlist'))
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
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('Wishlist') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('Wishlisted courses') }}</h3>
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

                    @foreach ($wishlist as $wishitem)
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-30">
                        <div class="card Ecard g-card c-card wish-card">
                            <div class="card-head">
                                <img src="{{ get_image($wishitem->course_thumbnail) }}" alt="{{ get_phrase('course_thumbnail') }}">
                            </div>
                            <div class="card-body entry-details">

                                <div class="info-card mb-15">
                                    <div class="creator">
                                        <img src="{{ get_image($wishitem->users_photo) }}" alt="{{ get_phrase('user_photo') }}">
                                        <h5>{{ $wishitem->user_name }}</h5>
                                    </div>
                                    <span data-bs-toggle="tooltip" data-bs-title="{{ get_phrase('Remove from wishlist') }}" class="heart fill-heart toggleWishItem" id="item-{{ $wishitem->course_id }}">
                                        <i class="fa-solid fa-heart"></i>
                                    </span>
                                </div>

                                <div class="entry-title">
                                    <a href="{{ route('course.details', $wishitem->slug) }}">
                                        <h3 class="w-100 ellipsis-line-2">{{ $wishitem->title }}</h3>
                                    </a>
                                </div>

                                <div class="ct-text">
                                    <h4>
                                        @if ($wishitem->is_paid == 0)
                                        {{ get_phrase('Free') }}
                                        @else
                                        @if ($wishitem->discount_flag == 1)
                                        @php $discounted_price = number_format(($wishitem->discounted_price), 2) @endphp
                                        {{ currency($discounted_price) }}
                                        @else
                                        {{ currency($wishitem->price, 2) }}
                                        @endif
                                        @endif
                                    </h4>
                                    <p><span>4.8</span><i class="fa fa-star"></i></p>
                                </div>

                                <a href="{{ route('course.details', $wishitem->slug) }}" class="eBtn learn-btn w-100 text-center mt-20 f-500">
                                    {{ get_phrase('View Course') }}
                                </a>

                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if ($wishlist->count() == 0)
                    <div class="col-12 bg-white radius-10 py-5 shadow-lg">
                        @include('frontend.default.empty')
                    </div>
                    @endif

                </div>

                <!-- Pagination -->
                @if (count($wishlist) > 0)
                <div class="entry-pagination">
                    <nav aria-label="Page navigation example">
                        {{ $wishlist->links() }}
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
@push('js')@endpush