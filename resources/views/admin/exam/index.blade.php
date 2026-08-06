@extends('layouts.admin')
@push('title', get_phrase('Exam Manager'))
@section('content')

<div class="ol-card">
    <div class="ol-card-body">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Manage exams') }}
            </h4>
            <a href="{{ route('admin.exam.create') }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-plus"></span>
                <span>{{ get_phrase('Add New exam') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="row g-2 g-sm-3 row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-lg-4 row-cols-xl-4">

    <div class="col">
        <a href="{{ route('admin.exams', ['status' => 'pending']) }}" class="d-block text-decoration-none">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background: linear-gradient(135deg, #fef9c3, #fef08a); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                            <p class="sub-title fs-14px fw-semibold" style="margin: 0;">{{ $pending_exams }}</p>
                            <h6 class="title fs-12px" style="margin: 0;">{{ get_phrase('Pending exams') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="{{ route('admin.exams', ['status' => 'checking']) }}" class="d-block text-decoration-none">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                            <p class="sub-title fs-14px fw-semibold" style="margin: 0;">{{ $checking_exams }}</p>
                            <h6 class="title fs-12px" style="margin: 0;">{{ get_phrase('Checking exams') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="{{ route('admin.exams', ['status' => 'checked']) }}" class="d-block text-decoration-none">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                            <p class="sub-title fs-14px fw-semibold" style="margin: 0;">{{ $checked_exams }}</p>
                            <h6 class="title fs-12px" style="margin: 0;">{{ get_phrase('Checked exams') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="{{ route('admin.exams', ['status' => 'published']) }}" class="d-block text-decoration-none">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="background: linear-gradient(135deg, #fdf4ff, #f3e8ff); border-radius: 12px; width: 48px; height: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="2" y1="12" x2="22" y2="12" />
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                            <p class="sub-title fs-14px fw-semibold" style="margin: 0;">{{ $published_exams }}</p>
                            <h6 class="title fs-12px" style="margin: 0;">{{ get_phrase('Published exams') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

<!-- Start Admin area -->
<div class="row">
    <div class="col-12">
        <div class="ol-card">
            <div class="ol-card-body">
                <div class="row mt-3 mb-4">
                    <div class="col-md-6 d-flex align-items-center gap-3">
                        <div class="custom-dropdown ms-2">
                            <button class="dropdown-header btn ol-btn-light">
                                {{ get_phrase('Export') }}
                                <i class="fi-rr-file-export ms-2"></i>
                            </button>
                            <ul class="dropdown-list">
                                <li>
                                    <a class="dropdown-item export-btn" href="#" onclick="downloadPDF('.print-table', 'exam-list')"><i class="fi-rr-file-pdf"></i> {{ get_phrase('PDF') }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item export-btn" href="#" onclick="window.print();"><i class="fi-rr-print"></i> {{ get_phrase('Print') }}</a>
                                </li>
                            </ul>
                        </div>

                        @php
                        $filterCount = 0;
                        if (request('status') && request('status') != 'all') $filterCount++;
                        @endphp

                        <div class="custom-dropdown dropdown-filter">
                            <button class="dropdown-header btn ol-btn-light">
                                <i class="fi-rr-filter me-2"></i>
                                {{ get_phrase('Filter') }}
                                @if($filterCount > 0)
                                <span class="text-12px"> ({{ $filterCount }})</span>
                                @endif
                            </button>
                            <ul class="dropdown-list w-250px">
                                <li>
                                    <form id="filter-dropdown" action="{{ route('admin.exams') }}" method="get">
                                        <div class="filter-option d-flex flex-column gap-3">
                                            <div>
                                                <label for="eDataList" class="form-label ol-form-label">{{ get_phrase('Status') }}</label>
                                                <select class="form-control ol-form-control neu-select" data-toggle="select2" name="status" data-placeholder="Type to search...">
                                                    <option value="all">{{ get_phrase('All') }}</option>
                                                    <option value="pending" @if (isset($status) && $status=='pending' ) selected @endif>{{ get_phrase('Pending') }}</option>
                                                    <option value="checking" @if (isset($status) && $status=='checking' ) selected @endif>{{ get_phrase('Checking') }}</option>
                                                    <option value="checked" @if (isset($status) && $status=='checked' ) selected @endif>{{ get_phrase('Checked') }}</option>
                                                    <option value="published" @if (isset($status) && $status=='published' ) selected @endif>{{ get_phrase('Published') }}</option>
                                                    <option value="private" @if (isset($status) && $status=='private' ) selected @endif>{{ get_phrase('Private') }}</option>
                                                    <option value="draft" @if (isset($status) && $status=='draft' ) selected @endif>{{ get_phrase('Draft') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="filter-button d-flex justify-content-end align-items-center mt-3">
                                            <button type="submit" class="ol-btn-primary">{{ get_phrase('Apply') }}</button>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        @if (isset($_GET) && count($_GET) > 0)
                        <a href="{{ route('admin.exams') }}" class="me-2" data-bs-toggle="tooltip" title="{{ get_phrase('Clear') }}"><i class="fi-rr-cross-circle text-dark"></i></a>
                        @endif
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <form action="{{ route('admin.exams') }}" method="get">
                            <div class="row row-gap-3">
                                <div class="col-md-9">
                                    <div class="search-input flex-grow-1">
                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ get_phrase('Search Title') }}" class="ol-form-control form-control" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn ol-btn-primary w-100" id="submit-button">{{ get_phrase('Search') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @if ($exams->count() > 0)
                        <div class="admin-tInfo-pagi d-flex justify-content-between align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($exams) . ' ' . get_phrase('of') . ' ' . $exams->total() . ' ' . get_phrase('data') }}
                            </p>
                        </div>
                        <div class="table-responsive overflow-auto exam_list" id="exam_list">
                            <table class="table eTable eTable-2 print-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ get_phrase('Title') }}</th>
                                        <th>{{ get_phrase('Description') }}</th>
                                        <th>{{ get_phrase('Marks') }}</th>
                                        <th>{{ get_phrase('Duration') }}</th>
                                        <th class="print-d-none">{{ get_phrase('Exam Mode') }}</th>
                                        <th>{{ get_phrase('Question') }}</th>
                                        <th class="print-d-none">{{ get_phrase('Options') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exams as $key => $exam)
                                    <tr>
                                        <th scope="row">{{ ++$key }}</th>
                                        <td>
                                            <a href="{{ route('admin.exam.submissions', $exam->id) }}" class="fw-bold text-decoration-none">
                                                {{ $exam->title }}
                                            </a>
                                            <br>
                                            <small>{{ optional($exam->creator)->name ?? 'N/A' }}</small>
                                            <br>
                                            <small>{{ optional($exam->creator)->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="description-link" data-description="{{ e($exam->description) }}">
                                                {{ \Illuminate\Support\Str::words($exam->description, 5, '...') }}
                                            </a>
                                        </td>
                                        <td>{{ $exam->marks }}</td>
                                        <td>{{ $exam->duration }} {{ get_phrase('minutes') }}</td>
                                        <td class="print-d-none">{{ ucfirst($exam->exam_mode) }}</td>
                                        <td>
                                            @if($exam->question_paper_pdf)
                                            <a href="javascript:void(0)" class="view-pdf-link" data-pdf="{{ asset($exam->question_paper_pdf) }}">
                                                {{ get_phrase('View PDF') }}
                                            </a>
                                            @else
                                            {{ get_phrase('N/A') }}
                                            @endif
                                        </td>
                                        <td class="print-d-none">
                                            <div class="dropdown ol-icon-dropdown ol-icon-dropdown-transparent">
                                                <button class="btn ol-btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="fi-rr-menu-dots-vertical"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.exam.edit', [$exam->id, 'tab' => 'basic']) }}">
                                                            {{ get_phrase('Edit Exam') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" onclick="confirmModal('{{ route('admin.exam.delete', $exam->id) }}')" href="javascript:void(0)">
                                                            {{ get_phrase('Delete Exam') }}
                                                        </a>
                                                    </li>

                                                    @if($exam->submissions->isNotEmpty() && $exam->submissions->whereNull('obtained_marks')->isEmpty())
                                                    @if($exam->submissions->where('status', 'published')->count() === $exam->submissions->count())
                                                    <li>
                                                        <span class="dropdown-item text-success" style="cursor:default;">
                                                            <i class="fi-rr-check"></i> {{ get_phrase('Published') }}
                                                        </span>
                                                    </li>
                                                    @else
                                                    <li>
                                                        <form action="{{ route('admin.exam.publish', $exam->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-primary">
                                                                <i class="fi-rr-paper-plane"></i> {{ get_phrase('Publish Results') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                    @endif

                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="admin-tInfo-pagi d-flex justify-content-between align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($exams) . ' ' . get_phrase('of') . ' ' . $exams->total() . ' ' . get_phrase('data') }}
                            </p>
                            {{ $exams->links() }}
                        </div>
                        @else
                        @include('admin.no_data')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Admin area -->


{{-- Description Modal --}}
<div class="custom-overlay" id="descriptionOverlay">
    <div class="desc-modal">
        <div class="desc-modal-header">
            <h5>{{ get_phrase('Description') }}</h5>
            <button class="modal-close-btn" onclick="closeDescriptionModal()">&#x2715;</button>
        </div>
        <div class="desc-modal-body" id="descriptionContent"></div>
        <div class="desc-modal-footer">
            <button class="btn btn-secondary btn-sm px-4" onclick="closeDescriptionModal()">{{ get_phrase('Close') }}</button>
        </div>
    </div>
</div>

{{-- PDF Modal --}}
<div class="custom-overlay" id="pdfOverlay">
    <div class="pdf-modal">
        <div class="pdf-modal-header">
            <div class="pdf-modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1f1f1f" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <polyline points="10 9 9 9 8 9" />
                </svg>
                {{ get_phrase('Question Paper') }}
            </div>
            <div class="pdf-toolbar">
                <button class="pdf-zoom-btn" id="zoomOutBtn" onclick="changeZoom(-0.2)" title="Zoom Out">&#8722;</button>
                <span class="pdf-zoom-label" id="pdfZoomLevel">100%</span>
                <button class="pdf-zoom-btn" id="zoomInBtn" onclick="changeZoom(0.2)" title="Zoom In">&#43;</button>
                <button class="pdf-close-btn" onclick="closePdfModal()" title="Close">&#x2715;</button>
            </div>
        </div>
        <div class="pdf-body" id="pdfContainer">
            <div class="pdf-loading" id="pdfPagesWrapper">
                <div class="pdf-spinner"></div>
                <span>Loading PDF...</span>
            </div>
        </div>
        <div class="pdf-footer">
            <span class="pdf-page-info" id="pdfPageInfo">Scroll to navigate pages</span>
        </div>
    </div>
</div>

{{-- FIX 1: Load PDF.js BEFORE the script that uses it --}}
<script src="{{ asset('assets/backend/js/pdf.min.js') }}"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('assets/backend/js/pdf.worker.min.js') }}";
</script>

<script>
    // ===== DESCRIPTION MODAL =====

    const noDescText = @json(get_phrase('No description available'));

    document.querySelectorAll('.description-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var el = document.createElement('textarea');
            el.innerHTML = this.getAttribute('data-description') || '';
            var description = el.value;
            document.getElementById('descriptionContent').textContent =
                description.trim() !== '' ? description : noDescText;
            document.getElementById('descriptionOverlay').classList.add('active');
        });
    });

    function closeDescriptionModal() {
        document.getElementById('descriptionOverlay').classList.remove('active');
    }

    document.getElementById('descriptionOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeDescriptionModal();
    });

    // ===== PDF MODAL (PDF.js) =====
    let pdfDoc = null;
    let zoomScale = 1.0;

    function setZoomButtonsState(enabled) {
        document.getElementById('zoomOutBtn').disabled = !enabled;
        document.getElementById('zoomInBtn').disabled = !enabled;
    }

    function renderAllPages() {
        // FIX 3: Guard against null pdfDoc (e.g. zoom clicked before PDF loads)
        if (!pdfDoc) return;

        const container = document.getElementById('pdfContainer');
        container.innerHTML = '<div class="pdf-loading"><div class="pdf-spinner"></div><span>Rendering pages...</span></div>';

        const dpr = window.devicePixelRatio || 1;
        const renders = [];

        for (let i = 1; i <= pdfDoc.numPages; i++) {
            renders.push(
                pdfDoc.getPage(i).then(function(page) {
                    const viewport = page.getViewport({
                        scale: zoomScale
                    });
                    const canvas = document.createElement('canvas');
                    canvas.className = 'pdf-page-canvas';
                    const ctx = canvas.getContext('2d');

                    // High-DPI / retina rendering
                    canvas.width = Math.floor(viewport.width * dpr);
                    canvas.height = Math.floor(viewport.height * dpr);
                    canvas.style.width = Math.floor(viewport.width) + 'px';
                    canvas.style.height = Math.floor(viewport.height) + 'px';
                    ctx.scale(dpr, dpr);

                    return page.render({
                        canvasContext: ctx,
                        viewport: viewport,
                        intent: 'display',
                    }).promise.then(function() {
                        return {
                            pageNum: page.pageNumber,
                            canvas: canvas
                        };
                    });
                })
            );
        }

        Promise.all(renders).then(function(results) {
            container.innerHTML = '';
            results.sort((a, b) => a.pageNum - b.pageNum);
            results.forEach(function(r) {
                container.appendChild(r.canvas);
            });
            document.getElementById('pdfPageInfo').textContent =
                pdfDoc.numPages + ' page' + (pdfDoc.numPages > 1 ? 's' : '') + ' · Scroll to navigate';
        });
    }

    function changeZoom(delta) {
        // FIX 4: Guard against null pdfDoc when zoom is clicked before PDF loads
        if (!pdfDoc) return;

        zoomScale = Math.min(Math.max(zoomScale + delta, 0.4), 3.0);
        document.getElementById('pdfZoomLevel').textContent = Math.round(zoomScale * 100) + '%';
        renderAllPages();
    }

    function loadPdf(url) {
        const container = document.getElementById('pdfContainer');
        container.innerHTML = '<div class="pdf-loading"><div class="pdf-spinner"></div><span>Loading PDF...</span></div>';
        pdfDoc = null; // Reset before loading
        zoomScale = 1.0;
        setZoomButtonsState(false); // Disable zoom until loaded
        document.getElementById('pdfZoomLevel').textContent = '100%';
        document.getElementById('pdfPageInfo').textContent = 'Loading...';

        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            pdfDoc = pdf;
            setZoomButtonsState(true); // Enable zoom once PDF is ready
            renderAllPages();
        }).catch(function(err) {
            container.innerHTML = '<div class="pdf-loading" style="color:#f87171;">Failed to load PDF.<br><small>' + err.message + '</small></div>';
            document.getElementById('pdfPageInfo').textContent = 'Error loading PDF';
        });
    }

    document.querySelectorAll('.view-pdf-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            setZoomButtonsState(false);
            document.getElementById('pdfOverlay').classList.add('active');
            loadPdf(this.getAttribute('data-pdf'));
        });
    });

    function closePdfModal() {
        document.getElementById('pdfOverlay').classList.remove('active');
        document.getElementById('pdfContainer').innerHTML = '';
        document.getElementById('pdfPageInfo').textContent = 'Scroll to navigate pages';
        document.getElementById('pdfZoomLevel').textContent = '100%';
        setZoomButtonsState(false);
        pdfDoc = null;
        zoomScale = 1.0;
    }

    document.getElementById('pdfOverlay').addEventListener('click', function(e) {
        if (e.target === this) closePdfModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDescriptionModal();
            closePdfModal();
        }
    });
</script>

@endsection