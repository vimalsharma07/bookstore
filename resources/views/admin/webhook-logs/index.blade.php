<x-admin.layout title="Admin · Webhook Logs">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl">Webhook Logs</h1>
            <p class="text-ink-500 dark:text-gray-300 mt-1">Incoming Razorpay webhooks and forward status.</p>
        </div>
    </div>

    <div class="mt-4 rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-4">
        <form method="GET" action="{{ route('admin.webhook-logs.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search in request payload..."
                class="w-full rounded-xl border border-black/10 dark:border-white/15 bg-white/80 dark:bg-white/5 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black/20 dark:focus:ring-white/20"
            >
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 rounded-xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/10 hover:bg-white dark:hover:bg-white/15 transition text-sm">Search</button>
                @if(!empty($search))
                    <a href="{{ route('admin.webhook-logs.index') }}" class="px-4 py-2.5 rounded-xl border border-black/10 dark:border-white/15 bg-white/70 dark:bg-white/10 hover:bg-white dark:hover:bg-white/15 transition text-sm">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <div class="mt-6 rounded-3xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-black/5 dark:bg-white/10 text-ink-700 dark:text-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3">#</th>
                        <th class="text-left px-4 py-3">Event</th>
                        <th class="text-left px-4 py-3">Email</th>
                        <th class="text-left px-4 py-3">Amount</th>
                        <th class="text-left px-4 py-3">Request Payload</th>
                        <th class="text-left px-4 py-3">Signature</th>
                        <th class="text-left px-4 py-3">Forward URL</th>
                        <th class="text-left px-4 py-3">Forward Status</th>
                        <th class="text-left px-4 py-3">Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $payloadArray = json_decode($log->request_payload ?? '', true);
                            $paymentEntity = is_array($payloadArray)
                                ? data_get($payloadArray, 'payload.payment.entity', [])
                                : [];
                            $email = data_get($paymentEntity, 'email');
                            $amount = data_get($paymentEntity, 'amount');
                            $currency = data_get($paymentEntity, 'currency');
                        @endphp
                        <tr class="border-t border-black/5 dark:border-white/10">
                            <td class="px-4 py-3 font-medium">{{ $log->id }}</td>
                            <td class="px-4 py-3">{{ $log->event ?? 'unknown' }}</td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $email ?: '—' }}</td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">
                                @if($amount !== null)
                                    {{ $currency ? strtoupper($currency).' ' : '' }}{{ number_format(((float) $amount) / 100, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button
                                    type="button"
                                    class="px-3 py-1.5 rounded-lg border border-black/10 dark:border-white/15 bg-white/60 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 transition text-xs"
                                    data-role="open-payload-modal"
                                    data-payload='@json($log->request_payload ?? "")'
                                >
                                    View Payload
                                </button>
                            </td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $log->signature ? \Illuminate\Support\Str::limit($log->signature, 28) : '—' }}</td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $log->forwarded_to ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full border {{ $log->is_forward_success ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/30' : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:border-rose-500/30' }}">
                                    {{ $log->forward_status_code ?? 'failed' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-ink-500 dark:text-gray-300">{{ $log->created_at?->format('M j, Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr class="border-t border-black/5 dark:border-white/10">
                            <td class="px-4 py-4 text-ink-500 dark:text-gray-300" colspan="9">
                                {{ !empty($search) ? 'No webhook logs found for this payload search.' : 'No webhook logs yet.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $logs->links() }}</div>

    <div id="payload-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/60" data-role="close-payload-modal"></div>
        <div class="relative mx-auto mt-16 w-[95%] max-w-4xl rounded-2xl border border-black/10 dark:border-white/15 bg-white dark:bg-[#141413] shadow-xl">
            <div class="flex items-center justify-between px-4 py-3 border-b border-black/10 dark:border-white/15">
                <h2 class="font-display text-xl">Request Payload</h2>
                <button type="button" class="px-2 py-1 rounded-lg hover:bg-black/5 dark:hover:bg-white/10" data-role="close-payload-modal">Close</button>
            </div>
            <div class="p-4">
                <pre id="payload-modal-content" class="max-h-[70vh] overflow-auto rounded-xl bg-black text-green-300 p-4 text-xs leading-relaxed"></pre>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('payload-modal');
            const content = document.getElementById('payload-modal-content');
            const openButtons = document.querySelectorAll('[data-role="open-payload-modal"]');
            const closeButtons = document.querySelectorAll('[data-role="close-payload-modal"]');

            const openModal = (payload) => {
                let output = payload || '';

                try {
                    const parsed = JSON.parse(output);
                    output = JSON.stringify(parsed, null, 2);
                } catch (e) {
                    // Keep raw text when payload is not valid JSON.
                }

                content.textContent = output || 'No payload available.';
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            openButtons.forEach((button) => {
                button.addEventListener('click', () => openModal(button.dataset.payload));
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', closeModal);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        })();
    </script>
</x-admin.layout>
