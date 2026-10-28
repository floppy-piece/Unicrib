{{-- resources/views/contact.blade.php --}}
@extends('layouts.public')

@section('title', 'Contact – UniCrib')

@section('content')
    {{-- MAIN CONTACT SECTION --}}
    <section class="py-20 bg-[#dae4ee] dark:bg-[#2e2f2c] flex-grow transition-colors duration-200" style="background-color: #2e2f2c; background-image: linear-gradient(rgba(46,47,44,0.92), rgba(46,47,44,0.96)), url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold tracking-widest text-[#aabdd2] uppercase bg-[#6a6c6a]/80 px-3 py-1 rounded-full border border-[#8da5c0]">Contact UniCrib</span>
                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-[#cad8e5]">
                    Talk to the team behind UniCrib.
                </h1>
                <p class="text-[#cad8e5] text-sm sm:text-base">
                    Use the form below for detailed enquiries, or reach us directly via email or phone for urgent issues.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- FORM CONTAINER (Col 7) --}}
                <div class="lg:col-span-7 bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6 transition-colors duration-200">
                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300 font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-1">
                        <h2 class="text-lg font-bold text-[#2e2f2c] dark:text-white">Send Unicrib a message</h2>
                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                            Share a bit about who you are and what you need. We’ll route your message to the right
                            person on the UniCrib team and respond as quickly as possible.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="name" class="block text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]">Full name</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name') }}"
                                    class="w-full rounded-xl border @error('name') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/50 @enderror px-3.5 py-2.5 text-sm text-[#2e2f2c] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8da5c0] focus:bg-white dark:focus:bg-[#2e2f2c] transition"
                                    required
                                    autocomplete="name"
                                >
                                @error('name')
                                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="email" class="block text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]">Email address</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    class="w-full rounded-xl border @error('email') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/50 @enderror px-3.5 py-2.5 text-sm text-[#2e2f2c] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8da5c0] focus:bg-white dark:focus:bg-[#2e2f2c] transition"
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
                                <label for="role" class="block text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]">Who are you?</label>
                                <select
                                    id="role"
                                    name="role"
                                    class="w-full rounded-xl border @error('role') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/50 @enderror px-3.5 py-2.5 text-sm text-[#2e2f2c] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8da5c0] focus:bg-white dark:focus:bg-[#2e2f2c] transition"
                                >
                                    <option value="" class="dark:bg-[#2e2f2c]">Select an option</option>
                                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }} class="dark:bg-[#2e2f2c]">Student</option>
                                    <option value="landlord" {{ old('role') === 'landlord' ? 'selected' : '' }} class="dark:bg-[#2e2f2c]">Landlord / Property manager</option>
                                    <option value="university" {{ old('role') === 'university' ? 'selected' : '' }} class="dark:bg-[#2e2f2c]">University / Institution</option>
                                    <option value="other" {{ old('role') === 'other' ? 'selected' : '' }} class="dark:bg-[#2e2f2c]">Other</option>
                                </select>
                                @error('role')
                                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="subject" class="block text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]">Subject</label>
                                <input
                                    id="subject"
                                    name="subject"
                                    type="text"
                                    value="{{ old('subject') }}"
                                    class="w-full rounded-xl border @error('subject') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/50 @enderror px-3.5 py-2.5 text-sm text-[#2e2f2c] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8da5c0] focus:bg-white dark:focus:bg-[#2e2f2c] transition"
                                    required
                                >
                                @error('subject')
                                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="message" class="block text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5]">How can we help?</label>
                            <textarea
                                id="message"
                                name="message"
                                rows="4"
                                class="w-full rounded-xl border @error('message') border-rose-500 bg-rose-50/30 dark:bg-rose-950/20 @else border-[#cad8e5] dark:border-[#6a6c6a] bg-[#dae4ee]/50 dark:bg-[#2e2f2c]/50 @enderror px-3.5 py-2.5 text-sm text-[#2e2f2c] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8da5c0] focus:bg-white dark:focus:bg-[#2e2f2c] transition resize-y"
                                required
                            >{{ old('message') }}</textarea>
                            <p class="text-[11px] text-[#6a6c6a] dark:text-[#aabdd2]">
                                Please avoid sharing passwords or sensitive financial details. Describe your campus, timelines, and what you need.
                            </p>
                            @error('message')
                                <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <p class="text-[11px] text-[#6a6c6a] dark:text-[#aabdd2]">
                                By submitting, you agree that UniCrib may contact you about this enquiry.
                            </p>
                            <button type="submit" class="w-full sm:w-auto bg-[#cad8e5] hover:bg-[#aabdd2] text-[#2e2f2c] text-xs font-semibold px-6 py-3 rounded-xl transition shadow-sm">
                                Send message
                            </button>
                        </div>
                    </form>
                </div>

                {{-- SIDEBAR INFO CARDS (Col 5) --}}
                <div class="lg:col-span-5 space-y-6">
                    
                    <div class="bg-[#2e2f2c] text-white rounded-2xl p-6 sm:p-8 shadow-md border border-[#6a6c6a] space-y-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#aabdd2] bg-[#6a6c6a]/80 px-2.5 py-1 rounded-md">Direct contact</span>
                        <h3 class="text-lg font-bold">Email or call UniCrib</h3>
                        <p class="text-xs text-[#cad8e5] leading-relaxed">
                            For urgent issues like account access, billing, or live bookings, reach us directly using the links below.
                            On mobile, the call link will open your phone app automatically.
                        </p>
                        
                        <ul class="space-y-2.5 text-xs border-t border-[#6a6c6a] pt-4 text-[#cad8e5]">
                            <li>
                                <strong class="text-white">Email:</strong>
                                <a href="mailto:unicrib@gmail.com?subject=UniCrib%20Enquiry" class="underline text-[#aabdd2] hover:text-white ml-1 font-medium">
                                    unicrib@gmail.com
                                </a>
                            </li>
                            <li>
                                <strong class="text-white">Phone / WhatsApp:</strong>
                                <a href="tel:+254704969006" class="underline text-[#aabdd2] hover:text-white ml-1 font-medium">
                                    +254 704 969 006
                                </a>
                            </li>
                            <li><strong class="text-white">Location:</strong> Kutus – Kirinyaga, Kenya</li>
                        </ul>

                        <div class="pt-2 text-xs flex flex-wrap gap-2 text-[#cad8e5]">
                            <a href="mailto:unicrib@gmail.com?subject=UniCrib%20Support" class="underline hover:text-white">Email support</a>
                            <span>•</span>
                            <a href="tel:+254704969006" class="underline hover:text-white">Tap to call</a>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 transition-colors duration-200">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#2e2f2c] dark:text-[#aabdd2] bg-[#dae4ee] dark:bg-[#2e2f2c] px-2.5 py-1 rounded-md border border-[#aabdd2] dark:border-[#6a6c6a]">Guidance</span>
                        <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">How UniCrib can help</h3>
                        <ul class="space-y-2 text-xs text-[#6a6c6a] dark:text-[#cad8e5] list-disc list-inside">
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
@endsection