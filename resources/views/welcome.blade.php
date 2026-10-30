{{-- resources/views/welcome.blade.php --}}
@extends('layouts.public')

@section('title', config('app.name') . ' – Premium Student Housing')

@section('content')
    {{-- HERO --}}
    <section class="text-[#2e2f2c] dark:text-white py-24 px-4 relative overflow-hidden preserve-3d" style="background-color: #2e2f2c; background-image: linear-gradient(rgba(46,47,44,0.92), rgba(46,47,44,0.96)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#8da5c0]/20 rounded-full blur-3xl pointer-events-none animate-pulse-glow"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[#6a6c6a]/30 rounded-full blur-3xl pointer-events-none animate-pulse-glow"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#6a6c6a]/80 border border-[#8da5c0]/60 text-xs font-bold text-[#cad8e5] shadow-lg backdrop-blur">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#aabdd2] animate-ping"></div>
                        ELITE ACCOMMODATIONS • Built for University Life
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight text-white">
                        Find your perfect <i>student </i>home.
                    </h1>

                    <p class="text-[#cad8e5] text-base sm:text-lg max-w-2xl font-normal leading-relaxed">
                        Discover carefully curated student accommodations near your university.
                        UniCrib gives you transparent options, real details, and a smoother way
                        to secure your next home with AI-powered WhatsApp tools.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ url('/register') }}" class="bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] font-bold px-8 py-4 rounded-2xl shadow-xl transition transform hover:-translate-y-1 text-sm">
                            Get started – it’s free
                        </a>
                        <a href="{{ url('/login') }}" class="bg-[#6a6c6a]/50 hover:bg-[#6a6c6a] text-white border border-[#8da5c0]/40 font-bold px-6 py-4 rounded-2xl transition transform hover:-translate-y-1 text-sm backdrop-blur">
                            Connect via WhatsApp
                        </a>
                    </div>

                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-[#6a6c6a]">
                        <div class="card-3d-hover p-3 rounded-xl bg-[#6a6c6a]/40 border border-[#8da5c0]/30 backdrop-blur">
                            <div class="text-xl sm:text-2xl font-black text-white">4.9 / 5</div>
                            <div class="text-xs text-[#cad8e5] mt-0.5 font-medium">Average student rating</div>
                        </div>
                        <div class="card-3d-hover p-3 rounded-xl bg-[#6a6c6a]/40 border border-[#8da5c0]/30 backdrop-blur">
                            <div class="text-xl sm:text-2xl font-black text-[#aabdd2]">{{ $totalProperties }}+</div>
                            <div class="text-xs text-[#cad8e5] mt-0.5 font-medium">Verified properties</div>
                        </div>
                        <div class="card-3d-hover p-3 rounded-xl bg-[#6a6c6a]/40 border border-[#8da5c0]/30 backdrop-blur">
                            <div class="text-xl sm:text-2xl font-black text-white">24/7</div>
                            <div class="text-xs text-[#cad8e5] mt-0.5 font-medium">AI &amp; student support</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 space-y-4 animate-float-3d">
                    <div class="card-3d-hover bg-[#6a6c6a]/40 backdrop-blur-xl border border-[#8da5c0]/30 rounded-3xl p-7 text-white shadow-2xl">
                        <h3 class="font-bold text-lg text-white mb-1">Campus proximity</h3>
                        <p class="text-xs text-[#aabdd2] uppercase tracking-wider font-bold mb-2">Walking distance</p>
                        <p class="text-sm text-[#cad8e5] leading-relaxed">
                            Short walks, safe routes. Many listings are within a 10–15 minute walk to campus gates with instant WhatsApp tour support.
                        </p>
                    </div>

                    <div class="card-3d-hover bg-[#6a6c6a]/40 backdrop-blur-xl border border-[#8da5c0]/30 rounded-3xl p-7 text-white shadow-2xl">
                        <h3 class="font-bold text-lg text-white mb-1">Verified &amp; secure</h3>
                        <p class="text-xs text-[#aabdd2] uppercase tracking-wider font-bold mb-2">Trust &amp; safety</p>
                        <p class="text-sm text-[#cad8e5] leading-relaxed">
                            Every property is reviewed and verified, so you can focus on your studies, not scams. Backed by guaranteed payment refunds.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="py-24 bg-[#dae4ee] dark:bg-[#2e2f2c] border-b border-[#aabdd2] dark:border-[#6a6c6a] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <div class="inline-block text-xs font-extrabold tracking-widest text-[#2e2f2c] dark:text-[#aabdd2] uppercase bg-[#cad8e5] dark:bg-[#6a6c6a] px-4 py-1.5 rounded-full border border-[#aabdd2] dark:border-[#8da5c0] shadow-sm">Why UniCrib</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#2e2f2c] dark:text-white">A simpler way to secure student housing</h2>
                <p class="text-[#6a6c6a] dark:text-[#cad8e5] text-sm sm:text-base">UniCrib removes the noise from the search process. You get relevant options, clear information, and support from enquiry to move‑in.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="card-3d-hover bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-3xl p-7 shadow-lg space-y-4">
                    <div class="inline-block bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#aabdd2] text-xs font-bold px-3 py-1 rounded-full border border-[#aabdd2] dark:border-[#6a6c6a]">Curated selection</div>
                    <p class="text-sm text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">Every listing is reviewed for quality, location, and suitability for student life.</p>
                </div>
                <div class="card-3d-hover bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-3xl p-7 shadow-lg space-y-4">
                    <div class="inline-block bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#aabdd2] text-xs font-bold px-3 py-1 rounded-full border border-[#aabdd2] dark:border-[#6a6c6a]">Time‑saving</div>
                    <p class="text-sm text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">Filter by budget, distance, and preferences. Shortlist in minutes instead of weeks.</p>
                </div>
                <div class="card-3d-hover bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-3xl p-7 shadow-lg space-y-4">
                    <div class="inline-block bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#aabdd2] text-xs font-bold px-3 py-1 rounded-full border border-[#aabdd2] dark:border-[#6a6c6a]">Student community</div>
                    <p class="text-sm text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">Discover shared housing, connect with roommates, and build your campus network.</p>
                </div>
                <div class="card-3d-hover bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-3xl p-7 shadow-lg space-y-4">
                    <div class="inline-block bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#aabdd2] text-xs font-bold px-3 py-1 rounded-full border border-[#aabdd2] dark:border-[#6a6c6a]">24/7 support</div>
                    <p class="text-sm text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">Our team and WhatsApp AI agent are available around the clock to help with viewings and issues.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURED PROPERTIES --}}
    <section class="py-24 bg-[#cad8e5]/40 dark:bg-[#2e2f2c] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <div class="inline-block text-xs font-extrabold tracking-widest text-[#2e2f2c] dark:text-[#aabdd2] uppercase bg-[#dae4ee] dark:bg-[#6a6c6a] px-4 py-1.5 rounded-full border border-[#aabdd2] dark:border-[#8da5c0] shadow-sm">Featured properties</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#2e2f2c] dark:text-white">A closer look at our accommodations</h2>
                <p class="text-[#6a6c6a] dark:text-[#cad8e5] text-sm sm:text-base">A taste of the verified student homes available near major universities.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($properties as $property)
                <div class="card-3d-hover bg-white dark:bg-[#6a6c6a]/30 rounded-3xl border border-[#cad8e5] dark:border-[#8da5c0]/40 overflow-hidden shadow-lg flex flex-col">
                    <div class="relative bg-[#dae4ee] dark:bg-[#2e2f2c] p-6 text-center">
                        @if($property->images && count($property->images) > 0)
                            <img class="w-full h-40 object-cover rounded-xl shadow-inner mx-auto transform hover:scale-105 transition duration-500"
                                src="{{ asset('storage/' . $property->images[0]) }}"
                                alt="{{ $property->title }}"
                                onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236a6c6a\'><rect width=\'100%\' height=\'100%\' fill=\'%23dae4ee\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg>';">
                        @else
                            <div class="w-full h-40 flex items-center justify-center bg-[#cad8e5] dark:bg-[#2e2f2c] rounded-xl text-[#6a6c6a] dark:text-[#aabdd2]">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-6 left-6 bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] text-xs font-extrabold px-3.5 py-1.5 rounded-xl shadow-lg">Ksh{{ number_format($property->price) }}</div>
                    </div>

                    <div class="p-7 flex flex-col flex-grow space-y-4">
                        <h3 class="text-xl font-extrabold text-[#2e2f2c] dark:text-white">{{ $property->building_name }}</h3>                        
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] font-medium">Walking distance to campus</p>

                        <div class="flex justify-between items-center text-xs text-[#6a6c6a] dark:text-[#cad8e5] pt-3 border-t border-[#dae4ee] dark:border-[#6a6c6a]">
                            <span class="font-semibold">{{ $property->property_type }}</span>
                            <span class="bg-[#dae4ee] dark:bg-[#2e2f2c] text-[#2e2f2c] dark:text-[#aabdd2] font-bold px-3 py-1 rounded-xl text-xs border border-[#aabdd2] dark:border-[#6a6c6a]">{{ $property->title }}</span>
                        </div>                     
                
                        <a href="{{ route('properties.show', $property) }}" class="mt-auto bg-[#2e2f2c] hover:bg-[#6a6c6a] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] dark:hover:bg-[#aabdd2] text-xs font-bold py-3 px-5 rounded-2xl transition shadow-md text-center">
                            View details
                        </a>
                    </div>
                </div>
                @empty
                    <div class="col-span-3 text-center py-16 bg-white dark:bg-[#6a6c6a]/30 rounded-3xl border border-[#cad8e5] dark:border-[#8da5c0]/40 shadow-sm">
                        <p class="text-[#6a6c6a] dark:text-[#cad8e5] text-sm font-medium">No verified properties are available right now. Check back soon or list your property!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <section class="py-20 bg-[#2e2f2c] text-white relative overflow-hidden border-t border-b border-[#6a6c6a]">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(141,165,192,0.15),transparent_50%)]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12 relative z-10">
            <div class="max-w-2xl mx-auto space-y-3">
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Trusted by students and landlords</h2>
                <p class="text-[#cad8e5] text-sm">UniCrib is growing with every intake. The numbers below represent real matches between students and verified homes.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="card-3d-hover p-6 rounded-3xl bg-[#6a6c6a]/40 border border-[#8da5c0]/30 backdrop-blur space-y-2">
                    <div class="text-3xl sm:text-4xl font-black text-[#aabdd2]">{{ number_format($totalStudents) }}+</div>
                    <div class="text-xs text-[#cad8e5] uppercase tracking-wider font-bold">Students served</div>
                </div>
                <div class="card-3d-hover p-6 rounded-3xl bg-[#6a6c6a]/40 border border-[#8da5c0]/30 backdrop-blur space-y-2">
                    <div class="text-3xl sm:text-4xl font-black text-[#aabdd2]">{{ number_format($totalProperties) }}+</div>
                    <div class="text-xs text-[#cad8e5] uppercase tracking-wider font-bold">Active properties</div>
                </div>
                <div class="card-3d-hover p-6 rounded-3xl bg-[#6a6c6a]/40 border border-[#8da5c0]/30 backdrop-blur space-y-2">
                    <div class="text-3xl sm:text-4xl font-black text-[#aabdd2]">1+</div>
                    <div class="text-xs text-[#cad8e5] uppercase tracking-wider font-bold">Universities covered</div>
                </div>
                <div class="card-3d-hover p-6 rounded-3xl bg-[#6a6c6a]/40 border border-[#8da5c0]/30 backdrop-blur space-y-2">
                    <div class="text-3xl sm:text-4xl font-black text-[#aabdd2]">98.9%</div>
                    <div class="text-xs text-[#cad8e5] uppercase tracking-wider font-bold">Satisfaction rating</div>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="py-24 bg-[#dae4ee] dark:bg-[#2e2f2c] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <div class="inline-block text-xs font-extrabold tracking-widest text-[#2e2f2c] dark:text-[#aabdd2] uppercase bg-[#cad8e5] dark:bg-[#6a6c6a] px-4 py-1.5 rounded-full border border-[#aabdd2] dark:border-[#8da5c0] shadow-sm">Student feedback</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#2e2f2c] dark:text-white">Students share their UniCrib experience</h2>
                <p class="text-[#6a6c6a] dark:text-[#cad8e5] text-sm sm:text-base">Real stories from students who secured housing quickly and confidently using UniCrib.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card-3d-hover bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-3xl p-7 shadow-lg space-y-5">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] flex items-center justify-center font-black text-sm shadow-md">BO</div>
                        <div>
                            <div class="font-bold text-sm text-[#2e2f2c] dark:text-white">Benson Okwemba</div>
                            <div class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="text-sm text-[#6a6c6a] dark:text-[#cad8e5] italic leading-relaxed">
                        “UniCrib saved me weeks of searching. I found a great settlement within walking distance of campus in just one day at an affordable price.”
                    </p>
                </div>

                <div class="card-3d-hover bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-3xl p-7 shadow-lg space-y-5">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] flex items-center justify-center font-black text-sm shadow-md">DO</div>
                        <div>
                            <div class="font-bold text-sm text-[#2e2f2c] dark:text-white">Daivine Oluoch</div>
                            <div class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="text-sm text-[#6a6c6a] dark:text-[#cad8e5] italic leading-relaxed">
                        “As a student, verified listings and support made the move feel safe. I could focus on classes instead of worrying about housing.”
                    </p>
                </div>

                <div class="card-3d-hover bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-3xl p-7 shadow-lg space-y-5">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] flex items-center justify-center font-black text-sm shadow-md">RM</div>
                        <div>
                            <div class="font-bold text-sm text-[#2e2f2c] dark:text-white">Raymond Mwenda</div>
                            <div class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">Kirinyaga University</div>
                        </div>
                    </div>
                    <p class="text-sm text-[#6a6c6a] dark:text-[#cad8e5] italic leading-relaxed">
                        “Money refund on booking rejection is a very good feature which has boost my trust towards unicrib on my money.”
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="py-24 bg-[#2e2f2c] text-white text-center relative overflow-hidden preserve-3d border-t border-[#6a6c6a]">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(141,165,192,0.2),transparent_60%)]"></div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10">
            <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Ready to secure your next home?</h2>
            <p class="text-[#cad8e5] text-base sm:text-lg max-w-xl mx-auto leading-relaxed">
                Create your UniCrib account, explore properties, and move into a place that fits your studies and your budget.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 pt-4">
                <a href="{{ url('/register') }}" class="bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] font-bold px-9 py-4 rounded-2xl transition transform hover:-translate-y-1 text-sm shadow-xl">
                    Get started now
                </a>
                <a href="{{ url('/dashboard') }}" class="text-[#aabdd2] hover:text-white text-sm font-semibold underline transition">
                    View properties on the dashboard
                </a>
            </div>
        </div>
    </section>
@endsection