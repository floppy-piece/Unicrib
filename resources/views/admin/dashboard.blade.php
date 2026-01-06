{{-- resources/views/admin/dashboard.blade.php --}}
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
        .uc-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        @media (max-width: 1024px) {
            .uc-stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 640px) {
            .uc-stats-grid { grid-template-columns: minmax(0, 1fr); }
        }
        .uc-card {
            border-radius: 12px;
            border: 1px solid #1f2937;
            background-color: #020617;
            padding: 14px;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
        }
        .uc-card-row {
            display: flex;
            align-items: center;
        }
        .uc-stat-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .uc-stat-text { margin-left: 12px; }
        .uc-stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9ca3af;
        }
        .uc-stat-value {
            margin-top: 4px;
            font-size: 20px;
            font-weight: 600;
            color: #f9fafb;
        }
        .uc-stat-icon-blue { background-color: rgba(59,130,246,0.18); color:#bfdbfe; }
        .uc-stat-icon-green { background-color: rgba(16,185,129,0.18); color:#6ee7b7; }
        .uc-stat-icon-purple { background-color: rgba(147,51,234,0.18); color:#e9d5ff; }
        .uc-stat-icon-amber { background-color: rgba(245,158,11,0.18); color:#fed7aa; }
        .uc-card-header {
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:8px;
        }
        .uc-card-title {
            font-size: 15px;
            font-weight: 600;
            color: #e5e7eb;
        }
        .uc-table-wrap { overflow-x:auto; margin-top:8px; }
        .uc-table { width:100%; border-collapse:collapse; font-size:12px; }
        .uc-table th, .uc-table td {
            padding:6px 10px;
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
        .uc-link {
            font-size:12px;
            color:#6366f1;
            text-decoration:none;
        }
        .uc-link:hover { color:#a5b4fc; }
    </style>

    <x-slot name="header">
        <div>
            <h2 class="uc-header-title">Admin Dashboard</h2>
            <p class="uc-header-subtitle">
                Global overview of users, properties, and bookings.
            </p>
        </div>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            {{-- Stat cards --}}
            <div class="uc-stats-grid">
                <div class="uc-card">
                    <div class="uc-card-row">
                        <div class="uc-stat-icon-wrap uc-stat-icon-blue">👥</div>
                        <div class="uc-stat-text">
                            <p class="uc-stat-label">Total users</p>
                            <p class="uc-stat-value">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>

                <div class="uc-card">
                    <div class="uc-card-row">
                        <div class="uc-stat-icon-wrap uc-stat-icon-green">🏠</div>
                        <div class="uc-stat-text">
                            <p class="uc-stat-label">Total properties</p>
                            <p class="uc-stat-value">{{ $totalProperties }}</p>
                        </div>
                    </div>
                </div>

                <div class="uc-card">
                    <div class="uc-card-row">
                        <div class="uc-stat-icon-wrap uc-stat-icon-purple">✅</div>
                        <div class="uc-stat-text">
                            <p class="uc-stat-label">Verified properties</p>
                            <p class="uc-stat-value">{{ $verifiedProperties }}</p>
                        </div>
                    </div>
                </div>

                <div class="uc-card">
                    <div class="uc-card-row">
                        <div class="uc-stat-icon-wrap uc-stat-icon-amber">🧾</div>
                        <div class="uc-stat-text">
                            <p class="uc-stat-label">Total bookings</p>
                            <p class="uc-stat-value">{{ $totalBookings }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent users --}}
            <div class="uc-card" style="margin-bottom:16px;">
                <div class="uc-card-header">
                    <h3 class="uc-card-title">Recent users</h3>
                    <a href="{{ route('admin.users.index') }}" class="uc-link">Manage users</a>
                </div>
                <div class="uc-table-wrap">
                    <table class="uc-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Category</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->category ?? 'n/a') }}</td>
                                <td>
                                    <span class="uc-pill {{ ($user->status ?? 'active') === 'active' ? 'uc-pill-green' : 'uc-pill-red' }}">
                                        {{ ucfirst($user->status ?? 'active') }}
                                    </span>
                                
                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        style="display:inline"
                                        onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="background:none;border:none;color:#fb7185;font-size:12px;cursor:pointer;">
                                            Delete
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr><td colspan="4">No users yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent properties --}}
            <div class="uc-card" style="margin-bottom:16px;">
                <div class="uc-card-header">
                    <h3 class="uc-card-title">Recent properties</h3>
                    <a href="{{ route('admin.properties.index') }}" class="uc-link">Manage properties</a>
                </div>
                <div class="uc-table-wrap">
                    <table class="uc-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Landlord</th>
                                <th>Area</th>
                                <th>Verified</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentProperties as $property)
                            <tr>
                                <td>{{ $property->title }}</td>
                                <td>{{ optional($property->landlord)->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($property->area ?? 'n/a') }}</td>
                                <td>
                                    @if($property->is_verified)
                                        <span class="uc-pill uc-pill-green">Verified</span>
                                    @else
                                        <span class="uc-pill uc-pill-red">Not verified</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No properties yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent bookings --}}
            <div class="uc-card">
                <div class="uc-card-header">
                    <h3 class="uc-card-title">Recent bookings</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="uc-link">Manage bookings</a>
                </div>
                <div class="uc-table-wrap">
                    <table class="uc-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Property</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentBookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ optional($booking->student)->name ?? 'N/A' }}</td>
                                <td>{{ optional($booking->property)->title ?? 'N/A' }}</td>
                                <td>
                                    @php $s = $booking->status; @endphp
                                    <span class="uc-pill
                                        {{ $s === 'approved' ? 'uc-pill-green' : ($s === 'rejected' ? 'uc-pill-red' : 'uc-pill-blue') }}">
                                        {{ ucfirst($s) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No bookings yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Recent transports --}}
            <div class="uc-card" style="margin-top:16px;">
                <div class="uc-card-header">
                    <h3 class="uc-card-title">Recent transports</h3>
                    <a href="{{ route('admin.transports.index') }}" class="uc-link">Manage transports</a>
                </div>
                <div class="uc-table-wrap">
                    <table class="uc-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Property</th>
                                <th>Date</th>
                                <th>Assigned slot</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentTransports as $transport)
                            <tr>
                                <td>#{{ $transport->id }}</td>
                                <td>{{ $transport->student_name }}</td>
                                <td>{{ optional($transport->property)->title ?? optional($transport->property)->name ?? 'N/A' }}</td>
                                <td>{{ optional($transport->requested_date)->format('d M Y') }}</td>
                                <td>{{ optional($transport->assigned_at)->format('d M Y, H:i') }}</td>
                                <td>
                                    @php $st = $transport->status; @endphp
                                    <span class="uc-pill
                                        {{ $st === 'completed' ? 'uc-pill-green'
                                        : ($st === 'cancelled' ? 'uc-pill-red' : 'uc-pill-blue') }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No transport requests yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
