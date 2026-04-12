<x-layouts.store title="Terms & Conditions — {{ config('app.name', 'BookQueue') }}">
    <div class="max-w-4xl mx-auto">
        <h1 class="font-display text-4xl tracking-tight">Terms & Conditions</h1>
        <p class="mt-2 text-ink-500 dark:text-gray-300">Last updated: {{ now()->format('F j, Y') }}</p>
        <p class="mt-4 text-sm text-ink-500 dark:text-gray-300 leading-relaxed">
            These Terms & Conditions (“Terms”) govern your access to and use of the website, applications, and services operated by <strong>{{ config('app.name', 'BookQueue') }}</strong> (“we,” “us,” “our”), including browsing, account registration, purchase of digital books, and downloads. By accessing or using our services, you agree to these Terms. If you do not agree, do not use the services.
        </p>

        <div class="mt-10 space-y-6 text-sm leading-7 text-ink-700 dark:text-gray-200">
            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">1. Eligibility</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    You must be at least the age of majority in your jurisdiction (or have verifiable parental consent where required) to create an account and make purchases. You represent that the information you provide is accurate and that you have the legal capacity to enter into these Terms.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">2. Description of the service</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    {{ config('app.name', 'BookQueue') }} provides an online storefront for digital books (eBooks). After payment is successfully authorized, you may access purchased content through your account (for example, in <strong>My Library</strong>) and download files where permitted. We may change catalog listings, availability, pricing, or features with reasonable notice where appropriate.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">3. Accounts and security</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    You are responsible for maintaining the confidentiality of your login credentials and for all activity under your account. Notify us promptly of any unauthorized use. We may suspend or terminate accounts that violate these Terms or pose a security risk.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">4. Orders, pricing, and payment</h2>
                <ul class="mt-3 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li>Prices are displayed in the currency indicated at checkout and may change before you complete an order.</li>
                    <li>By placing an order, you authorize us and our payment partners to charge your selected payment method for the total amount shown, including applicable taxes where collected.</li>
                    <li>You are responsible for providing accurate billing and payment information.</li>
                    <li>If payment fails or is reversed, we may cancel or withhold access to the relevant digital content until payment is successfully completed.</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">5. License to digital content</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    When you purchase a digital book, you receive a limited, non-exclusive, non-transferable, revocable license to access and download the content for your <strong>personal, non-commercial</strong> use, subject to these Terms and any technical restrictions we apply (such as device or download limits).
                </p>
                <p class="mt-3 text-ink-500 dark:text-gray-300">You may not:</p>
                <ul class="mt-2 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li>Copy, resell, redistribute, publicly perform, or sublicense purchased files except as allowed by applicable law.</li>
                    <li>Remove or alter copyright, trademark, or other proprietary notices.</li>
                    <li>Use automated systems to scrape, bulk-download, or circumvent access controls.</li>
                    <li>Share or sell access to your account or downloads.</li>
                </ul>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Title to digital books remains with the publisher or rights holder; you receive only the license described above.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">6. Refunds</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Digital purchases are governed by our <a href="{{ route('pages.refunds') }}" class="text-ink-900 dark:text-white underline underline-offset-2 hover:no-underline">Refund Policy</a>, which explains when refunds may be granted or denied. Refunds are not guaranteed except as required by law or as stated in that policy.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">7. Intellectual property</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    The platform, branding, and all books and materials are protected by copyright, trademark, and other intellectual property laws. Except for the limited license in Section 5, no rights are granted to you. Infringement notices should be sent to the contact method on our website.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">8. Prohibited conduct</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">You agree not to:</p>
                <ul class="mt-2 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li>Violate any law or third-party rights.</li>
                    <li>Interfere with or disrupt the services or servers.</li>
                    <li>Attempt to gain unauthorized access to data, accounts, or systems.</li>
                    <li>Post false reviews, harass others, or abuse the service.</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">9. Disclaimers</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    The services and content are provided “as is” and “as available” without warranties of any kind, express or implied, to the fullest extent permitted by law, including merchantability, fitness for a particular purpose, and non-infringement. We do not warrant uninterrupted or error-free operation.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">10. Limitation of liability</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    To the maximum extent permitted by law, we and our affiliates, directors, and employees shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or for loss of profits, data, or goodwill, arising from your use of the services or digital content. Our total liability for any claim arising from these Terms or the services shall not exceed the amount you paid in the twelve (12) months preceding the claim, unless mandatory law provides otherwise.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">11. Indemnity</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    You agree to indemnify and hold harmless {{ config('app.name', 'BookQueue') }} and its affiliates from claims, damages, losses, and expenses (including reasonable legal fees) arising from your use of the services, your breach of these Terms, or your violation of law or third-party rights.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">12. Termination</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    You may stop using the services at any time. We may suspend or terminate access for violations of these Terms or for operational reasons, with notice where required. Provisions that by nature should survive (including licenses, disclaimers, limitations, and indemnity) will survive termination.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">13. Governing law and disputes</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    These Terms are governed by the laws of the jurisdiction in which the operator is established, without regard to conflict-of-law rules, except where consumer protection laws in your country of residence require otherwise. Courts or dispute bodies in that jurisdiction (or as required by consumer law) shall have exclusive jurisdiction unless mandatory law provides a different forum.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">14. Privacy</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Our collection and use of personal data is described in our <a href="{{ route('pages.privacy') }}" class="text-ink-900 dark:text-white underline underline-offset-2 hover:no-underline">Privacy Policy</a>.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">15. Changes</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    We may modify these Terms. We will post the updated Terms on this page and update the “Last updated” date. Continued use after changes constitutes acceptance where permitted by law.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">16. Contact</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    For questions about these Terms, contact us using the information provided on <strong>{{ config('app.url') }}</strong>.
                </p>
            </section>
        </div>
    </div>
</x-layouts.store>
