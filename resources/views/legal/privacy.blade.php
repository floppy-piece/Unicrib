@extends('layouts.public')

@section('title', config('app.name') . ' – Premium Student Housing')

@section('content')
    <x-slot name="header">
        <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">Privacy Policy</h2>
        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">Last updated: October 28, 2026</p>
    </x-slot>

    <div class="py-8 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 space-y-6 text-xs text-[#2e2f2c] dark:text-[#cad8e5] leading-relaxed">
            
            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">1. Introduction</h3>
                <p>UniCrib ("we," "our," or "us") operates a student housing, transport, and storage coordination platform in Kenya. We are committed to protecting your personal data in compliance with the <strong>Data Protection Act, 2019</strong>. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website and services.</p>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">2. Information We Collect</h3>
                <p>We collect information that identifies you or can be used to contact you, including:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Account Details:</strong> Full name, email address, phone number, and user category (Student, Landlord, Admin).</li>
                    <li><strong>Verification Data:</strong> Student ID documentation or institutional proof required for platform access.</li>
                    <li><strong>Transaction Data:</strong> Payment details, M-Pesa transaction reference numbers, booking dates, and amounts.</li>
                    <li><strong>Operational Data:</strong> Transport request logs, storage booking schedules, and property preferences.</li>
                </ul>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">3. Legal Basis for Processing (ODPC Compliance)</h3>
                <p>We process your data based on your explicit consent, the necessity of fulfilling our contractual obligations to you (such as processing bookings and transport slots), and our legal obligations under Kenyan law.</p>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">4. Data Sharing and Disclosure</h3>
                <p>We do not sell your personal data. We may share your information only with:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Landlords:</strong> Basic contact and booking details necessary to secure property reservations.</li>
                    <li><strong>Service Partners:</strong> Verified transport and storage providers to fulfill requested logistics.</li>
                    <li><strong>Legal Authorities:</strong> Law enforcement or regulatory bodies when mandated by Kenyan courts or statutory law.</li>
                </ul>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">5. Your Rights</h3>
                <p>Under the Data Protection Act, you have the right to access your personal data, request correction of inaccurate data, request deletion of your data, and withdraw consent at any time by contacting our support team.</p>
            </section>

            <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                <a href="{{ url()->previous() }}" class="font-semibold text-[#2e2f2c] dark:text-white hover:underline">&larr; Back</a>
            </div>
        </div>
    </div>
@endsection