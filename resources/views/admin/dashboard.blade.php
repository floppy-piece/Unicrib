{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#2e2f2c] dark:text-white">Admin Dashboard</h2>
                <p class="text-xs text-[#6a6c6a] dark:text-[#cad8e5]">
                    Global overview of users, properties, bookings, transport, and storage requests.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#cad8e5] text-[#2e2f2c] hover:bg-[#aabdd2] transition shadow-sm">
                    Manage Users
                </a>
                <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-white dark:bg-[#6a6c6a]/30 text-[#2e2f2c] dark:text-white border border-[#cad8e5] dark:border-[#6a6c6a] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]/50 transition shadow-sm">
                    Manage Properties
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
         x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
         :class="{ 'dark': darkMode }"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
         
        <script>
            if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Admin Navigation Tabs --}}
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-3 shadow-sm flex items-center gap-2 overflow-x-auto">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Users
                </a>
                <a href="{{ route('admin.properties.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.properties.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Properties
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.bookings.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Bookings
                </a>
                <a href="{{ route('admin.transports.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.transports.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Transports
                </a>
                <a href="{{ route('admin.storage.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request()->routeIs('admin.storage.*') ? 'bg-[#cad8e5] text-[#2e2f2c] dark:bg-[#cad8e5] dark:text-[#2e2f2c] shadow-sm' : 'text-[#6a6c6a] dark:text-[#cad8e5] hover:bg-[#dae4ee] dark:hover:bg-[#6a6c6a]' }}">
                    Store Housing
                </a>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4 transition-colors duration-200">
                    <div class="w-12 h-12 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] text-[#2e2f2c] dark:text-[#aabdd2] flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Total users</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $totalUsers }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4 transition-colors duration-200">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Total properties</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $totalProperties }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4 transition-colors duration-200">
                    <div class="w-12 h-12 rounded-xl bg-[#dae4ee] dark:bg-[#2e2f2c] border border-[#cad8e5] dark:border-[#6a6c6a] text-[#2e2f2c] dark:text-[#aabdd2] flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Verified properties</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $verifiedProperties }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl p-5 shadow-sm flex items-center space-x-4 transition-colors duration-200">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-[#6a6c6a] dark:text-[#cad8e5]">Total bookings</p>
                        <p class="text-2xl font-bold text-[#2e2f2c] dark:text-white mt-0.5">{{ $totalBookings }}</p>
                    </div>
                </div>

            </div>

            {{-- Recent users --}}
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 space-y-4 transition-colors duration-200">
                <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Recent users</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">Manage users →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#cad8e5] dark:border-[#6a6c6a] text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">Name</th>
                                <th class="py-3 px-4 font-semibold">Email</th>
                                <th class="py-3 px-4 font-semibold">Category</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                        @forelse($recentUsers as $user)
                            <tr class="hover:bg-[#dae4ee]/40 dark:hover:bg-[#2e2f2c]/50 transition">
                                <td class="py-3 px-4 font-medium text-[#2e2f2c] dark:text-white">{{ $user->name }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ $user->email }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ ucfirst($user->category ?? 'n/a') }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ ($user->status ?? 'active') === 'active' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800' }}">
                                        {{ ucfirst($user->status ?? 'active') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 font-medium transition text-xs">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-[#6a6c6a] dark:text-[#cad8e5] text-xs">No users yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent properties --}}
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 space-y-4 transition-colors duration-200">
                <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Recent properties</h3>
                    <a href="{{ route('admin.properties.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">Manage properties →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#cad8e5] dark:border-[#6a6c6a] text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">Title</th>
                                <th class="py-3 px-4 font-semibold">Landlord</th>
                                <th class="py-3 px-4 font-semibold">Area</th>
                                <th class="py-3 px-4 font-semibold">Verified</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                        @forelse($recentProperties as $property)
                            <tr class="hover:bg-[#dae4ee]/40 dark:hover:bg-[#2e2f2c]/50 transition">
                                <td class="py-3 px-4 font-medium text-[#2e2f2c] dark:text-white">{{ $property->title }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($property->landlord)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ ucfirst($property->area ?? 'n/a') }}</td>
                                <td class="py-3 px-4">
                                    @if($property->is_verified)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Verified</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">Not verified</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 text-center text-[#6a6c6a] dark:text-[#cad8e5] text-xs">No properties yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent bookings --}}
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 space-y-4 transition-colors duration-200">
                <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Recent bookings</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">Manage bookings →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#cad8e5] dark:border-[#6a6c6a] text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Student</th>
                                <th class="py-3 px-4 font-semibold">Property</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-[#dae4ee]/40 dark:hover:bg-[#2e2f2c]/50 transition">
                                <td class="py-3 px-4 font-medium text-[#2e2f2c] dark:text-white">#{{ $booking->id }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($booking->student)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($booking->property)->title ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    @php $s = $booking->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $s === 'approved' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : ($s === 'rejected' ? 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800') }}">
                                        {{ ucfirst($s) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 text-center text-[#6a6c6a] dark:text-[#cad8e5] text-xs">No bookings yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent transports --}}
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 space-y-4 transition-colors duration-200">
                <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Recent transports</h3>
                    <a href="{{ route('admin.transports.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">Manage transports →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#cad8e5] dark:border-[#6a6c6a] text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Student</th>
                                <th class="py-3 px-4 font-semibold">Property</th>
                                <th class="py-3 px-4 font-semibold">Date</th>
                                <th class="py-3 px-4 font-semibold">Assigned slot</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                        @forelse($recentTransports as $transport)
                            <tr class="hover:bg-[#dae4ee]/40 dark:hover:bg-[#2e2f2c]/50 transition">
                                <td class="py-3 px-4 font-medium text-[#2e2f2c] dark:text-white">#{{ $transport->id }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ $transport->student_name }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($transport->property)->title ?? optional($transport->property)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($transport->requested_date)->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ optional($transport->assigned_at)->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-4">
                                    @php $st = $transport->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $st === 'completed' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : ($st === 'cancelled' ? 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-center text-[#6a6c6a] dark:text-[#cad8e5] text-xs">No transport requests yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- STORAGE SUMMARY CARD --}}
            <div class="bg-white dark:bg-[#6a6c6a]/30 border border-[#cad8e5] dark:border-[#8da5c0]/40 rounded-2xl shadow-sm p-6 space-y-4 transition-colors duration-200">
                <div class="flex items-center justify-between border-b border-[#cad8e5] dark:border-[#6a6c6a] pb-4">
                    <h3 class="text-base font-bold text-[#2e2f2c] dark:text-white">Store housing bookings</h3>
                    <a href="{{ route('admin.storage.index') }}" class="text-xs font-semibold text-[#2e2f2c] dark:text-white hover:text-[#8da5c0] transition">Manage store housing →</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                        <tr class="border-b border-[#cad8e5] dark:border-[#6a6c6a] text-[#6a6c6a] dark:text-[#cad8e5] uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4 font-semibold">ID</th>
                            <th class="py-3 px-4 font-semibold">Student</th>
                            <th class="py-3 px-4 font-semibold">Period</th>
                            <th class="py-3 px-4 font-semibold">Months</th>
                            <th class="py-3 px-4 font-semibold">Amount</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-[#cad8e5] dark:divide-[#6a6c6a]">
                        @forelse($recentStorageBookings as $booking)
                            <tr class="hover:bg-[#dae4ee]/40 dark:hover:bg-[#2e2f2c]/50 transition">
                                <td class="py-3 px-4 font-medium text-[#2e2f2c] dark:text-white">#{{ $booking->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-[#2e2f2c] dark:text-white">
                                        {{ optional($booking->user)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-[11px] text-[#6a6c6a] dark:text-[#aabdd2]">
                                        {{ optional($booking->user)->email ?? '' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">
                                    {{ $booking->start_date }} – {{ $booking->end_date }}
                                </td>
                                <td class="py-3 px-4 text-[#6a6c6a] dark:text-[#cad8e5]">{{ $booking->months }}</td>
                                <td class="py-3 px-4 font-semibold text-[#2e2f2c] dark:text-white">KES {{ number_format($booking->amount) }}</td>
                                <td class="py-3 px-4">
                                    @php $st = $booking->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $st === 'paid' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : ($st === 'completed' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-center text-[#6a6c6a] dark:text-[#cad8e5] text-xs">No storage bookings yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>