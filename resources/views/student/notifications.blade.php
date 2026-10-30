{{-- resources/views/student/notifications.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-[#2e2f2c] dark:text-white">
                {{ __('Booking Updates') }}
            </h2>
            <p class="mt-0.5 text-xs font-medium text-[#6a6c6a] dark:text-[#cad8e5]">
                Track the status of your housing requests and booking confirmations.
            </p>
        </div>
    </x-slot>

    <div class="py-6 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 transition-colors duration-200">
                
                @if($user && $user->category === 'student')
                    <div class="border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4 mb-6">
                        <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white tracking-tight">Booking updates</h3>
                    </div>

                    <div class="space-y-6">
                        @forelse($notifications as $notification)
                            <div class="flex flex-col sm:flex-row gap-4 p-4 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl bg-white dark:bg-[#2e2f2c]/50 hover:border-[#8da5c0] hover:shadow-md transition-all">
                                
                                {{-- Property Thumbnail --}}
                                <div class="flex-shrink-0">
                                   @if(($notification->property)->images && count(($notification->property)->images) > 0)
                                        <img class="w-full sm:w-32 h-28 rounded-xl object-cover border border-[#cad8e5] dark:border-[#6a6c6a]"
                                            src="{{ asset('storage/' . ($notification->property)->images[0]) }}"
                                            alt="{{ ($notification->property)->building_name }}"
                                            onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236a6c6a\'><rect width=\'100%\' height=\'100%\' fill=\'%23dae4ee\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 0 02-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 02 2z\'/></svg>';">
                                    @else
                                        <div class="w-full sm:w-32 h-28 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] flex items-center justify-center text-[#6a6c6a] dark:text-[#aabdd2]">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Notification Details --}}
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                            <h4 class="text-sm font-bold text-[#2e2f2c] dark:text-white">
                                                {{ optional($notification->property)->building_name }}
                                            </h4>
                                            <span class="text-xs font-bold text-[#2e2f2c] dark:text-[#aabdd2]">
                                                KES {{ number_format(optional($notification->property)->price) }}
                                            </span>
                                        </div>

                                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-1">
                                            Landlord Phone: 
                                            <a href="tel:{{ optional($notification->landlord)->phone }}" class="text-[#2e2f2c] dark:text-white font-semibold hover:underline">
                                                {{ optional($notification->landlord)->phone ?? 'Phone not set' }}
                                            </a>
                                        </p>

                                        <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5] mt-2">
                                            {{ $notification->data['message'] ?? 'Update on your booking' }}
                                        </p>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-[#cad8e5] dark:border-[#6a6c6a] flex flex-wrap items-center justify-between gap-2">
                                        <div class="text-[11px] font-medium text-[#6a6c6a] dark:text-[#aabdd2] space-x-2">
                                            <span>Requested: {{ $notification->created_at->format('d M Y, H:i') }}</span>
                                        </div>

                                        {{-- Status Badges --}}
                                        <div>
                                            @if($notification->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50">
                                                    Pending
                                                </span>
                                            @elseif($notification->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50">
                                                    Approved, you can move in.
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50">
                                                    Rejected, Proceed searching for another.
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-xs font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">
                                    You have no booking updates yet.
                                </p>
                            </div>
                        @endforelse
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>