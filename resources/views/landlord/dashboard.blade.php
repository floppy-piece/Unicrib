{{-- resources/views/landlord/dashboard.blade.php --}}
<x-app-layout>
    <style>
        .uc-page {
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            min-height: 100vh;
            padding-top: 16px;
            padding-bottom: 24px;
        }
        .uc-container {
            max-width: 1120px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 16px;
            padding-right: 16px;
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

        /* Stat cards */
        .uc-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        @media (max-width: 1024px) {
            .uc-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 640px) {
            .uc-stats-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        .uc-card {
            border-radius: 12px;
            border: 1px solid #1f2937;
            background-color: #020617;
            padding: 14px 14px;
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
        .uc-stat-text {
            margin-left: 12px;
        }
        .uc-stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9ca3af;
        }
        .uc-stat-value {
            margin-top: 4px;
            font-size: 22px;
            font-weight: 600;
            color: #f9fafb;
        }

        .uc-stat-icon-blue {
            background-color: rgba(79,70,229,0.18);
            color: #a5b4fc;
        }
        .uc-stat-icon-green {
            background-color: rgba(16,185,129,0.18);
            color: #6ee7b7;
        }
        .uc-stat-icon-purple {
            background-color: rgba(147,51,234,0.18);
            color: #c4b5fd;
        }
        .uc-stat-icon-amber {
            background-color: rgba(245,158,11,0.18);
            color: #fed7aa;
        }

        /* Quick actions */
        .uc-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .uc-card-title {
            font-size: 15px;
            font-weight: 600;
            color: #e5e7eb;
        }

        .uc-btn-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 8px;
        }
        .uc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            border-radius: 999px;
            border: 1px solid transparent;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }
        .uc-btn-primary {
            background-color: #6366f1;
            border-color: #4f46e5;
            color: #ffffff;
        }
        .uc-btn-primary:hover {
            background-color: #4f46e5;
        }
        .uc-btn-ghost {
            background-color: #020617;
            border-color: #1f2937;
            color: #e5e7eb;
        }
        .uc-btn-ghost:hover {
            border-color: #6366f1;
        }
        .uc-btn svg {
            margin-right: 6px;
        }

        /* Table */
        .uc-table-wrap {
            overflow-x: auto;
        }
        .uc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .uc-table thead {
            background-color: #020617;
        }
        .uc-table th,
        .uc-table td {
            padding: 8px 14px;
            border-bottom: 1px solid #111827;
            text-align: left;
        }
        .uc-table th {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
        }
        .uc-table td {
            color: #e5e7eb;
        }

        .uc-table-property {
            display: flex;
            align-items: center;
        }
        .uc-table-thumb {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #1f2937;
        }
        .uc-table-thumb-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background-color: #020617;
            border: 1px solid #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .uc-table-text-block {
            margin-left: 10px;
        }
        .uc-table-title {
            font-size: 13px;
            font-weight: 500;
            color: #f9fafb;
        }
        .uc-table-meta {
            font-size: 11px;
            color: #9ca3af;
        }

        .uc-pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 500;
            border: 1px solid transparent;
        }
        .uc-pill-green {
            background-color: rgba(16,185,129,0.15);
            color: #6ee7b7;
            border-color: rgba(16,185,129,0.5);
        }
        .uc-pill-red {
            background-color: rgba(239,68,68,0.15);
            color: #fecaca;
            border-color: rgba(239,68,68,0.5);
        }
        .uc-pill-blue {
            background-color: rgba(59,130,246,0.15);
            color: #bfdbfe;
            border-color: rgba(59,130,246,0.5);
        }
        .uc-pill-amber {
            background-color: rgba(245,158,11,0.15);
            color: #fef3c7;
            border-color: rgba(245,158,11,0.5);
        }

        .uc-link {
            font-size: 13px;
            color: #6366f1;
            text-decoration: none;
        }
        .uc-link:hover {
            color: #a5b4fc;
        }
        .uc-link-danger {
            color: #fb7185;
        }
        .uc-link-danger:hover {
            color: #fecaca;
        }

        .uc-empty {
            text-align: center;
            padding: 40px 16px;
        }
        .uc-empty-icon {
            color: #4b5563;
            margin-bottom: 10px;
        }
        .uc-empty-title {
            font-size: 15px;
            font-weight: 500;
            color: #f9fafb;
            margin-bottom: 4px;
        }
        .uc-empty-text {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 16px;
        }
    </style>


    <x-slot name="header">
        <div>
            <h2 class="uc-header-title">
                {{ __('Landlord Dashboard') }}
            </h2>
            <p class="uc-header-subtitle">
                Overview of your properties and recent activity.
            </p>
        </div>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            {{-- Stats Cards --}}
            <div class="uc-stats-grid">
                <div class="uc-card">
                    <div class="uc-card-row">
                        <div class="uc-stat-icon-wrap uc-stat-icon-blue">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="uc-stat-text">
                            <p class="uc-stat-label">Total properties</p>
                            <p class="uc-stat-value">{{ auth()->user()->properties->count() }}</p>
                        </div>
                    </div>
                </div>

                @php
                    $landlord = auth()->user();
                    $properties = $landlord->properties;
                    // rooms available = sum(capacity - booked_count, but not below 0)
                    $totalRoomsAvailable = $properties->sum(function ($p) {
                        $cap = (int) ($p->capacity ?? 0);
                        $booked = (int) ($p->booked_count ?? 0);
                        $free = $cap - $booked;
                        return $free > 0 ? $free : 0;
                    });
                    $totalRooms = $properties->sum(function ($p) {
                        return (int) ($p->capacity ?? 0);
                    });
                    $totalRoomsBooked = $properties->sum(function ($p) {
                        return (int) ($p->booked_count ?? 0);
                    });
                @endphp

                <div class="uc-card">
                    <div class="uc-card-row">
                        <div class="uc-stat-icon-wrap uc-stat-icon-green">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="uc-stat-text">
                            <p class="uc-stat-label">Rooms available</p>
                            <p class="uc-stat-value">
                                {{ $totalRoomsAvailable }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="uc-card">
                    <div class="uc-card-row">
                        <div class="uc-stat-icon-wrap uc-stat-icon-purple">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="uc-stat-text">
                            <p class="uc-stat-label">Total rooms</p>
                            <p class="uc-stat-value">
                                {{ $totalRooms }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="uc-card">
                    <div class="uc-card-row">
                        <div class="uc-stat-icon-wrap uc-stat-icon-amber">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="uc-stat-text">
                            <p class="uc-stat-label">Rooms booked</p>
                            <p class="uc-stat-value">
                                {{ $totalRoomsBooked }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="uc-card" style="margin-bottom: 20px;">
                <div class="uc-card-header">
                    <h3 class="uc-card-title">Quick actions</h3>
                </div>
                <div class="uc-btn-row">
                    <a href="{{ route('landlord.properties.add') }}" class="uc-btn uc-btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add new property
                    </a>
                    <a href="{{ route('landlord.properties.myProperties') }}" class="uc-btn uc-btn-ghost">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Manage properties
                    </a>
                </div>
            </div>

            {{-- Recent Properties --}}
            <div class="uc-card">
                <div class="uc-card-header" style="margin-bottom: 10px;">
                    <h3 class="uc-card-title">Recent properties</h3>
                    <a href="{{ route('landlord.properties.myProperties') }}" class="uc-link">
                        View all
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="margin-left: 4px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                @php
                    $recentProperties = auth()->user()->properties()->latest()->take(5)->get();
                @endphp

                @if($recentProperties->count() > 0)
                    <div class="uc-table-wrap">
                        <table class="uc-table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Area</th>
                                    <th>Price</th>
                                    <th>Rooms</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProperties as $property)
                                <tr>
                                    <td>
                                        <div class="uc-table-property">
                                            @if($property->images && count($property->images) > 0)
                                                <img class="uc-table-thumb"
                                                     src="{{ asset('storage/' . $property->images[0]) }}"
                                                     alt="{{ $property->title }}">
                                            @else
                                                <div class="uc-table-thumb-placeholder">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="uc-table-text-block">
                                                <div class="uc-table-title">{{ $property->title }}</div>
                                                <div class="uc-table-meta">{{ ucfirst($property->property_type) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $areaNames = [
                                                'mjini' => 'Mjini',
                                                'ngomongo' => 'Ngomongo',
                                                'diaspora' => 'Diaspora',
                                                'kutus' => 'Kutus'
                                            ];
                                        @endphp
                                        {{ $areaNames[$property->area] ?? $property->area }}
                                    </td>
                                    <td>
                                        <div style="color:#6ee7b7;font-weight:500;">
                                            KES {{ number_format($property->price) }}
                                        </div>
                                        <div style="font-size:11px;color:#9ca3af;">per month</div>
                                    </td>
                                    <td>
                                        <span class="uc-pill uc-pill-blue">
                                            {{ (int)($property->booked_count ?? 0) }} / {{ (int)($property->capacity ?? 0) }} rooms
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $isFull = (($property->booked_count ?? 0) >= ($property->capacity ?? 0)) && ($property->capacity ?? 0) > 0;
                                        @endphp

                                        @if($isFull)
                                            <span class="uc-pill uc-pill-red">Full</span>
                                        @else
                                            <span class="uc-pill uc-pill-green">Has rooms</span>
                                        @endif

                                        @if($property->is_verified)
                                            <span class="uc-pill uc-pill-blue" style="margin-left:4px;">Verified</span>
                                        @else
                                            <span class="uc-pill uc-pill-amber" style="margin-left:4px;">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('landlord.properties.edit', $property) }}" class="uc-link" style="margin-right:8px;">
                                            Edit
                                        </a>
                                        <form action="{{ route('landlord.properties.destroy', $property) }}"
                                              method="POST" style="display:inline;"
                                              onsubmit="return confirm('Delete this property?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="uc-link uc-link-danger" style="background:none;border:none;padding:0;">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="uc-empty">
                        <div class="uc-empty-icon">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="margin:auto;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="uc-empty-title">No properties yet</h3>
                        <p class="uc-empty-text">Get started by adding your first property.</p>
                        <a href="{{ route('landlord.properties.add') }}" class="uc-btn uc-btn-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add your first property
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
