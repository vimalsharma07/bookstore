<?php

namespace App\Support;

final class RazorpayPaymentSignature
{
    public static function verifyOrderPayment(string $orderId, string $paymentId, string $signature): bool
    {
        $secret = (string) config('razorpay.secret');
        if ($secret === '') {
            return false;
        }

        $payload = $orderId.'|'.$paymentId;
        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }
}
