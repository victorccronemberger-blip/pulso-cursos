<div class="form-group mb-2">
    <label class="form-label ol-form-label" for="text_description"> {{ get_phrase('Enter your text') }}</label>
    <textarea name="text_description" class="form-control nEditor" id="text_description" rows="4">{{ $lessons->attachment }}</textarea>
</div>
@include('admin.init')
