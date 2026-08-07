<div class="form-group mb-2">
    <label class="form-label ol-form-label">Bunny Stream embed URL</label>
    <input type="url" name="lesson_src" class="form-control ol-form-control" value="{{ $lessons->lesson_src }}" placeholder="https://iframe.mediadelivery.net/embed/SEU_LIBRARY_ID/SEU_VIDEO_ID" required>
    <small class="form-label text-muted text-12px mb-0">Use uma URL de incorporação Bunny Stream, não uma URL de download ou CDN.</small>
</div>

<div class="form-group mb-2">
    <label class="form-label ol-form-label">{{ get_phrase('Duration') }}</label>
    <input type="text" name="duration" class="form-control ol-form-control" value="{{ $lessons->duration ?? '00:00:00' }}">
</div>

<script>
    "use strict";
    initializeDurationPickers(["input[name=duration]"]);
</script>
