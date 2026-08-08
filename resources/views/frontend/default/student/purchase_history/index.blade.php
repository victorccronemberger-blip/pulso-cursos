@extends('layouts.default')
@push('title', get_phrase('Purchase History'))
@push('meta')@endpush
@push('css')@endpush
@section('content')

@include('frontend.default.student.page_header', [
    'title' => 'Compras e faturas',
    'current' => 'Compras e faturas',
    'description' => 'Consulte pagamentos e imprima seus comprovantes.',
])

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
                                    <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</td>
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
                @include('frontend.default.student.empty_state', [
                    'icon' => 'fi-rr-receipt',
                    'title' => 'Nenhuma compra registrada.',
                    'message' => 'Suas compras e faturas ficarão organizadas nesta página.',
                    'actionUrl' => route('courses'),
                    'actionLabel' => 'Ver catálogo',
                ])
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
