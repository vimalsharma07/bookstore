<x-layouts.store title="Contact Us">
    <div class="max-w-6xl mx-auto px-4">
        
        <!-- Header -->
        <div class="text-center">
            <h1 class="font-display text-4xl tracking-tight">Contact Us</h1>
            <p class="mt-2 text-ink-500 dark:text-gray-300">
                Have questions about books, orders, or your library? We’re here to help.
            </p>
        </div>

        <!-- Grid Layout -->
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Left: Contact Info -->
            <div class="space-y-6">
                
                <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                    <h2 class="font-semibold text-lg">Get in touch</h2>
                    <p class="mt-2 text-sm text-ink-500 dark:text-gray-300">
                        Reach out to us anytime. We usually respond within 24 hours.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    <!-- Email -->
                    <div class="rounded-xl border border-black/5 dark:border-white/10 p-4">
                        <p class="text-sm text-ink-500">Email</p>
                        <p class="font-medium">support@bookqueue.store</p>
                    </div>

                    <!-- Phone -->
                    <div class="rounded-xl border border-black/5 dark:border-white/10 p-4">
                        <p class="text-sm text-ink-500">Phone</p>
                        <p class="font-medium">+91 98765 43210</p>
                    </div>

                    <!-- Location -->
                    <div class="rounded-xl border border-black/5 dark:border-white/10 p-4 col-span-1 sm:col-span-2">
                        <p class="text-sm text-ink-500">Location</p>
                        <p class="font-medium">Dadri, Uttar Pradesh, India</p>
                    </div>
                </div>

                <!-- FAQ Link -->
                <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6">
                    <h3 class="font-semibold">Need quick help?</h3>
                    <p class="mt-2 text-sm text-ink-500 dark:text-gray-300">
                        Check our 
                        <a href="{{ url('/faq') }}" class="underline font-medium">
                            FAQ page
                        </a> 
                        for instant answers.
                    </p>
                </div>

            </div>

            <!-- Right: Contact Form -->
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-white/5 p-6 shadow-sm">
                
                <h2 class="font-semibold text-lg">Send a message</h2>

                <!-- Success -->
                @if(session('success'))
                    <div class="mt-4 rounded-lg bg-green-100 text-green-700 p-3 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Errors -->
                @if($errors->any())
                    <div class="mt-4 rounded-lg bg-red-100 text-red-700 p-3 text-sm">
                        <ul class="list-disc ml-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ url('contact/submit') }}" method="POST" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm">Name</label>
                        <input type="text" name="name"
                               value="{{ old('name') }}"
                               class="w-full mt-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 py-2 focus:ring-2 focus:ring-black/20"
                               required>
                    </div>

                    <div>
                        <label class="text-sm">Email</label>
                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               class="w-full mt-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 py-2"
                               required>
                    </div>

                    <div>
                        <label class="text-sm">Subject</label>
                        <input type="text" name="subject"
                               value="{{ old('subject') }}"
                               class="w-full mt-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 py-2"
                               required>
                    </div>

                    <div>
                        <label class="text-sm">Message</label>
                        <textarea name="message" rows="4"
                                  class="w-full mt-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 py-2"
                                  required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full rounded-lg bg-black text-white py-2.5 hover:bg-gray-800 transition">
                        Send Message
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-layouts.store>
