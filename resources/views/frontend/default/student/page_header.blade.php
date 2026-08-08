@php
    $pageTitle = $title ?? 'Área do aluno';
    $pageCurrent = $current ?? $pageTitle;
    $pageParentUrl = $parentUrl ?? route('my.courses');
    $pageParentLabel = $parentLabel ?? 'Área do aluno';
@endphp

<section class="pf-portal-page-header">
    <div class="container">
        <nav aria-label="Navegação estrutural">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ $pageParentUrl }}">{{ $pageParentLabel }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageCurrent }}</li>
            </ol>
        </nav>
        <div class="pf-portal-page-title-row">
            <div>
                <h1>{{ $pageTitle }}</h1>
                @if (!empty($description))
                    <p>{{ $description }}</p>
                @endif
            </div>
            @if (!empty($actionUrl) && !empty($actionLabel))
                <a href="{{ $actionUrl }}" class="pf-portal-page-action">
                    @if (!empty($actionIcon))<i class="{{ $actionIcon }}" aria-hidden="true"></i>@endif
                    {{ $actionLabel }}
                </a>
            @endif
        </div>
    </div>
</section>
