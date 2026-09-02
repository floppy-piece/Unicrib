{{-- resources/views/admin/bookings/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Admin • Bookings</h2>
                <p class="text-xs text-slate-500">Inspect and override booking statuses across the platform.</p>
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

                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900">All bookings</h3>
                    <span class="text-xs text-slate-400 font-medium">Manage student reservations</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Student</th>
                                <th class="py-3 px-4 font-semibold">Property</th>
                                <th class="py-3 px-4 font-semibold">Landlord</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold">Payment Status</th>
                                <th class="py-3 px-4 font-semibold">Created</th>
                                <th class="py-3 px-4 font-semibold text-right">Change status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition align-middle">
                                <td class="py-3 px-4 font-medium text-slate-900">#{{ $booking->id }}</td>
                                <td class="py-3 px-4 text-slate-700 font-medium">{{ optional($booking->student)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ optional($booking->property)->title ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ optional(optional($booking->property)->landlord)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    @php $s = $booking->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $s === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($s === 'rejected' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-blue-50 text-blue-700 border-blue-200') }}">
                                        {{ ucfirst($s) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $paymentStatus = $booking->payment_status;
                                    @endphp
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                            {{ $paymentStatus === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($paymentStatus === 'refunded' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-blue-50 text-blue-700 border-blue-200') }}">
                                            {{ ucfirst($paymentStatus) }}
                                        </span> 
                                        <span class="text-slate-400">&rarr;</span> 
                                        <span class="text-emerald-600 font-bold">KES {{ number_format($booking->amount, 0, '.', ',') }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $booking->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.bookings.updateStatus', $booking) }}"
                                          method="POST"
                                          class="inline-flex items-center justify-end gap-2">
                                        @csrf
                                        <select name="status" class="rounded-xl border-slate-200 text-slate-700 text-xs py-1.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                            <option value="rejected" {{ $booking->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="approved" {{ $booking->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                        <button type="submit" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-3 py-1.5 rounded-xl transition shadow-sm">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-8 text-center text-slate-400 text-xs">No bookings found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    {{ $bookings->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>