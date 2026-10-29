@extends('layouts.public')

@section('title', config('app.name') . ' – Premium Student Housing')

@section('content')
    <x-slot name="header">
        <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">Cookie Policy</h2>
        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">Last updated: October 28, 2026</p>
    </x-slot>

    <div class="py-8 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 space-y-6 text-xs text-[#2e2f2c] dark:text-[#cad8e5] leading-relaxed">
            
            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">1. What Are Cookies?</h3>
                <p>Cookies are small text files stored on your device when you visit our website. They help us remember your preferences (such as dark mode settings and authentication tokens) and analyze platform traffic.</p>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">2. Cookies We Use</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Essential Cookies:</strong> Required for secure login sessions (e.g., Laravel session cookies) and CSRF protection.</li>
                    <li><strong>Functional Cookies:</strong> Stores user preferences like dark/light mode configurations via local storage.</li>
                    <li><strong>Analytics Cookies:</strong> Optional cookies used to monitor site performance and user navigation patterns.</li>
                </ul>
            </section>

            <section class="space-y-2">
                <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white">3. Managing Cookies</h3>
                <p>You can control or delete cookies through your browser settings. Disabling essential cookies may impact your ability to log in or complete bookings on UniCrib.</p>
            </section>

            <div class="pt-4 border-t border-[#cad8e5] dark:border-[#6a6c6a]">
                <a href="{{ url()->previous() }}" class="font-semibold text-[#2e2f2c] dark:text-white hover:underline">&larr; Back</a>
            </div>
        </div>
    </div>
@endsection