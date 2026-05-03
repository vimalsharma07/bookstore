<x-layouts.store :title="$title ?? 'Pay with Razorpay'">
    <div class="max-w-lg mx-auto text-center">
        <h1 class="font-display text-2xl">Secure payment</h1>
        <p class="mt-2 text-sm text-ink-500 dark:text-gray-400">The Razorpay window opens automatically. Your name, email, and phone are pre-filled from your account.</p>

        <div class="mt-8 rounded-2xl border border-black/8 dark:border-white/10 bg-white/70 dark:bg-white/[0.06] px-4 py-8">
            <div class="animate-pulse text-ink-500 dark:text-gray-400 text-sm">Opening Razorpay…</div>
            <p class="mt-4 text-xs text-ink-500 dark:text-gray-400">
                If nothing opens, disable popup blockers or <button type="button" id="rzp-open-btn" class="underline font-medium text-ink-900 dark:text-white">click here to pay</button>.
            </p>
            <a href="{{ $backUrl }}" class="mt-6 inline-block text-sm text-ink-500 hover:underline">← Back</a>
        </div>

        <form id="rzp-verify-form" method="POST" action="{{ $verifyAction }}" class="hidden">
            @csrf
            @if($context === 'order')
                <input type="hidden" name="order_id" value="{{ $order->id }}" />
            @else
                <input type="hidden" name="reading_subscription_id" value="{{ $subscription->id }}" />
            @endif
            <input type="hidden" name="razorpay_payment_id" id="rzp-payment-id" />
            <input type="hidden" name="razorpay_order_id" id="rzp-order-id" />
            <input type="hidden" name="razorpay_signature" id="rzp-signature" />
        </form>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        (function () {
            var options = {
                key: @json($razorpayKey),
                order_id: @json($razorpayOrderId),
                name: @json(config('app.name')),
                description: @json($description),
                prefill: {
                    name: @json($prefillName),
                    email: @json($prefillEmail),
                    contact: @json($prefillContact)
                },
                theme: { color: '#1a1a1a' },
                modal: {
                    ondismiss: function () {
                        window.location.href = @json($backUrl);
                    }
                },
                handler: function (response) {
                    document.getElementById('rzp-payment-id').value = response.razorpay_payment_id;
                    document.getElementById('rzp-order-id').value = response.razorpay_order_id;
                    document.getElementById('rzp-signature').value = response.razorpay_signature;
                    document.getElementById('rzp-verify-form').submit();
                }
            };

            var rzp = new Razorpay(options);

            function open() {
                rzp.open();
            }

            document.getElementById('rzp-open-btn').addEventListener('click', open);

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', open);
            } else {
                open();
            }
        })();
    </script>
</x-layouts.store>
