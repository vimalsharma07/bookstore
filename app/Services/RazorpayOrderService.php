<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RazorpayOrderService
{
    private const BASE = 'https://api.razorpay.com/v1';

    /**
     * @param  array<string, mixed>  $notes
     * @return array<string, mixed>
     */
    public function createOrder(int $amountSubunits, string $currency, string $receipt, array $notes = []): array
    {
        $receipt = substr(preg_replace('/\s+/', '_', $receipt), 0, 40);

        $response = Http::withBasicAuth((string) config('razorpay.key'), (string) config('razorpay.secret'))
            ->acceptJson()
            ->asJson()
            ->timeout(60)
            ->post(self::BASE.'/orders', [
                'amount' => $amountSubunits,
                'currency' => strtoupper($currency),
                'receipt' => $receipt !== '' ? $receipt : 'rcpt_'.time(),
                'notes' => $notes,
            ]);

        return $this->decodeOrThrow($response);
    }

    /**
     * @return array<string, mixed>
     */
    public function fetchPayment(string $paymentId): array
    {
        $response = Http::withBasicAuth((string) config('razorpay.key'), (string) config('razorpay.secret'))
            ->acceptJson()
            ->timeout(30)
            ->get(self::BASE.'/payments/'.rawurlencode($paymentId));

        return $this->decodeOrThrow($response);
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeOrThrow(Response $response): array
    {
        if ($response->successful()) {
            return $response->json();
        }

        $data = $response->json();
        $message = null;
        if (is_array($data)) {
            $message = data_get($data, 'error.description')
                ?? data_get($data, 'error.reason')
                ?? data_get($data, 'message');
        }

        throw new \RuntimeException($message ?: 'Razorpay API error (HTTP '.$response->status().').');
    }
}
