<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} – Premium Student Housing</title>
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

    {{-- HERO --}}
    <section class="bg-indigo-900 text-white py-20 px-4 relative overflow-hidden" style="background-image: linear-gradient(rgba(15,23,42,0.92), rgba(15,23,42,0.96)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
        <!-- Subtle Background Glow Element -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-800/80 border border-indigo-700 text-xs font-semibold text-indigo-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>ELITE ACCOMMODATIONS • Built for University Life</span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-bold tracking-tight leading-tight">
                        Find your <span class="text-indigo-400 underline decoration-indigo-500/50">perfect</span> student home.
                    </h1>

                    <p class="text-indigo-200 text-base sm:text-lg max-w-2xl font-normal">
                        Discover carefully curated student accommodations near your university.
                        UniCrib gives you transparent options, real details, and a smoother way
                        to secure your next home.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ url('/register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-8 py-3.5 rounded-xl shadow-lg shadow-indigo-600/30 transition text-sm">
                            Get started – it’s free
                        </a>
                        <a href="#" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 font-semibold px-6 py-3.5 rounded-xl transition text-sm backdrop-blur">
                            Watch platform overview
                        </a>
                    </div>

                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-indigo-800/80">
                        <div>
                            <div class="text-xl sm:text-2xl font-bold text-white">4.9 / 5</div>
                            <div class="text-xs text-indigo-300 mt-0.5">Average student rating</div>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-bold text-amber-400">{{ $totalProperties }}+</div>
                            <div class="text-xs text-indigo-300 mt-0.5">Verified properties</div>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-bold text-white">24/7</div>
                            <div class="text-xs text-indigo-300 mt-0.5">Student support</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 space-y-4">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white shadow-xl">
                        <h3 class="font-bold text-lg text-white mb-1">Campus proximity</h3>
                        <p class="text-xs text-indigo-200 uppercase tracking-wider font-semibold mb-2">Walking distance</p>
                        <p class="text-sm text-indigo-100">
                            Short walks, safe routes. Many listings are within a 10–15 minute walk to campus gates.
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white shadow-xl">
                        <h3 class="font-bold text-lg text-white mb-1">Verified &amp; secure</h3>
                        <p class="text-xs text-indigo-200 uppercase tracking-wider font-semibold mb-2">Trust &amp; safety</p>
                        <p class="text-sm text-indigo-100">
                            Every property is reviewed and verified, so you can focus on your studies, not scams.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="py-20 bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Why UniCrib</span>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">A simpler way to secure student housing</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">UniCrib removes the noise from the search process. You get relevant options, clear information, and support from enquiry to move‑in.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition space-y-3">
                    <div class="inline-block bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Curated selection</div>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Every listing is reviewed for quality, location, and suitability for student life.</p>
                </div>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition space-y-3">
                    <div class="inline-block bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Time‑saving</div>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Filter by budget, distance, and preferences. Shortlist in minutes instead of weeks.</p>
                </div>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition space-y-3">
                    <div class="inline-block bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Student community</div>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Discover shared housing, connect with roommates, and build your campus network.</p>
                </div>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition space-y-3">
                    <div class="inline-block bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">24/7 support</div>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Our team is available around the clock to help with viewings, questions, and issues.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURED PROPERTIES --}}
    <section class="py-20 bg-white dark:bg-slate-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Featured properties</span>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">A closer look at our accommodations</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">A taste of the verified student homes available near major universities.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($properties as $property)
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                    <div class="relative bg-slate-200 dark:bg-slate-800 p-6 text-center">
                        @if($property->images && count($property->images) > 0)
                            <img class="w-full max-h-56 object-cover rounded-xl shadow-inner mx-auto"
                                 src="{{ asset('storage/' . $property->images[0]) }}"
                                 alt="{{ $property->title }}">
                        @else
                            <div class="w-full h-56 flex items-center justify-center bg-slate-200 dark:bg-slate-800 rounded-xl text-slate-400 dark:text-slate-500">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                        @endif
                        <span class="absolute top-4 left-4 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-lg shadow">Ksh{{ number_format($property->price) }}</span>
                    </div>

                    <div class="p-6 flex flex-col flex-grow space-y-3">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $property->building_name }}</h3>                        
                        <p class="text-xs text-slate-500 dark:text-slate-400">Walking distance to campus</p>

                        <div class="flex justify-between items-center text-xs text-slate-600 dark:text-slate-300 pt-2 border-t border-slate-200 dark:border-slate-700/80">
                            <span class="font-medium">• {{ $property->property_type }}</span>
                            <span class="bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 font-semibold px-2.5 py-1 rounded-full text-[11px] border border-indigo-100 dark:border-indigo-900/50">{{ $property->title }}</span>
                        </div>                     
                
                        <a href="{{ route('properties.show', $property) }}" class="mt-auto bg-slate-900 hover:bg-indigo-600 dark:bg-slate-800 dark:hover:bg-indigo-600 text-white text-xs font-semibold py-2.5 px-4 rounded-xl transition text-center shadow-sm">
                            View details
                        </a>
                    </div>
                </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <p class="text-slate-500 dark:text-slate-400 text-sm">No verified properties are available right now. Check back soon or list your property!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <section class="py-16 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12">
            <div class="max-w-2xl mx-auto space-y-2">
                <h2 class="text-3xl font-bold tracking-tight">Trusted by students and landlords</h2>
                <p class="text-slate-400 text-sm">UniCrib is growing with every intake. The numbers below represent real matches between students and verified homes.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="space-y-1">
                    <div class="text-3xl font-bold text-indigo-400">{{ number_format($totalStudents) }}+</div>
                    <div class="text-xs text-slate-400 uppercase tracking-wider font-medium">Students served</div>
                </div>
                <div class="space-y-1">
                    <div class="text-3xl font-bold text-emerald-400">{{ number_format($totalProperties) }}+</div>
                    <div class="text-xs text-slate-400 uppercase tracking-wider font-medium">Active properties</div>
                </div>
                <div class="space-y-1">
                    <div class="text-3xl font-bold text-indigo-400">1+</div>
                    <div class="text-xs text-slate-400 uppercase tracking-wider font-medium">Universities covered</div>
                </div>
                <div class="space-y-1">
                    <div class="text-3xl font-bold text-amber-400">98.9%</div>
                    <div class="text-xs text-slate-400 uppercase tracking-wider font-medium">Satisfaction rating</div>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="py-20 bg-slate-50 dark:bg-slate-950 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Student feedback</span>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Students share their UniCrib experience</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">Real stories from students who secured housing quickly and confidently using UniCrib.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">BO</div>
                        <div>
                            <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Benson Okwemba</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 italic">
                        “UniCrib saved me weeks of searching. I found a great settlement within walking distance of campus in just one day at an affordable price.”
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">DO</div>
                        <div>
                            <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Daivine Oluoch</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 italic">
                        “As a student, verified listings and support made the move feel safe. I could focus on classes instead of worrying about housing.”
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-800 text-white flex items-center justify-center font-bold text-sm shadow-sm">RM</div>
                        <div>
                            <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Raymond Mwenda</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 italic">
                        “Money refund on booking rejection is a very good feature which has boost my trust towards unicrib on my money.”
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="py-20 bg-indigo-900 text-white text-center">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Ready to secure your next home?</h2>
            <p class="text-indigo-200 text-base max-w-xl mx-auto">
                Create your UniCrib account, explore properties, and move into a place that fits your studies and your budget.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 pt-2">
                <a href="{{ url('/register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-8 py-3.5 rounded-xl transition text-sm shadow-lg shadow-indigo-600/30">
                    Get started now
                </a>
                <a href="{{ url('/dashboard') }}" class="text-indigo-200 hover:text-white text-sm font-medium underline transition">
                    View properties on the dashboard
                </a>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-12 text-sm text-slate-500 dark:text-slate-400 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center max-w-lg mx-auto space-y-2">
                <div class="text-lg font-bold text-slate-900 dark:text-slate-100">UniCrib</div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Premium student accommodations with clear information and responsive support. UniCrib is committed to improving the housing experience for students and landlords.
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
                        <li><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">About us</a></li>
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