@php
    $emptyIcon = $icon ?? 'fi-rr-folder-open';
    $emptyTitle = $title ?? 'Nada por aqui ainda.';
    $emptyMessage = $message ?? 'Quando houver novidades, elas aparecerão nesta página.';
@endphp

<div class="pf-portal-empty">
    <span class="pf-portal-empty-icon" aria-hidden="true"><i class="{{ $emptyIcon }}"></i></span>
    <h2>{{ $emptyTitle }}</h2>
    <p>{{ $emptyMessage }}</p>
    @if (!empty($actionUrl) && !empty($actionLabel))
        <a href="{{ $actionUrl }}" class="pf-portal-empty-action">{{ $actionLabel }}</a>
    @endif
</div>
