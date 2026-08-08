@extends('layouts.default')
@push('title', get_phrase('Exam Details'))
@push('meta')@endpush
@section('content')

<!------------------- Breadcum Area Start  ------>
<section class="breadcum-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="eNtry-breadcum">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('my.courses') }}">Área do aluno</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ get_phrase('Exam Details') }}</li>
                        </ol>
                    </nav>
                    <div class="row row-gap-3">
                        <div class="col-auto col-md-4 col-lg-3">
                            <h3 class="g-title mt-4">{{ get_phrase('Exam Details') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!------------------- Breadcum Area End  --------->

<!-------------- List Item Start   --------------->
<div class="eNtery-item">
    <div class="container">
        <div class="row">
            @include('frontend.default.student.left_sidebar')

            <div class="col-lg-9 col-md-8">

                <!-- ===== Exam Overview Card ===== -->
                <div class="exam-card">
                    <div class="exam-card-body">

                        <div class="exam-title-row">
                            <div>
                                <h2>{{ $exam->title }}</h2>
                                <p class="exam-course-name">{{ $exam->course->title }}</p>
                            </div>
                            <div>
                                {{--
                                    Status badge reflects the actual submission status:
                                    no submission  → Not Submitted
                                    pending        → Not Submitted (registered but no script uploaded)
                                    checking       → Under Review  (script uploaded, not graded yet)
                                    checked        → Under Review  (graded but not published to student yet)
                                    published      → Evaluated     (student can see results)
                                --}}
                                @if(!$submission || $submission->status === 'pending')
                                <span class="exam-status-badge not-submitted">{{ get_phrase('Not Submitted') }}</span>
                                @elseif($submission->status === 'checking' || $submission->status === 'checked')
                                <span class="exam-status-badge under-review">{{ get_phrase('Under Review') }}</span>
                                @elseif($submission->status === 'published')
                                <span class="exam-status-badge evaluated">{{ get_phrase('Evaluated') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($exam->description)
                        <p class="exam-description">{{ $exam->description }}</p>
                        @endif

                        <div class="exam-divider"></div>

                        <div class="exam-instructor">
                            <img src="{{ get_image($exam->creator->photo) }}" alt="instructor">
                            <div>
                                <p class="exam-instructor-label">{{ get_phrase('Instructor') }}</p>
                                <h5 class="exam-instructor-name">{{ $exam->creator->name }}</h5>
                            </div>
                        </div>

                        <div class="exam-divider"></div>

                        <div class="exam-info-grid">
                            <div class="exam-info-cell">
                                <span class="exam-info-cell-label">{{ get_phrase('Duration') }}</span>
                                <span class="exam-info-cell-value">{{ $exam->duration }} {{ get_phrase('min') }}</span>
                            </div>
                            <div class="exam-info-cell">
                                <span class="exam-info-cell-label">{{ get_phrase('Total Marks') }}</span>
                                <span class="exam-info-cell-value">{{ $exam->marks }}</span>
                            </div>
                            <div class="exam-info-cell">
                                <span class="exam-info-cell-label">{{ get_phrase('Start Time') }}</span>
                                <span class="exam-info-cell-value">{{ \Carbon\Carbon::parse($exam->start_at)->format('d/m/Y, H:i') }}</span>
                            </div>
                            <div class="exam-info-cell">
                                <span class="exam-info-cell-label">{{ get_phrase('End Time') }}</span>
                                <span class="exam-info-cell-value">
                                    {{ $exam->end_at ? \Carbon\Carbon::parse($exam->end_at)->format('d/m/Y, H:i') : get_phrase('No deadline') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ===== Question Paper Card ===== -->
                <div class="exam-card">
                    <div class="exam-card-body">
                        <div class="exam-qp-row">
                            <div class="exam-qp-left">
                                <div class="exam-qp-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 512 512" width="20" height="20" xml:space="preserve">
                                        <polygon style="fill:#288FD9;" points="488.56,456.499 512,456.499 512,402.598 478.109,381.695 " />
                                        <polygon style="fill:#8BCBF1;" points="465.122,402.598 465.122,456.499 488.56,456.499 488.56,381.695 " />
                                        <polygon style="fill:#F7C02D;" points="395.055,0 197.528,0 176.624,271.665 197.528,512 395.055,512 " />
                                        <rect style="fill:#FBE27B;" width="197.53" height="511.996" />
                                        <g>
                                            <polygon style="fill:#F39624;" points="264.35,67.852 197.528,67.852 187.076,83.517 197.528,99.183 264.35,99.183" />
                                            <polygon style="fill:#F39624;" points="325.241,121.753 197.528,121.753 187.076,137.419 197.528,153.084 325.241,153.084" />
                                        </g>
                                        <polygon style="fill:#D9000C;" points="488.56,55.501 478.109,264.703 488.56,402.598 512,402.598 512,87.842 " />
                                        <polygon style="fill:#E43138;" points="488.56,55.501 465.122,87.842 465.122,402.598 488.56,402.598 " />
                                        <g>
                                            <rect x="130.701" y="67.852" style="fill:#F7C02D;" width="66.822" height="31.331" />
                                            <rect x="69.814" y="121.751" style="fill:#F7C02D;" width="127.714" height="31.331" />
                                        </g>
                                        <polygon style="fill:#F39624;" points="325.241,175.653 197.528,175.653 187.076,191.319 197.528,206.984 325.241,206.984 " />
                                        <rect x="69.814" y="175.65" style="fill:#F7C02D;" width="127.714" height="31.331" />
                                        <polygon style="fill:#F39624;" points="325.241,229.555 197.528,229.555 187.076,245.22 197.528,260.885 325.241,260.885 " />
                                        <rect x="69.814" y="229.56" style="fill:#F7C02D;" width="127.714" height="31.331" />
                                        <polygon style="fill:#F39624;" points="325.241,358.916 197.528,358.916 187.076,374.581 197.528,390.247 325.241,390.247 " />
                                        <rect x="69.814" y="358.914" style="fill:#F7C02D;" width="127.714" height="31.331" />
                                        <polygon style="fill:#F39624;" points="325.241,412.816 197.528,412.816 187.076,428.482 197.528,444.147 325.241,444.147 " />
                                        <rect x="69.814" y="412.813" style="fill:#F7C02D;" width="127.714" height="31.331" />
                                    </svg>
                                </div>
                                <div class="exam-qp-info">
                                    <h5>{{ get_phrase('Question Paper') }}</h5>
                                    <p>{{ get_phrase('View or download the exam question paper') }}</p>
                                </div>
                            </div>
                            <div>
                                @if($exam->question_paper_pdf)
                                <a href="{{ asset($exam->question_paper_pdf) }}" target="_blank" class="eBtn gradient">
                                    {{ get_phrase('View Question Paper') }}
                                </a>
                                @else
                                <span class="exam-not-available">
                                    <i class="bi bi-file-earmark-x"></i> {{ get_phrase('Not Available') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== Submission Card ===== -->
                {{--
                    Show upload form only if student has no submission yet,
                    or their submission is still in pending (no script uploaded).
                    Once script is uploaded (checking/checked/published), show submitted alert.
                --}}
                @if(!$submission || $submission->status === 'pending')
                <div class="exam-card">
                    <div class="exam-card-header">
                        <h5>{{ get_phrase('Submit Your Answer') }}</h5>
                        <p>{{ get_phrase('Upload your completed answer script') }}</p>
                    </div>
                    <div class="exam-card-body" style="padding-top: 24px;">
                        <form id="examForm" action="{{ route('my.exam.submit', $exam->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="file" id="examFile" name="answer_script" accept=".pdf,.doc,.docx">

                            <div class="uploader-zone" id="uploaderZone">
                                <div class="uploader-zone-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.338-2.32 5.75 5.75 0 0 1 .857 11.095H6.75Z" />
                                    </svg>
                                </div>
                                <p class="uploader-zone-text">
                                    {{ get_phrase('Drop your file here or') }} <span>{{ get_phrase('browse') }}</span>
                                </p>
                                <p class="uploader-zone-hint">PDF, DOC, DOCX &nbsp;·&nbsp; {{ get_phrase('Max 10MB') }}</p>
                            </div>

                            <div class="uploader-file-row" id="uploaderFileRow">
                                <div class="uploader-file-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                </div>
                                <div class="uploader-file-meta">
                                    <div class="uploader-file-name" id="uploaderFileName"></div>
                                    <div class="uploader-file-size" id="uploaderFileSize"></div>
                                </div>
                                <button type="button" class="uploader-file-clear" id="uploaderClear">
                                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <button type="submit" class="eBtn gradient" id="examSubmitBtn" disabled style="opacity:0.45;cursor:not-allowed;">
                                {{ get_phrase('Submit Exam') }}
                            </button>

                        </form>
                    </div>
                </div>

                @else
                {{-- Script has been uploaded — show submitted confirmation --}}
                <div class="exam-card">
                    <div class="exam-card-body">
                        <div class="exam-submitted-alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            <div>
                                <h5>{{ get_phrase('Answer Script Submitted') }}</h5>
                                <p>{{ get_phrase('Submitted on') }}: {{ \Carbon\Carbon::parse($submission->created_at)->format('d/m/Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ===== Evaluation Result Card ===== -->
                {{--
                    ONLY show results when admin has published — status = 'published'.
                    checked means graded but NOT yet released to student.
                    Student must not see marks until admin explicitly publishes.
                --}}
                @if($submission && $submission->status === 'published')
                <div class="exam-card">
                    <div class="exam-card-header">
                        <h5>{{ get_phrase('Evaluation Result') }}</h5>
                    </div>
                    <div class="exam-card-body" style="padding-top: 24px;">

                        <div class="exam-score-block">
                            <div class="exam-score-top">
                                <span class="exam-score-label">{{ get_phrase('Marks Obtained') }}</span>
                                <span class="exam-score-value">
                                    {{ $submission->obtained_marks }}
                                    <span>/ {{ $exam->marks }}</span>
                                </span>
                            </div>
                            <div class="exam-score-bar">
                                <div class="exam-score-bar-fill" style="width: {{ ($submission->obtained_marks / $exam->marks) * 100 }}%"></div>
                            </div>
                            <div class="exam-score-percent">
                                {{ number_format(($submission->obtained_marks / $exam->marks) * 100, 1) }}% {{ get_phrase('Score') }}
                            </div>
                        </div>

                        @if($submission->remarks)
                        <div class="exam-feedback-block">
                            <h6>{{ get_phrase('Instructor Feedback') }}</h6>
                            <p>{{ $submission->remarks }}</p>
                        </div>
                        @endif

                        @if($submission->annotated_pdf)
                        <div>
                            <p class="exam-section-label">{{ get_phrase('Annotated Answer Script') }}</p>
                            <div id="pdf-viewer-container" class="exam-pdf-viewer"></div>
                            <a href="{{ asset($submission->annotated_pdf) }}" class="eBtn gradient" download style="margin-top:12px;display:inline-block;">
                                {{ get_phrase('Download PDF') }}
                            </a>
                        </div>
                        @endif

                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
<!-------------- List Item End  --------------->

{{-- File uploader script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var zone = document.getElementById('uploaderZone');
        var input = document.getElementById('examFile');
        var fileRow = document.getElementById('uploaderFileRow');
        var nameEl = document.getElementById('uploaderFileName');
        var sizeEl = document.getElementById('uploaderFileSize');
        var clearBtn = document.getElementById('uploaderClear');
        var submitBtn = document.getElementById('examSubmitBtn');
        var form = document.getElementById('examForm');

        if (!zone) return;

        function fmtBytes(b) {
            if (b < 1024) return b + ' B';
            if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
            return (b / 1048576).toFixed(2) + ' MB';
        }

        function setFile(file) {
            nameEl.textContent = file.name;
            sizeEl.textContent = fmtBytes(file.size);
            zone.style.display = 'none';
            fileRow.classList.add('show');
            submitBtn.removeAttribute('disabled');
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }

        function clearFile() {
            input.value = '';
            input._fallback = null;
            zone.style.display = '';
            fileRow.classList.remove('show');
            submitBtn.setAttribute('disabled', true);
            submitBtn.style.opacity = '0.45';
            submitBtn.style.cursor = 'not-allowed';
        }

        zone.addEventListener('click', function() {
            input.click();
        });
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                input._fallback = null;
                setFile(this.files[0]);
            }
        });
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearFile();
        });

        var counter = 0;
        zone.addEventListener('dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            counter++;
            zone.classList.add('is-over');
        });
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        zone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (--counter <= 0) {
                counter = 0;
                zone.classList.remove('is-over');
            }
        });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            counter = 0;
            zone.classList.remove('is-over');
            var files = e.dataTransfer.files;
            if (!files || !files.length) return;
            var file = files[0];
            try {
                var dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                input._fallback = null;
            } catch (err) {
                input._fallback = file;
            }
            setFile(file);
        });
        form.addEventListener('submit', function(e) {
            if (input._fallback && (!input.files || !input.files.length)) {
                e.preventDefault();
                var fd = new FormData(form);
                fd.set('answer_script', input._fallback, input._fallback.name);
                fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(res) {
                        if (res.redirected) {
                            window.location.href = res.url;
                            return;
                        }
                        res.text().then(function(html) {
                            document.open();
                            document.write(html);
                            document.close();
                        });
                    });
            }
        });
    });
</script>

{{-- PDF viewer — only when status is published and annotated PDF exists --}}
@if($submission && $submission->status === 'published' && !empty($submission->annotated_pdf))
<script>
    (function() {
        var PDF_URL = "{{ asset($submission->annotated_pdf) }}";
        var WORKER_URL = "{{ asset('assets/backend/js/pdf.worker.min.js') }}";
        var PDFJS_SRC = "{{ asset('assets/backend/js/pdf.min.js') }}";

        function renderPdf(pdfjsLib) {
            pdfjsLib.GlobalWorkerOptions.workerSrc = WORKER_URL;

            var container = document.getElementById('pdf-viewer-container');
            if (!container) return;

            container.innerHTML = '<div style="padding:20px;text-align:center;color:#888;">Loading PDF...</div>';

            pdfjsLib.getDocument(PDF_URL).promise.then(function(pdfDoc) {
                var dpr = window.devicePixelRatio || 1;
                var containerWidth = container.clientWidth > 0 ? container.clientWidth : 800;
                var renders = [];

                for (var i = 1; i <= pdfDoc.numPages; i++) {
                    renders.push(pdfDoc.getPage(i).then(function(page) {
                        var baseViewport = page.getViewport({
                            scale: 1.0
                        });
                        var scale = containerWidth / baseViewport.width;
                        var viewport = page.getViewport({
                            scale: scale
                        });

                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');

                        canvas.width = Math.floor(viewport.width * dpr);
                        canvas.height = Math.floor(viewport.height * dpr);
                        canvas.style.width = Math.floor(viewport.width) + 'px';
                        canvas.style.height = Math.floor(viewport.height) + 'px';
                        canvas.style.display = 'block';
                        canvas.style.margin = '10px auto';
                        canvas.style.background = '#fff';
                        canvas.style.boxShadow = '0 4px 12px rgba(0,0,0,0.08)';
                        canvas.style.borderRadius = '6px';
                        canvas.style.maxWidth = '100%';
                        ctx.scale(dpr, dpr);

                        return page.render({
                                canvasContext: ctx,
                                viewport: viewport,
                                intent: 'display'
                            })
                            .promise.then(function() {
                                return {
                                    pageNum: page.pageNumber,
                                    canvas: canvas
                                };
                            });
                    }));
                }

                Promise.all(renders).then(function(results) {
                    container.innerHTML = '';
                    results.sort(function(a, b) {
                        return a.pageNum - b.pageNum;
                    });
                    results.forEach(function(r) {
                        container.appendChild(r.canvas);
                    });
                });

            }).catch(function(err) {
                console.error('PDF error:', err);
                document.getElementById('pdf-viewer-container').innerHTML =
                    '<p style="color:#f87171;padding:12px;">Could not load PDF preview.<br><small>' + err.message + '</small></p>';
            });
        }

        if (window['pdfjs-dist/build/pdf']) {
            renderPdf(window['pdfjs-dist/build/pdf']);
        } else {
            var s = document.createElement('script');
            s.src = PDFJS_SRC;
            s.onload = function() {
                renderPdf(window['pdfjs-dist/build/pdf']);
            };
            s.onerror = function() {
                document.getElementById('pdf-viewer-container').innerHTML =
                    '<p style="color:#f87171;padding:12px;">Failed to load PDF viewer library.</p>';
            };
            document.head.appendChild(s);
        }
    })();
</script>
@endif

@endsection
