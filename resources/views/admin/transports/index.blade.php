{{-- resources/views/admin/transports/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Admin • Transports</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Manage student transport requests, time slots, and completion status.
                </p>
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

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen transition-colors duration-200"
         x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
         :class="{ 'dark': darkMode }"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))">
         
        <script>
            if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 space-y-6 transition-colors duration-200">
                
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-800 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-100 dark:border-rose-800 text-xs font-semibold text-rose-700 dark:text-rose-300">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 gap-4">
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Transport requests</h3>

                    {{-- Status filter links --}}
                    <div class="flex items-center gap-1.5 flex-wrap text-xs">
                        <a href="{{ route('admin.transports.index') }}"
                           class="px-3 py-1 rounded-lg font-semibold transition {{ !$status ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            All ({{ array_sum($statusCounts) }})
                        </a>
                        @foreach (['pending','confirmed','completed','cancelled'] as $st)
                            <a href="{{ route('admin.transports.index', ['status' => $st]) }}"
                               class="px-3 py-1 rounded-lg font-semibold transition {{ $status === $st ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                                {{ ucfirst($st) }} ({{ $statusCounts[$st] ?? 0 }})
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Student</th>
                                <th class="py-3 px-4 font-semibold">Property</th>
                                <th class="py-3 px-4 font-semibold">Requested date</th>
                                <th class="py-3 px-4 font-semibold">Assigned slot</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold text-right">Update</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($transports as $transport)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition align-middle">
                                <td class="py-3 px-4 font-medium text-slate-900 dark:text-slate-100">#{{ $transport->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">
                                        {{ $transport->student_name }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 dark:text-slate-500">
                                        {{ $transport->student_phone }}
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">
                                        {{ optional($transport->property)->title ?? optional($transport->property)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 dark:text-slate-500">
                                        Booking #{{ $transport->booking_id }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400 font-medium">
                                    {{ optional($transport->requested_date)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($transport->assigned_at)
                                        <div class="text-slate-900 dark:text-slate-100 font-medium">
                                            {{ $transport->assigned_at->format('d M Y, H:i') }}
                                        </div>
                                        @if($transport->status !== 'completed' && $transport->status !== 'cancelled')
                                            <div class="text-[11px] text-indigo-600 dark:text-indigo-400 font-semibold mt-0.5">
                                                In: <span class="transport-countdown" data-deadline="{{ $transport->assigned_at->toIso8601String() }}">calculating...</span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500">N/A</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @php $st = $transport->status; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border
                                        {{ $st === 'completed' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' : ($st === 'cancelled' ? 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.transports.update', $transport->id) }}"
                                          method="POST"
                                          class="inline-flex items-center justify-end">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs py-1 px-3 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                            @foreach (['pending','confirmed','completed','cancelled'] as $st)
                                                <option value="{{ $st }}" {{ $transport->status === $st ? 'selected' : '' }} class="dark:bg-slate-800">
                                                    {{ ucfirst($st) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs">No transport requests found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $transports->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const countdownEls = document.querySelectorAll('.transport-countdown');
        if (!countdownEls.length) return;

        function updateAllCountdowns() {
            const now = new Date().getTime();

            countdownEls.forEach(function (el) {
                const deadlineStr = el.getAttribute('data-deadline');
                if (!deadlineStr) return;

                const deadline = new Date(deadlineStr).getTime();
                const diff = deadline - now;

                if (diff <= 0) {
                    el.textContent = 'due';
                    return;
                }

                const second = 1000;
                const minute = 60 * second;
                const hour   = 60 * minute;

                const hours   = Math.floor(diff / hour);
                const minutes = Math.floor((diff % hour) / minute);
                const seconds = Math.floor((diff % minute) / second);

                let parts = [];
                if (hours > 0) parts.push(hours + 'h');
                parts.push(minutes + 'm');
                parts.push(seconds + 's');

                el.textContent = parts.join(' ');
            });
        }

        updateAllCountdowns();
        setInterval(updateAllCountdowns, 1000);
    });
    </script>
</x-app-layout>