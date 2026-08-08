@extends('layouts.default')
@push('title', 'Suporte')
@section('content')
<section class="breadcum-area">
    <div class="container">
        <div class="eNtry-breadcum">
            <nav aria-label="Navegação estrutural">
                <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('my.courses') }}">Área do aluno</a></li><li class="breadcrumb-item active">Suporte</li></ol>
            </nav>
            <div class="row align-items-center mt-4">
                <div class="col"><h1 class="g-title">Chamados de suporte</h1></div>
                <div class="col-auto"><a href="{{ route('support.ticket.create') }}" class="eBtn gradient"><i class="fi fi-rr-plus me-1"></i>Abrir chamado</a></div>
            </div>
        </div>
    </div>
</section>

<div class="eNtery-item">
    <div class="container"><div class="row">
        @include('frontend.default.student.left_sidebar')
        <div class="col-lg-9 col-md-8">
            <div class="my-panel">
                @if ($tickets->count())
                    <div class="table-responsive">
                        <table class="table eTable">
                            <thead><tr><th>Chamado</th><th>Situação</th><th>Prioridade</th><th>Categoria</th><th class="text-end">Ação</th></tr></thead>
                            <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td><a href="{{ route('support.ticket.message', $ticket->code) }}">{{ $ticket->subject }}</a><small class="d-block text-muted mt-1">#{{ $ticket->code }}</small></td>
                                    <td><span class="pf-ticket-status" style="--ticket-color: {{ $ticket->status?->color ?? '#64748b' }}">{{ get_phrase($ticket->status?->title ?? 'Novo') }}</span></td>
                                    <td>{{ get_phrase($ticket->priority?->title ?? 'Normal') }}</td>
                                    <td>{{ get_phrase($ticket->category?->title ?? 'Outros') }}</td>
                                    <td class="text-end"><a class="pf-ticket-open" href="{{ route('support.ticket.message', $ticket->code) }}">Abrir</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="pf-ticket-empty">
                        <i class="fi-rr-headset" aria-hidden="true"></i>
                        <h2>Você ainda não abriu nenhum chamado.</h2>
                        <p>Quando precisar de ajuda com acesso, pagamento ou conteúdo, fale com nossa equipe por aqui.</p>
                        <a href="{{ route('support.ticket.create') }}" class="eBtn gradient">Abrir primeiro chamado</a>
                    </div>
                @endif
            </div>
            @if ($tickets->hasPages())<div class="entry-pagination">{{ $tickets->links() }}</div>@endif
        </div>
    </div></div>
</div>
@endsection
