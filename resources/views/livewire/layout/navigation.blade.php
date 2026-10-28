<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    return redirect('/');
};

?>

<nav class="bg-white dark:bg-[#6a6c6a]/30 border-b border-[#cad8e5] dark:border-[#6a6c6a] sticky top-0 z-50 transition-colors duration-200 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Left Side: Logo & Navigation Links -->
            <div class="flex items-center space-x-6">
                <!-- Brand Logo & Name -->
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center space-x-3 group">
                    <div class="h-10 w-10 rounded-full bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#aabdd2] dark:border-[#6a6c6a] flex items-center justify-center overflow-hidden shadow-sm">
                        <x-application-logo class="h-full w-full object-cover" />
                    </div>
                    <span class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white group-hover:text-[#8da5c0] transition">UniCrib</span>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center gap-1.5">
                    @if (Auth::user()->category === 'landlord')
                        <a href="{{ route('landlord.dashboard') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->routeIs('landlord.dashboard') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            <span class="text-current">{{ __('Dashboard') }}</span>
                        </a>
                        <a href="{{ route('messages') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->routeIs('messages') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span class="text-current">{{ __('Tenants') }}</span>
                        </a>
                        <a href="{{ route('landlord.properties.myProperties') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->routeIs('landlord.properties.myProperties') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span class="text-current">{{ __('My Properties') }}</span>
                        </a>
                        <a href="{{ route('landlord.properties.add') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->routeIs('landlord.properties.add') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            <span class="text-current">{{ __('Add Property') }}</span>
                        </a>
                    @endif

                    @if (Auth::user()->category === 'admin')
                        <a href="{{ route('admin.dashboard') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            <span class="text-current">{{ __('Dashboard') }}</span>
                        </a>
                    @endif

                    @if (Auth::user()->category === 'student')
                        <a href="{{ route('student.dashboard') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->routeIs('student.dashboard') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            <span class="text-current">{{ __('Dashboard') }}</span>
                        </a>
                        <a href="{{ route('student.notifications') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->routeIs('student.notifications') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                            <span class="text-current">{{ __('Requests') }}</span>
                        </a>
                        <a href="{{ route('store-housing') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->routeIs('store-housing') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <span class="text-current">{{ __('Store house') }}</span>
                        </a>
                    @endif

                    @if (in_array(Auth::user()->category, ['landlord', 'student', 'admin']))
                        <a href="{{ route('contact.show') }}" wire:navigate
                           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ request()->routeIs('contact.show') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#cad8e5]/60 dark:hover:bg-[#6a6c6a]/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                            <span class="text-current">{{ __('Contact') }}</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right Side: Dark Mode Toggle & Settings Dropdown (Desktop) -->
            <div class="hidden md:flex items-center space-x-3">
                <x-dark-mode-toggle />

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3.5 py-2 border border-[#cad8e5] dark:border-[#6a6c6a] text-xs font-semibold rounded-xl text-[#2e2f2c] dark:text-[#cad8e5] bg-white dark:bg-[#2e2f2c] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white focus:outline-none transition shadow-sm">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            <div class="ml-1.5">
                                <svg class="fill-current h-4 w-4 text-[#6a6c6a] dark:text-[#cad8e5]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu Button & Mobile Drawer Container -->
            <div x-data="{ open: false }" class="relative md:hidden flex items-center space-x-2">
                <x-dark-mode-toggle />

                <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2.5 rounded-xl text-[#2e2f2c] dark:text-[#cad8e5] bg-white dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] focus:outline-none transition shadow-sm" aria-label="Toggle Navigation">
                    <!-- Bars Icon -->
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <!-- Close Icon -->
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Mobile Dropdown Menu Drawer --}}
                <div x-show="open"
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 top-12 w-64 bg-white dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] rounded-2xl shadow-xl py-3 px-2 z-50 flex flex-col space-y-1"
                     style="display: none;">
                    
                    @if (Auth::user()->category === 'landlord')
                        <a href="{{ route('landlord.dashboard') }}" wire:navigate
                           class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('landlord.dashboard') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            <span class="text-current">{{ __('Dashboard') }}</span>
                        </a>
                        <a href="{{ route('messages') }}" wire:navigate
                           class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('messages') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span class="text-current">{{ __('Tenants') }}</span>
                        </a>
                        <a href="{{ route('landlord.properties.myProperties') }}" wire:navigate
                           class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('landlord.properties.myProperties') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span class="text-current">{{ __('My Properties') }}</span>
                        </a>
                        <a href="{{ route('landlord.properties.add') }}" wire:navigate
                           class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('landlord.properties.add') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            <span class="text-current">{{ __('Add Property') }}</span>
                        </a>
                    @endif

                    @if (Auth::user()->category === 'admin')
                        <a href="{{ route('admin.dashboard') }}" wire:navigate
                           class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            <span class="text-current">{{ __('Dashboard') }}</span>
                        </a>
                    @endif

                    @if (Auth::user()->category === 'student')
                        <a href="{{ route('student.dashboard') }}" wire:navigate
                           class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('student.dashboard') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            <span class="text-current">{{ __('Dashboard') }}</span>
                        </a>
                        <a href="{{ route('student.notifications') }}" wire:navigate
                           class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('student.notifications') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                            <span class="text-current">{{ __('Requests') }}</span>
                        </a>
                        <a href="{{ route('store-housing') }}" wire:navigate
                           class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('store-housing') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <span class="text-current">{{ __('Store house') }}</span>
                        </a>
                    @endif

                    @if (in_array(Auth::user()->category, ['landlord', 'student', 'admin']))
                        <a href="{{ route('contact.show') }}" wire:navigate
                           class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('contact.show') ? 'bg-[#2e2f2c] text-white dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 flex-shrink-0"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                            <span class="text-current">{{ __('Contact') }}</span>
                        </a>
                    @endif

                    <div class="border-t border-[#cad8e5] dark:border-[#6a6c6a] pt-3 mt-2 px-3">
                        <div class="font-bold text-xs text-[#2e2f2c] dark:text-white truncate" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                        <div class="font-medium text-[11px] text-[#6a6c6a] dark:text-[#cad8e5] truncate">{{ Auth::user()->email }}</div>

                        <div class="mt-2 space-y-1">
                            <a href="{{ route('profile') }}" wire:navigate
                               class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-[#2e2f2c] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a] hover:text-[#2e2f2c] dark:hover:text-white transition">
                                {{ __('Profile') }}
                            </a>

                            <button wire:click="logout" class="w-full text-start">
                                <span class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-[#6a6c6a]/80 transition">
                                    {{ __('Log Out') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>