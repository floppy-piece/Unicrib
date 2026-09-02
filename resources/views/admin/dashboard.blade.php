{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Admin Dashboard</h2>
                <p class="text-xs text-slate-500">
                    Global overview of users, properties, bookings, transport, and storage requests.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-500 transition shadow-sm">
                    Manage Users
                </a>
                <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 transition shadow-sm">
                    Manage Properties
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg shadow-sm">👥</div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Total users</p>
                        <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $totalUsers }}</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-lg shadow-sm">🏠</div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Total properties</p>
                        <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $totalProperties }}</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center text-lg shadow-sm">✅</div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Verified properties</p>
                        <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $verifiedProperties }}</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-lg shadow-sm">🧾</div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Total bookings</p>
                        <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $totalBookings }}</p>
                    </div>
                </div>

            </div>

            {{-- Recent users --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900">Recent users</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">Manage users →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">Name</th>
                                <th class="py-3 px-4 font-semibold">Email</th>
                                <th class="py-3 px-4 font-semibold">Category</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($recentUsers as $user)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-medium text-slate-900">{{ $user->name }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $user->email }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ ucfirst($user->category ?? 'n/a') }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ ($user->status ?? 'active') === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
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
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium transition text-xs">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-slate-400 text-xs">No users yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent properties --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900">Recent properties</h3>
                    <a href="{{ route('admin.properties.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">Manage properties →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">Title</th>
                                <th class="py-3 px-4 font-semibold">Landlord</th>
                                <th class="py-3 px-4 font-semibold">Area</th>
                                <th class="py-3 px-4 font-semibold">Verified</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($recentProperties as $property)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-medium text-slate-900">{{ $property->title }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ optional($property->landlord)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ ucfirst($property->area ?? 'n/a') }}</td>
                                <td class="py-3 px-4">
                                    @if($property->is_verified)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Verified</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Not verified</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 text-center text-slate-400 text-xs">No properties yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent bookings --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900">Recent bookings</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">Manage bookings →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Student</th>
                                <th class="py-3 px-4 font-semibold">Property</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-medium text-slate-900">#{{ $booking->id }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ optional($booking->student)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ optional($booking->property)->title ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    @php $s = $booking->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $s === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($s === 'rejected' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-blue-50 text-blue-700 border-blue-200') }}">
                                        {{ ucfirst($s) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 text-center text-slate-400 text-xs">No bookings yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent transports --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900">Recent transports</h3>
                    <a href="{{ route('admin.transports.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">Manage transports →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Student</th>
                                <th class="py-3 px-4 font-semibold">Property</th>
                                <th class="py-3 px-4 font-semibold">Date</th>
                                <th class="py-3 px-4 font-semibold">Assigned slot</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($recentTransports as $transport)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-medium text-slate-900">#{{ $transport->id }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $transport->student_name }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ optional($transport->property)->title ?? optional($transport->property)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ optional($transport->requested_date)->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ optional($transport->assigned_at)->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-4">
                                    @php $st = $transport->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $st === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($st === 'cancelled' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-blue-50 text-blue-700 border-blue-200') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-center text-slate-400 text-xs">No transport requests yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- STORAGE SUMMARY CARD --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900">Store housing bookings</h3>
                    <a href="{{ route('admin.storage.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">Manage store housing →</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                        <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4 font-semibold">ID</th>
                            <th class="py-3 px-4 font-semibold">Student</th>
                            <th class="py-3 px-4 font-semibold">Period</th>
                            <th class="py-3 px-4 font-semibold">Months</th>
                            <th class="py-3 px-4 font-semibold">Amount</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($recentStorageBookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-medium text-slate-900">#{{ $booking->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-900">
                                        {{ optional($booking->user)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ optional($booking->user)->email ?? '' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $booking->start_date }} – {{ $booking->end_date }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">{{ $booking->months }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">KES {{ number_format($booking->amount) }}</td>
                                <td class="py-3 px-4">
                                    @php $st = $booking->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $st === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($st === 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-rose-50 text-rose-700 border-rose-200') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-center text-slate-400 text-xs">No storage bookings yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>