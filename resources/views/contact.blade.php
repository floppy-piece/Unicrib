<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Contact – UniCrib</title>
    <x-icon/>

    <!-- Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    {{-- Prevent flash of light mode --}}
    <script>
        if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased selection:bg-indigo-600 selection:text-white min-h-screen flex flex-col transition-colors duration-200">

    {{-- HEADER --}}
    <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center overflow-hidden shadow-sm">
                        <x-application-logo class="h-full w-full object-cover" />
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">UniCrib</span>
                </div>

                <div class="flex items-center space-x-4">
                    <x-non-nav/>
                    @if (Route::has('login'))
                        <livewire:welcome.navigation/>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTACT SECTION --}}
    <section class="py-20 bg-slate-50 dark:bg-slate-950 flex-grow transition-colors duration-200" style="background-image: linear-gradient(rgba(15,23,42,0.92), rgba(15,23,42,0.96)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold tracking-widest text-indigo-400 uppercase bg-indigo-950/80 px-3 py-1 rounded-full border border-indigo-800">Contact UniCrib</span>
                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-200">
                    Talk to the team behind UniCrib.
                </h1>
                <p class="text-slate-400 text-sm sm:text-base">
                    Use the form below for detailed enquiries, or reach us directly via email or phone for urgent issues.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- FORM CONTAINER (Col 7) --}}
                <div class="lg:col-span-7 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6 transition-colors duration-200">
                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300 font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-1">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Send Unicrib a message</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Share a bit about who you are and what you need. We’ll route your message to the right
                            person on the UniCrib team and respond as quickly as possible.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Full name</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name') }}"
                                    class="w-full rounded-xl border @error('name') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 @enderror px-3.5 py-2.5 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white dark:focus:bg-slate-900 transition"
                                    required
                                    autocomplete="name"
                                >
                                @error('name')
                                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Email address</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    class="w-full rounded-xl border @error('email') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 @enderror px-3.5 py-2.5 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white dark:focus:bg-slate-900 transition"
                                    required
                                    autocomplete="email"
                                >
                                @error('email')
                                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="role" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Who are you?</label>
                                <select
                                    id="role"
                                    name="role"
                                    class="w-full rounded-xl border @error('role') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 @enderror px-3.5 py-2.5 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white dark:focus:bg-slate-900 transition"
                                >
                                    <option value="" class="dark:bg-slate-900">Select an option</option>
                                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }} class="dark:bg-slate-900">Student</option>
                                    <option value="landlord" {{ old('role') === 'landlord' ? 'selected' : '' }} class="dark:bg-slate-900">Landlord / Property manager</option>
                                    <option value="university" {{ old('role') === 'university' ? 'selected' : '' }} class="dark:bg-slate-900">University / Institution</option>
                                    <option value="other" {{ old('role') === 'other' ? 'selected' : '' }} class="dark:bg-slate-900">Other</option>
                                </select>
                                @error('role')
                                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="subject" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Subject</label>
                                <input
                                    id="subject"
                                    name="subject"
                                    type="text"
                                    value="{{ old('subject') }}"
                                    class="w-full rounded-xl border @error('subject') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 @enderror px-3.5 py-2.5 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white dark:focus:bg-slate-900 transition"
                                    required
                                >
                                @error('subject')
                                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="message" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">How can we help?</label>
                            <textarea
                                id="message"
                                name="message"
                                rows="4"
                                class="w-full rounded-xl border @error('message') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 @enderror px-3.5 py-2.5 text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white dark:focus:bg-slate-900 transition resize-y"
                                required
                            >{{ old('message') }}</textarea>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                Please avoid sharing passwords or sensitive financial details. Describe your campus, timelines, and what you need.
                            </p>
                            @error('message')
                                <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                By submitting, you agree that UniCrib may contact you about this enquiry.
                            </p>
                            <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-6 py-3 rounded-xl transition shadow-sm">
                                Send message
                            </button>
                        </div>
                    </form>
                </div>

                {{-- SIDEBAR INFO CARDS (Col 5) --}}
                <div class="lg:col-span-5 space-y-6">
                    
                    <div class="bg-indigo-900 text-white rounded-2xl p-6 sm:p-8 shadow-md border border-indigo-800 space-y-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-300 bg-indigo-800/80 px-2.5 py-1 rounded-md">Direct contact</span>
                        <h3 class="text-lg font-bold">Email or call UniCrib</h3>
                        <p class="text-xs text-indigo-200 leading-relaxed">
                            For urgent issues like account access, billing, or live bookings, reach us directly using the links below.
                            On mobile, the call link will open your phone app automatically.
                        </p>
                        
                        <ul class="space-y-2.5 text-xs border-t border-indigo-800/80 pt-4 text-indigo-100">
                            <li>
                                <strong class="text-white">Email:</strong>
                                <a href="mailto:unicrib@gmail.com?subject=UniCrib%20Enquiry" class="underline text-indigo-200 hover:text-white ml-1 font-medium">
                                    unicrib@gmail.com
                                </a>
                            </li>
                            <li>
                                <strong class="text-white">Phone / WhatsApp:</strong>
                                <a href="tel:+254704969006" class="underline text-indigo-200 hover:text-white ml-1 font-medium">
                                    +254 704 969 006
                                </a>
                            </li>
                            <li><strong class="text-white">Location:</strong> Kutus – Kirinyaga, Kenya</li>
                        </ul>

                        <div class="pt-2 text-xs flex flex-wrap gap-2 text-indigo-200">
                            <a href="mailto:unicrib@gmail.com?subject=UniCrib%20Support" class="underline hover:text-white">Email support</a>
                            <span>•</span>
                            <a href="tel:+254704969006" class="underline hover:text-white">Tap to call</a>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 transition-colors duration-200">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 px-2.5 py-1 rounded-md border border-indigo-100 dark:border-indigo-900/50">Guidance</span>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">How UniCrib can help</h3>
                        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-300 list-disc list-inside">
                            <li>Students: help with housing search, bookings, or a specific property.</li>
                            <li>Landlords: onboarding new properties or improving occupancy.</li>
                            <li>Universities: pilots, integrations, or campus‑wide housing solutions.</li>
                            <li>Partners &amp; investors: product demos and collaboration discussions.</li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-12 text-sm text-slate-500 dark:text-slate-400 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center max-w-lg mx-auto space-y-2">
                <div class="text-lg font-bold text-slate-900 dark:text-slate-100">UniCrib</div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Premium student accommodations and services with a human team behind every message you send.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-xs tracking-wider uppercase mb-3">Platform</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Properties</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Universities</a></li>
                        <li><a href="{{ url('/services') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Services</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Dashboard</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-xs tracking-wider uppercase mb-3">Company</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ url('/about') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">About us</a></li>
                        <li><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Careers</a></li>
                        <li><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Blog</a></li>
                        <li><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Press</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-xs tracking-wider uppercase mb-3">Contact</h3>
                    <ul class="space-y-2 text-xs">
                        <li>Kutus – Kirinyaga</li>
                        <li><a href="tel:+254704969006" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">+254 704 969 006</a></li>
                        <li><a href="mailto:unicrib@gmail.com?subject=UniCrib%20Enquiry" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">unicrib@gmail.com</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400 dark:text-slate-500 gap-4">
                <p>© {{ date('Y') }} UniCrib. All rights reserved.</p>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-slate-600 dark:hover:text-slate-300 transition">Privacy policy</a>
                    <a href="#" class="hover:text-slate-600 dark:hover:text-slate-300 transition">Terms of service</a>
                    <a href="#" class="hover:text-slate-600 dark:hover:text-slate-300 transition">Cookie policy</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>