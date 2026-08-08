@extends('layouts.default')
@push('title', 'Carrinho')
@push('meta')@endpush
@push('css')@endpush

@section('content')
    @include('frontend.default.student.page_header', [
        'title' => 'Carrinho',
        'current' => 'Carrinho',
        'description' => 'Revise os cursos selecionados antes de seguir para o pagamento.',
    ])

    <div class="eNtery-item pf-student-content">
        <div class="container">
            <div class="row">
                @include('frontend.default.student.left_sidebar')

                <div class="col-lg-9 col-md-8">
                    @if ($cart_items->count() === 0)
                        @include('frontend.default.student.empty_state', [
                            'icon' => 'fi-rr-shopping-cart',
                            'title' => 'Seu carrinho está vazio.',
                            'message' => 'Escolha um curso para iniciar sua preparação e volte aqui para concluir a matrícula.',
                            'actionUrl' => route('courses'),
                            'actionLabel' => 'Explorar cursos',
                        ])
                    @else
                        @php $count_items_price = 0; @endphp
                        <div class="pf-cart-layout">
                            <section class="my-panel pf-cart-list" aria-labelledby="cart-items-title">
                                <div class="pf-panel-heading">
                                    <h2 id="cart-items-title">Cursos selecionados</h2>
                                    <span>{{ $cart_items->count() }} {{ $cart_items->count() === 1 ? 'item' : 'itens' }}</span>
                                </div>

                                @foreach ($cart_items as $course)
                                    @php
                                        $itemPrice = $course->is_paid == 0 ? 0 : ($course->discount_flag == 1 ? $course->discounted_price : $course->price);
                                        $count_items_price += $itemPrice;
                                    @endphp
                                    <article class="pf-cart-item">
                                        <a href="{{ route('course.details', $course->slug) }}" class="pf-cart-item-image">
                                            <img src="{{ get_image($course->thumbnail) }}" alt="{{ $course->title }}">
                                        </a>
                                        <div class="pf-cart-item-content">
                                            <h3><a href="{{ route('course.details', $course->slug) }}">{{ $course->title }}</a></h3>
                                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($course->description), 130) }}</p>
                                            <strong>{{ $itemPrice > 0 ? currency($itemPrice, 2) : 'Grátis' }}</strong>
                                        </div>
                                        <a class="pf-cart-remove" title="Remover do carrinho" aria-label="Remover {{ $course->title }} do carrinho" href="{{ route('cart.delete', ['id' => $course->id]) }}">
                                            <i class="fi-rr-trash" aria-hidden="true"></i>
                                        </a>
                                    </article>
                                @endforeach
                            </section>

                            @php
                                $coupon_discount = $count_items_price * ($discount / 100);
                                $tax = (get_settings('course_selling_tax') / 100) * ($count_items_price - $coupon_discount);
                                $payable = $count_items_price - $coupon_discount + $tax;
                            @endphp
                            <aside class="my-panel pf-cart-summary" aria-labelledby="cart-summary-title">
                                <h2 id="cart-summary-title">Resumo do pagamento</h2>
                                <dl>
                                    <div><dt>Subtotal</dt><dd>{{ currency($count_items_price, 2) }}</dd></div>
                                    @if ($discount)
                                        <div><dt>Desconto ({{ $discount }}%)</dt><dd>- {{ currency($coupon_discount, 2) }}</dd></div>
                                    @endif
                                    <div><dt>Impostos ({{ get_settings('course_selling_tax') }}%)</dt><dd>+ {{ currency($tax, 2) }}</dd></div>
                                    <div class="pf-cart-total"><dt>Total</dt><dd>{{ currency($payable, 2) }}</dd></div>
                                </dl>

                                <form action="{{ route('payout') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="payable" value="{{ $payable }}">
                                    <input type="hidden" name="coupon_code" value="{{ request()->query('coupon') }}">
                                    <input type="hidden" name="coupon_discount" value="{{ $coupon_discount }}">
                                    <input type="hidden" name="tax" value="{{ $tax }}">
                                    <input type="hidden" name="items" value="{{ json_encode($cart_items->pluck('id')) }}">

                                    @if (request()->has('coupon') && isset($coupon) && $coupon_discount > 0)
                                        <div class="pf-coupon-applied">
                                            <span>Cupom {{ $coupon->discount }}% aplicado</span>
                                            <a href="{{ route('cart') }}" aria-label="Remover cupom"><i class="fi-rr-cross-circle"></i></a>
                                        </div>
                                    @endif

                                    <div class="pf-coupon-field">
                                        <label for="coupon">Cupom de desconto</label>
                                        <div><input type="text" class="form-control" name="coupon" id="coupon" placeholder="Digite o código" value="{{ request()->query('coupon') }}"><button type="button" id="apply-coupon">Aplicar</button></div>
                                    </div>

                                    <div class="form-check pf-gift-check">
                                        <input class="form-check-input" type="checkbox" name="is_gift" value="1" id="send_gift">
                                        <label class="form-check-label" for="send_gift">Enviar como presente</label>
                                    </div>
                                    <input type="email" class="form-control gifted_user d-none" placeholder="E-mail de quem receberá o presente">

                                    <button type="submit" class="pf-cart-checkout">Continuar para o pagamento</button>
                                </form>
                            </aside>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    "use strict";
    $(document).ready(function() {
        $('#apply-coupon').on('click', function(event) {
            event.preventDefault();
            const code = $('input[name="coupon"]').val();
            window.location.href = "{{ route('cart') }}" + '?coupon=' + encodeURIComponent(code);
        });

        $('input[name="is_gift"]').on('change', function() {
            const giftInput = $('.gifted_user');
            if ($(this).prop('checked')) {
                giftInput.attr({ name: 'gifted_user_email', required: 'required' }).removeClass('d-none');
            } else {
                giftInput.removeAttr('name required').addClass('d-none');
            }
        });
    });
</script>
@endpush
