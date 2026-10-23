@extends('layouts.public')

@section('title', 'Services – ' . config('app.name'))

@section('content')
    {{-- HERO --}}
    <section class="relative bg-indigo-950 text-white py-20 px-4 overflow-hidden" style="background-image: linear-gradient(rgba(15,23,42,0.92), rgba(15,23,42,0.96)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-900/80 border border-indigo-700 text-xs font-semibold text-indigo-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>UNICRIB SERVICES • End‑to‑end student housing</span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-bold tracking-tight leading-tight">
                        A full stack of <span class="text-indigo-400 underline decoration-indigo-500/50">student housing</span> services.
                    </h1>

                    <p class="text-indigo-100 text-base sm:text-lg max-w-2xl font-normal">
                        UniCrib is more than listings. It combines a curated housing marketplace, landlord tools,
                        move &amp; storage support, and campus partnerships into a single platform built to scale into
                        a highly satisfactory category leader.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ url('/register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-sm">
                            Get started as a student
                        </a>
                        <a href="{{ url('/login') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-semibold px-6 py-3 rounded-xl transition backdrop-blur-sm">
                            List properties as a landlord
                        </a>
                    </div>

                    <p class="text-xs text-indigo-300">
                        One account unlocks discovery, messaging, move support, and more.
                    </p>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-indigo-900/80 backdrop-blur-md border border-indigo-700/60 rounded-2xl p-6 sm:p-8 text-white shadow-xl space-y-4">
                        <h3 class="font-bold text-base text-white">What UniCrib handles for you</h3>
                        <ul class="space-y-3 text-xs sm:text-sm text-indigo-100">
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-400 font-bold">•</span>
                                <span><strong>Discovery:</strong> campus‑centric search for verified homes</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-400 font-bold">•</span>
                                <span><strong>Operations:</strong> move‑in, move‑out, and storage rails</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-400 font-bold">•</span>
                                <span><strong>Trust:</strong> verification, ratings, and structured payments</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-400 font-bold">•</span>
                                <span><strong>Insights:</strong> data to help universities and landlords plan</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- CORE SERVICES GRID --}}
    <section class="py-20 bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Core services</span>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Built for the full housing journey</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base">
                    From the moment a student starts searching to the day they move out, UniCrib is the operating
                    system behind every step.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                {{-- Service 1 --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-lg shadow-sm">🏠</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Student housing marketplace</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            A curated catalogue of student‑ready homes with real photos, amenities, and distance‑to‑campus
                            details instead of vague descriptions.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-800 pt-4 list-disc list-inside">
                        <li>Verified listings and landlord profiles</li>
                        <li>Filters for budget, distance, and property type</li>
                        <li>Shortlists to compare options with parents/friends</li>
                    </ul>
                </div>

                {{-- Service 2 --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-lg shadow-sm">📊</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Landlord tools &amp; dashboards</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            A modern control centre for landlords to manage units, availability, and communication with
                            student tenants from any device.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-800 pt-4 list-disc list-inside">
                        <li>Create and edit listings in minutes</li>
                        <li>Track availability, verification, and status</li>
                        <li>View enquiries and respond inside UniCrib</li>
                    </ul>
                </div>

                {{-- Service 3 --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-lg shadow-sm">🚚</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Move‑in &amp; move‑out logistics</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Designed for student realities: small loads, tight timelines, and the chaos of new‑semester weekends.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-800 pt-4 list-disc list-inside">
                        <li>Scheduled and on‑demand moves</li>
                        <li>Coordinated pick‑ups from campus or previous housing</li>
                        <li>Clear pricing before move day</li>
                    </ul>
                </div>

                {{-- Service 4 --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-lg shadow-sm">📦</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Short‑term storage for breaks</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Students can store belongings securely during holidays, internships, or campus transfers instead
                            of re‑starting from scratch.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-800 pt-4 list-disc list-inside">
                        <li>Storage plans matched to academic calendars</li>
                        <li>Pickup, storage, and drop‑off options</li>
                        <li>Digital inventory and reminders</li>
                    </ul>
                </div>

                {{-- Service 5 --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-lg shadow-sm">🤝</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Campus &amp; enterprise solutions</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            University‑level views of off‑campus housing, safety standards, and student flows without building
                            and maintaining custom software.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-800 pt-4 list-disc list-inside">
                        <li>University‑branded UniCrib experiences</li>
                        <li>Curated pools of approved properties</li>
                        <li>Reports on demand trends and pricing</li>
                    </ul>
                </div>

                {{-- Service 6 --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center text-lg shadow-sm">💳</div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Trust, payments &amp; records</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            A safer way to handle rent, deposits, and documentation so that students, parents, and landlords
                            have a single source of truth.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-800 pt-4 list-disc list-inside">
                        <li>Digitised rent and deposit flows</li>
                        <li>Receipts and timelines for every transaction</li>
                        <li>Foundation for future credit and loyalty products</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    {{-- SCALE & IMPACT SPLIT SECTION --}}
    <section class="py-20 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-6 space-y-4">
                    <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase bg-indigo-50 dark:bg-indigo-950/50 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-900/50">Scale &amp; impact</span>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                        Designed for thousands today, millions tomorrow.
                    </h2>
                    <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed">
                        UniCrib’s services are architected to support a single campus launch and then scale to handle
                        entire countries of students and landlords without losing trust or performance.
                    </p>
                </div>

                <div class="lg:col-span-6">
                    <div class="bg-indigo-900 text-white rounded-2xl p-8 shadow-lg border border-indigo-800 space-y-4">
                        <h3 class="text-base font-bold text-white">Partner with UniCrib</h3>
                        <p class="text-xs sm:text-sm text-indigo-100 leading-relaxed">
                            Whether you manage a single building or a university portfolio, UniCrib is the partner that
                            brings product‑grade discipline to student housing. Talk to the team about pilots, integrations,
                            or city‑wide rollouts.
                        </p>
                        <div class="pt-2">
                            <a href="{{ url('/contact') }}" class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-6 py-3 rounded-xl transition shadow-sm">
                                Talk to the UniCrib team
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection