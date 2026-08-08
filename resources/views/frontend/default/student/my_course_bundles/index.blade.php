@extends('layouts.default')
@push('title', get_phrase('My Course Bundles'))
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
                            <li class="breadcrumb-item">
                                <a href="{{ route('my.courses') }}">Área do aluno</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ get_phrase('My Course Bundles') }}
                            </li>
                        </ol>
                    </nav>

                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">
                                {{ get_phrase('My Course Bundles') }}
                            </h3>
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

                    @if (count($my_course_bundles) > 0)

                    @foreach ($my_course_bundles as $bundle)

                    @php
                    $userId = session('user_id');
                    $course_ids = json_decode($bundle->course_ids);
                    $total_courses_price = 0;
                    @endphp

                    <div class="col-lg-6 col-md-6 col-sm-12 mb-30">
                        <div class="course-bundle-item">

                            <div class="bundle-header d-flex justify-content-between align-items-center flex-wrap px-0 pt-2">
                                <a href="{{ route('course.bundle.details', $bundle->slug) }}">
                                    <div class="bundle-head">
                                        <h4 class="title">{{ $bundle->title }}</h4>
                                        <p class="course-count">
                                            {{ count($course_ids ?? []) }} {{ get_phrase('courses') }}
                                        </p>
                                    </div>
                                </a>
                            </div>

                            <div class="bundle-body">
                                <ul class="course-bundle">

                                    @foreach ($course_ids as $key => $course_id)

                                    @php
                                    $course_details = App\Models\Course::where('id', $course_id)
                                    ->where('status', 'active')
                                    ->first();

                                    if ($course_details && !$course_details->is_free_course) {
                                    $total_courses_price += $course_details->discount_flag
                                    ? $course_details->discounted_price
                                    : $course_details->price;
                                    }
                                    @endphp

                                    @if ($course_details)
                                    <li>
                                        <div class="course-item">
                                            <a href="{{ route('course.details', $course_details->slug, $course_details->id) }}" target="_blank">
                                                <div class="course-content d-flex align-items-center gap-4">
                                                    <div class="course-img">
                                                        <img src="{{ get_image($course_details->thumbnail ?? '') }}" alt="">
                                                    </div>
                                                    <h3 class="course-title">
                                                        {{ $course_details->title }}
                                                    </h3>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    @endif

                                    @endforeach

                                </ul>
                            </div>

                            <div class="bundle-footer">

                                <a class="bundle-details"
                                    href="javascript:void(0);"
                                    onclick="ajaxModal('{{ route('my.course.bundle.purchase.history', $bundle->id) }}', '{{ get_phrase('Bundle purchase history') }}', 'modal-xl')">
                                    {{ get_phrase('Purchase history') }}
                                </a>

                                @php
                                $btn['url'] = route('purchase.course.bundle', $bundle->id);
                                $btn['title'] = get_phrase('Buy Now');

                                if (isset(auth()->user()->id)) {

                                $my_bundle = App\Models\BundlePayment::where('user_id', auth()->user()->id)
                                ->where('bundle_id', $bundle->id)
                                ->first();

                                if ($my_bundle) {
                                if ($my_bundle->expiry_date && $my_bundle->expiry_date < now()) {
                                    $btn['title']=get_phrase('Renew');
                                    $btn['url']=route('purchase.course.bundle', $bundle->id);
                                    } else {
                                    $btn['title'] = get_phrase('Rating');
                                    $btn['url'] = "javascript:ajaxModal('" .
                                    route('modal', ['frontend.default.student.my_course_bundles.bundle_rating', 'id' => $bundle->id]) .
                                    "', '" . get_phrase('Rating') . "')";
                                    }
                                    }

                                    $pending_payment = DB::table('offline_payments')
                                    ->where('user_id', auth()->id())
                                    ->where('item_type', 'bundle')
                                    ->where('items', $bundle->id)
                                    ->where('status', 0)
                                    ->exists();

                                    if ($pending_payment) {
                                    $btn['title'] = get_phrase('Processing');
                                    $btn['url'] = 'javascript:void(0);';
                                    }
                                    }
                                    @endphp

                                    <a href="{{ $btn['url'] }}" class="buy-now">
                                        {{ $btn['title'] }}
                                    </a>

                            </div>

                        </div>
                    </div>

                    @endforeach

                    @else

                    <div class="col-12">
                        @include('frontend.default.student.empty_state', [
                            'icon' => 'fi-rr-books',
                            'title' => 'Você ainda não possui pacotes de cursos.',
                            'message' => 'Quando um pacote for adquirido, os cursos incluídos aparecerão aqui.',
                            'actionUrl' => route('courses'),
                            'actionLabel' => 'Explorar cursos',
                        ])
                    </div>

                    @endif

                </div>
            </div>

            <!-- Pagination -->
            @if (count($my_course_bundles) > 0)
            <div class="entry-pagination">
                <nav aria-label="Page navigation example">
                    {{ $my_course_bundles->links() }}
                </nav>
            </div>
            @endif
            <!-- Pagination -->

        </div>
    </div>
</div>
<!-------------- List Item End  --------------->

@endsection
@push('js')@endpush
