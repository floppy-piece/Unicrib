<?php


use App\Livewire\Actions\Logout;


$logout = function (Logout $logout) {
    $logout();


    $this->redirect('/', navigate: true);
};


?>



<nav x-data="{ open: false }" style="background-color: #1f2937;border-bottom-width: 1px;border-color: #374151;overflow:scroll;">
    <div style="display:flex;justify-content:center;text-align:center;align-items:center;">
        <x-application-logo style="border-radius:50px;height:50px;width:50px;object-fit:cover;"/>                            
        <h1 style="font-size:50px;font-weight:bold;color:#374151;">UniCrib</h1>
    </div>
    <!-- Primary Navigation Menu -->
    <div style="max-width: 80rem;margin-left:auto;margin-right:auto;padding-left:1rem;padding-right:1rem;padding-left:1.5rem;padding-right:1.5rem;padding-left: 2rem;padding-right: 2rem;">
        <div style="display:flex;justify-content:space-between;height:4rem;">
            <div style="display:flex;">
                <!-- Logo -->
                <div style="flex-shrink: 0;align-items: center;">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                       
                    </a>
                </div>


                <!-- Navigation Links -->
                @if (Auth::user()->category==='landlord')
                <div style="flex-shrink: 0;align-items: center;display: none;display: flex;margin-left: 2rem;margin-top: -1px;margin-bottom: -1px;margin-left: 2.5rem;">
                    <x-nav-link :href="route('landlord.dashboard')" :active="request()->routeIs('landlord.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                @endif
                @if (Auth::user()->category==='admin')
                <div style="flex-shrink: 0;align-items: center;display: none;display: flex;margin-left: 2rem;margin-top: -1px;margin-bottom: -1px;margin-left: 2.5rem;">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                @endif

                @if (Auth::user()->category==='student')
                <div style="flex-shrink: 0;align-items: center;display: none;display: flex;margin-left: 2rem;margin-top: -1px;margin-bottom: -1px;margin-left: 2.5rem;">
                    <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                @endif

                @if (Auth::user()->category==='landlord')
                <div style="flex-shrink: 0;align-items: center;display: none;display: flex;margin-left: 2rem;margin-top: -1px;margin-bottom: -1px;margin-left: 2.5rem;">
                    <x-nav-link :href="route('messages')" :active="request()->routeIs('messages')" wire:navigate>
                        {{ __('Tenants') }}
                    </x-nav-link>
                </div>
                @endif

                @if (Auth::user()->category==='student')
                <div style="flex-shrink: 0;align-items: center;display: none;display: flex;margin-left: 2rem;margin-top: -1px;margin-bottom: -1px;margin-left: 2.5rem;">
                    <x-nav-link :href="route('student.notifications')" :active="request()->routeIs('student.notifications')" wire:navigate>
                        {{ __('Requests') }}
                    </x-nav-link>
                </div>
                @endif

                @if (Auth::user()->category==='landlord')
                    <div style="flex-shrink: 0;align-items: center;display: none;display: flex;margin-left: 2rem;margin-top: -1px;margin-bottom: -1px;margin-left: 2.5rem;">
                        <x-nav-link :href="route('landlord.properties.myProperties')" :active="request()->routeIs('landlord.properties.myProperties')" wire:navigate>
                            {{ __('My Properties') }}
                        </x-nav-link>
                    </div>
                @endif


                @if (Auth::user()->category==='landlord')
                    <div style="flex-shrink: 0;align-items: center;display: none;display: flex;margin-left: 2rem;margin-top: -1px;margin-bottom: -1px;margin-left: 2.5rem;">
                        <x-nav-link :href="route('landlord.properties.add')" :active="request()->routeIs('landlord.properties.add')" wire:navigate>
                            {{ __('Add Property') }}
                        </x-nav-link>
                    </div>
                    
                @endif
                
                @if (Auth::user()->category==='landlord'||Auth::user()->category==='student'||Auth::user()->category==='admin')
                <div style="flex-shrink: 0;align-items: center;display: none;display: flex;margin-left: 2rem;margin-top: -1px;margin-bottom: -1px;margin-left: 2.5rem;">
                    <x-nav-link :href="route('contact.show')" :active="request()->routeIs('contact.show')" wire:navigate>
                        {{ __('Contact') }}
                    </x-nav-link>
                </div>
                @endif

            </div>                


            <!-- Settings Dropdown -->
            <div style="display:flex;align-items:center;margin-left: 1.5rem;">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#9ca3af'" style="display: inline-flex;padding-left: 0.75rem;padding-right: 0.75rem;padding-top: 0.5rem;padding-bottom: 0.5rem;border-width: 1px;border-color: transparent;font-size: 0.875rem;line-height: 1.25rem;line-height: 1rem;font-weight: 500;border-radius: 0.375rem;color: #6b7280;color: #9ca3af;background-color: #1f2937;outline: 2px solid transparent;outline-offset: 2px;transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);transition-duration: 150ms;transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);transition-duration: 150ms;margin-left: 0.25rem;">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>


                            <div style="margin-left: 0.25rem;">
                                <svg style="fill: currentColor;height: 1rem; width: 1rem;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
                        <button wire:click="logout" style="width:100%;text-align:start;">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>


           
    </div>
</nav>

