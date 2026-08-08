@extends('layouts.default')
@push('title', 'Abrir chamado')
@push('meta')@endpush
@push('css')@endpush

@section('content')
    @include('frontend.default.student.page_header', [
        'title' => 'Abrir chamado',
        'current' => 'Novo chamado',
        'parentUrl' => route('support.ticket.index'),
        'parentLabel' => 'Suporte',
        'description' => 'Descreva o problema para receber uma resposta objetiva da equipe.',
    ])

    <div class="eNtery-item pf-student-content"><div class="container"><div class="row">
        @include('frontend.default.student.left_sidebar')
        <div class="col-lg-9 col-md-8">
            <div class="my-panel pf-ticket-form">
                <form action="{{ route('support.ticket.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3"><label for="subject" class="form-label">Assunto</label><input type="text" class="form-control" name="subject" id="subject" maxlength="160" value="{{ old('subject') }}" placeholder="Resuma sua solicitação" required></div>
                    <div class="row">
                        <div class="col-md-7 mb-3"><label for="category_id" class="form-label">Categoria</label><select class="form-control" name="category_id" id="category_id" required><option value="">Selecione</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ get_phrase($category->title) }}</option>@endforeach</select></div>
                        <div class="col-md-5 mb-3"><label for="priority_id" class="form-label">Prioridade</label><select class="form-control" name="priority_id" id="priority_id" required><option value="">Selecione</option>@foreach ($priorities as $priority)<option value="{{ $priority->id }}" @selected(old('priority_id') == $priority->id)>{{ get_phrase($priority->title) }}</option>@endforeach</select></div>
                    </div>
                    <div class="mb-3"><label for="messageInput" class="form-label">Mensagem</label><textarea name="message" class="form-control" id="messageInput" rows="6" maxlength="10000" placeholder="Conte o que você estava tentando fazer e o que ocorreu" required>{{ old('message') }}</textarea></div>
                    <div class="mb-4"><label for="file" class="form-label">Anexos <span class="text-muted fw-normal">(opcional, até 10 MB por arquivo)</span></label><input type="file" name="file[]" multiple class="form-control" id="file"><span class="pf-form-help">Formatos comuns de imagem, documento e PDF.</span></div>
                    <button class="eBtn gradient" type="submit">Enviar chamado</button>
                </form>
            </div>
        </div>
    </div></div></div>
@endsection
