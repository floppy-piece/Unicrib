@extends('layouts.public')

@section('title', config('app.name') . ' – Premium Student Housing')

@section('content')
    <x-slot name="header">
        <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">Terms of Service</h2>
        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">Last updated: October 28, 2026</p>
    </x-slot>

    <div class="py-8 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 space-y-6 text-xs text-[#2e2f2c] dark:text-[#cad8e5] leading-relaxed">
            
            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">1. Acceptance of Terms</h3>
                <p>By accessing or using UniCrib, you agree to be bound by these Terms of Service. If you do not agree to all terms, you must immediately discontinue use of the platform.</p>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">2. Nature of Platform & Limitation of Liability</h3>
                <p>UniCrib acts solely as an intermediary digital marketplace connecting students with independent landlords, transport providers, and storage facilities. <strong>We do not own, manage, or control the properties listed.</strong> UniCrib disclaims all liability for disputes between users, property condition discrepancies, lease defaults, or incidents occurring during transport and storage services.</p>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">3. User Accounts and Verification</h3>
                <p>Users must provide accurate, current, and complete registration information. Students must maintain valid institutional verification to access student-exclusive listings. You are responsible for maintaining the confidentiality of your account credentials.</p>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">4. Payments and Refunds</h3>
                <p>All financial transactions, booking deposits, and storage payments processed through the platform are governed by specific booking terms. Refunds are handled on a case-by-case basis subject to landlord approval and platform fee policies.</p>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">5. Governing Law</h3>
                <p>These terms shall be governed by and construed in accordance with the laws of the Republic of Kenya. Any legal disputes arising from these terms shall be subject to the exclusive jurisdiction of the courts of Kenya.</p>
            </section>

            <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                <a href="{{ url()->previous() }}" class="font-semibold text-[#2e2f2c] dark:text-white hover:underline">&larr; Back</a>
            </div>
        </div>
    </div>
@endsection