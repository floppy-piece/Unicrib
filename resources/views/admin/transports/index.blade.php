<x-app-layout>
    <style>
        .uc-page {
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            min-height: 100vh;
            padding: 16px 0 24px;
        }
        .uc-container {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 16px;
        }
        .uc-header-title {
            font-size: 20px;
            font-weight: 600;
            color: #f9fafb;
            margin-bottom: 4px;
        }
        .uc-header-subtitle {
            font-size: 13px;
            color: #9ca3af;
        }
        .uc-card {
            border-radius: 12px;
            border: 1px solid #1f2937;
            background-color: #020617;
            padding: 16px;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
        }
        .uc-table-wrap { overflow-x:auto; margin-top:10px; }
        .uc-table {
            width:100%;
            border-collapse:collapse;
            font-size:12px;
        }
        .uc-table th, .uc-table td {
            padding:8px 10px;
            border-bottom:1px solid #111827;
            text-align:left;
        }
        .uc-table th {
            font-size:10px;
            text-transform:uppercase;
            letter-spacing:0.08em;
            color:#9ca3af;
        }
        .uc-table td { color:#e5e7eb; }
        .uc-pill {
            display:inline-flex;
            align-items:center;
            padding:2px 8px;
            border-radius:999px;
            font-size:10px;
            font-weight:500;
            border:1px solid transparent;
        }
        .uc-pill-green {
            background-color:rgba(16,185,129,0.15);
            color:#6ee7b7;
            border-color:rgba(16,185,129,0.5);
        }
        .uc-pill-red {
            background-color:rgba(239,68,68,0.15);
            color:#fecaca;
            border-color:rgba(239,68,68,0.5);
        }
        .uc-pill-blue {
            background-color:rgba(59,130,246,0.15);
            color:#bfdbfe;
            border-color:rgba(59,130,246,0.5);
        }
        .uc-select {
            background:#020617;
            border:1px solid #374151;
            border-radius:999px;
            color:#e5e7eb;
            font-size:11px;
            padding:3px 8px;
        }
        .uc-btn-small {
            font-size:11px;
            padding:4px 10px;
            border-radius:999px;
            border:1px solid #4f46e5;
            background-color:#6366f1;
            color:#ffffff;
            cursor:pointer;
        }
        .uc-btn-small:hover { background-color:#4f46e5; }
        .uc-filter-links {
            font-size:12px;
            margin-bottom:6px;
            color:#9ca3af;
        }
        .uc-filter-links a {
            margin-right:8px;
            text-decoration:none;
            color:#9ca3af;
        }
        .uc-filter-links a.active {
            color:#f9fafb;
        }
    </style>

    <x-slot name="header">
        <div>
            <h2 class="uc-header-title">Admin • Transports</h2>
            <p class="uc-header-subtitle">
                Manage student transport requests, time slots, and completion status.
            </p>
        </div>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            <div class="uc-card">
                @if(session('success'))
                    <p style="font-size:12px;color:#6ee7b7;margin-bottom:8px;">
                        {{ session('success') }}
                    </p>
                @endif
                @if(session('error'))
                    <p style="font-size:12px;color:#fecaca;margin-bottom:8px;">
                        {{ session('error') }}
                    </p>
                @endif

                <h3 style="font-size:15px;font-weight:600;color:#e5e7eb;margin-bottom:6px;">
                    Transport requests
                </h3>

                {{-- Status filter links --}}
                <div class="uc-filter-links">
                    <a href="{{ route('admin.transports.index') }}"
                       class="{{ !$status ? 'active' : '' }}">
                        All ({{ array_sum($statusCounts) }})
                    </a>
                    @foreach (['pending','confirmed','completed','cancelled'] as $st)
                        <a href="{{ route('admin.transports.index', ['status' => $st]) }}"
                           class="{{ $status === $st ? 'active' : '' }}">
                            {{ ucfirst($st) }} ({{ $statusCounts[$st] }})
                        </a>
                    @endforeach
                </div>

                <div class="uc-table-wrap">
                    <table class="uc-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Property</th>
                            <th>Requested date</th>
                            <th>Assigned slot</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($transports as $transport)
                            <tr>
                                <td>#{{ $transport->id }}</td>
                                <td>
                                    <div style="font-size:13px;font-weight:500;color:#f9fafb;">
                                        {{ $transport->student_name }}
                                    </div>
                                    <div style="font-size:11px;color:#9ca3af;">
                                        {{ $transport->student_phone }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:13px;font-weight:500;color:#f9fafb;">
                                        {{ optional($transport->property)->title ?? optional($transport->property)->name ?? 'N/A' }}
                                    </div>
                                    <div style="font-size:11px;color:#9ca3af;">
                                        Booking #{{ $transport->booking_id }}
                                    </div>
                                </td>
                                <td>{{ optional($transport->requested_date)->format('d M Y') }}</td>
                                <td>
                                    @if($transport->assigned_at)
                                        <div>
                                            {{ $transport->assigned_at->format('d M Y, H:i') }}
                                        </div>
                                        @if($transport->status !== 'completed' && $transport->status !== 'cancelled')
                                            <div style="font-size:11px;color:#9ca3af;">
                                                In:
                                                <span class="transport-countdown"
                                                    data-deadline="{{ $transport->assigned_at->toIso8601String() }}">
                                                    calculating...
                                                </span>
                                            </div>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td>
                                    @php $st = $transport->status; @endphp
                                    <span class="uc-pill
                                        {{ $st === 'completed' ? 'uc-pill-green'
                                           : ($st === 'cancelled' ? 'uc-pill-red' : 'uc-pill-blue') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.transports.update', $transport->id) }}"
                                          method="POST"
                                          style="display:inline-flex;align-items:center;gap:4px;">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="uc-select" onchange="this.form.submit()">
                                            @foreach (['pending','confirmed','completed','cancelled'] as $st)
                                                <option value="{{ $st }}" {{ $transport->status === $st ? 'selected' : '' }}>
                                                    {{ ucfirst($st) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7">No transport requests found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:10px;">
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
