@extends('layouts.default')
@push('title', 'Fatura')
@push('meta')@endpush
@push('css')@endpush

@section('content')
    @include('frontend.default.student.page_header', [
        'title' => 'Fatura #' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT),
        'current' => 'Fatura',
        'parentUrl' => route('purchase.history'),
        'parentLabel' => 'Compras e faturas',
    ])

    <div class="eNtery-item pf-student-content"><div class="container"><div class="row">
        @include('frontend.default.student.left_sidebar')
        <div class="col-lg-9 col-md-8">
            <div class="my-panel pf-invoice" id="invoice">
                <header class="pf-invoice-header">
                    <div><p>Comprovante de pagamento</p><h2>#{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</h2><span>Emitido em {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</span></div>
                    <img src="{{ get_image(get_frontend_settings('dark_logo')) }}" alt="Academy Learning Club">
                </header>
                <div class="pf-invoice-parties">
                    <div><p>Faturado para</p><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->email }}</span></div>
                    <div><p>Valor pago</p><strong>{{ currency($invoice->amount, 2) }}</strong><span>{{ ucfirst($invoice->payment_type) }}</span></div>
                </div>
                <div class="table-responsive"><table class="table eTable">
                    <thead><tr><th>Curso</th><th>Data</th><th>Forma de pagamento</th><th class="text-end">Valor</th></tr></thead>
                    <tbody><tr><td>{{ $invoice->course_title }}</td><td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</td><td>{{ ucfirst($invoice->payment_type) }}</td><td class="text-end">{{ currency($invoice->amount, 2) }}</td></tr></tbody>
                </table></div>
                <div class="pf-invoice-total"><span>Total</span><strong>{{ currency($invoice->amount, 2) }}</strong></div>
            </div>
            <div class="pf-invoice-actions"><a class="pf-button-secondary" href="{{ route('purchase.history') }}">Voltar</a><button type="button" class="pf-button-primary" onclick="window.print()"><i class="fi-rr-print"></i> Imprimir</button></div>
        </div>
    </div></div></div>
@endsection
