{{--<section class="section-padding free-webinar-section">
    <div class="container">


        <div class="text-center mb-5">
            <h2 class="webinar-section-title builder-editable" builder-identity="webinar-main-title">
                {{ get_phrase('TRANSLATED') }}
            </h2>
        </div>

        @php
        $bootcamps = \App\Models\Bootcamp::where('status', 1)->where('is_paid', 0)->latest()->take(4)->get();
        @endphp

        <div class="row g-4 justify-content-center">
            @foreach ($bootcamps as $bootcamp)
            <div class="col-lg-3 col-md-6">
                <div class="webinar-card">

                   
                    <div class="webinar-card-banner">
                        <img src="{{ asset($bootcamp->thumbnail) }}" alt="{{ $bootcamp->title }}">
                    </div>

                    
                    <div class="webinar-card-body">

                        
                        <div class="webinar-date-time">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1.5" y="3" width="15" height="13" rx="2" stroke="#555" stroke-width="1.4" fill="none" />
                                <path d="M1.5 7h15" stroke="#555" stroke-width="1.4" />
                                <path d="M5.5 1.5v3M12.5 1.5v3" stroke="#555" stroke-width="1.4" stroke-linecap="round" />
                            </svg>
                            <span>{{ date('d M Y', $bootcamp->publish_date) }}</span>
                        </div>

                        
                        <h4 class="webinar-card-title">
                            {{ $bootcamp->title }}
                        </h4>

                        <a href="{{ url('bootcamp/' . $bootcamp->slug) }}"
                            class="webinar-btn webinar-btn-filled builder-editable"
                            builder-identity="webinar-btn-filled">
                            {{ get_phrase('TRANSLATED') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center">
            <a href="{{ url('bootcamps') }}" class="webinar-view-all-btn builder-editable" builder-identity="webinar-view-all">
                {{ get_phrase('TRANSLATED') }}
            </a>
        </div>
    </div>
</section>--}}