@extends('layouts.default')
@push('title', get_phrase('My Team Packages'))
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
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('My Team Packages') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('My Team Packages') }}</h3>
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

                    @foreach ($packages as $package)
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-30">
                        <div class="card Ecard g-card c-card">
                            <div class="card-head">
                                <img src="{{ get_image($package->thumbnail) }}" alt="package-thumbnail">
                            </div>
                            <div class="card-body entry-details">

                                <div class="entry-title">
                                    <a href="{{ route('my.team.packages.details', $package->slug) }}">
                                        <h3 class="w-100 ellipsis-line-2">{{ ucfirst($package->title) }}</h3>
                                    </a>
                                </div>

                                <div class="info-card mb-15">
                                    <div class="creator">
                                        <h5 class="ellipsis-line-2">{{ $package->course_title }}</h5>
                                    </div>
                                </div>

                                <div class="class-details pt-3">
                                    <div class="d-flex gap-3 justify-content-between">
                                        <div class="class-status">
                                            <span class="text-capitalize">{{ get_phrase('Expiry') }}:</span>
                                        </div>
                                        <div class="class-status">
                                            @if ($package->expiry == 'lifetime')
                                            <span class="badge bg-success text-capitalize">{{ get_phrase('Lifetime') }}</span>
                                            @else
                                            <span class="badge bg-success text-capitalize">{{ date('d M Y', $package->expiry_date) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3 justify-content-between mt-2">
                                        <div class="class-status">
                                            <span class="text-capitalize">{{ get_phrase('Members') }}:</span>
                                        </div>
                                        <div class="class-status">
                                            <span class="badge bg-success text-capitalize">{{ $package->allocation }} / {{ reserved_team_members($package->id) }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3 justify-content-between mt-2">
                                        <div class="class-status">
                                            <span class="text-capitalize">{{ get_phrase('Sections') }}:</span>
                                        </div>
                                        <div class="class-status">
                                            <span class="badge bg-success text-capitalize">{{ section_count($package->course_id) }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3 justify-content-between mt-2">
                                        <div class="class-status">
                                            <span class="text-capitalize">{{ get_phrase('Lessons') }}:</span>
                                        </div>
                                        <div class="class-status">
                                            <span class="badge bg-success text-capitalize">{{ lesson_count($package->course_id) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('my.team.packages.details', $package->slug) }}" class="eBtn learn-btn w-100 text-center mt-20 f-500">
                                    {{ get_phrase('View Package') }}
                                </a>

                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if (count($packages) == 0)
                    <div class="col-12 bg-white radius-10 py-5 shadow-lg">
                        @include('frontend.default.empty')
                    </div>
                    @endif

                </div>

                <!-- Pagination -->
                @if (count($packages) > 0)
                <div class="entry-pagination">
                    <nav aria-label="Page navigation example">
                        {{ $packages->links() }}
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
