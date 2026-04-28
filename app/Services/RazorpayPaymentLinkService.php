<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RazorpayPaymentLinkService
{
    private const BASE = 'https://api.razorpay.com/v1';

    public function isConfigured(): bool
    {
        $key = config('razorpay.key');
        $secret = config('razorpay.secret');

        return is_string($key) && $key !== '' && is_string($secret) && $secret !== '';
    }

    /**
     * @return array<string, mixed>
     */
    public function createPaymentLink(array $body): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->basicAuthHeader(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
            ->timeout(60)
            ->post(self::BASE.'/payment_links', $body);

        return $this->decodeOrThrow($response);
    }

    /**
     * @return array<string, mixed>
     */
    public function getPaymentLink(string $paymentLinkId): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->basicAuthHeader(),
            'Accept' => 'application/json',
        ])
            ->timeout(60)
            ->get(self::BASE.'/payment_links/'.rawurlencode($paymentLinkId));

        return $this->decodeOrThrow($response);
    }

    private function basicAuthHeader(): string
    {
        $pair = config('razorpay.key').':'.config('razorpay.secret');

        return 'Basic '.base64_encode($pair);
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
