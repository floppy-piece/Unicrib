@extends('layouts.public')

@section('title', config('app.name') . ' – Premium Student Housing')

@section('content')
    {{-- HERO --}}
    <section class="bg-indigo-950 text-white py-24 px-4 relative overflow-hidden preserve-3d" style="background-image: linear-gradient(rgba(15,23,42,0.90), rgba(15,23,42,0.95)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/40 rounded-full blur-3xl pointer-events-none animate-pulse-glow"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none animate-pulse-glow"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-900/90 border border-indigo-700/80 text-xs font-bold text-indigo-200 shadow-lg backdrop-blur">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></div>
                        ELITE ACCOMMODATIONS • Built for University Life
                    </div>

                    <i><h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight">
                        Find your perfect rental home.
                    </h1></i>

                    <p class="text-indigo-200 text-base sm:text-lg max-w-2xl font-normal leading-relaxed">
                        Discover carefully curated student accommodations near your university.
                        UniCrib gives you transparent options, real details, and a smoother way
                        to secure your next home with AI-powered WhatsApp tools.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ url('/register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-8 py-4 rounded-2xl shadow-xl shadow-indigo-600/40 transition transform hover:-translate-y-1 text-sm">
                            Get started – it’s free
                        </a>
                        <a href="{{ url('/login') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold px-6 py-4 rounded-2xl transition transform hover:-translate-y-1 text-sm backdrop-blur">
                            Connect via WhatsApp
                        </a>
                    </div>

                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-indigo-800/80">
                        <div class="card-3d-hover p-3 rounded-xl bg-indigo-900/30 border border-indigo-700/50 backdrop-blur">
                            <div class="text-xl sm:text-2xl font-black text-white">4.9 / 5</div>
                            <div class="text-xs text-indigo-300 mt-0.5 font-medium">Average student rating</div>
                        </div>
                        <div class="card-3d-hover p-3 rounded-xl bg-indigo-900/30 border border-indigo-700/50 backdrop-blur">
                            <div class="text-xl sm:text-2xl font-black text-amber-400">{{ $totalProperties }}+</div>
                            <div class="text-xs text-indigo-300 mt-0.5 font-medium">Verified properties</div>
                        </div>
                        <div class="card-3d-hover p-3 rounded-xl bg-indigo-900/30 border border-indigo-700/50 backdrop-blur">
                            <div class="text-xl sm:text-2xl font-black text-white">24/7</div>
                            <div class="text-xs text-indigo-300 mt-0.5 font-medium">AI &amp; student support</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 space-y-4 animate-float-3d">
                    <div class="card-3d-hover bg-white/10 backdrop-blur-xl border border-white/25 rounded-3xl p-7 text-white shadow-2xl">
                        <h3 class="font-bold text-lg text-white mb-1">Campus proximity</h3>
                        <p class="text-xs text-indigo-200 uppercase tracking-wider font-bold mb-2">Walking distance</p>
                        <p class="text-sm text-indigo-100 leading-relaxed">
                            Short walks, safe routes. Many listings are within a 10–15 minute walk to campus gates with instant WhatsApp tour support.
                        </p>
                    </div>

                    <div class="card-3d-hover bg-white/10 backdrop-blur-xl border border-white/25 rounded-3xl p-7 text-white shadow-2xl">
                        <h3 class="font-bold text-lg text-white mb-1">Verified &amp; secure</h3>
                        <p class="text-xs text-indigo-200 uppercase tracking-wider font-bold mb-2">Trust &amp; safety</p>
                        <p class="text-sm text-indigo-100 leading-relaxed">
                            Every property is reviewed and verified, so you can focus on your studies, not scams. Backed by guaranteed payment refunds.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="py-24 bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <div class="inline-block text-xs font-extrabold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-4 py-1.5 rounded-full border border-indigo-100 dark:border-indigo-900/50 shadow-sm">Why UniCrib</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">A simpler way to secure student housing</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">UniCrib removes the noise from the search process. You get relevant options, clear information, and support from enquiry to move‑in.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="card-3d-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-7 shadow-lg space-y-4">
                    <div class="inline-block bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Curated selection</div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Every listing is reviewed for quality, location, and suitability for student life.</p>
                </div>
                <div class="card-3d-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-7 shadow-lg space-y-4">
                    <div class="inline-block bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Time‑saving</div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Filter by budget, distance, and preferences. Shortlist in minutes instead of weeks.</p>
                </div>
                <div class="card-3d-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-7 shadow-lg space-y-4">
                    <div class="inline-block bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Student community</div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Discover shared housing, connect with roommates, and build your campus network.</p>
                </div>
                <div class="card-3d-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-7 shadow-lg space-y-4">
                    <div class="inline-block bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">24/7 support</div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Our team and WhatsApp AI agent are available around the clock to help with viewings and issues.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURED PROPERTIES --}}
    <section class="py-24 bg-white dark:bg-slate-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <div class="inline-block text-xs font-extrabold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-4 py-1.5 rounded-full border border-indigo-100 dark:border-indigo-900/50 shadow-sm">Featured properties</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">A closer look at our accommodations</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">A taste of the verified student homes available near major universities.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($properties as $property)
                <div class="card-3d-hover bg-slate-50 dark:bg-slate-800/60 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 overflow-hidden shadow-lg flex flex-col">
                    <div class="relative bg-slate-200 dark:bg-slate-800 p-6 text-center">
                        @if($property->images && count($property->images) > 0)
                            <img class="w-full h-56 object-cover rounded-2xl shadow-inner mx-auto transform hover:scale-105 transition duration-500"
                                 src="{{ asset('storage/' . $property->images[0]) }}"
                                 alt="{{ $property->title }}">
                        @else
                            <div class="w-full h-56 flex items-center justify-center bg-slate-200 dark:bg-slate-800 rounded-2xl text-slate-400 dark:text-slate-500">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-6 left-6 bg-indigo-600 text-white text-xs font-extrabold px-3.5 py-1.5 rounded-xl shadow-lg">Ksh{{ number_format($property->price) }}</div>
                    </div>

                    <div class="p-7 flex flex-col flex-grow space-y-4">
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100">{{ $property->building_name }}</h3>                        
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Walking distance to campus</p>

                        <div class="flex justify-between items-center text-xs text-slate-600 dark:text-slate-300 pt-3 border-t border-slate-200 dark:border-slate-700/80">
                            <span class="font-semibold">{{ $property->property_type }}</span>
                            <span class="bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 font-bold px-3 py-1 rounded-xl text-xs border border-indigo-100 dark:border-indigo-900/50">{{ $property->title }}</span>
                        </div>                     
                
                        <a href="{{ route('properties.show', $property) }}" class="mt-auto bg-slate-900 hover:bg-indigo-600 dark:bg-slate-800 dark:hover:bg-indigo-600 text-white text-xs font-bold py-3 px-5 rounded-2xl transition shadow-md text-center">
                            View details
                        </a>
                    </div>
                </div>
                @empty
                    <div class="col-span-3 text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">No verified properties are available right now. Check back soon or list your property!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <section class="py-20 bg-indigo-950 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.15),transparent_50%)]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12 relative z-10">
            <div class="max-w-2xl mx-auto space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Trusted by students and landlords</h2>
                <p class="text-slate-400 text-sm">UniCrib is growing with every intake. The numbers below represent real matches between students and verified homes.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="card-3d-hover p-6 rounded-3xl bg-white/5 border border-white/10 backdrop-blur space-y-2">
                    <div class="text-3xl sm:text-4xl font-black text-indigo-400">{{ number_format($totalStudents) }}+</div>
                    <div class="text-xs text-slate-300 uppercase tracking-wider font-bold">Students served</div>
                </div>
                <div class="card-3d-hover p-6 rounded-3xl bg-white/5 border border-white/10 backdrop-blur space-y-2">
                    <div class="text-3xl sm:text-4xl font-black text-emerald-400">{{ number_format($totalProperties) }}+</div>
                    <div class="text-xs text-slate-300 uppercase tracking-wider font-bold">Active properties</div>
                </div>
                <div class="card-3d-hover p-6 rounded-3xl bg-white/5 border border-white/10 backdrop-blur space-y-2">
                    <div class="text-3xl sm:text-4xl font-black text-indigo-400">1+</div>
                    <div class="text-xs text-slate-300 uppercase tracking-wider font-bold">Universities covered</div>
                </div>
                <div class="card-3d-hover p-6 rounded-3xl bg-white/5 border border-white/10 backdrop-blur space-y-2">
                    <div class="text-3xl sm:text-4xl font-black text-amber-400">98.9%</div>
                    <div class="text-xs text-slate-300 uppercase tracking-wider font-bold">Satisfaction rating</div>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="py-24 bg-slate-50 dark:bg-slate-950 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <div class="inline-block text-xs font-extrabold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-4 py-1.5 rounded-full border border-indigo-100 dark:border-indigo-900/50 shadow-sm">Student feedback</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">Students share their UniCrib experience</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">Real stories from students who secured housing quickly and confidently using UniCrib.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card-3d-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-7 shadow-lg space-y-5">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-sm shadow-md">BO</div>
                        <div>
                            <div class="font-bold text-sm text-slate-900 dark:text-slate-100">Benson Okwemba</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 italic leading-relaxed">
                        “UniCrib saved me weeks of searching. I found a great settlement within walking distance of campus in just one day at an affordable price.”
                    </p>
                </div>

                <div class="card-3d-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-7 shadow-lg space-y-5">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-sm shadow-md">DO</div>
                        <div>
                            <div class="font-bold text-sm text-slate-900 dark:text-slate-100">Daivine Oluoch</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 italic leading-relaxed">
                        “As a student, verified listings and support made the move feel safe. I could focus on classes instead of worrying about housing.”
                    </p>
                </div>

                <div class="card-3d-hover bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-7 shadow-lg space-y-5">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-800 text-white flex items-center justify-center font-black text-sm shadow-md">RM</div>
                        <div>
                            <div class="font-bold text-sm text-slate-900 dark:text-slate-100">Raymond Mwenda</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 italic leading-relaxed">
                        “Money refund on booking rejection is a very good feature which has boost my trust towards unicrib on my money.”
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="py-24 bg-indigo-950 text-white text-center relative overflow-hidden preserve-3d">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(99,102,241,0.2),transparent_60%)]"></div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10">
            <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Ready to secure your next home?</h2>
            <p class="text-indigo-200 text-base sm:text-lg max-w-xl mx-auto leading-relaxed">
                Create your UniCrib account, explore properties, and move into a place that fits your studies and your budget.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 pt-4">
                <a href="{{ url('/register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-9 py-4 rounded-2xl transition transform hover:-translate-y-1 text-sm shadow-xl shadow-indigo-600/40">
                    Get started now
                </a>
                <a href="{{ url('/dashboard') }}" class="text-indigo-200 hover:text-white text-sm font-semibold underline transition">
                    View properties on the dashboard
                </a>
            </div>
        </div>
    </section>
@endsection
