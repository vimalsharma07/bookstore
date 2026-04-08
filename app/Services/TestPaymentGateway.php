<?php

namespace App\Services;

class TestPaymentGateway
{
    /**
     * Simulate payment outcome based on card number.
     *
     * Test cards:
     * - 4242 4242 4242 4242 => success
     * - 4000 0000 0000 0002 => declined
     * - 4000 0000 0000 9995 => insufficient funds
     */
    public static function charge(string $cardNumber, string $expiry, string $cvc): array
    {
        $n = preg_replace('/\D+/', '', $cardNumber) ?? '';

        return match ($n) {
            '4242424242424242' => ['ok' => true, 'code' => 'approved', 'message' => 'Payment approved.'],
            '4000000000000002' => ['ok' => false, 'code' => 'card_declined', 'message' => 'Your card was declined.'],
            '4000000000009995' => ['ok' => false, 'code' => 'insufficient_funds', 'message' => 'Insufficient funds.'],
            default => ['ok' => false, 'code' => 'invalid_test_card', 'message' => 'Unknown test card. Use a listed test card number.'],
        };
    }
}

