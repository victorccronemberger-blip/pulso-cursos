@extends('layouts.default')
@push('title', get_phrase('Booking Invoice ') . $invoice)
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
                            <li class="breadcrumb-item"><a href="{{ route('my_bookings') }}">{{ get_phrase('My Bookings') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('Booking Invoice') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('Booking Invoice') }} {{ $invoice }}</h3>
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

                <div class="my-panel purchase-history-panel">
                    <div class="invoice" id="invoice">
                        <div class="top d-flex justify-content-between align-items-center border-1 border-bottom mb-5 pb-5">
                            <div>
                                <h2><span>{{ get_phrase('Invoice') }} {{ $invoice }}</span></h2>
                                <p class="description">{{ get_phrase('Date') }} {{ date('d-M-Y', $booking->start_time) }}</p>
                            </div>
                            <div>
                                <img src="{{ get_image(get_frontend_settings('dark_logo')) }}"
                                    alt="system logo"
                                    class="object-fit-cover rounded"
                                    width="200px">
                            </div>
                        </div>

                        <div class="billing-area">
                            <div class="table-responsive">
                                <table class="eTable table">
                                    <thead>
                                        <tr>
                                            <th>{{ get_phrase('Subject') }}</th>
                                            <th>{{ get_phrase('Tutor') }}</th>
                                            <th>{{ get_phrase('Time') }}</th>
                                            <th>{{ get_phrase('Method') }}</th>
                                            <th>{{ get_phrase('Price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $booking->booking_to_schedule->schedule_to_tutorSubjects->name }}</td>
                                            <td>{{ $booking->booking_to_tutor->name }}</td>
                                            <td>
                                                {{ date('d-M-Y', $booking->start_time) }}
                                                {{ date('h:i a', $booking->start_time) . ' - ' . date('h:i a', $booking->end_time) }}
                                            </td>
                                            <td class="text-capitalize">{{ get_phrase('Stripe') }}</td>
                                            <td>{{ currency($booking->booking_to_schedule->schedule_to_tutorCanteach->price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ get_phrase('Billed to :') }}</td>
                                            <td>{{ $booking->booking_to_student->name }}</td>
                                            <td></td>
                                            <td>{{ get_phrase('Total :') }}</td>
                                            <td>{{ currency($booking->booking_to_schedule->schedule_to_tutorCanteach->price, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end mt-3 gap-3">
                    <a class="eBtn gradient" href="{{ route('my_bookings') }}">{{ get_phrase('Back') }}</a>
                    <a class="eBtn gradient" id="print" href="javascript:void(0);" onclick="printableDiv('invoice')">{{ get_phrase('Print') }}</a>
                </div>

            </div>
        </div>
    </div>
</div>
<!-------------- List Item End  --------------->

@endsection

@push('js')
<script>
    "use strict";

    function printableDiv(printableAreaDivId) {
        var printContents = document.getElementById(printableAreaDivId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
@endpush
