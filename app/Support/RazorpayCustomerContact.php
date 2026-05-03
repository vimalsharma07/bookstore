<?php

namespace App\Support;

use App\Models\User;

/**
 * Razorpay Checkout.js expects contact as E.164 (+country + national digits).
 * Payment Links API no longer auto-fills the hosted page (Razorpay security policy);
 * we use Orders API + Checkout with prefill instead.
 */
final class RazorpayCustomerContact
{
    /**
     * For Checkout.js `prefill.contact` — E.164 format per Razorpay docs.
     */
    public static function prefillContact(?User $user, string $currency): string
    {
        $currency = strtoupper($currency);
        $digits = preg_replace('/\D+/', '', (string) ($user?->phone ?? ''));

        if ($digits !== '') {
            if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
                return '+'.$digits;
            }

            if (strlen($digits) === 11 && str_starts_with($digits, '1')) {
                return '+'.$digits;
            }

            if (strlen($digits) === 10) {
                if ($currency === 'INR') {
                    return '+91'.$digits;
                }

                return '+1'.$digits;
            }

            if (strlen($digits) >= 11 && strlen($digits) <= 15) {
                return '+'.$digits;
            }
        }

        return self::placeholderE164($currency);
    }

    private static function placeholderE164(string $currency): string
    {
        if ($currency === 'INR') {
            return '+91'.self::randomTenDigits();
        }

        return '+1'.self::randomTenDigits();
    }

    private static function randomTenDigits(): string
    {
        $s = '';
        for ($i = 0; $i < 10; $i++) {
            $s .= (string) random_int(0, 9);
        }

        return $s;
    }
}
