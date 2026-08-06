{{-- To make a editable image or text need to be add a "builder editable" class and builder identity attribute with a unique value --}}
{{-- builder identity and builder editable --}}
{{-- builder identity value have to be unique under a single file --}}

{{--<section class="pricing-section ">
    <div class="container">
      
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="pricing-main-title builder-editable" builder-identity="pricing_title_1">
                    {{ get_phrase('TRANSLATED') }}
                </h1>
            </div>
        </div>

       
        <div class="row mb-5">
            <div class="col-12">
                <div class="pricing-toggle-wrapper">
                    <div class="pricing-toggle">
                        <button class="toggle-btn active" data-plan="monthly">
                            <span class="builder-editable" builder-identity="toggle_btn_1">{{ get_phrase('TRANSLATED') }}</span>
                        </button>
                        <button class="toggle-btn" data-plan="yearly">
                            <span class="builder-editable" builder-identity="toggle_btn_2">{{ get_phrase('TRANSLATED') }}</span>
                        </button>
                        <div class="toggle-slider"></div>
                    </div>
                </div>
            </div>
        </div>

       
        <div class="row g-4 justify-content-center pb-5">
           
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card" data-plan-card="basic">
                    <div class="pricing-card-header">
                        <h3 class="plan-name builder-editable" builder-identity="plan_name_1">{{ get_phrase('TRANSLATED') }}</h3>
                        <p class="plan-description builder-editable" builder-identity="plan_desc_1">
                            {{ get_phrase('TRANSLATED') }}
                        </p>
                        <div class="plan-price-wrapper">
                           
                            <div class="plan-price-content active" data-price-type="monthly">
                                <div class="plan-price">
                                    <h2 class="price builder-editable" builder-identity="monthly_plan_price_1">{{ get_phrase('TRANSLATED') }}</h2>
                                    <span class="price-period builder-editable" builder-identity="monthly_plan_period_1">{{ get_phrase('TRANSLATED') }}</span>
                                </div>
                                <p class="price-tax builder-editable" builder-identity="monthly_plan_tax_1">{{ get_phrase('TRANSLATED') }}</p>
                            </div>
                          
                            <div class="plan-price-content" data-price-type="yearly">
                                <div class="plan-price">
                                    <h2 class="price builder-editable" builder-identity="yearly_plan_price_1">{{ get_phrase('TRANSLATED') }}</h2>
                                    <span class="price-period builder-editable" builder-identity="yearly_plan_period_1">{{ get_phrase('TRANSLATED') }}</span>
                                </div>
                                <p class="price-tax builder-editable" builder-identity="yearly_plan_tax_1">{{ get_phrase('TRANSLATED') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="pricing-card-body">
                        <a href="javascript:void(0);" class="pricing-btn builder-editable" builder-identity="plan_btn_1">
                            {{ get_phrase('TRANSLATED') }}
                        </a>
                        <ul class="plan-features">
                            <li class="builder-editable" builder-identity="feature_1_1">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_1_2">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_1_3">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_1_4">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_1_5">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

          
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card pricing-card-popular" data-plan-card="professional">
                    <div class="popular-badge builder-editable" builder-identity="popular_badge_1">
                        <i class="fas fa-star"></i>
                        {{ get_phrase('TRANSLATED') }}
                    </div>
                    <div class="pricing-card-header">
                        <h3 class="plan-name builder-editable" builder-identity="plan_name_2">{{ get_phrase('TRANSLATED') }}</h3>
                        <p class="plan-description builder-editable" builder-identity="plan_desc_2">
                            {{ get_phrase('TRANSLATED') }}
                        </p>
                        <div class="plan-price-wrapper">
                           
                            <div class="plan-price-content active" data-price-type="monthly">
                                <div class="plan-price">
                                    <h2 class="price builder-editable" builder-identity="monthly_plan_price_2">{{ get_phrase('TRANSLATED') }}</h2>
                                    <span class="price-period builder-editable" builder-identity="monthly_plan_period_2">{{ get_phrase('TRANSLATED') }}</span>
                                </div>
                                <p class="price-tax builder-editable" builder-identity="monthly_plan_tax_2">{{ get_phrase('TRANSLATED') }}</p>
                            </div>
                          
                            <div class="plan-price-content" data-price-type="yearly">
                                <div class="plan-price">
                                    <h2 class="price builder-editable" builder-identity="yearly_plan_price_2">{{ get_phrase('TRANSLATED') }}</h2>
                                    <span class="price-period builder-editable" builder-identity="yearly_plan_period_2">{{ get_phrase('TRANSLATED') }}</span>
                                </div>
                                <p class="price-tax builder-editable" builder-identity="yearly_plan_tax_2">{{ get_phrase('TRANSLATED') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="pricing-card-body">
                        <a href="javascript:void(0);" class="btn-popular builder-editable" builder-identity="plan_btn_2">
                            {{ get_phrase('TRANSLATED') }}
                        </a>
                        <ul class="plan-features">
                            <li class="builder-editable" builder-identity="feature_2_1">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_2_2">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_2_3">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_2_4">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_2_5">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card" data-plan-card="allinone">
                    <div class="pricing-card-header">
                        <h3 class="plan-name builder-editable" builder-identity="plan_name_3">{{ get_phrase('TRANSLATED') }}</h3>
                        <p class="plan-description builder-editable" builder-identity="plan_desc_3">
                            {{ get_phrase('TRANSLATED') }}
                        </p>
                        <div class="plan-price-wrapper">
                            
                            <div class="plan-price-content active" data-price-type="monthly">
                                <div class="plan-price">
                                    <h2 class="price builder-editable" builder-identity="monthly_plan_price_3">{{ get_phrase('TRANSLATED') }}</h2>
                                    <span class="price-period builder-editable" builder-identity="monthly_plan_period_3">{{ get_phrase('TRANSLATED') }}</span>
                                </div>
                                <p class="price-tax builder-editable" builder-identity="monthly_plan_tax_3">{{ get_phrase('TRANSLATED') }}</p>
                            </div>
                           
                            <div class="plan-price-content" data-price-type="yearly">
                                <div class="plan-price">
                                    <h2 class="price builder-editable" builder-identity="yearly_plan_price_3">{{ get_phrase('TRANSLATED') }}</h2>
                                    <span class="price-period builder-editable" builder-identity="yearly_plan_period_3">{{ get_phrase('TRANSLATED') }}</span>
                                </div>
                                <p class="price-tax builder-editable" builder-identity="yearly_plan_tax_3">{{ get_phrase('TRANSLATED') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="pricing-card-body">
                        <a href="javascript:void(0);" class="pricing-btn builder-editable" builder-identity="plan_btn_3">
                            {{ get_phrase('TRANSLATED') }}
                        </a>
                        <ul class="plan-features">
                            <li class="builder-editable" builder-identity="feature_3_1">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_3_2">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_3_3">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_3_4">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                            <li class="builder-editable" builder-identity="feature_3_5">
                                <i class="fas fa-check"></i>
                                <span>{{ get_phrase('TRANSLATED') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtns = document.querySelectorAll('.toggle-btn');
        const pricingToggle = document.querySelector('.pricing-toggle');
        const priceContents = document.querySelectorAll('.plan-price-content');

        toggleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const plan = this.getAttribute('data-plan');

                // Remove active class from all buttons
                toggleBtns.forEach(b => b.classList.remove('active'));

                // Add active class to clicked button
                this.classList.add('active');

                // Toggle slider position
                if (plan === 'yearly') {
                    pricingToggle.classList.add('yearly');
                } else {
                    pricingToggle.classList.remove('yearly');
                }

                // Switch price content in all cards
                priceContents.forEach(content => {
                    const priceType = content.getAttribute('data-price-type');

                    if (priceType === plan) {
                        content.classList.add('active');
                    } else {
                        content.classList.remove('active');
                    }
                });
            });
        });
    });
</script>--}}