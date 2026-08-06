@php
// edit mode support (array of feature strings)
$features = isset($course_details->features)
? $course_details->features->pluck('title')->toArray()
: [];
@endphp

<div class="row mb-3">
    <label class="col-md-2 form-label ol-form-label">
        {{ get_phrase('Course Features') }}
    </label>

    <div class="col-md-10">
        <div id="feature_area">

            @if(is_array($features) && count($features) > 0)
            @foreach($features as $key => $feature)
            <div class="d-flex mt-2">
                <div class="flex-grow-1 px-3">
                    <div class="form-group">
                        <input
                            type="text"
                            class="form-control ol-form-control"
                            name="features[]"
                            value="{{ $feature }}"
                            placeholder="{{ get_phrase('Enter feature') }}">
                    </div>
                </div>

                <div>
                    @if($key == 0)
                    <button type="button"
                        class="btn ol-btn-light ol-icon-btn"
                        data-bs-toggle="tooltip"
                        title="{{ get_phrase('Add new') }}"
                        onclick="appendFeature()">
                        <i class="fi-rr-plus-small"></i>
                    </button>
                    @else
                    <button type="button"
                        class="btn ol-btn-light ol-icon-btn"
                        data-bs-toggle="tooltip"
                        title="{{ get_phrase('Remove') }}"
                        onclick="removeFeature(this)">
                        <i class="fi-rr-minus-small"></i>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
            @else
            <div class="d-flex mt-2">
                <div class="flex-grow-1 px-3">
                    <div class="form-group">
                        <input
                            type="text"
                            class="form-control ol-form-control"
                            name="features[]"
                            placeholder="{{ get_phrase('Enter feature') }}">
                    </div>
                </div>

                <div>
                    <button type="button"
                        class="btn ol-btn-light ol-icon-btn"
                        data-bs-toggle="tooltip"
                        title="{{ get_phrase('Add new') }}"
                        onclick="appendFeature()">
                        <i class="fi-rr-plus-small"></i>
                    </button>
                </div>
            </div>
            @endif

            {{-- hidden template --}}
            <div id="blank_feature_field">
                <div class="d-flex mt-2">
                    <div class="flex-grow-1 px-3">
                        <div class="form-group">
                            <input
                                type="text"
                                class="form-control ol-form-control"
                                name="features[]"
                                placeholder="{{ get_phrase('Enter feature') }}">
                        </div>
                    </div>

                    <div>
                        <button type="button"
                            class="btn ol-btn-light ol-icon-btn"
                            data-bs-toggle="tooltip"
                            title="{{ get_phrase('Remove') }}"
                            onclick="removeFeature(this)">
                            <i class="fi-rr-minus-small"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('js')
<script type="text/javascript">
    "use strict";

    var blank_feature = jQuery('#blank_feature_field').html();

    jQuery(document).ready(function() {
        jQuery('#blank_feature_field').hide();
    });

    function appendFeature() {
        jQuery('#feature_area').append(blank_feature);
    }

    function removeFeature(elem) {
        jQuery(elem).parent().parent().remove();
    }
</script>
@endpush