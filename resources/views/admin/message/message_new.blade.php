<link rel="stylesheet" href="{{ asset('assets/backend/css/neu-select.css') }}">

<div class="ol-card p-4">
    <div class="ol-card-body">
        <h4 class="title text-16px mb-4">{{ get_phrase('Create a new conversation with a new user') }}</h4>
        <form action="{{ route('admin.message.thread.store') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <label class="form-label ol-form-label">{{ get_phrase('Select a new user') }}</label>
                <select class="neu-select" name="receiver_id" id="receiver">
                    @foreach(App\Models\User::where('id', '!=', auth()->user()->id)->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group mb-5">
                <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Next') }}</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('assets/backend/js/neu-select.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof NeuSelect !== 'undefined') {
            new NeuSelect('#receiver');
        }
    });
</script>