@extends('layouts.default')
@push('title', $category_details['title'] ?? get_phrase('Cursos'))
@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
@endpush
@section('content')
<section class="pf-catalog-hero">
    <div class="container">
        <nav class="pf-catalog-breadcrumb" aria-label="breadcrumb"><a href="{{ route('home') }}">{{ get_phrase('Início') }}</a><span>/</span><span>{{ get_phrase('Cursos') }}</span></nav>
        <div class="pf-catalog-hero-grid">
            <div>
                <p class="pf-catalog-kicker"><i></i>{{ get_phrase('Trilhas para o mercado financeiro') }}</p>
                <h1>{{ get_phrase('Escolha sua próxima') }} <em>{{ get_phrase('aprovação.') }}</em></h1>
                <p>{{ get_phrase('Prepare-se com uma jornada objetiva: teoria essencial, simulados e acompanhamento para transformar estudo em confiança no dia da prova.') }}</p>
            </div>
            <div class="pf-catalog-proof">
                <strong>{{ $courses->total() }}</strong><span>{{ get_phrase('cursos para avançar no seu ritmo') }}</span>
                <small>{{ get_phrase('Conteúdo focado. Prática orientada. Acesso online.') }}</small>
            </div>
        </div>
    </div>
</section>

<main class="pf-catalog-main">
    <div class="container">
        <section class="pf-catalog-controls" aria-label="Filtrar cursos">
            <div>
                <p>{{ get_phrase('Encontre a trilha que faz sentido para você') }}</p>
                <div class="pf-catalog-filters">
                    <a href="{{ route('courses') }}" class="{{ request()->route('category') ? '' : 'active' }}">{{ get_phrase('Todos os cursos') }}</a>
                    @foreach (\App\Models\Category::where('parent_id', 0)->orderBy('id')->get() as $filter_cat)
                        @php $is_active = $category_details && ($category_details->id == $filter_cat->id || $category_details->parent_id == $filter_cat->id); @endphp
                        <a href="{{ route('courses', $filter_cat->slug) }}" class="{{ $is_active ? 'active' : '' }}">{{ $filter_cat->title }}</a>
                    @endforeach
                </div>
            </div>
            <div class="pf-catalog-count"><span>{{ $courses->count() }}</span> {{ get_phrase('resultados nesta página') }}</div>
        </section>

        <section class="row" aria-label="{{ get_phrase('Cursos disponíveis') }}">
            @forelse ($courses as $course)
                @include('frontend.default.course.course_' . $layout)
            @empty
                <div class="col-12"><div class="pf-catalog-empty">@include('frontend.default.empty')</div></div>
            @endforelse
        </section>
        @if ($courses->count() > 0)<div class="entry-pagination"><nav aria-label="{{ get_phrase('Paginas de cursos') }}">{{ $courses->links() }}</nav></div>@endif
    </div>
</main>
@endsection
