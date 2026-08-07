<?php

namespace App\Models\payment_gateway;

use App\Http\Requests;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

//for stripe
use Session;
use Stripe;

class StripePay extends Model
{
    use HasFactory;

    public static function payment_status($identifier, $transaction_keys = [])
    {
        $payment_gateway = DB::table('payment_gateways')->where('identifier', $identifier)->first();
        $keys            = json_decode($payment_gateway->keys, true);
        $payment_details = Session::get('payment_details');
        $expected_reference = Session::get('stripe_payment_reference');

        if ($payment_gateway->test_mode == 1) :
            $stripeSecretKey = $keys['secret_key'];
        else :
            $stripeSecretKey = $keys['secret_live_key'];
        endif;

        // A returned Stripe session must belong to this exact checkout, user and total.
        // This prevents a paid session from a different purchase being reused to unlock a course.
        $session_id = $transaction_keys['session_id'] ?? null;
        if (!$session_id || !is_array($payment_details) || !$expected_reference) {
            return false;
        }

        \Stripe\Stripe::setApiKey($stripeSecretKey);

        try {
            $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);
        } catch (\Throwable $e) {
            return false;
        }

        $expected_amount = (int) round(((float) $payment_details['payable_amount']) * 100);
        $expected_currency = strtolower((string) $payment_gateway->currency);
        $expected_user = (string) ($payment_details['custom_field']['user_id'] ?? auth()->id());
        $returned_reference = (string) ($checkout_session->metadata->academy_payment_reference ?? '');

        if (
            !$checkout_session ||
            $checkout_session->payment_status !== 'paid' ||
            (int) $checkout_session->amount_total !== $expected_amount ||
            strtolower((string) $checkout_session->currency) !== $expected_currency ||
            (string) $checkout_session->client_reference_id !== $expected_user ||
            !hash_equals((string) $expected_reference, $returned_reference)
        ) {
            return false;
        }

        Session::put(['session_id' => $session_id]);
        return true;
    }

    public static function payment_create($identifier)
    {

        $payment_gateway = DB::table('payment_gateways')->where('identifier', $identifier)->first();
        $payment_details = session('payment_details');
        $keys            = json_decode($payment_gateway->keys, true);

        $products_name = '';
        foreach ($payment_details['items'] as $key => $value) :
            if ($key == 0) {
                $products_name .= $value['title'];
            } else {
                $products_name .= ', ' . $value['title'];
            }
        endforeach;

        if ($payment_gateway->test_mode == 1) :
            $stripeSecretKey = $keys['secret_key'];
        else :
            $stripeSecretKey = $keys['secret_live_key'];
        endif;

        \Stripe\Stripe::setApiKey($stripeSecretKey);
        header('Content-Type: application/json');

        $payment_reference = bin2hex(random_bytes(16));
        Session::put('stripe_payment_reference', $payment_reference);
        $user_id = (string) ($payment_details['custom_field']['user_id'] ?? auth()->id());

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => get_phrase('Purchasing') . ' ' . $products_name,
                        ],
                        'unit_amount'  => round($payment_details['payable_amount'] * 100, 2),
                        'currency'     => $payment_gateway->currency,
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'       => 'payment', //Checkout has three modes: payment, subscription, or setup. Use payment mode for one-time purchases. Learn more about subscription and setup modes in the docs.
            'client_reference_id' => $user_id,
            'metadata' => [
                'academy_payment_reference' => $payment_reference,
            ],
            'success_url' => $payment_details['success_url'] . '/' . $identifier . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $payment_details['cancel_url'],
        ]);

        return $checkout_session->url;
    }
}
