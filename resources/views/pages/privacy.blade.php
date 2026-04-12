<x-layouts.store title="Privacy Policy — {{ config('app.name', 'BookQueue') }}">
    <div class="max-w-4xl mx-auto">
        <h1 class="font-display text-4xl tracking-tight">Privacy Policy</h1>
        <p class="mt-2 text-ink-500 dark:text-gray-300">Last updated: {{ now()->format('F j, Y') }}</p>
        <p class="mt-4 text-sm text-ink-500 dark:text-gray-300 leading-relaxed">
            This Privacy Policy describes how <strong>{{ config('app.name', 'BookQueue') }}</strong> (“we,” “us,” or “our”) collects, uses, stores, and shares personal information when you use our website and services, including when you browse titles, create an account, purchase digital books, and download content to your library. By using our services, you agree to this policy.
        </p>

        <div class="mt-10 space-y-6 text-sm leading-7 text-ink-700 dark:text-gray-200">
            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">1. Who we are</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    {{ config('app.name', 'BookQueue') }} operates an online bookstore offering digital books (eBooks) for purchase and download. The operator identified at checkout or in your account communications is the data controller responsible for personal data described in this policy, unless another entity is expressly identified (for example, a payment processor acting on its own behalf).
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">2. Information we collect</h2>
                <h3 class="mt-4 font-semibold text-ink-900 dark:text-white">2.1 Account and identity</h3>
                <ul class="mt-2 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li>Name and email address when you register or update your profile.</li>
                    <li>Hashed password and security-related data used to authenticate you.</li>
                    <li>Optional profile information you choose to provide.</li>
                </ul>
                <h3 class="mt-4 font-semibold text-ink-900 dark:text-white">2.2 Purchases, orders, and payments</h3>
                <ul class="mt-2 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li>Order identifiers, items purchased (e.g., book titles and IDs), amounts, currency, and order status.</li>
                    <li>Payment-related metadata: we use payment processors to collect and process card and other payment details; we typically do not store full card numbers on our servers.</li>
                    <li>Transaction references, timestamps, and fraud-prevention signals our providers may share with us.</li>
                </ul>
                <h3 class="mt-4 font-semibold text-ink-900 dark:text-white">2.3 Library, downloads, and digital access</h3>
                <ul class="mt-2 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li>Records of titles added to your library after purchase, download counts, and last download time where such features are enabled.</li>
                    <li>Technical data needed to enforce access limits and protect content (for example, session or device identifiers associated with your account).</li>
                </ul>
                <h3 class="mt-4 font-semibold text-ink-900 dark:text-white">2.4 Usage, device, and technical data</h3>
                <ul class="mt-2 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li>IP address, browser type, device type, and approximate location derived from IP.</li>
                    <li>Pages viewed, actions taken (e.g., cart, checkout), and timestamps.</li>
                    <li>Cookies and similar technologies as described in Section 6.</li>
                </ul>
                <h3 class="mt-4 font-semibold text-ink-900 dark:text-white">2.5 Communications and support</h3>
                <p class="mt-2 text-ink-500 dark:text-gray-300">If you contact us, we retain the content of your messages and our replies to handle your request and improve support quality.</p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">3. How we use your information</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">We use personal data to:</p>
                <ul class="mt-2 list-disc pl-5 space-y-1 text-ink-500 dark:text-gray-300">
                    <li>Create and manage your account and authenticate you.</li>
                    <li>Process purchases, take payment, and deliver digital products to your library.</li>
                    <li>Provide download links, verify access rights, and prevent unauthorized sharing or abuse.</li>
                    <li>Send transactional emails (order confirmations, receipts, access issues, security notices).</li>
                    <li>Operate, secure, and improve the platform; detect fraud and misuse of our services.</li>
                    <li>Comply with legal obligations and respond to lawful requests.</li>
                    <li>Where permitted, analyze how the service is used (e.g., aggregated analytics) to improve discovery and recommendations.</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">4. Legal bases (where applicable)</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Depending on your country or region, we rely on one or more of: performance of a contract (providing purchases and downloads); legitimate interests (security, fraud prevention, service improvement); consent (where you opt in, e.g., certain cookies or marketing); and legal obligation.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">5. Payment processors and service providers</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Payments are processed by third-party payment and banking partners. They receive information necessary to complete transactions (such as card details you enter on their secure forms, billing address if required, and order amount). Their use of your data is governed by their own privacy policies and terms. We encourage you to read those documents when you pay.
                </p>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    We may also use hosting providers, email delivery, analytics, and security vendors who process data on our instructions and under appropriate safeguards.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">6. Cookies and similar technologies</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    We use cookies and similar technologies to keep you logged in, remember preferences, remember your cart, measure traffic, and protect against abuse. You can control cookies through your browser settings; blocking essential cookies may affect checkout or login.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">7. Retention</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    We retain account and order data for as long as your account is active and as needed to provide downloads, demonstrate purchase history, meet tax and accounting requirements, and resolve disputes. Some data may be retained in backups for a limited period. When retention is no longer necessary, we delete or anonymize data subject to applicable law.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">8. Security</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    We implement technical and organizational measures appropriate to the nature of the data (including encryption in transit where standard, access controls, and monitoring). No method of transmission or storage is 100% secure; we cannot guarantee absolute security.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">9. International transfers</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Your information may be processed in countries other than where you live, including where our servers or providers operate. Where required, we use appropriate safeguards (such as standard contractual clauses) to protect transfers.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">10. Your rights</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Depending on applicable law, you may have rights to access, correct, delete, or export your personal data; restrict or object to certain processing; withdraw consent where processing is consent-based; and lodge a complaint with a supervisory authority. To exercise rights, contact us using the details on our website or your account. We may need to verify your identity before responding.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">11. Children</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Our services are not directed at children under the age required by local law to consent to data processing (often 13 or 16). We do not knowingly collect personal information from children. If you believe we have collected such data, please contact us so we can delete it.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">12. Changes to this policy</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    We may update this Privacy Policy from time to time. We will post the revised version on this page and update the “Last updated” date. Material changes may be communicated by email or a notice on the site where appropriate.
                </p>
            </section>

            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                <h2 class="font-display text-xl text-ink-900 dark:text-white">13. Contact</h2>
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    For privacy-related questions or requests, please contact us using the support or contact information published on <strong>{{ config('app.url') }}</strong> or in your account communications.
                </p>
                <p class="mt-3 text-xs text-ink-500 dark:text-gray-400">
                    This policy is provided for general information and does not constitute legal advice. You may wish to consult a qualified professional regarding your specific obligations or rights in your jurisdiction.
                </p>
            </section>
        </div>
    </div>
</x-layouts.store>
