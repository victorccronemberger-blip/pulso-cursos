@extends('layouts.default')
@push('title', 'Suporte')
@push('meta')@endpush
@push('css')@endpush

@section('content')
    @include('frontend.default.student.page_header', [
        'title' => 'Chamados de suporte',
        'current' => 'Suporte',
        'description' => 'Acompanhe solicitações e respostas da nossa equipe.',
        'actionUrl' => route('support.ticket.create'),
        'actionLabel' => 'Abrir chamado',
        'actionIcon' => 'fi-rr-plus',
    ])

    <div class="eNtery-item pf-student-content">
        <div class="container"><div class="row">
            @include('frontend.default.student.left_sidebar')
            <div class="col-lg-9 col-md-8">
                @if ($tickets->count())
                    <div class="my-panel"><div class="table-responsive"><table class="table eTable">
                        <thead><tr><th>Chamado</th><th>Situação</th><th>Prioridade</th><th>Categoria</th><th class="text-end">Ação</th></tr></thead>
                        <tbody>@foreach ($tickets as $ticket)<tr>
                            <td><a href="{{ route('support.ticket.message', $ticket->code) }}">{{ $ticket->subject }}</a><small class="d-block text-muted mt-1">#{{ $ticket->code }}</small></td>
                            <td><span class="pf-ticket-status" style="--ticket-color: {{ $ticket->status?->color ?? '#64748b' }}">{{ get_phrase($ticket->status?->title ?? 'Novo') }}</span></td>
                            <td>{{ get_phrase($ticket->priority?->title ?? 'Normal') }}</td>
                            <td>{{ get_phrase($ticket->category?->title ?? 'Outros') }}</td>
                            <td class="text-end"><a class="pf-ticket-open" href="{{ route('support.ticket.message', $ticket->code) }}">Abrir</a></td>
                        </tr>@endforeach</tbody>
                    </table></div></div>
                @else
                    @include('frontend.default.student.empty_state', [
                        'icon' => 'fi-rr-headset',
                        'title' => 'Você ainda não abriu nenhum chamado.',
                        'message' => 'Quando precisar de ajuda com acesso, pagamento ou conteúdo, fale com nossa equipe por aqui.',
                        'actionUrl' => route('support.ticket.create'),
                        'actionLabel' => 'Abrir primeiro chamado',
                    ])
                @endif
                @if ($tickets->hasPages())<div class="entry-pagination">{{ $tickets->links() }}</div>@endif
            </div>
        </div></div>
    </div>
@endsection
