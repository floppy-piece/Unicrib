@extends('layouts.public')

@section('title', 'About – ' . config('app.name'))

@section('content')
    {{-- HERO --}}
    <section class="bg-indigo-900 text-white py-20 px-4 relative overflow-hidden" style="background-image: linear-gradient(rgba(15,23,42,0.92), rgba(15,23,42,0.96)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-800/80 border border-indigo-700 text-xs font-semibold text-indigo-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>ABOUT UNICRIB • Built for serious student life</span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-bold tracking-tight leading-tight">
                        The infrastructure behind <span class="text-indigo-400 underline decoration-indigo-500/50">student housing</span> in Kenya.
                    </h1>

                    <p class="text-indigo-200 text-base sm:text-lg max-w-2xl font-normal">
                        UniCrib is a student‑first housing and move platform built to make finding, securing,
                        and living in off‑campus accommodation feel as predictable as registering for classes.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 border-t border-indigo-800/80">
                        <div>
                            <div class="text-xl sm:text-2xl font-bold text-white">End‑to‑end</div>
                            <div class="text-xs text-indigo-300 mt-0.5">Discovery, verification &amp; moves</div>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-bold text-amber-400">Student‑first</div>
                            <div class="text-xs text-indigo-300 mt-0.5">Designed around campus life</div>
                        </div>
                        <div>
                            <div class="text-xl sm:text-2xl font-bold text-white">Built to scale</div>
                            <div class="text-xs text-indigo-300 mt-0.5">A platform worth satisfaction</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 space-y-4">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-white shadow-xl space-y-2">
                        <h3 class="font-bold text-lg text-white">From one campus to a continent</h3>
                        <p class="text-sm text-indigo-100">
                            UniCrib starts where demand is most intense—Kenyan university towns—then scales
                            across Kenyan campuses with the same promise: verified homes, safer moves, and
                            serious tools for landlords.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- MISSION & VISION --}}
    <section class="py-20 bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Mission &amp; vision</span>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Why UniCrib exists</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">
                    Students shouldn’t gamble with safety, time, or money just to find a place to sleep.
                    UniCrib gives housing the same structure and polish as a world‑class digital product.
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-8 shadow-sm space-y-4 text-slate-600 dark:text-slate-300 text-base leading-relaxed transition-colors duration-200">
                <p>
                    Across Kenyan campuses, student housing is still fragmented: screenshots, WhatsApp groups,
                    “ask a friend of a friend”, and last‑minute moves that depend on luck more than process.
                </p>
                <p>
                    UniCrib was created to change that by combining a verified housing marketplace with move,
                    storage, and landlord tools—all tuned to the realities of campus life and local cities.
                </p>
                <p>
                    The long‑term vision is clear: a category‑defining platform that quietly
                    powers student housing, logistics, and payments across the continent.
                </p>
            </div>
        </div>
    </section>

    {{-- WHAT WE SOLVE --}}
    <section class="py-20 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">What we solve</span>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">A better experience for every side</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">
                    UniCrib sits at the centre of students, landlords, and universities—aligning everyone around
                    verified information, clear expectations, and smooth operations.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-3">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">For students</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        Verified properties, real photos, campus‑centric search, and optional move &amp; storage support
                        so you spend more time learning and less time negotiating.
                    </p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-3">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">For landlords</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        A professional channel for serious student tenants, smart dashboards for listings and
                        availability, and a brand that signals trust and quality.
                    </p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-3">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">For universities</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        A trusted off‑campus partner that raises safety and satisfaction without requiring
                        the university to build, host, and run its own tech stack.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- PRODUCT DNA --}}
    <section class="py-20 bg-slate-50 dark:bg-slate-950 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-4">
                    <span class="inline-block bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-900/50">Product DNA</span>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                        Student‑first, landlord‑serious, investor‑grade.
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 text-base">
                        UniCrib is designed with students in mind, built with landlord discipline, and architected
                        at a level that can support institutional partners and investors.
                    </p>
                </div>
                <div class="bg-indigo-900 text-white rounded-2xl p-8 shadow-lg border border-indigo-800 space-y-3">
                    <div class="text-xs font-bold uppercase tracking-wider text-indigo-300">Where UniCrib is going</div>
                    <p class="text-indigo-100 text-base leading-relaxed">
                        Today, UniCrib helps students discover and secure better homes. Tomorrow, the same rails
                        can power credit history, insurance, community, and all the infrastructure around young
                        people building real lives—starting from where they live.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
