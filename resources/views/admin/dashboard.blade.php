{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">Admin Command Center</h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                    Manage UniCrib users, real-time listings, bookings, transport slots, and storage operations.
                </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#cad8e5] text-[#2e2f2c] hover:bg-[#aabdd2] transition shadow-sm">
                    Manage Users
                </a>
                <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#cad8e5] text-[#2e2f2c] hover:bg-[#aabdd2] transition shadow-sm">
                    Manage Properties
                </a>
                <a href="{{ route('admin.storage.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-white dark:bg-[#6a6c6a]/30 text-[#2e2f2c] dark:text-white border border-[#cad8e5] dark:border-[#6a6c6a] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]/50 transition shadow-sm">
                    Store Housing
                </a>
            </div>
        </div>
    </x-slot>

    @push('scripts')
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    @endpush

    <div class="py-8 bg-[#dae4ee] dark:bg-[#2e2f2c] min-h-screen transition-colors duration-200" 
         x-data="{ darkMode: localStorage.getItem('dark') === 'true', activeTab: 'overview' }" 
         :class="{ 'dark': darkMode }"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
         
        <script>
            if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Admin Navigation Tabs --}}
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-3 shadow-sm flex items-center justify-between gap-2 overflow-x-auto">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                        Users ({{ $totalUsers ?? 0 }})
                    </a>
                    <a href="{{ route('admin.properties.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.properties.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                        Properties ({{ $totalProperties ?? 0 }})
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.bookings.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                        Bookings ({{ $totalBookings ?? 0 }})
                    </a>
                    <a href="{{ route('admin.transports.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.transports.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                        Transports
                    </a>
                    <a href="{{ route('admin.storage.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.storage.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                        Store Housing
                    </a>
                </div>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4 transition hover:border-[#8da5c0]">
                    <div class="w-12 h-12 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] text-[#2e2f2c] dark:text-[#aabdd2] flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Total users</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $totalUsers }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.properties.index') }}" class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4 transition hover:border-[#8da5c0]">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Total properties</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $totalProperties }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.properties.index') }}" class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4 transition hover:border-[#8da5c0]">
                    <div class="w-12 h-12 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] text-[#2e2f2c] dark:text-[#aabdd2] flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Verified properties</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $verifiedProperties }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.bookings.index') }}" class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4 transition hover:border-[#8da5c0]">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Total bookings</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $totalBookings }}</p>
                    </div>
                </a>
            </div>

            {{-- Management Grid: Two columns for compact monitoring --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Recent Users --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-3">
                        <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white flex items-center gap-2">
                            <span>👥 Recent Users</span>
                        </h3>
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">View All →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px] border-b border-[#cad8e5]/50 dark:border-[#6a6c6a]/50">
                                    <th class="py-2 px-3 font-semibold">Name</th>
                                    <th class="py-2 px-3 font-semibold">Category</th>
                                    <th class="py-2 px-3 font-semibold">Status</th>
                                    <th class="py-2 px-3 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#cad8e5]/40 dark:divide-[#6a6c6a]/40">
                            @forelse($recentUsers as $user)
                                <tr class="hover:bg-[#dae4ee]/30 dark:hover:bg-[#2e2f2c]/40 transition">
                                    <td class="py-2.5 px-3 font-medium text-[#2e2f2c] dark:text-white">
                                        {{ $user->name }}
                                        <div class="text-[10px] text-[#6a6c6a] dark:text-[#cad8e5]">{{ $user->email }}</div>
                                    </td>
                                    <td class="py-2.5 px-3 text-[#6a6c6a] dark:text-[#cad8e5]">{{ ucfirst($user->category ?? 'n/a') }}</td>
                                    <td class="py-2.5 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ ($user->status ?? 'active') === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ ucfirst($user->status ?? 'active') }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:underline">Manage</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-[#6a6c6a] text-xs">No users found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Recent Properties --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-3">
                        <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white flex items-center gap-2">
                            <span>🏠 Recent Properties</span>
                        </h3>
                        <a href="{{ route('admin.properties.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">View All →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px] border-b border-[#cad8e5]/50 dark:border-[#6a6c6a]/50">
                                    <th class="py-2 px-3 font-semibold">Title</th>
                                    <th class="py-2 px-3 font-semibold">Landlord</th>
                                    <th class="py-2 px-3 font-semibold">Verified</th>
                                    <th class="py-2 px-3 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#cad8e5]/40 dark:divide-[#6a6c6a]/40">
                            @forelse($recentProperties as $property)
                                <tr class="hover:bg-[#dae4ee]/30 dark:hover:bg-[#2e2f2c]/40 transition">
                                    <td class="py-2.5 px-3 font-medium text-[#2e2f2c] dark:text-white">{{ $property->title ?? $property->building_name }}</td>
                                    <td class="py-2.5 px-3 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($property->landlord)->name ?? 'N/A' }}</td>
                                    <td class="py-2.5 px-3">
                                        @if($property->is_verified)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700">Verified</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700">Pending</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <a href="{{ route('admin.properties.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:underline">Verify</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-[#6a6c6a] text-xs">No properties found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Recent Bookings --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-3">
                        <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white flex items-center gap-2">
                            <span>📋 Recent Bookings</span>
                        </h3>
                        <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">View All →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px] border-b border-[#cad8e5]/50 dark:border-[#6a6c6a]/50">
                                    <th class="py-2 px-3 font-semibold">ID</th>
                                    <th class="py-2 px-3 font-semibold">Student</th>
                                    <th class="py-2 px-3 font-semibold">Status</th>
                                    <th class="py-2 px-3 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#cad8e5]/40 dark:divide-[#6a6c6a]/40">
                            @forelse($recentBookings as $booking)
                                <tr class="hover:bg-[#dae4ee]/30 dark:hover:bg-[#2e2f2c]/40 transition">
                                    <td class="py-2.5 px-3 font-medium text-[#2e2f2c] dark:text-white">#{{ $booking->id }}</td>
                                    <td class="py-2.5 px-3 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($booking->student)->name ?? 'N/A' }}</td>
                                    <td class="py-2.5 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $booking->status === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:underline">Inspect</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-[#6a6c6a] text-xs">No bookings yet.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Recent Storage & Transport --}}
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-3">
                        <h3 class="text-sm font-bold text-[#2e2f2c] dark:text-white flex items-center gap-2">
                            <span>📦 Storage &amp; Transport</span>
                        </h3>
                        <a href="{{ route('admin.storage.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">Manage Store →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px] border-b border-[#cad8e5]/50 dark:border-[#6a6c6a]/50">
                                    <th class="py-2 px-3 font-semibold">Type</th>
                                    <th class="py-2 px-3 font-semibold">User</th>
                                    <th class="py-2 px-3 font-semibold">Status</th>
                                    <th class="py-2 px-3 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#cad8e5]/40 dark:divide-[#6a6c6a]/40">
                            @forelse($recentStorageBookings as $storage)
                                <tr class="hover:bg-[#dae4ee]/30 dark:hover:bg-[#2e2f2c]/40 transition">
                                    <td class="py-2.5 px-3 font-medium text-[#2e2f2c] dark:text-white">Storage #{{ $storage->id }}</td>
                                    <td class="py-2.5 px-3 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($storage->user)->name ?? 'N/A' }}</td>
                                    <td class="py-2.5 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                                            {{ ucfirst($storage->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <a href="{{ route('admin.storage.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:underline">Update</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-[#6a6c6a] text-xs">No storage logs yet.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>