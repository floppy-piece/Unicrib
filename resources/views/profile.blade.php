{{-- resources/views/profile.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm transition-colors duration-200">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm transition-colors duration-200">
                <div class="max-w-xl">
                    <livewire:profile.connect-telegram />
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm transition-colors duration-200">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm transition-colors duration-200">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>