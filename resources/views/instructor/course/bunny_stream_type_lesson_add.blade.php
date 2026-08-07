<input type="hidden" name="lesson_type" value="bunny_stream">

<div class="form-group mb-2">
    <label class="form-label ol-form-label">Bunny Stream embed URL</label>
    <input type="url" name="lesson_src" class="form-control ol-form-control" placeholder="https://iframe.mediadelivery.net/embed/SEU_LIBRARY_ID/SEU_VIDEO_ID" required>
    <small class="form-label text-muted text-12px mb-0">Cole a URL de incorporação gerada pelo Bunny Stream. URLs de download ou CDN não são aceitas.</small>
</div>

<div class="form-group mb-2">
    <label class="form-label ol-form-label">{{ get_phrase('Duration') }}</label>
    <input type="text" name="duration" class="form-control ol-form-control" value="00:00:00" placeholder="00:00:00">
</div>

<script>
    "use strict";
    initializeDurationPickers(["input[name=duration]"]);
</script>
