@extends('layouts.default')
@push('title', 'Fatura do pacote')
@push('meta')@endpush
@push('css')@endpush

@section('content')
    @include('frontend.default.student.page_header', [
        'title' => 'Fatura do pacote',
        'current' => 'Fatura',
        'parentUrl' => route('my.course.bundles'),
        'parentLabel' => 'Meus pacotes',
    ])

    <div class="eNtery-item pf-student-content">
        <div class="container"><div class="row">
            @include('frontend.default.student.left_sidebar')
            <div class="col-lg-9 col-md-8">
                <div class="my-panel pf-invoice" id="bundle-invoice">
                    <header class="pf-invoice-header">
                        <div><p>Fatura</p><h2>#{{ $invoice->invoice }}</h2><span>Emitida em {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</span></div>
                        <img src="{{ get_image(get_frontend_settings('dark_logo')) }}" alt="Academy Learning Club">
                    </header>
                    <div class="pf-invoice-parties">
                        @php $user = get_user_info($invoice->user_id); @endphp
                        <div><p>Faturado para</p><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span>@if($user->phone)<span>{{ $user->phone }}</span>@endif</div>
                        <div><p>Pagamento</p><strong>{{ currency($invoice->amount, 2) }}</strong><span>{{ ucfirst($invoice->payment_method) }}</span></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table eTable">
                            <thead><tr><th>Pacote</th><th>Cursos incluídos</th><th class="text-end">Valor</th></tr></thead>
                            <tbody><tr><td>{{ $invoice->title }}</td><td><ul class="pf-invoice-course-list">@foreach(\App\Models\Course::whereIn('id', json_decode($bundle->course_ids, true) ?: [])->get() as $course)<li>{{ $course->title }}</li>@endforeach</ul></td><td class="text-end">{{ currency($invoice->amount, 2) }}</td></tr></tbody>
                        </table>
                    </div>
                </div>
                <div class="pf-invoice-actions"><a href="{{ route('my.course.bundles') }}" class="pf-button-secondary">Voltar</a><button type="button" class="pf-button-primary" onclick="window.print()"><i class="fi-rr-print"></i> Imprimir</button></div>
            </div>
        </div></div>
    </div>
@endsection
