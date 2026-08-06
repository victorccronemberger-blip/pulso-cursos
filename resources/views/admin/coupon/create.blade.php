<link rel="stylesheet" href="{{ asset('assets/backend/css/neu-select.css') }}">

<form action="{{ route('admin.coupon.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">

        <div class="mb-3">
            <label class="form-label ol-form-label" for="code">{{ get_phrase('Code') }}</label>
            <input type="text" class="form-control ol-form-control" name="code" id="code"
                placeholder="{{ get_phrase('Enter coupon code') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label ol-form-label" for="discount">{{ get_phrase('Discount (%)') }}</label>
            <input type="number" max="100" min="0" class="form-control ol-form-control" name="discount" id="discount"
                placeholder="{{ get_phrase('Enter coupon discount') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label ol-form-label" for="expiry">{{ get_phrase('Expiry') }}</label>
            <input type="date" class="form-control ol-form-control" name="expiry" id="expiry"
                placeholder="{{ get_phrase('Enter coupon expiry') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label ol-form-label" for="status">{{ get_phrase('Status') }}</label>
            <select class="form-control ol-form-control neu-select" name="status" id="status" required>
                <option value="">{{ get_phrase('Choose status ...') }}</option>
                <option value="1">{{ get_phrase('Active') }}</option>
                <option value="0">{{ get_phrase('Inactive') }}</option>
            </select>
        </div>
    </div>

    <div class="mb-2 d-flex justify-content-end">
        <button type="submit" class="ol-btn-primary">{{ get_phrase('Add coupon') }}</button>
    </div>
</form>

<script src="{{ asset('assets/backend/js/neu-select.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof NeuSelect !== 'undefined') {
            new NeuSelect('#status');
        }
    });
</script>