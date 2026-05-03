<?php

namespace App\Support;

use App\Models\User;

/**
 * Razorpay payment links require a customer.contact value.
 * Using a random number causes Razorpay to ask the payer to verify or enter a real mobile.
 * Prefer the user's saved phone from their profile so the hosted page can pre-fill correctly.
 */
final class RazorpayCustomerContact
{
    public static function forPayment(?User $user, string $currency): string
    {
        $currency = strtoupper($currency);

        if ($user !== null) {
            $digits = preg_replace('/\D+/', '', (string) ($user->phone ?? ''));

            if ($digits !== '') {
                if ($currency === 'INR') {
                    if (strlen($digits) > 10 && str_starts_with($digits, '91')) {
                        $digits = substr($digits, -10);
                    }

                    if (strlen($digits) === 10) {
                        return $digits;
                    }
                } elseif (strlen($digits) >= 10 && strlen($digits) <= 15) {
                    return $digits;
                }
            }
        }

        return self::randomPlaceholder($currency);
    }

    private static function randomPlaceholder(string $currency): string
    {
        if ($currency === 'INR') {
            $first = (string) random_int(6, 9);
            $rest = '';
            for ($i = 0; $i < 9; $i++) {
                $rest .= (string) random_int(0, 9);
            }

            return $first.$rest;
        }

        $first = (string) random_int(2, 9);
        $rest = '';
        for ($i = 0; $i < 9; $i++) {
            $rest .= (string) random_int(0, 9);
        }

        return $first.$rest;
    }
}
