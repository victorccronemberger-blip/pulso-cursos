<link rel="stylesheet" href="{{ asset('assets/backend/css/neu-select.css') }}">

<form action="{{ route('admin.course.change_course_author.store', $course->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @php
    $instructors = App\Models\User::whereIn('role', ['admin', 'instructor'])
    ->where('status', 1)
    ->get();
    @endphp

    <div class="mb-3">
        <label for="user_id" class="form-label ol-form-label">{{ get_phrase('Select an author') }}</label>
        <select class="neu-select" name="user_id" id="user_id" required>
            @foreach ($instructors as $instructor)
            <option value="{{ $instructor->id }}" {{ $course->user_id == $instructor->id ? 'selected' : '' }}>
                {{ $instructor->name }} ( {{ $instructor->email }} )
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-12 text-end mt-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update') }}</button>
    </div>
</form>

<script src="{{ asset('assets/backend/js/neu-select.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof NeuSelect !== 'undefined') {
            new NeuSelect('#user_id');
        }
    });
</script>