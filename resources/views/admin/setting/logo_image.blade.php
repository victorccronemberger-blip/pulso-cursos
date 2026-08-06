

<div class="mb-3">
    <h4 class="title mt-4 mb-2">{{ get_phrase('Media Management') }}</h4>
    <p class="upload-section-sub">{{ get_phrase('Manage your media files here') }}</p>
</div>

<div class="row g-3">

    {{-- Banner Image --}}
    <div class="col-xl-4 col-lg-6 col-md-6 col-12">
        <div class="upload-media-card">
            <div class="upload-media-card-header">
                <div class="media-icon icon-banner">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <rect x="2" y="5" width="20" height="14" rx="3" stroke="#6366f1" stroke-width="1.8" />
                        <path d="M2 9h20" stroke="#6366f1" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </div>
                <div>
                    <h6>{{ get_phrase('Imagem do banner') }}</h6>
                    <span>{{ get_phrase('Banner da página inicial') }}</span>
                </div>
            </div>
            <div class="upload-media-card-body">
                <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="banner_image">
                    <div class="upload-preview-zone">
                        @php
                        $bannerData = json_decode(get_frontend_settings('banner_image'));
                        $banneractive = get_frontend_settings('home_page');
                        if ($bannerData !== null && is_object($bannerData) && property_exists($bannerData, $banneractive)) {
                        $banner = json_decode(get_frontend_settings('banner_image'))->$banneractive;
                        } elseif (!get_frontend_settings('home_page')) {
                        $banner = get_frontend_settings('banner_image');
                        }
                        @endphp
                        @if (isset($banner))
                        <img src="{{ asset($banner) }}" alt="Banner">
                        <div class="img-overlay"><span>{{ get_phrase('Alterar') }}</span></div>
                        @else
                        <div class="upload-placeholder">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="#6366f1" stroke-width="1.8" stroke-linecap="round" />
                                <polyline points="17 8 12 3 7 8" stroke="#6366f1" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                <line x1="12" y1="3" x2="12" y2="15" stroke="#6366f1" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            <small>{{ get_phrase('Enviar imagem do banner') }}</small>
                            <span class="size-hint">1000 × 700 px</span>
                        </div>
                        @endif
                        <label for="banner_image"></label>
                        <input id="banner_image" type="file" class="image-upload d-none" name="banner_image" accept="image/*">
                    </div>
                    <p class="text-center mb-2" style="font-size:11px;font-weight:600;color:#6366f1;">1000 × 700 px</p>
                    <button type="submit" class="upload-media-btn upload-media-btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" />
                            <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2" />
                            <polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="2" />
                        </svg>
                        {{ get_phrase('Salvar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Banner Video --}}
    <div class="col-xl-4 col-lg-6 col-md-6 col-12">
        <div class="upload-media-card">
            <div class="upload-media-card-header">
                <div class="media-icon icon-video">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <rect x="2" y="5" width="20" height="14" rx="3" stroke="#059669" stroke-width="1.8" />
                        <polygon points="10 9 16 12 10 15 10 9" fill="#059669" />
                    </svg>
                </div>
                <div>
                    <h6>{{ get_phrase('Vídeo do banner') }}</h6>
                    <span>{{ get_phrase('Vídeo do banner da página inicial') }}</span>
                </div>
            </div>
            <div class="upload-media-card-body">
                <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="banner_video">
                    @php $bannerVideo = get_frontend_settings('banner_video'); @endphp

                    {{-- Video area --}}
                    <div id="videoArea" style="margin-bottom:10px;">
                        @if ($bannerVideo)
                        {{-- Existing video --}}
                        <div id="videoPreviewWrapper" style="position:relative;border-radius:12px;overflow:hidden;background:#111;">
                            <video id="bannerVideoPlayer" src="{{ asset($bannerVideo) }}" style="width:100%;max-height:160px;display:block;object-fit:cover;"></video>
                            {{-- Play/Pause button — only control inside video area --}}
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;z-index:2;">
                                <button type="button" id="videoPlayCircle"
                                    style="width:48px;height:48px;border-radius:50%;border:none;
                                           background:rgba(5,150,105,0.88);display:flex;
                                           align-items:center;justify-content:center;
                                           box-shadow:0 2px 14px rgba(0,0,0,0.35);
                                           cursor:pointer;pointer-events:all;transition:transform 0.15s;">
                                    <svg id="playIcon" width="18" height="18" viewBox="0 0 24 24" fill="white">
                                        <polygon points="6 3 20 12 6 21 6 3" />
                                    </svg>
                                    <svg id="pauseIcon" width="18" height="18" viewBox="0 0 24 24" fill="white" style="display:none;">
                                        <rect x="5" y="4" width="4" height="16" rx="1" />
                                        <rect x="15" y="4" width="4" height="16" rx="1" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @else
                        {{-- Empty upload zone --}}
                        <div class="upload-preview-zone" id="videoEmptyZone" style="border-color:rgba(5,150,105,0.25);background:rgba(236,253,245,0.5);">
                            <div class="upload-placeholder">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                                    <rect x="2" y="5" width="20" height="14" rx="3" stroke="#059669" stroke-width="1.8" />
                                    <polygon points="10 9 16 12 10 15 10 9" fill="#059669" />
                                </svg>
                                <small>{{ get_phrase('Enviar vídeo do banner') }}</small>
                                <span class="size-hint" style="color:#059669;background:rgba(5,150,105,0.08);">MP4 / WebM</span>
                            </div>
                            <label for="banner_video" style="position:absolute;inset:0;cursor:pointer;z-index:4;"></label>
                        </div>
                        @endif
                    </div>

                    {{-- Selected filename display --}}
                    <p id="videoFileName" class="text-center mb-2" style="font-size:11px;font-weight:600;color:#059669;display:none;"></p>

                    {{-- Change/Upload button — outside video, always visible when video exists --}}
                    @if ($bannerVideo)
                    <label for="banner_video" class="upload-media-btn d-flex align-items-center justify-content-center gap-2 mb-2"
                        style="background:rgba(5,150,105,0.08);color:#059669;border:1.5px solid rgba(5,150,105,0.25);cursor:pointer;border-radius:10px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="#059669" stroke-width="2" stroke-linecap="round" />
                            <polyline points="17 8 12 3 7 8" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <line x1="12" y1="3" x2="12" y2="15" stroke="#059669" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        <span id="changeBtnText">{{ get_phrase('Alterar vídeo') }}</span>
                    </label>
                    @endif

                    <input id="banner_video" type="file" class="d-none" name="banner_video" accept="video/*">
                    <p class="text-center mb-2" style="font-size:11px;font-weight:600;color:#059669;">MP4 / WebM</p>
                    <button type="submit" class="upload-media-btn upload-media-btn-primary" style="background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 2px 12px rgba(5,150,105,0.18);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" />
                            <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2" />
                            <polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="2" />
                        </svg>
                        {{ get_phrase('Salvar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Light Logo --}}
    <div class="col-xl-4 col-lg-6 col-md-6 col-12">
        <div class="upload-media-card">
            <div class="upload-media-card-header">
                <div class="media-icon icon-light">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="5" stroke="#d97706" stroke-width="1.8" />
                        <path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" stroke="#d97706" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </div>
                <div>
                    <h6>{{ get_phrase('Logotipo claro') }}</h6>
                    <span>{{ get_phrase('Para fundos claros') }}</span>
                </div>
            </div>
            <div class="upload-media-card-body">
                <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="light_logo">
                    <div class="upload-preview-zone" style="border-color: rgba(217,119,6,0.22); background: rgba(255,251,235,0.6);">
                        <img src="{{ asset(get_frontend_settings('light_logo')) }}" alt="Light Logo" class="bg-dark radious-15px px-2 py-2" style="max-height:70px;">
                        <div class="img-overlay" style="background: rgba(217,119,6,0.55);"><span>{{ get_phrase('Alterar') }}</span></div>
                        <label for="light_logo"></label>
                        <input id="light_logo" type="file" class="image-upload d-none" name="light_logo" accept="image/*">
                    </div>
                    <p class="text-center mb-2" style="font-size:11px;font-weight:600;color:#d97706;">330 × 70 px</p>
                    <button type="submit" class="upload-media-btn upload-media-btn-primary" style="background: linear-gradient(135deg, #d97706, #f59e0b); box-shadow: 0 2px 12px rgba(217,119,6,0.18);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" />
                            <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2" />
                            <polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="2" />
                        </svg>
                        {{ get_phrase('Salvar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Dark Logo --}}
    <div class="col-xl-4 col-lg-6 col-md-6 col-12">
        <div class="upload-media-card">
            <div class="upload-media-card-header">
                <div class="media-icon icon-dark">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke="#475569" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <h6>{{ get_phrase('Logotipo escuro') }}</h6>
                    <span>{{ get_phrase('Para fundos escuros') }}</span>
                </div>
            </div>
            <div class="upload-media-card-body">
                <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="dark_logo">
                    <div class="upload-preview-zone" style="border-color: rgba(71,85,105,0.22); background: rgba(241,245,249,0.6);">
                        <img src="{{ asset(get_frontend_settings('dark_logo')) }}" alt="Dark Logo" style="max-height:70px;">
                        <div class="img-overlay" style="background: rgba(71,85,105,0.60);"><span>{{ get_phrase('Alterar') }}</span></div>
                        <label for="dark_logo"></label>
                        <input id="dark_logo" type="file" class="image-upload d-none" name="dark_logo" accept="image/*">
                    </div>
                    <p class="text-center mb-2" style="font-size:11px;font-weight:600;color:#475569;">330 × 70 px</p>
                    <button type="submit" class="upload-media-btn upload-media-btn-primary" style="background: linear-gradient(135deg, #475569, #64748b); box-shadow: 0 2px 12px rgba(71,85,105,0.18);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" />
                            <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2" />
                            <polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="2" />
                        </svg>
                        {{ get_phrase('Salvar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Favicon --}}
    <div class="col-xl-4 col-lg-6 col-md-6 col-12">
        <div class="upload-media-card">
            <div class="upload-media-card-header">
                <div class="media-icon icon-fav">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke="#db2777" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <h6>{{ get_phrase('Favicon') }}</h6>
                    <span>{{ get_phrase('Ícone da aba do navegador') }}</span>
                </div>
            </div>
            <div class="upload-media-card-body">
                <form action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="favicon">
                    <div class="upload-preview-zone" style="border-color: rgba(219,39,119,0.22); background: rgba(253,242,248,0.6);">
                        <img src="{{ asset(get_frontend_settings('favicon')) }}" alt="Favicon" style="max-width:80px; max-height:80px;">
                        <div class="img-overlay" style="background: rgba(219,39,119,0.55);"><span>{{ get_phrase('Alterar') }}</span></div>
                        <label for="favicon"></label>
                        <input id="favicon" type="file" class="image-upload d-none" name="favicon" accept="image/*">
                    </div>
                    <p class="text-center mb-2" style="font-size:11px;font-weight:600;color:#db2777;">90 × 90 px</p>
                    <button type="submit" class="upload-media-btn upload-media-btn-primary" style="background: linear-gradient(135deg, #db2777, #ec4899); box-shadow: 0 2px 12px rgba(219,39,119,0.18);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" />
                            <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2" />
                            <polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="2" />
                        </svg>
                        {{ get_phrase('Salvar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Image live preview ────────────────────────────────────────
        document.querySelectorAll('input[type="file"][accept="image/*"]').forEach(function(input) {
            input.addEventListener('change', function() {
                if (!this.files || !this.files[0]) return;
                const zone = this.closest('.upload-preview-zone');
                if (!zone) return;
                let img = zone.querySelector('img');
                if (!img) {
                    img = document.createElement('img');
                    img.style.cssText = 'max-width:100%;max-height:120px;object-fit:contain;border-radius:8px;';
                    zone.prepend(img);
                    const ph = zone.querySelector('.upload-placeholder');
                    if (ph) ph.style.display = 'none';
                }
                img.src = URL.createObjectURL(this.files[0]);
            });
        });

        // ── Video player controls ─────────────────────────────────────
        const video = document.getElementById('bannerVideoPlayer');
        const playCircle = document.getElementById('videoPlayCircle');
        const playIcon = document.getElementById('playIcon');
        const pauseIcon = document.getElementById('pauseIcon');

        if (video && playCircle) {
            playCircle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                video.paused ? video.play() : video.pause();
            });
            video.addEventListener('play', function() {
                playIcon.style.display = 'none';
                pauseIcon.style.display = 'block';
            });
            video.addEventListener('pause', function() {
                playIcon.style.display = 'block';
                pauseIcon.style.display = 'none';
            });
            video.addEventListener('ended', function() {
                playIcon.style.display = 'block';
                pauseIcon.style.display = 'none';
            });
            playCircle.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
            });
            playCircle.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }

        // ── Video file selected ───────────────────────────────────────
        const videoInput = document.getElementById('banner_video');
        const videoArea = document.getElementById('videoArea');
        const videoFileName = document.getElementById('videoFileName');
        const changeBtnText = document.getElementById('changeBtnText');

        if (videoInput) {
            videoInput.addEventListener('change', function() {
                if (!this.files || !this.files[0]) return;
                const src = URL.createObjectURL(this.files[0]);
                const fileName = this.files[0].name;

                // Show filename below video area
                if (videoFileName) {
                    videoFileName.textContent = fileName;
                    videoFileName.style.display = 'block';
                }
                // Update change button text
                if (changeBtnText) changeBtnText.textContent = 'Alterar vídeo';

                // Get or build the active video element
                let activeVideo = document.getElementById('bannerVideoPlayer');

                if (activeVideo) {
                    // ── Video element already exists: just swap the src ──
                    activeVideo.pause();
                    activeVideo.src = src;
                    activeVideo.load();
                    // Reset play icon
                    const pi = document.getElementById('playIcon');
                    const pa = document.getElementById('pauseIcon');
                    if (pi) pi.style.display = 'block';
                    if (pa) pa.style.display = 'none';

                } else {
                    // ── Empty state: build video preview inside videoArea ──
                    const emptyZone = document.getElementById('videoEmptyZone');
                    if (!emptyZone || !videoArea) return;

                    // Remove empty zone
                    emptyZone.style.display = 'none';

                    // Build wrapper
                    const wrapper = document.createElement('div');
                    wrapper.id = 'videoPreviewWrapper';
                    wrapper.style.cssText = 'position:relative;border-radius:12px;overflow:hidden;background:#111;';

                    // Video
                    const newVid = document.createElement('video');
                    newVid.id = 'bannerVideoPlayer';
                    newVid.src = src;
                    newVid.style.cssText = 'width:100%;max-height:160px;display:block;object-fit:cover;';

                    // Play button
                    const btnWrap = document.createElement('div');
                    btnWrap.style.cssText = 'position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;z-index:2;';
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.style.cssText = 'width:48px;height:48px;border-radius:50%;border:none;' +
                        'background:rgba(5,150,105,0.88);display:flex;align-items:center;' +
                        'justify-content:center;box-shadow:0 2px 14px rgba(0,0,0,0.35);' +
                        'cursor:pointer;pointer-events:all;transition:transform 0.15s;';
                    const playSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="white"><polygon points="6 3 20 12 6 21 6 3"/></svg>';
                    const pauseSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="white"><rect x="5" y="4" width="4" height="16" rx="1"/><rect x="15" y="4" width="4" height="16" rx="1"/></svg>';
                    btn.innerHTML = playSvg;
                    btnWrap.appendChild(btn);

                    wrapper.appendChild(newVid);
                    wrapper.appendChild(btnWrap);

                    // Insert wrapper at top of videoArea, before emptyZone
                    videoArea.insertBefore(wrapper, emptyZone);

                    // Also inject change label button after videoArea (before the hidden input)
                    const existingChangeBtn = document.querySelector('label[for="banner_video"].upload-media-btn');
                    if (!existingChangeBtn) {
                        const lbl = document.createElement('label');
                        lbl.htmlFor = 'banner_video';
                        lbl.className = 'upload-media-btn d-flex align-items-center justify-content-center gap-2 mb-2';
                        lbl.style.cssText = 'background:rgba(5,150,105,0.08);color:#059669;border:1.5px solid rgba(5,150,105,0.25);cursor:pointer;border-radius:10px;';
                        lbl.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="#059669" stroke-width="2" stroke-linecap="round"/><polyline points="17 8 12 3 7 8" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="3" x2="12" y2="15" stroke="#059669" stroke-width="2" stroke-linecap="round"/></svg>' +
                            '<span>Alterar vídeo</span>';
                        videoArea.parentNode.insertBefore(lbl, videoArea.nextSibling);
                    }

                    // Wire play/pause
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (newVid.paused) {
                            newVid.play();
                            btn.innerHTML = pauseSvg;
                        } else {
                            newVid.pause();
                            btn.innerHTML = playSvg;
                        }
                    });
                    newVid.addEventListener('ended', function() {
                        btn.innerHTML = playSvg;
                    });
                    btn.addEventListener('mouseenter', function() {
                        this.style.transform = 'scale(1.1)';
                    });
                    btn.addEventListener('mouseleave', function() {
                        this.style.transform = 'scale(1)';
                    });
                }
            });
        }

    });
</script>