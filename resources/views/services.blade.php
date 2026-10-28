{{-- resources/views/services.blade.php --}}
@extends('layouts.public')

@section('title', 'Services – ' . config('app.name'))

@section('content')
    {{-- HERO --}}
    <section class="relative bg-[#2e2f2c] text-white py-20 px-4 overflow-hidden" style="background-color: #2e2f2c; background-image: linear-gradient(rgba(46,47,44,0.92), rgba(46,47,44,0.96)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#6a6c6a]/80 border border-[#8da5c0] text-xs font-semibold text-[#cad8e5]">
                        <span class="w-2 h-2 rounded-full bg-[#aabdd2]"></span>
                        <span>UNICRIB SERVICES • End‑to‑end student housing</span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-bold tracking-tight leading-tight text-white">
                        A full stack of <span class="text-[#aabdd2] underline decoration-[#8da5c0]/50">student housing</span> services.
                    </h1>

                    <p class="text-[#cad8e5] text-base sm:text-lg max-w-2xl font-normal">
                        UniCrib is more than listings. It combines a curated housing marketplace, landlord tools,
                        move &amp; storage support, and campus partnerships into a single platform built to scale into
                        a highly satisfactory category leader.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ url('/register') }}" class="bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] text-sm font-semibold px-6 py-3 rounded-xl transition shadow-sm">
                            Get started as a student
                        </a>
                        <a href="{{ url('/login') }}" class="bg-[#6a6c6a]/40 hover:bg-[#6a6c6a] border border-[#8da5c0]/40 text-white text-sm font-semibold px-6 py-3 rounded-xl transition backdrop-blur-sm">
                            List properties as a landlord
                        </a>
                    </div>

                    <p class="text-xs text-[#aabdd2]">
                        One account unlocks discovery, messaging, move support, and more.
                    </p>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-[#6a6c6a]/40 backdrop-blur-md border border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 text-white shadow-xl space-y-4">
                        <h3 class="font-bold text-base text-white">What UniCrib handles for you</h3>
                        <ul class="space-y-3 text-xs sm:text-sm text-[#cad8e5]">
                            <li class="flex items-start gap-2.5">
                                <span class="text-[#aabdd2] font-bold">•</span>
                                <span><strong>Discovery:</strong> campus‑centric search for verified homes</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-[#aabdd2] font-bold">•</span>
                                <span><strong>Operations:</strong> move‑in, move‑out, and storage rails</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-[#aabdd2] font-bold">•</span>
                                <span><strong>Trust:</strong> verification, ratings, and structured payments</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-[#aabdd2] font-bold">•</span>
                                <span><strong>Insights:</strong> data to help universities and landlords plan</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- CORE SERVICES GRID --}}
    <section class="py-20 bg-[#dae4ee] dark:bg-[#2e2f2c] border-b border-[#aabdd2] dark:border-[#6a6c6a] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold tracking-widest text-[#2e2f2c] dark:text-[#aabdd2] uppercase bg-[#cad8e5] dark:bg-[#6a6c6a] px-3 py-1 rounded-full border border-[#aabdd2] dark:border-[#8da5c0]">Core services</span>
                <h2 class="text-3xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">Built for the full housing journey</h2>
                <p class="text-[#6a6c6a] dark:text-[#cad8e5] text-sm sm:text-base">
                    From the moment a student starts searching to the day they move out, UniCrib is the operating
                    system behind every step.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                {{-- Service 1 --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center text-[#2e2f2c] dark:text-[#aabdd2] shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Student housing marketplace</h3>
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                            A curated catalogue of student‑ready homes with real photos, amenities, and distance‑to‑campus
                            details instead of vague descriptions.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-[#6a6c6a] dark:text-[#cad8e5] border-t border-[#cad8e5] dark:border-[#6a6c6a] pt-4 list-disc list-inside">
                        <li>Verified listings and landlord profiles</li>
                        <li>Filters for budget, distance, and property type</li>
                        <li>Shortlists to compare options with parents/friends</li>
                    </ul>
                </div>

                {{-- Service 2 --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center text-[#2e2f2c] dark:text-[#aabdd2] shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Landlord tools &amp; dashboards</h3>
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                            A modern control centre for landlords to manage units, availability, and communication with
                            student tenants from any device.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-[#6a6c6a] dark:text-[#cad8e5] border-t border-[#cad8e5] dark:border-[#6a6c6a] pt-4 list-disc list-inside">
                        <li>Create and edit listings in minutes</li>
                        <li>Track availability, verification, and status</li>
                        <li>View enquiries and respond inside UniCrib</li>
                    </ul>
                </div>

                {{-- Service 3 --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center text-[#2e2f2c] dark:text-[#aabdd2] shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Move‑in &amp; move‑out logistics</h3>
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                            Designed for student realities: small loads, tight timelines, and the chaos of new‑semester weekends.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-[#6a6c6a] dark:text-[#cad8e5] border-t border-[#cad8e5] dark:border-[#6a6c6a] pt-4 list-disc list-inside">
                        <li>Scheduled and on‑demand moves</li>
                        <li>Coordinated pick‑ups from campus or previous housing</li>
                        <li>Clear pricing before move day</li>
                    </ul>
                </div>

                {{-- Service 4 --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center text-[#2e2f2c] dark:text-[#aabdd2] shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Short‑term storage for breaks</h3>
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                            Students can store belongings securely during holidays, internships, or campus transfers instead
                            of re‑starting from scratch.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-[#6a6c6a] dark:text-[#cad8e5] border-t border-[#cad8e5] dark:border-[#6a6c6a] pt-4 list-disc list-inside">
                        <li>Storage plans matched to academic calendars</li>
                        <li>Pickup, storage, and drop‑off options</li>
                        <li>Digital inventory and reminders</li>
                    </ul>
                </div>

                {{-- Service 5 --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center text-[#2e2f2c] dark:text-[#aabdd2] shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Campus &amp; enterprise solutions</h3>
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                            University‑level views of off‑campus housing, safety standards, and student flows without building
                            and maintaining custom software.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-[#6a6c6a] dark:text-[#cad8e5] border-t border-[#cad8e5] dark:border-[#6a6c6a] pt-4 list-disc list-inside">
                        <li>University‑branded UniCrib experiences</li>
                        <li>Curated pools of approved properties</li>
                        <li>Reports on demand trends and pricing</li>
                    </ul>
                </div>

                {{-- Service 6 --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between transition-colors duration-200">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center text-[#2e2f2c] dark:text-[#aabdd2] shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Trust, payments &amp; records</h3>
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] leading-relaxed">
                            A safer way to handle rent, deposits, and documentation so that students, parents, and landlords
                            have a single source of truth.
                        </p>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-[#6a6c6a] dark:text-[#cad8e5] border-t border-[#cad8e5] dark:border-[#6a6c6a] pt-4 list-disc list-inside">
                        <li>Digitised rent and deposit flows</li>
                        <li>Receipts and timelines for every transaction</li>
                        <li>Foundation for future credit and loyalty products</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    {{-- SCALE & IMPACT SPLIT SECTION --}}
    <section class="py-20 bg-white dark:bg-[#2e2f2c] border-b border-[#cad8e5] dark:border-[#6a6c6a] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-6 space-y-4">
                    <span class="text-xs font-bold tracking-widest text-[#2e2f2c] dark:text-[#aabdd2] uppercase bg-[#cad8e5] dark:bg-[#6a6c6a] px-3 py-1 rounded-full border border-[#aabdd2] dark:border-[#8da5c0]">Scale &amp; impact</span>
                    <h2 class="text-3xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">
                        Designed for thousands today, millions tomorrow.
                    </h2>
                    <p class="text-[#6a6c6a] dark:text-[#cad8e5] text-sm sm:text-base leading-relaxed">
                        UniCrib’s services are architected to support a single campus launch and then scale to handle
                        entire countries of students and landlords without losing trust or performance.
                    </p>
                </div>

                <div class="lg:col-span-6">
                    <div class="bg-[#2e2f2c] text-white rounded-2xl p-8 shadow-lg border border-[#6a6c6a] space-y-4">
                        <h3 class="text-base font-bold text-white">Partner with UniCrib</h3>
                        <p class="text-xs sm:text-sm text-[#cad8e5] leading-relaxed">
                            Whether you manage a single building or a university portfolio, UniCrib is the partner that
                            brings product‑grade discipline to student housing. Talk to the team about pilots, integrations,
                            or city‑wide rollouts.
                        </p>
                        <div class="pt-2">
                            <a href="{{ url('/contact') }}" class="inline-block bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold px-6 py-3 rounded-xl transition shadow-sm">
                                Talk to the UniCrib team
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection