@extends('layouts.admin')

@push('title', get_phrase('Categories'))

@push('meta')
@endpush

@push('css')
@endpush



@section('content')
<div class="ol-card ">
    <div class="ol-card-body ">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('All Category') }} <span class="text-muted">({{ $categories->count() }})</span>
            </h4>

            <a onclick="ajaxModal('{{ route('modal', ['admin.category.create', 'parent_id' => 0]) }}', '{{ get_phrase('Add new category') }}')" href="#" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-plus"></span>
                <span>{{ get_phrase('Add new category') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="cxcat-grid">
    @foreach ($categories as $category)
    <div class="cxcat-card">

        {{-- Thumbnail --}}
        <div class="cxcat-thumb-wrap">
            <img src="{{ get_image($category->thumbnail) }}" alt="{{ $category->title }}">
            <div class="cxcat-thumb-overlay"></div>

            {{-- Dark gradient behind buttons, fades in on hover --}}
            <div class="cxcat-thumb-hover-gradient"></div>

            {{-- Edit / Delete — bottom-right of image, appear on card hover --}}
            <div class="cxcat-img-actions">
                <a href="#"
                    onclick="ajaxModal('{{ route('modal', ['admin.category.edit', 'id' => $category->id]) }}', '{{ get_phrase('Edit category') }}')"
                    class="cxcat-img-edit">
                    <i class="fi fi-rr-pen-clip"></i> {{ get_phrase('Edit') }}
                </a>
                <a href="#"
                    onclick="confirmModal('{{ route('admin.category.delete', $category->id) }}')"
                    class="cxcat-img-del">
                    <i class="fi-rr-trash"></i> {{ get_phrase('Delete') }}
                </a>
            </div>
        </div>

        {{-- Header --}}
        <div class="cxcat-header">
            <div class="cxcat-icon-badge">
                <i class="{{ $category->icon }}"></i>
            </div>
            <p class="cxcat-title">{{ $category->title }}</p>
            <span class="cxcat-count-badge">{{ $category->childs->count() }}</span>
        </div>

        {{-- Subcategory List --}}
        <ul class="cxcat-list">
            @foreach ($category->childs as $child_category)
            <li class="cxcat-list-item">
                <div class="cxcat-sub-left">
                    <i class="cxcat-sub-icon {{ $child_category->icon }}"></i>
                    <span class="cxcat-sub-name">{{ $child_category->title }}</span>
                </div>
                <div class="cxcat-sub-actions">
                    <a onclick="ajaxModal('{{ route('modal', ['admin.category.edit', 'id' => $child_category->id]) }}', '{{ get_phrase('Edit category') }}')"
                        data-bs-toggle="tooltip" title="{{ get_phrase('Edit') }}" href="#">
                        <i class="fi fi-rr-pen-clip"></i>
                    </a>
                    <a onclick="confirmModal('{{ route('admin.category.delete', $child_category->id) }}')"
                        class="cxcat-del" data-bs-toggle="tooltip" title="{{ get_phrase('Delete') }}" href="#">
                        <i class="fi fi-rr-trash"></i>
                    </a>
                </div>
            </li>
            @endforeach
        </ul>

        {{-- Footer — Add subcategory, always centered --}}
        <div class="cxcat-footer">
            <a onclick="ajaxModal('{{ route('modal', ['admin.category.create', 'parent_id' => $category->id]) }}', '{{ get_phrase('Add new category') }}')"
                href="#" class="cxcat-btn-add">
                <i class="fi fi-rr-plus"></i> {{ get_phrase('Add') }}
            </a>
        </div>

    </div>
    @endforeach
</div>
@endsection