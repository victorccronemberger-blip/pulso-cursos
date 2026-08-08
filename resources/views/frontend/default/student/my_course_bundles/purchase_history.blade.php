<div class="pf-modal-history">
    <div class="pf-modal-history-head"><div><span>Pacote</span><h3>{{ $bundle->title }}</h3></div><strong>{{ $purchase_histories->count() }} {{ $purchase_histories->count() === 1 ? 'compra' : 'compras' }}</strong></div>
    @forelse ($purchase_histories as $purchase)
        <div class="pf-modal-history-row">
            <div><span>Data</span><strong>{{ $purchase->created_at->format('d/m/Y') }}</strong></div>
            <div><span>Valor</span><strong>{{ currency($purchase->amount) }}</strong></div>
            <div><span>Pagamento</span><strong>{{ ucfirst($purchase->payment_method) }}</strong></div>
            <a href="{{ route('my.course.bundle.invoice', $bundle->id) }}">Ver fatura</a>
        </div>
    @empty
        <p class="pf-modal-history-empty">Nenhuma compra registrada para este pacote.</p>
    @endforelse
</div>
