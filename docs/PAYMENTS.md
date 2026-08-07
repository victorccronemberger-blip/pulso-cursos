# Pagamentos — bimbangladesh71 (LMS)

## Gateways suportados

Configurados como classes em `app/Models/payment_gateway/` e com views de
checkout em `resources/views/payment/`:

| Gateway | Adapter | View de checkout |
| ------- | ------- | ---------------- |
| Stripe  | `StripePay.php` | `payment/stripe/` |
| Razorpay | `Razorpay.php` | `payment/razorpay/` |
| Paytm   | `Paytm.php` | `payment/paytm/` |
| PayPal  | `Paypal.php` | `payment/paypal/` |
| SSLCommerz | `Sslcommerz.php` | `payment/sslcommerz/` |
| Flutterwave | `Flutterwave.php` | `payment/flutterwave/` |
| Paystack | `Paystack.php` | `payment/paystack/` |
| Aamarpay | `Aamarpay.php` | `payment/aamarpay/` |
| Doku    | `Doku.php` | `payment/doku/` |
| Maxicash | `Maxicash.php` | `payment/maxicash/` |
| Offline | `OfflinePayment` | `payment/offline/` |

## Fluxo

1. **Início** — `PaymentController@index` (`routes/payment.php`, `auth`).
   Recebe o que está sendo comprado (curso, bundle, bootcamp, pacote, tutoria)
   e monta o contexto de pagamento.
2. **Seleção do gateway** — `show_payment_gateway_by_ajax/{identifier}`
   renderiza a view de checkout do gateway escolhido.
3. **Criação do pedido** — `payment_create/{identifier}` valida cupom e gera o
   payload de pagamento no gateway.
4. **Pagamento** — o usuário completa no checkout do gateway (ou via
   `payment_razorpay` / `make_paytm_order` / `doku_checkout`).
5. **Callback** — `payment_success/{identifier}`, `payment/status`,
   `paytm_paymentCallback`, e **`payment-notification/{identifier}`**
   (endpoint público, sem CSRF — ver `VerifyCsrfToken.php`) atualizam
   `Payment_history`, criam `Enrollment` / `Purchase*` e liberam o item.

6. **Histórico e fatura** — as faturas da área do aluno são filtradas pelo
   usuário autenticado; um identificador de pagamento não concede acesso à
   compra de outro aluno.
7. **Stripe** — a sessão devolvida pelo Checkout é conferida no servidor contra
   o valor, moeda, aluno e referência aleatória gerada para aquela compra antes
   de liberar qualquer matrícula.

## Pontos de atenção

- **Callbacks públicos**: `POST payment-notification/{identifier}` é acessível
  sem autenticação para os gateways notificarem — crítico para integridade da
  liberação de acesso (validar assinatura/valor antes de liberar).
- **CSRF**: os callbacks de gateway estão na exceção do middleware CSRF
  (`app/Http/Middleware/VerifyCsrfToken.php`).
- **Config**: chaves/secrets ficam em `config/services.php`, `config/payment`
  e/ou `.env` (ex.: `RAZORPAY_KEY`, `RAZORPAY_SECRET`). O `.env` local não
  deve ser versionado.
- **Payouts de instrutor**: `PayoutController` / `PayoutSettingsController`
  no painel do instrutor.
- **Fatura**: `InvoiceController` + rota `GET /Invoice/{id}` (aluno).

## Dependências PHP relevantes

`stripe/stripe-php`, `razorpay/razorpay`, `paytm/paytmchecksum`,
`guzzlehttp/guzzle` (para callbacks HTTP).
