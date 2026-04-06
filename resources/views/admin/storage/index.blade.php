{{-- resources/views/admin/storage/index.blade.php --}}
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
            <h2 class="uc-header-title">Admin • Store housing</h2>
            <p class="uc-header-subtitle">
                Manage student storage bookings, payment status, and completion.
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
                    Storage bookings
                </h3>

                {{-- Status filter links --}}
                <div class="uc-filter-links">
                    <a href="{{ route('admin.storage.index') }}"
                       class="{{ !$status ? 'active' : '' }}">
                        All ({{ array_sum($statusCounts) }})
                    </a>
                    @foreach (['pending','paid','completed','cancelled'] as $st)
                        <a href="{{ route('admin.storage.index', ['status' => $st]) }}"
                           class="{{ $status === $st ? 'active' : '' }}">
                            {{ ucfirst($st) }} ({{ $statusCounts[$st] ?? 0 }})
                        </a>
                    @endforeach
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:10px;">
                    <div style="flex:1;min-width:140px;">
                        <p class="uc-stat-label" style="margin-bottom:2px;">Total storage bookings</p>
                        <p class="uc-stat-value" style="font-size:16px;">{{ $totalStorageBookings }}</p>
                    </div>
                    <div style="flex:1;min-width:140px;">
                        <p class="uc-stat-label" style="margin-bottom:2px;">Active (paid)</p>
                        <p class="uc-stat-value" style="font-size:16px;">{{ $storageStatusCounts['paid'] ?? 0 }}</p>
                    </div>
                    <div style="flex:1;min-width:140px;">
                        <p class="uc-stat-label" style="margin-bottom:2px;">Completed</p>
                        <p class="uc-stat-value" style="font-size:16px;">{{ $storageStatusCounts['completed'] ?? 0 }}</p>
                    </div>
                </div>

                <div class="uc-table-wrap">
                    <table class="uc-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Period</th>
                            <th>Months</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Reference</th>
                            <th>Update</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($storageBookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>
                                    <div style="font-size:13px;font-weight:500;color:#f9fafb;">
                                        {{ optional($booking->user)->name ?? 'N/A' }}
                                    </div>
                                    <div style="font-size:11px;color:#9ca3af;">
                                        {{ optional($booking->user)->email ?? '' }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:12px;">
                                        {{ $booking->start_date }} – {{ $booking->end_date }}
                                    </div>
                                </td>
                                <td>{{ $booking->months }}</td>
                                <td>KES {{ number_format($booking->amount) }}</td>
                                <td>
                                    @php $st = $booking->status; @endphp
                                    <span class="uc-pill
                                        {{ $st === 'paid' ? 'uc-pill-green'
                                           : ($st === 'completed' ? 'uc-pill-blue' : 'uc-pill-red') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td style="font-size:11px;">
                                    {{ $booking->reference ?? '—' }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.storage.update', $booking->id) }}"
                                          method="POST"
                                          style="display:inline-flex;align-items:center;gap:4px;">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="uc-select" onchange="this.form.submit()">
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
                            <tr><td colspan="8">No storage bookings found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:10px;">
                    {{ $storageBookings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
