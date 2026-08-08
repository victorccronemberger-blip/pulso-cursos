@extends('layouts.default')
@push('title', 'Mensagens')
@push('meta')@endpush
@push('css')@endpush

@section('content')
<section class="breadcum-area">
    <div class="container">
        <div class="eNtry-breadcum">
            <nav aria-label="Navegação estrutural">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('my.courses') }}">Área do aluno</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mensagens</li>
                </ol>
            </nav>
            <h1 class="g-title mt-4">Mensagens</h1>
        </div>
    </div>
</section>

<div class="eNtery-item">
    <div class="container">
        <div class="row">
            @include('frontend.default.student.left_sidebar')

            <div class="col-lg-9 col-md-8">
                <div class="my-panel message-panel">
                    <div class="row g-0 pf-message-layout">
                        <div class="col-lg-4">
                            @include('frontend.default.student.message.sidebar')
                        </div>

                        <div class="col-lg-8">
                            @if (request()->has('inbox'))
                                @include('frontend.default.student.message.inbox')
                            @else
                                <div class="welcome-msg">
                                    <div class="text-center">
                                        <span class="pf-message-welcome-icon" aria-hidden="true">
                                            <i class="fi-rr-comment-alt"></i>
                                        </span>
                                        <h2>Central de mensagens</h2>
                                        <p>Selecione um contato ao lado para iniciar ou continuar uma conversa.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    "use strict";
    $(document).ready(function() {
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.search-box').length) {
                $('#msg-search-list').removeClass('active');
            }
        });

        $('.Esearch_entry').on('click', function(event) {
            event.preventDefault();
            $('#msg-search-list').addClass('active');
        });

        $('#search_student').on('keyup', function() {
            const searchTerm = $(this).val();
            if (searchTerm.indexOf('@') !== -1) {
                $.ajax({
                    type: 'post',
                    url: "{{ route('search.student') }}",
                    data: { search_mail: searchTerm },
                    success: function(response) {
                        $('#msg-search-list').empty().append(response);
                    }
                });
            }
        });
    });
</script>
@endpush
