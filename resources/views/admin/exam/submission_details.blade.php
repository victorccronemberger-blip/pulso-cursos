@extends('layouts.admin')
@push('title', get_phrase('Submission Details'))
@section('content')

<!-- Header Card -->

<div class="ol-card  ">
    <div class="ol-card-body   ">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Submission Details') }}
            </h4>

            <a href="{{ route('admin.exam.submissions', $exam->id) }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi fi-rr-arrow-left"></span>
                <span>{{ get_phrase('Back') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="ol-card   mb-4">
    <div class="ol-card-body py-3 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="title fs-16px mb-1 fw-semibold" style="color: var(--title-color, #1e293b);">
                    {{ optional($submission->exam)->title ?? 'N/A' }}
                </h4>
            </div>
            @if($submission->status === 'checked')
            <span class="badge bg-success px-3 py-2">{{ get_phrase('Evaluated') }}</span>
            @elseif($submission->status === 'checking')
            <span class="badge bg-info text-white px-3 py-2">{{ get_phrase('Under Review') }}</span>
            @else
            <span class="badge bg-warning text-dark px-3 py-2">{{ get_phrase('Pending') }}</span>
            @endif
        </div>
    </div>
</div>


<div class="row g-4">
    <!-- Sidebar: Student & Exam Info -->
    <div class="col-lg-4">
        <!-- Student Info -->
        <div class="ol-card   mb-4">
            <div class="ol-card-body p-4">
                <h5 class="fw-semibold mb-3 pb-2" style="border-bottom: 2px solid #f0f0f0; color: var(--title-color, #1e293b);">
                    {{ get_phrase('Student Info') }}
                </h5>
                <div class="mb-3">
                    <small class="d-block mb-1" style="color: #64748b;">{{ get_phrase('Name') }}</small>
                    <p class="mb-0 fw-medium" style="color: var(--title-color, #1e293b);">{{ optional($submission->student)->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <small class="d-block mb-1" style="color: #64748b;">{{ get_phrase('Email') }}</small>
                    <p class="mb-0 fw-medium text-break" style="color: var(--title-color, #1e293b);">{{ optional($submission->student)->email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Exam Info -->
        <div class="ol-card   mb-4">
            <div class="ol-card-body p-4">
                <h5 class="fw-semibold mb-3 pb-2" style="border-bottom: 2px solid #f0f0f0; color: var(--title-color, #1e293b);">
                    {{ get_phrase('Exam Info') }}
                </h5>
                <div class="mb-3">
                    <small class="d-block mb-1" style="color: #64748b;">{{ get_phrase('Title') }}</small>
                    <p class="mb-0 fw-medium" style="color: var(--title-color, #1e293b);">{{ optional($submission->exam)->title ?? 'N/A' }}</p>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="d-block mb-1" style="color: #64748b;">{{ get_phrase('Marks') }}</small>
                        <p class="mb-0 fw-medium" style="color: var(--title-color, #1e293b);">{{ optional($submission->exam)->marks ?? 'N/A' }}</p>
                    </div>
                    <div class="col-6">
                        <small class="d-block mb-1" style="color: #64748b;">{{ get_phrase('Duration') }}</small>
                        <p class="mb-0 fw-medium" style="color: var(--title-color, #1e293b);">{{ optional($submission->exam)->duration ?? 'N/A' }} {{ get_phrase('min') }}</p>
                    </div>
                </div>
                <div>
                    <small class="d-block mb-1" style="color: #64748b;">{{ get_phrase('Submitted At') }}</small>
                    <p class="mb-0 fw-medium" style="color: var(--title-color, #1e293b);">
                        {{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('d M, Y H:i') : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Evaluation Form -->
        <div class="ol-card  ">
            <div class="ol-card-body p-4">
                <h5 class="fw-semibold mb-3 pb-2" style="border-bottom: 2px solid #f0f0f0; color: var(--title-color, #1e293b);">
                    {{ get_phrase('Evaluation') }}
                </h5>
                <form action="{{ route('admin.submission.update', $submission->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label small" style="color: #64748b;">{{ get_phrase('Marks Obtained') }}</label>
                        <input type="number"
                            class="form-control"
                            name="obtained_marks"
                            value="{{ $submission->obtained_marks ?? '' }}"
                            min="0"
                            max="{{ optional($submission->exam)->marks ?? 100 }}"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small" style="color: #64748b;">{{ get_phrase('Remarks') }}</label>
                        <textarea name="remarks" class="form-control" rows="4" placeholder="Enter your feedback...">{{ $submission->remarks ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="btn ol-btn-primary w-100">
                        <i class="fi-rr-check me-2"></i>{{ get_phrase('Save Evaluation') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content: PDF Annotation -->
    <div class="col-lg-8">
        <div class="ol-card  ">
            <div class="ol-card-body p-4">
                <h5 class="fw-semibold mb-3 pb-2" style="border-bottom: 2px solid #f0f0f0; color: var(--title-color, #1e293b);">
                    {{ get_phrase('Annotate Submitted PDF') }}
                </h5>

                @if($submission->submitted_pdf)

                {{-- ===== PREMIUM TOOLBAR ===== --}}
                <div class="anno-toolbar mb-3">

                    {{-- Tool Groups --}}
                    <div class="anno-toolbar-section">
                        <span class="anno-toolbar-label">Tools</span>
                        <div class="anno-tool-group">
                            <button type="button" class="anno-tool-btn active" data-tool="select" title="Select & Move">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m4 4 7.07 17 2.51-7.39L21 11.07z" />
                                </svg>
                            </button>
                            <button type="button" class="anno-tool-btn" data-tool="pen" title="Pen">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 19l7-7 3 3-7 7-3-3z" />
                                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
                                    <path d="M2 2l7.586 7.586" />
                                    <circle cx="11" cy="11" r="2" />
                                </svg>
                            </button>
                            <button type="button" class="anno-tool-btn" data-tool="highlighter" title="Highlighter">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m9 11-6 6v3h9l3-3" />
                                    <path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4" />
                                </svg>
                            </button>
                            <button type="button" class="anno-tool-btn" data-tool="eraser" title="Eraser">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" />
                                    <path d="M22 21H7" />
                                    <path d="m5 11 9 9" />
                                </svg>
                            </button>
                            <button type="button" class="anno-tool-btn" data-tool="text" title="Add Text">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="4 7 4 4 20 4 20 7" />
                                    <line x1="9" y1="20" x2="15" y2="20" />
                                    <line x1="12" y1="4" x2="12" y2="20" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="anno-toolbar-divider"></div>

                    {{-- Shapes --}}
                    <div class="anno-toolbar-section">
                        <span class="anno-toolbar-label">Shapes</span>
                        <div class="anno-tool-group">
                            <button type="button" class="anno-tool-btn" data-tool="rectangle" title="Rectangle">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                </svg>
                            </button>
                            <button type="button" class="anno-tool-btn" data-tool="circle" title="Circle">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                            </button>
                            <button type="button" class="anno-tool-btn" data-tool="arrow" title="Arrow">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                </svg>
                            </button>
                            <button type="button" class="anno-tool-btn" data-tool="line" title="Line">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="19" x2="19" y2="5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="anno-toolbar-divider"></div>

                    {{-- Color & Size --}}
                    <div class="anno-toolbar-section">
                        <span class="anno-toolbar-label">Style</span>
                        <div class="anno-tool-group" style="gap:8px;">
                            <input type="color" id="colorPicker" value="#ff0000" class="anno-color-pick" title="Color">
                            <div class="anno-size-wrap">
                                <input type="range" id="penSize" min="1" max="20" value="3" class="anno-range">
                                <span id="penSizeLabel" class="anno-size-label">3</span>
                            </div>
                            <div class="anno-font-wrap" id="fontSizeControl" style="display:none;">
                                <input type="number" id="fontSize" min="8" max="72" value="16" class="anno-font-input">
                                <span class="anno-size-label" style="color:#64748b;">pt</span>
                            </div>
                        </div>
                    </div>

                    <div class="anno-toolbar-divider"></div>

                    {{-- Actions --}}
                    <div class="anno-toolbar-section">
                        <span class="anno-toolbar-label">Actions</span>
                        <div class="anno-tool-group">
                            <button class="anno-action-btn" id="deleteSelected" style="display:none;" title="Delete Selected">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path d="M19 6l-1 14H6L5 6" />
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                    <path d="M9 6V4h6v2" />
                                </svg>
                            </button>
                            <button class="anno-action-btn" id="undoBtn" title="Undo">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 7v6h6" />
                                    <path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13" />
                                </svg>
                            </button>
                            <button class="anno-action-btn anno-action-danger" id="clearAll" title="Clear All">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18" />
                                    <path d="M8 6V4h8v2" />
                                    <path d="M19 6l-1 14H6L5 6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <style>
                    .anno-toolbar {
                        display: flex;
                        align-items: center;
                        flex-wrap: wrap;
                        gap: 4px;
                        background: #f8fafc;
                        border: 1px solid #e2e8f0;
                        border-radius: 12px;
                        padding: 10px 14px;
                        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
                    }

                    .anno-toolbar-section {
                        display: flex;
                        flex-direction: column;
                        gap: 4px;
                    }

                    .anno-toolbar-label {
                        font-size: 9px;
                        font-weight: 600;
                        letter-spacing: 0.08em;
                        text-transform: uppercase;
                        color: #94a3b8;
                        padding-left: 2px;
                    }

                    .anno-tool-group {
                        display: flex;
                        align-items: center;
                        gap: 2px;
                    }

                    .anno-toolbar-divider {
                        width: 1px;
                        height: 44px;
                        background: #e2e8f0;
                        margin: 0 6px;
                        align-self: flex-end;
                        margin-bottom: 2px;
                    }

                    .anno-tool-btn {
                        width: 34px;
                        height: 34px;
                        border-radius: 8px;
                        border: 1px solid transparent;
                        background: transparent;
                        color: #475569;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: all 0.15s;
                        position: relative;
                    }

                    .anno-tool-btn:hover {
                        background: #e2e8f0;
                        color: #1e293b;
                        border-color: #cbd5e1;
                    }

                    .anno-tool-btn.active {
                        background: #3b82f6;
                        color: #fff;
                        border-color: #2563eb;
                        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.35);
                    }

                    .anno-color-pick {
                        width: 34px;
                        height: 34px;
                        border-radius: 8px;
                        border: 1px solid #e2e8f0;
                        padding: 2px;
                        cursor: pointer;
                        background: #fff;
                    }

                    .anno-color-pick::-webkit-color-swatch {
                        border-radius: 6px;
                        border: none;
                    }

                    .anno-color-pick::-moz-color-swatch {
                        border-radius: 6px;
                        border: none;
                    }

                    .anno-size-wrap {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        background: #fff;
                        border: 1px solid #e2e8f0;
                        border-radius: 8px;
                        padding: 0 10px;
                        height: 34px;
                    }

                    .anno-range {
                        width: 72px;
                        accent-color: #3b82f6;
                        height: 4px;
                    }

                    .anno-size-label {
                        font-size: 11px;
                        font-weight: 600;
                        color: #475569;
                        min-width: 20px;
                    }

                    .anno-font-wrap {
                        display: flex;
                        align-items: center;
                        gap: 4px;
                        background: #fff;
                        border: 1px solid #e2e8f0;
                        border-radius: 8px;
                        padding: 0 8px;
                        height: 34px;
                    }

                    .anno-font-input {
                        width: 44px;
                        border: none;
                        outline: none;
                        font-size: 12px;
                        background: transparent;
                        color: #1e293b;
                        font-weight: 600;
                    }

                    .anno-action-btn {
                        width: 34px;
                        height: 34px;
                        border-radius: 8px;
                        border: 1px solid #e2e8f0;
                        background: #fff;
                        color: #475569;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: all 0.15s;
                    }

                    .anno-action-btn:hover {
                        background: #f1f5f9;
                        color: #1e293b;
                        border-color: #cbd5e1;
                    }

                    .anno-action-danger {
                        color: #ef4444;
                        border-color: #fecaca;
                    }

                    .anno-action-danger:hover {
                        background: #fef2f2;
                        border-color: #ef4444;
                    }

                    @media (max-width: 768px) {
                        .anno-toolbar {
                            gap: 6px;
                        }

                        .anno-toolbar-divider {
                            display: none;
                        }
                    }

                    /* Keep existing canvas styles unchanged */
                    .color-picker-circle {
                        -webkit-appearance: none;
                        -moz-appearance: none;
                        appearance: none;
                        width: 22px;
                        height: 22px;
                        border: none;
                        border-radius: 50%;
                        padding: 0;
                        cursor: pointer;
                        background-color: #ff0000;
                    }

                    .color-picker-circle::-webkit-color-swatch {
                        border-radius: 50%;
                        border: none;
                    }

                    .color-picker-circle::-moz-color-swatch {
                        border-radius: 50%;
                        border: none;
                    }

                    .drawing-canvas {
                        position: absolute;
                        top: 0;
                        left: 0;
                        z-index: 10;
                    }

                    .page-wrapper {
                        position: relative;
                        margin-bottom: 20px;
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
                        background: white;
                        display: inline-block;
                        border-radius: 4px;
                        overflow: hidden;
                    }

                    .tool-cursor-pen {
                        cursor: crosshair;
                    }

                    .tool-cursor-highlighter {
                        cursor: crosshair;
                    }

                    .tool-cursor-eraser {
                        cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"><circle cx="10" cy="10" r="8" fill="white" stroke="black" stroke-width="2"/></svg>') 10 10, auto;
                    }

                    .tool-cursor-text {
                        cursor: text;
                    }

                    .tool-cursor-select {
                        cursor: default;
                    }

                    .tool-cursor-rectangle,
                    .tool-cursor-circle,
                    .tool-cursor-arrow,
                    .tool-cursor-line {
                        cursor: crosshair;
                    }

                    .selection-box {
                        position: absolute;
                        border: 2px dashed #007bff;
                        background: rgba(0, 123, 255, 0.08);
                        pointer-events: none;
                        z-index: 100;
                    }

                    .resize-handle {
                        position: absolute;
                        width: 10px;
                        height: 10px;
                        background: #007bff;
                        border: 2px solid white;
                        border-radius: 50%;
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
                        z-index: 101;
                        pointer-events: all;
                    }

                    .resize-handle.nw {
                        top: -5px;
                        left: -5px;
                        cursor: nw-resize;
                    }

                    .resize-handle.ne {
                        top: -5px;
                        right: -5px;
                        cursor: ne-resize;
                    }

                    .resize-handle.sw {
                        bottom: -5px;
                        left: -5px;
                        cursor: sw-resize;
                    }

                    .resize-handle.se {
                        bottom: -5px;
                        right: -5px;
                        cursor: se-resize;
                    }

                    .resize-handle.n {
                        top: -5px;
                        left: 50%;
                        transform: translateX(-50%);
                        cursor: n-resize;
                    }

                    .resize-handle.s {
                        bottom: -5px;
                        left: 50%;
                        transform: translateX(-50%);
                        cursor: s-resize;
                    }

                    .resize-handle.e {
                        top: 50%;
                        right: -5px;
                        transform: translateY(-50%);
                        cursor: e-resize;
                    }

                    .resize-handle.w {
                        top: 50%;
                        left: -5px;
                        transform: translateY(-50%);
                        cursor: w-resize;
                    }
                </style>

                <!-- PDF Viewer -->
                <div id="pdf-container" class="rounded" style="width:100%; max-height:700px; overflow-y:auto; border:1px solid #e5e5e5; background:#fafafa;">
                    <div id="pages-container" style="padding:20px;"></div>
                </div>

                <!-- Save Button -->
                <div class="mt-3 d-flex align-items-center gap-2">
                    <button type="button" class="btn ol-btn-primary" id="saveBtn">
                        <i class="fi-rr-disk me-2"></i>{{ get_phrase('Save Annotated PDF') }}
                    </button>
                    <span id="saveStatus" class="text-muted small"></span>
                </div>
                @else
                <div class="alert alert-warning mb-0">
                    <i class="fi-rr-info me-2"></i>{{ get_phrase('No PDF Submitted') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($submission->submitted_pdf)
{{-- Offline PDF.js --}}
<script src="{{ asset('assets/backend/js/pdf.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('assets/backend/js/pdf.worker.min.js') }}";

    const pdfUrl = "{{ asset($submission->submitted_pdf) }}";
    let pdfDoc;
    let drawings = {};
    let history = {};
    let currentTool = 'select';
    let currentColor = '#ff0000';
    let penSize = 5;
    let fontSize = 16;
    let isDrawing = false;
    let startX = 0;
    let startY = 0;
    let lastX = 0;
    let lastY = 0;
    let selectedObject = null;
    let selectedPage = null;
    let isDragging = false;
    let isResizing = false;
    let resizeHandle = null;
    let dragOffsetX = 0;
    let dragOffsetY = 0;
    let activeCanvas = null;
    let penPoints = [];
    let lastTimestamp = 0;
    let smoothingFactor = 0.3;

    function getEventCoordinates(e, rect) {
        if (e.touches && e.touches.length > 0) {
            return {
                x: e.touches[0].clientX - rect.left,
                y: e.touches[0].clientY - rect.top
            };
        }
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }

    let isScrolling = false;
    let scrollStartY = 0;
    let scrollStartTop = 0;

    async function initPDF() {
        try {
            pdfDoc = await pdfjsLib.getDocument(pdfUrl).promise;
            const totalPages = pdfDoc.numPages;
            const container = document.getElementById('pages-container');
            const pdfContainer = document.getElementById('pdf-container');
            let containerWidth = pdfContainer.clientWidth || pdfContainer.offsetWidth || window.innerWidth;
            containerWidth = containerWidth - 40;
            setupContainerScrolling(container);

            for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
                const page = await pdfDoc.getPage(pageNum);
                const viewport = page.getViewport({
                    scale: 1.0
                });
                const scale = containerWidth / viewport.width;
                const pixelRatio = window.devicePixelRatio || 1;
                const scaledViewport = page.getViewport({
                    scale
                });

                const pageWrapper = document.createElement('div');
                pageWrapper.className = 'page-wrapper';
                pageWrapper.style.width = scaledViewport.width + 'px';
                pageWrapper.style.height = scaledViewport.height + 'px';

                const pdfCanvas = document.createElement('canvas');
                pdfCanvas.width = scaledViewport.width * pixelRatio;
                pdfCanvas.height = scaledViewport.height * pixelRatio;
                pdfCanvas.style.width = scaledViewport.width + 'px';
                pdfCanvas.style.height = scaledViewport.height + 'px';
                pdfCanvas.style.display = 'block';

                const drawCanvas = document.createElement('canvas');
                drawCanvas.className = 'drawing-canvas';
                drawCanvas.width = scaledViewport.width * pixelRatio;
                drawCanvas.height = scaledViewport.height * pixelRatio;
                drawCanvas.style.width = scaledViewport.width + 'px';
                drawCanvas.style.height = scaledViewport.height + 'px';
                drawCanvas.dataset.page = pageNum;
                drawCanvas.dataset.scale = pixelRatio;
                drawCanvas.style.touchAction = 'pan-y';

                pageWrapper.appendChild(pdfCanvas);
                pageWrapper.appendChild(drawCanvas);
                container.appendChild(pageWrapper);

                const context = pdfCanvas.getContext('2d');
                context.scale(pixelRatio, pixelRatio);
                await page.render({
                    canvasContext: context,
                    viewport: scaledViewport
                }).promise;

                drawings[pageNum] = [];
                history[pageNum] = [];
                setupDrawing(drawCanvas, pageNum);
                updateCanvasCursor(drawCanvas);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to load PDF');
        }
    }

    function setupContainerScrolling(container) {
        container.style.touchAction = 'auto';
        container.style.overflowY = 'scroll';
        container.style.webkitOverflowScrolling = 'touch';
    }

    function updateCanvasCursor(canvas) {
        if (!canvas) return;
        canvas.className = 'drawing-canvas tool-cursor-' + currentTool;
    }

    function setupDrawing(canvas, pageNum) {
        const ctx = canvas.getContext('2d');
        const wrapper = canvas.parentElement;
        const scale = parseFloat(canvas.dataset.scale) || 1;

        function handleStart(e) {
            if (currentTool === 'select' || !e.target.classList.contains('drawing-canvas')) return;
            activeCanvas = canvas;
            const rect = canvas.getBoundingClientRect();
            const coords = getEventCoordinates(e, rect);
            startX = coords.x * scale;
            startY = coords.y * scale;
            lastX = startX;
            lastY = startY;

            if (currentTool === 'select') {
                const handleInfo = getResizeHandleAt(wrapper, startX / scale, startY / scale);
                if (handleInfo && selectedObject && selectedPage === pageNum) {
                    isResizing = true;
                    resizeHandle = handleInfo.handle;
                    e.preventDefault();
                    return;
                }
                const clicked = findObjectAt(pageNum, startX, startY);
                if (clicked) {
                    if (selectedObject === clicked && selectedPage === pageNum) {
                        if (isPointInObject(clicked, startX, startY)) {
                            isDragging = true;
                            if (clicked.tool === 'text') {
                                dragOffsetX = startX - clicked.x;
                                dragOffsetY = startY - clicked.y;
                            } else {
                                dragOffsetX = startX - clicked.x1;
                                dragOffsetY = startY - clicked.y1;
                            }
                        }
                    } else {
                        selectObject(clicked, pageNum, canvas);
                    }
                } else {
                    deselectObject();
                }
                e.preventDefault();
                return;
            }

            if (currentTool === 'text') {
                addText(canvas, pageNum, startX, startY);
                e.preventDefault();
                return;
            }
            if (currentTool === 'pen') {
                penPoints.push({
                    x: startX,
                    y: startY,
                    time: Date.now()
                });
                lastTimestamp = Date.now();
            }
            if (currentTool === 'eraser') eraseAtPoint(canvas, pageNum, startX, startY);
            isDrawing = true;
            e.preventDefault();
        }

        function handleMove(e) {
            const rect = canvas.getBoundingClientRect();
            const coords = getEventCoordinates(e, rect);
            const x = coords.x * scale;
            const y = coords.y * scale;

            if (currentTool === 'select' && selectedObject && selectedPage === pageNum) {
                if (isDragging) {
                    moveObject(canvas, pageNum, x - dragOffsetX, y - dragOffsetY);
                    e.preventDefault();
                    return;
                } else if (isResizing) {
                    resizeObject(canvas, pageNum, x, y);
                    e.preventDefault();
                    return;
                }
            }

            if (!isDrawing) return;

            if (currentTool === 'pen') {
                const currentTime = Date.now();
                const timeDelta = Math.max(currentTime - lastTimestamp, 1);
                const distance = Math.sqrt(Math.pow(x - lastX, 2) + Math.pow(y - lastY, 2));
                const speed = distance / timeDelta;
                const normalizedSpeed = Math.min(speed / 2, 1);
                const pressure = Math.pow(1 - normalizedSpeed, 2);
                const finalPressure = Math.max(0.3, Math.min(1, pressure));
                const dynamicSize = penSize * finalPressure;
                const smoothX = lastX + (x - lastX) * smoothingFactor;
                const smoothY = lastY + (y - lastY) * smoothingFactor;
                penPoints.push({
                    x: smoothX,
                    y: smoothY,
                    time: currentTime,
                    pressure: finalPressure,
                    size: dynamicSize
                });

                if (penPoints.length >= 4) {
                    const p0 = penPoints[penPoints.length - 4];
                    const p1 = penPoints[penPoints.length - 3];
                    const p2 = penPoints[penPoints.length - 2];
                    const p3 = penPoints[penPoints.length - 1];
                    const cp1x = p1.x + (p2.x - p0.x) / 6;
                    const cp1y = p1.y + (p2.y - p0.y) / 6;
                    const cp2x = p2.x - (p3.x - p1.x) / 6;
                    const cp2y = p2.y - (p3.y - p1.y) / 6;

                    ctx.save();
                    ctx.strokeStyle = currentColor;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                    const segments = 10;
                    for (let i = 0; i < segments; i++) {
                        const t = i / segments,
                            t1 = (i + 1) / segments;
                        const x1 = Math.pow(1 - t, 3) * p1.x + 3 * Math.pow(1 - t, 2) * t * cp1x + 3 * (1 - t) * Math.pow(t, 2) * cp2x + Math.pow(t, 3) * p2.x;
                        const y1 = Math.pow(1 - t, 3) * p1.y + 3 * Math.pow(1 - t, 2) * t * cp1y + 3 * (1 - t) * Math.pow(t, 2) * cp2y + Math.pow(t, 3) * p2.y;
                        const x2 = Math.pow(1 - t1, 3) * p1.x + 3 * Math.pow(1 - t1, 2) * t1 * cp1x + 3 * (1 - t1) * Math.pow(t1, 2) * cp2x + Math.pow(t1, 3) * p2.x;
                        const y2 = Math.pow(1 - t1, 3) * p1.y + 3 * Math.pow(1 - t1, 2) * t1 * cp1y + 3 * (1 - t1) * Math.pow(t1, 2) * cp2y + Math.pow(t1, 3) * p2.y;
                        const width = p1.size + (p2.size - p1.size) * t;
                        ctx.lineWidth = width;
                        ctx.beginPath();
                        ctx.moveTo(x1, y1);
                        ctx.lineTo(x2, y2);
                        ctx.stroke();
                    }
                    ctx.restore();

                    drawings[pageNum].push({
                        tool: 'pen',
                        id: Date.now() + Math.random(),
                        x1: p1.x,
                        y1: p1.y,
                        x2: p2.x,
                        y2: p2.y,
                        cp1x,
                        cp1y,
                        cp2x,
                        cp2y,
                        color: currentColor,
                        size1: p1.size,
                        size2: p2.size,
                        pressure1: p1.pressure,
                        pressure2: p2.pressure
                    });
                }
                lastX = smoothX;
                lastY = smoothY;
                lastTimestamp = currentTime;
            } else if (currentTool === 'highlighter') {
                drawLine(ctx, lastX, lastY, x, y, currentColor, penSize * 3, 0.3);
                drawings[pageNum].push({
                    tool: 'highlighter',
                    id: Date.now() + Math.random(),
                    x1: lastX,
                    y1: lastY,
                    x2: x,
                    y2: y,
                    color: currentColor,
                    size: penSize * 3
                });
                lastX = x;
                lastY = y;
            } else if (currentTool === 'eraser') {
                eraseAtPoint(canvas, pageNum, x, y);
                lastX = x;
                lastY = y;
            } else if (['rectangle', 'circle', 'arrow', 'line'].includes(currentTool)) {
                redrawCanvas(canvas, pageNum);
                drawShape(ctx, currentTool, startX, startY, x, y, currentColor, penSize);
            }
            e.preventDefault();
        }

        function handleEnd(e) {
            if (isDragging || isResizing) {
                isDragging = false;
                isResizing = false;
                resizeHandle = null;
                saveHistory(pageNum);
                if (currentTool !== 'select') e.preventDefault();
                return;
            }
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            const coords = getEventCoordinates(e, rect);
            const x = coords.x * scale,
                y = coords.y * scale;

            if (['rectangle', 'circle', 'arrow', 'line'].includes(currentTool)) {
                drawings[pageNum].push({
                    tool: currentTool,
                    id: Date.now(),
                    x1: startX,
                    y1: startY,
                    x2: x,
                    y2: y,
                    color: currentColor,
                    size: penSize
                });
                saveHistory(pageNum);
            } else if (currentTool === 'pen') {
                penPoints = [];
                saveHistory(pageNum);
            } else if (currentTool === 'highlighter') {
                saveHistory(pageNum);
            }
            isDrawing = false;
            if (currentTool !== 'select') e.preventDefault();
        }

        canvas.addEventListener('mousedown', handleStart);
        canvas.addEventListener('mousemove', handleMove);
        canvas.addEventListener('mouseup', handleEnd);
        canvas.addEventListener('mouseleave', () => {
            isDrawing = false;
            if (currentTool === 'pen') penPoints = [];
        });
        canvas.addEventListener('touchstart', handleStart, {
            passive: false
        });
        canvas.addEventListener('touchmove', handleMove, {
            passive: false
        });
        canvas.addEventListener('touchend', handleEnd, {
            passive: false
        });
        canvas.addEventListener('touchcancel', () => {
            isDrawing = false;
            if (currentTool === 'pen') penPoints = [];
        }, {
            passive: false
        });
        canvas.addEventListener('pointerdown', handleStart);
        canvas.addEventListener('pointermove', handleMove);
        canvas.addEventListener('pointerup', handleEnd);
        canvas.addEventListener('pointercancel', () => {
            isDrawing = false;
            if (currentTool === 'pen') penPoints = [];
        });

        wrapper.addEventListener('mousedown', (e) => {
            if (e.target.classList.contains('resize-handle')) {
                e.stopPropagation();
                isResizing = true;
                resizeHandle = e.target.dataset.handle;
                const rect = canvas.getBoundingClientRect();
                startX = (e.clientX - rect.left) * scale;
                startY = (e.clientY - rect.top) * scale;
                e.preventDefault();
            }
        });
        wrapper.addEventListener('touchstart', (e) => {
            if (e.target.classList.contains('resize-handle')) {
                e.stopPropagation();
                isResizing = true;
                resizeHandle = e.target.dataset.handle;
                const rect = canvas.getBoundingClientRect();
                const touch = e.touches[0];
                startX = (touch.clientX - rect.left) * scale;
                startY = (touch.clientY - rect.top) * scale;
                e.preventDefault();
            }
        }, {
            passive: false
        });
        wrapper.addEventListener('pointerdown', (e) => {
            if (e.target.classList.contains('resize-handle')) {
                e.stopPropagation();
                isResizing = true;
                resizeHandle = e.target.dataset.handle;
                const rect = canvas.getBoundingClientRect();
                startX = (e.clientX - rect.left) * scale;
                startY = (e.clientY - rect.top) * scale;
                e.preventDefault();
            }
        });
    }

    function drawLine(ctx, x1, y1, x2, y2, color, width, alpha = 1) {
        ctx.save();
        ctx.globalAlpha = alpha;
        ctx.strokeStyle = color;
        ctx.lineWidth = width;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.beginPath();
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.stroke();
        ctx.restore();
    }

    function drawSmoothLine(ctx, x1, y1, x2, y2, cp1x, cp1y, cp2x, cp2y, color, size1, size2, alpha = 1) {
        ctx.save();
        ctx.globalAlpha = alpha;
        ctx.strokeStyle = color;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        if (cp1x !== undefined && cp1y !== undefined && cp2x !== undefined && cp2y !== undefined) {
            const segments = 15;
            for (let i = 0; i < segments; i++) {
                const t = i / segments,
                    t1 = (i + 1) / segments;
                const bx1 = Math.pow(1 - t, 3) * x1 + 3 * Math.pow(1 - t, 2) * t * cp1x + 3 * (1 - t) * Math.pow(t, 2) * cp2x + Math.pow(t, 3) * x2;
                const by1 = Math.pow(1 - t, 3) * y1 + 3 * Math.pow(1 - t, 2) * t * cp1y + 3 * (1 - t) * Math.pow(t, 2) * cp2y + Math.pow(t, 3) * y2;
                const bx2 = Math.pow(1 - t1, 3) * x1 + 3 * Math.pow(1 - t1, 2) * t1 * cp1x + 3 * (1 - t1) * Math.pow(t1, 2) * cp2x + Math.pow(t1, 3) * x2;
                const by2 = Math.pow(1 - t1, 3) * y1 + 3 * Math.pow(1 - t1, 2) * t1 * cp1y + 3 * (1 - t1) * Math.pow(t1, 2) * cp2y + Math.pow(t1, 3) * y2;
                const width = size1 + (size2 - size1) * t;
                ctx.lineWidth = width;
                ctx.beginPath();
                ctx.moveTo(bx1, by1);
                ctx.lineTo(bx2, by2);
                ctx.stroke();
            }
        } else {
            ctx.lineWidth = size1 || size2;
            ctx.beginPath();
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.stroke();
        }
        ctx.restore();
    }

    function drawShape(ctx, shape, x1, y1, x2, y2, color, width) {
        ctx.strokeStyle = color;
        ctx.lineWidth = width;
        ctx.lineCap = 'round';
        if (shape === 'rectangle') {
            ctx.strokeRect(x1, y1, x2 - x1, y2 - y1);
        } else if (shape === 'circle') {
            const radius = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
            ctx.beginPath();
            ctx.arc(x1, y1, radius, 0, 2 * Math.PI);
            ctx.stroke();
        } else if (shape === 'line') {
            ctx.beginPath();
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.stroke();
        } else if (shape === 'arrow') {
            ctx.beginPath();
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.stroke();
            const angle = Math.atan2(y2 - y1, x2 - x1),
                arrowLength = 15;
            ctx.beginPath();
            ctx.moveTo(x2, y2);
            ctx.lineTo(x2 - arrowLength * Math.cos(angle - Math.PI / 6), y2 - arrowLength * Math.sin(angle - Math.PI / 6));
            ctx.moveTo(x2, y2);
            ctx.lineTo(x2 - arrowLength * Math.cos(angle + Math.PI / 6), y2 - arrowLength * Math.sin(angle + Math.PI / 6));
            ctx.stroke();
        }
    }

    function addText(canvas, pageNum, x, y) {
        const text = prompt('Enter text:');
        if (!text) return;
        const ctx = canvas.getContext('2d');
        ctx.font = `${fontSize}px Arial`;
        ctx.fillStyle = currentColor;
        ctx.fillText(text, x, y);
        drawings[pageNum].push({
            tool: 'text',
            id: Date.now(),
            x,
            y,
            text,
            color: currentColor,
            size: fontSize
        });
        saveHistory(pageNum);
    }

    function eraseAtPoint(canvas, pageNum, x, y) {
        const eraserSize = penSize * 3;
        const originalLength = drawings[pageNum].length;
        drawings[pageNum] = drawings[pageNum].filter(item => {
            if (item.tool === 'text') {
                const dist = Math.sqrt(Math.pow(item.x - x, 2) + Math.pow(item.y - y, 2));
                return dist > eraserSize;
            }
            if (item.x1 !== undefined) {
                const dist = pointToLineDistance(x, y, item.x1, item.y1, item.x2, item.y2);
                return dist > eraserSize;
            }
            return true;
        });
        if (drawings[pageNum].length !== originalLength) {
            redrawCanvas(canvas, pageNum);
            if (selectedObject && !drawings[pageNum].includes(selectedObject)) deselectObject();
        }
    }

    function pointToLineDistance(px, py, x1, y1, x2, y2) {
        const A = px - x1,
            B = py - y1,
            C = x2 - x1,
            D = y2 - y1;
        const dot = A * C + B * D,
            lenSq = C * C + D * D;
        let param = -1;
        if (lenSq !== 0) param = dot / lenSq;
        let xx, yy;
        if (param < 0) {
            xx = x1;
            yy = y1;
        } else if (param > 1) {
            xx = x2;
            yy = y2;
        } else {
            xx = x1 + param * C;
            yy = y1 + param * D;
        }
        return Math.sqrt(Math.pow(px - xx, 2) + Math.pow(py - yy, 2));
    }

    function redrawCanvas(canvas, pageNum) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawings[pageNum].forEach(item => {
            if (item.tool === 'pen') {
                if (item.cp1x !== undefined) drawSmoothLine(ctx, item.x1, item.y1, item.x2, item.y2, item.cp1x, item.cp1y, item.cp2x, item.cp2y, item.color, item.size1, item.size2);
                else drawLine(ctx, item.x1, item.y1, item.x2, item.y2, item.color, item.size);
            } else if (item.tool === 'highlighter') {
                drawLine(ctx, item.x1, item.y1, item.x2, item.y2, item.color, item.size, 0.3);
            } else if (['rectangle', 'circle', 'arrow', 'line'].includes(item.tool)) {
                drawShape(ctx, item.tool, item.x1, item.y1, item.x2, item.y2, item.color, item.size);
            } else if (item.tool === 'text') {
                ctx.font = `${item.size}px Arial`;
                ctx.fillStyle = item.color;
                ctx.fillText(item.text, item.x, item.y);
            }
        });
    }

    function findObjectAt(pageNum, x, y) {
        for (let i = drawings[pageNum].length - 1; i >= 0; i--) {
            const obj = drawings[pageNum][i];
            if (isPointInObject(obj, x, y)) return obj;
        }
        return null;
    }

    function isPointInObject(obj, x, y) {
        const tolerance = 10;
        if (obj.tool === 'text') {
            const textWidth = obj.text.length * obj.size * 0.6;
            return x >= obj.x - tolerance && x <= obj.x + textWidth + tolerance && y >= obj.y - obj.size - tolerance && y <= obj.y + tolerance;
        }
        if (obj.tool === 'rectangle') {
            const minX = Math.min(obj.x1, obj.x2) - tolerance,
                maxX = Math.max(obj.x1, obj.x2) + tolerance;
            const minY = Math.min(obj.y1, obj.y2) - tolerance,
                maxY = Math.max(obj.y1, obj.y2) + tolerance;
            return x >= minX && x <= maxX && y >= minY && y <= maxY;
        }
        if (obj.tool === 'circle') {
            const radius = Math.sqrt(Math.pow(obj.x2 - obj.x1, 2) + Math.pow(obj.y2 - obj.y1, 2));
            return Math.abs(Math.sqrt(Math.pow(x - obj.x1, 2) + Math.pow(y - obj.y1, 2)) - radius) <= tolerance;
        }
        if (obj.x1 !== undefined) return pointToLineDistance(x, y, obj.x1, obj.y1, obj.x2, obj.y2) <= (obj.size || 3) + tolerance;
        return false;
    }

    function selectObject(obj, pageNum, canvas) {
        deselectObject();
        selectedObject = obj;
        selectedPage = pageNum;
        drawSelectionBox(canvas, obj);
        document.getElementById('deleteSelected').style.display = 'flex';
    }

    function deselectObject() {
        selectedObject = null;
        selectedPage = null;
        document.querySelectorAll('.selection-box').forEach(box => box.remove());
        document.getElementById('deleteSelected').style.display = 'none';
    }

    function drawSelectionBox(canvas, obj) {
        const wrapper = canvas.parentElement;
        wrapper.querySelectorAll('.selection-box').forEach(el => el.remove());
        const scale = parseFloat(canvas.dataset.scale) || 1;
        let bounds = getObjectBounds(obj);
        const displayBounds = {
            x: bounds.x / scale,
            y: bounds.y / scale,
            width: bounds.width / scale,
            height: bounds.height / scale
        };
        const box = document.createElement('div');
        box.className = 'selection-box';
        box.style.left = displayBounds.x + 'px';
        box.style.top = displayBounds.y + 'px';
        box.style.width = displayBounds.width + 'px';
        box.style.height = displayBounds.height + 'px';
        wrapper.appendChild(box);
        ['nw', 'ne', 'sw', 'se', 'n', 's', 'e', 'w'].forEach(pos => {
            const handle = document.createElement('div');
            handle.className = 'resize-handle ' + pos;
            handle.dataset.handle = pos;
            box.appendChild(handle);
        });
    }

    function getObjectBounds(obj) {
        if (obj.tool === 'text') {
            const textWidth = obj.text.length * obj.size * 0.6;
            return {
                x: obj.x - 5,
                y: obj.y - obj.size - 5,
                width: textWidth + 10,
                height: obj.size + 10
            };
        }
        const minX = Math.min(obj.x1, obj.x2),
            maxX = Math.max(obj.x1, obj.x2);
        const minY = Math.min(obj.y1, obj.y2),
            maxY = Math.max(obj.y1, obj.y2);
        return {
            x: minX - 10,
            y: minY - 10,
            width: maxX - minX + 20,
            height: maxY - minY + 20
        };
    }

    function getResizeHandleAt(wrapper, x, y) {
        const handles = wrapper.querySelectorAll('.resize-handle');
        const tolerance = 20;
        for (let handle of handles) {
            const rect = handle.getBoundingClientRect();
            const wrapperRect = wrapper.getBoundingClientRect();
            const hx = rect.left - wrapperRect.left + rect.width / 2;
            const hy = rect.top - wrapperRect.top + rect.height / 2;
            if (Math.abs(x - hx) < tolerance && Math.abs(y - hy) < tolerance) return {
                handle: handle.dataset.handle,
                element: handle
            };
        }
        return null;
    }

    function moveObject(canvas, pageNum, newX, newY) {
        if (!selectedObject) return;
        if (selectedObject.tool === 'text') {
            selectedObject.x = newX;
            selectedObject.y = newY;
        } else if (selectedObject.x1 !== undefined) {
            const dx = newX - selectedObject.x1,
                dy = newY - selectedObject.y1;
            selectedObject.x1 = newX;
            selectedObject.y1 = newY;
            selectedObject.x2 += dx;
            selectedObject.y2 += dy;
        }
        redrawCanvas(canvas, pageNum);
        drawSelectionBox(canvas, selectedObject);
    }

    function resizeObject(canvas, pageNum, x, y) {
        if (!selectedObject || !resizeHandle) return;
        if (selectedObject.tool === 'text') {
            const delta = Math.sqrt(Math.pow(x - selectedObject.x, 2) + Math.pow(y - selectedObject.y, 2));
            selectedObject.size = Math.max(8, Math.min(72, delta / 2));
        } else if (selectedObject.tool === 'circle') {
            selectedObject.x2 = x;
            selectedObject.y2 = y;
        } else {
            if (resizeHandle.includes('e')) selectedObject.x2 = x;
            if (resizeHandle.includes('w')) selectedObject.x1 = x;
            if (resizeHandle.includes('n')) selectedObject.y1 = y;
            if (resizeHandle.includes('s')) selectedObject.y2 = y;
        }
        redrawCanvas(canvas, pageNum);
        drawSelectionBox(canvas, selectedObject);
    }

    function saveHistory(pageNum) {
        history[pageNum].push(JSON.parse(JSON.stringify(drawings[pageNum])));
        if (history[pageNum].length > 20) history[pageNum].shift();
    }

    // Tool selection — uses new anno-tool-btn class
    document.querySelectorAll('[data-tool]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('[data-tool]').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentTool = btn.dataset.tool;
            document.querySelectorAll('.drawing-canvas').forEach(canvas => updateCanvasCursor(canvas));
            const fontControl = document.getElementById('fontSizeControl');
            fontControl.style.display = currentTool === 'text' ? 'flex' : 'none';
            if (currentTool !== 'select') deselectObject();
        });
    });

    document.getElementById('colorPicker').addEventListener('change', (e) => {
        currentColor = e.target.value;
        if (selectedObject) {
            selectedObject.color = currentColor;
            const canvas = document.querySelector(`[data-page="${selectedPage}"]`);
            if (canvas) {
                redrawCanvas(canvas, selectedPage);
                drawSelectionBox(canvas, selectedObject);
            }
        }
    });

    document.getElementById('penSize').addEventListener('input', (e) => {
        penSize = parseInt(e.target.value);
        document.getElementById('penSizeLabel').textContent = penSize;
        if (selectedObject && selectedObject.size) {
            selectedObject.size = penSize;
            const canvas = document.querySelector(`[data-page="${selectedPage}"]`);
            if (canvas) {
                redrawCanvas(canvas, selectedPage);
                drawSelectionBox(canvas, selectedObject);
            }
        }
    });

    document.getElementById('fontSize').addEventListener('input', (e) => {
        fontSize = parseInt(e.target.value);
    });

    document.getElementById('deleteSelected').addEventListener('click', () => {
        if (!selectedObject || selectedPage === null) return;
        const index = drawings[selectedPage].indexOf(selectedObject);
        if (index > -1) {
            drawings[selectedPage].splice(index, 1);
            const canvas = document.querySelector(`[data-page="${selectedPage}"]`);
            if (canvas) {
                redrawCanvas(canvas, selectedPage);
                saveHistory(selectedPage);
            }
            deselectObject();
        }
    });

    document.getElementById('undoBtn').addEventListener('click', () => {
        document.querySelectorAll('.drawing-canvas').forEach(canvas => {
            const pageNum = parseInt(canvas.dataset.page);
            if (history[pageNum].length > 0) {
                history[pageNum].pop();
                drawings[pageNum] = history[pageNum].length > 0 ? JSON.parse(JSON.stringify(history[pageNum][history[pageNum].length - 1])) : [];
                redrawCanvas(canvas, pageNum);
                deselectObject();
            }
        });
    });

    document.getElementById('clearAll').addEventListener('click', () => {
        if (!confirm('Clear all annotations?')) return;
        document.querySelectorAll('.drawing-canvas').forEach(canvas => {
            const pageNum = parseInt(canvas.dataset.page);
            drawings[pageNum] = [];
            history[pageNum] = [];
            redrawCanvas(canvas, pageNum);
        });
        deselectObject();
    });

    document.getElementById('saveBtn').addEventListener('click', async () => {
        const saveBtn = document.getElementById('saveBtn');
        const saveStatus = document.getElementById('saveStatus');
        try {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            saveStatus.textContent = 'Generating PDF...';
            const {
                jsPDF
            } = window.jspdf;
            let pdf = null;
            const pageWrappers = document.querySelectorAll('.page-wrapper');
            for (let i = 0; i < pageWrappers.length; i++) {
                const wrapper = pageWrappers[i];
                const pdfCanvas = wrapper.querySelector('canvas:not(.drawing-canvas)');
                const drawCanvas = wrapper.querySelector('.drawing-canvas');
                const mergeCanvas = document.createElement('canvas');
                mergeCanvas.width = pdfCanvas.width;
                mergeCanvas.height = pdfCanvas.height;
                const ctx = mergeCanvas.getContext('2d');
                ctx.drawImage(pdfCanvas, 0, 0);
                ctx.drawImage(drawCanvas, 0, 0);
                const imgData = mergeCanvas.toDataURL('image/jpeg', 0.95);
                const width = mergeCanvas.width * 0.75,
                    height = mergeCanvas.height * 0.75;
                if (i === 0) {
                    pdf = new jsPDF({
                        orientation: width > height ? 'l' : 'p',
                        unit: 'pt',
                        format: [width, height]
                    });
                } else {
                    pdf.addPage([width, height]);
                }
                pdf.addImage(imgData, 'JPEG', 0, 0, width, height);
            }
            saveStatus.textContent = 'Uploading...';
            const pdfBlob = pdf.output('blob');
            const formData = new FormData();
            formData.append('annotated_pdf', pdfBlob, 'annotated.pdf');
            formData.append('annotation_data', JSON.stringify(drawings));
            formData.append('_token', '{{ csrf_token() }}');
            const response = await fetch("{{ route('admin.submission.annotated.upload', $submission->id) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) throw new Error('Upload failed');
            saveStatus.textContent = '✓ Saved Successfully';
            saveStatus.className = 'text-success fw-bold small ms-2';
            setTimeout(() => {
                saveStatus.textContent = '';
                saveStatus.className = 'text-muted small ms-2';
            }, 3000);
        } catch (error) {
            console.error(error);
            saveStatus.textContent = '✗ Save Error';
            saveStatus.className = 'text-danger fw-bold small ms-2';
            alert('Failed to save annotated PDF. Please try again.');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fi-rr-disk"></i> Save Annotated PDF';
        }
    });

    initPDF();
</script>
@endif
@endsection