<?php

namespace App\Services;

class Currency
{
    public const SUPPORTED = [
        'USD' => '$',
        'EUR' => '€',
        'INR' => '₹',
    ];

    public static function current(): string
    {
        $c = strtoupper((string) session('shop_currency', 'USD'));

        return array_key_exists($c, self::SUPPORTED) ? $c : 'USD';
    }

    public static function set(string $code): void
    {
        $code = strtoupper($code);
        if (array_key_exists($code, self::SUPPORTED)) {
            session(['shop_currency' => $code]);
        }
    }

    /** DB column for filtering/sorting by storefront currency. */
    public static function priceColumn(?string $currency = null): string
    {
        return match ($currency ?? self::current()) {
            'EUR' => 'price_cents_eur',
            'INR' => 'price_cents_inr',
            default => 'price_cents_usd',
        };
    }

    public static function format(int $cents, string $currency): string
    {
        $amount = $cents / 100;

        return match (strtoupper($currency)) {
            'INR' => '₹'.number_format($amount, 2),
            'EUR' => '€'.number_format($amount, 2),
            default => '$'.number_format($amount, 2),
        };
    }
}
