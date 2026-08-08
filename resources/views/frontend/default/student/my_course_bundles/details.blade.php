@extends('layouts.default')
@push('title', $course_bundle->title)
@push('meta')@endpush
@push('css')@endpush

@section('content')
    @include('frontend.default.student.page_header', [
        'title' => $course_bundle->title,
        'current' => 'Conteúdo do pacote',
        'parentUrl' => route('my.course.bundles'),
        'parentLabel' => 'Meus pacotes',
        'description' => $courses->count() . ($courses->count() === 1 ? ' curso incluído' : ' cursos incluídos'),
    ])

    <div class="eNtery-item pf-student-content">
        <div class="container">
            <div class="row">
                @include('frontend.default.student.left_sidebar')
                <div class="col-lg-9 col-md-8">
                    @if ($courses->count())
                        <div class="my-panel pf-bundle-owned">
                            <div class="pf-panel-heading"><h2>Cursos do pacote</h2><span>Acesso liberado</span></div>
                            @foreach ($courses as $course)
                                <article class="pf-bundle-owned-course">
                                    <a href="{{ route('course.details', $course->slug) }}" class="pf-bundle-owned-image">
                                        <img src="{{ get_image($course->thumbnail) }}" alt="{{ $course->title }}">
                                    </a>
                                    <div>
                                        <p>Curso incluído</p>
                                        <h3>{{ $course->title }}</h3>
                                        @if ($course->short_description)<span>{{ \Illuminate\Support\Str::limit(strip_tags($course->short_description), 150) }}</span>@endif
                                    </div>
                                    <a href="{{ route('course.details', $course->slug) }}" class="pf-bundle-owned-action">Abrir curso</a>
                                </article>
                            @endforeach
                        </div>
                    @else
                        @include('frontend.default.student.empty_state', [
                            'icon' => 'fi-rr-books',
                            'title' => 'Nenhum curso disponível neste pacote.',
                            'message' => 'O conteúdo deste pacote está sendo atualizado. Consulte novamente mais tarde.',
                            'actionUrl' => route('my.course.bundles'),
                            'actionLabel' => 'Voltar aos pacotes',
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
