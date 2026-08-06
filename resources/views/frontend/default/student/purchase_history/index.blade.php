@extends('layouts.default')
@push('title', get_phrase('Purchase History'))
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
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('Purchase History') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('Payment History') }}</h3>
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
                @if ($payments->count() > 0)
                <div class="my-panel purchase-history-panel">

                    <div class="table-responsive">
                        <table class="table eTable">
                            <thead>
                                <tr>
                                    <th>{{ get_phrase('Course Name') }}</th>
                                    <th>{{ get_phrase('Date') }}</th>
                                    <th>{{ get_phrase('Payment Method') }}</th>
                                    <th>{{ get_phrase('Price') }}</th>
                                    <th>{{ get_phrase('Invoice') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment->course_title }}</td>
                                    <td>{{ date('Y-m-d', strtotime($payment->created_at)) }}</td>
                                    <td>{{ ucfirst($payment->payment_type) }}</td>
                                    <td>{{ currency($payment->amount) }}</td>
                                    <td>
                                        <a href="{{ route('invoice', $payment->id) }}"
                                            class="d-flex align-items-center justify-content-center btn btn-primary text-18 text-white py-3"
                                            data-bs-toggle="tooltip"
                                            data-bs-title="{{ get_phrase('Print Invoice') }}">
                                            <i class="fi fi-rr-print d-inline-flex"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="col-12 bg-white radius-10 py-5 shadow-lg">
                    @include('frontend.default.empty')
                </div>
                @endif
                <!-- Pagination -->
                @if (count($payments) > 0)
                <div class="entry-pagination">
                    <nav aria-label="Page navigation example">
                        {{ $payments->links() }}
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