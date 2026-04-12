<x-layouts.store title="Refund Policy — {{ config('app.name', 'BookQueue') }}">
    <div class="max-w-4xl mx-auto">
        <h1 class="font-display text-4xl tracking-tight">Refund Policy</h1>
        <p class="mt-2 text-ink-500 dark:text-gray-300">Last updated: {{ now()->format('F j, Y') }}</p>
        <p class="mt-4 text-sm text-ink-500 dark:text-gray-300 leading-relaxed">
            This Refund Policy explains how <strong>{{ config('app.name', 'BookQueue') }}</strong> handles refunds for digital book purchases (eBooks) made through our online bookstore. Because digital goods can be delivered instantly, refund rules differ from physical products. Please read this policy together with our <a href="{{ route('pages.terms') }}" class="text-ink-900 dark:text-white underline underline-offset-2 hover:no-underline">Terms & Conditions</a>.
        </p>

        <div class="mt-10 space-y-6 text-sm leading-7 text-ink-700 dark:text-gray-200">
            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">1. Nature of digital products</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    When your payment is successful, your order is typically fulfilled by granting access to download the purchased eBook from your account library. Digital content is deemed delivered once access is available. This immediacy affects when refunds are appropriate or legally required.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">2. When we may issue a refund</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Subject to verification and applicable law, we may approve a refund or replacement access in situations including:
                </p>
                <ul class="mt-2 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li><strong>Duplicate charge:</strong> You were charged more than once for the same order due to a processing error.</li>
                    <li><strong>Failed delivery:</strong> Payment succeeded but the title never appeared in your library and we cannot restore access within a reasonable time.</li>
                    <li><strong>Corrupt or unusable file:</strong> The file we provide is defective (for example, will not open in standard readers) and we cannot supply a working copy.</li>
                    <li><strong>Wrong item delivered:</strong> You received a different title than the one you purchased and we cannot correct the order.</li>
                    <li><strong>Unauthorized transaction:</strong> You report unauthorized use of your payment method and we or your bank confirm the claim in line with our investigation.</li>
                    <li><strong>Mandatory statutory rights:</strong> Where local consumer law grants an unconditional or conditional right of withdrawal or refund for digital goods, we will honor those requirements (including any need to confirm you have not consented to early delivery and waived withdrawal, if applicable).</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">3. When refunds are generally not provided</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Except where required by law or as stated in Section 2, we typically do <strong>not</strong> offer refunds for:
                </p>
                <ul class="mt-2 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li><strong>Change of mind</strong> after purchase (including dislike of the book’s content, style, or subject).</li>
                    <li><strong>Compatibility issues</strong> caused by your device, software, or settings, if the file is in a standard format described on the product page.</li>
                    <li><strong>Failed downloads</strong> due to your network, storage, or account configuration.</li>
                    <li><strong>Loss of access</strong> due to account closure, breach of our Terms, or sharing of your account.</li>
                    <li><strong>Promotional or discounted purchases</strong> where stated as final sale (if we apply such labels).</li>
                </ul>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    If you have already downloaded the full file, we may treat the purchase as consumed and decline a refund unless a problem in Section 2 applies or the law requires otherwise.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">4. How to request a refund</h2>
                <ul class="mt-3 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li>Contact us using the support or contact information on <strong>{{ config('app.url') }}</strong> (or from your account area, if available).</li>
                    <li>Include your order number, email address, book title, and a clear description of the issue.</li>
                    <li>Allow reasonable time for us to verify your purchase and the reported problem.</li>
                </ul>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    We may ask for screenshots, error messages, or other information needed to confirm duplicate charges, failed delivery, or file defects.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">5. Processing and timing</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Approved refunds are processed to the original payment method when possible. Timing depends on your bank or card issuer—typically <strong>5–10 business days</strong> after we approve. If we revoke access to a refunded title, you must delete any copies you obtained.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">6. Chargebacks and payment disputes</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    If you initiate a chargeback or payment dispute, we may suspend access to the relevant digital content until the dispute is resolved. We encourage you to contact us first so we can resolve the issue without a dispute where possible.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">7. Changes to this policy</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    We may update this Refund Policy from time to time. The “Last updated” date will change, and we will post the revised policy on this page. For purchases made before an update, the policy in effect at the time of purchase may apply unless law requires otherwise.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">8. Contact</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    For refund questions, contact us using the details on <strong>{{ config('app.url') }}</strong>.
                </p>
                <p class="mt-3 text-xs text-ink-500 dark:text-gray-400">
                    This policy is provided for general information. Consumer rights vary by country and region; nothing here limits any statutory rights you may have.
                </p>
            </section>
        </div>
    </div>
</x-layouts.store>
