<x-layouts.store title="Enquiry Received">
    <div class="min-h-[70vh] flex items-center justify-center px-4">
        
        <div class="max-w-xl w-full text-center">
            
            <!-- Card -->
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-8 shadow-sm">
                
                <!-- Icon -->
                <div class="flex justify-center">
                    <div class="w-16 h-16 flex items-center justify-center rounded-full bg-green-100 text-green-600 text-2xl">
                        ✓
                    </div>
                </div>

                <!-- Heading -->
                <h1 class="mt-5 font-display text-3xl tracking-tight">
                    Enquiry Received!
                </h1>

                <!-- Message -->
                <p class="mt-3 text-ink-500 dark:text-gray-300">
                    Thank you for reaching out to us. Our team has received your message and will get back to you shortly.
                </p>

                <p class="mt-2 text-sm text-ink-400">
                    We usually respond within 24 hours.
                </p>

                <!-- Buttons -->
                <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                    
                    <a href="{{ url('/home') }}"
                       class="px-5 py-2.5 rounded-lg bg-black text-white hover:bg-gray-800 transition">
                        Go to Home
                    </a>

                    <a href="{{ url('/faq') }}"
                       class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-white/10 transition">
                        View FAQ
                    </a>
                </div>

            </div>

        </div>
    </div>
</x-layouts.store>
