{{-- resources/views/admin/storage/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Admin • Store housing</h2>
                <p class="text-xs text-slate-500">
                    Manage student storage bookings, payment status, and completion.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-6">
                
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-xs font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-xs font-semibold text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-4">
                    <h3 class="text-base font-bold text-slate-900">Storage bookings</h3>

                    {{-- Status filter links --}}
                    <div class="flex items-center gap-1.5 flex-wrap text-xs">
                        <a href="{{ route('admin.storage.index') }}"
                           class="px-3 py-1 rounded-lg font-semibold transition {{ !$status ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            All ({{ array_sum($statusCounts) }})
                        </a>
                        @foreach (['pending','paid','completed','cancelled'] as $st)
                            <a href="{{ route('admin.storage.index', ['status' => $st]) }}"
                               class="px-3 py-1 rounded-lg font-semibold transition {{ $status === $st ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ ucfirst($st) }} ({{ $statusCounts[$st] ?? 0 }})
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Metric cards summary --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4">
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400 mb-1">Total storage bookings</p>
                        <p class="text-xl font-bold text-slate-900">{{ $totalStorageBookings }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4">
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400 mb-1">Active (paid)</p>
                        <p class="text-xl font-bold text-emerald-600">{{ $storageStatusCounts['paid'] ?? 0 }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4">
                        <p class="text-[11px] uppercase tracking-wider font-semibold text-slate-400 mb-1">Completed</p>
                        <p class="text-xl font-bold text-blue-600">{{ $storageStatusCounts['completed'] ?? 0 }}</p>
                    </div>
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
                                <th class="py-3 px-4 font-semibold">Reference</th>
                                <th class="py-3 px-4 font-semibold text-right">Update</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($storageBookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition align-middle">
                                <td class="py-3 px-4 font-medium text-slate-900">#{{ $booking->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-900">
                                        {{ optional($booking->user)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ optional($booking->user)->email ?? '' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-medium">
                                    {{ $booking->start_date }} – {{ $booking->end_date }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">{{ $booking->months }}</td>
                                <td class="py-3 px-4 font-bold text-slate-900">KES {{ number_format($booking->amount) }}</td>
                                <td class="py-3 px-4">
                                    @php $st = $booking->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $st === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($st === 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-rose-50 text-rose-700 border-rose-200') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                                    {{ $booking->reference ?? '—' }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.storage.update', $booking->id) }}"
                                          method="POST"
                                          class="inline-flex items-center justify-end">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="rounded-xl border-slate-200 text-slate-700 text-xs py-1 px-3 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                            @foreach (['pending','paid','completed','cancelled'] as $st)
                                                <option value="{{ $st }}" {{ $booking->status === $st ? 'selected' : '' }}>
                                                    {{ ucfirst($st) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-8 text-center text-slate-400 text-xs">No storage bookings found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    {{ $storageBookings->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>