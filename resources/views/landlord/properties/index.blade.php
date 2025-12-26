{{-- resources/views/landlord/properties/index.blade.php --}}
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
        .uc-card {
            border-radius: 12px;
            border: 1px solid #1f2937;
            background-color: #020617;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
            overflow: hidden;
        }
        .uc-card-inner {
            padding: 18px 18px 20px;
        }
        .uc-header-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .uc-header-main-title {
            font-size: 20px;
            font-weight: 600;
            color: #f9fafb;
            margin: 0;
        }
        .uc-header-main-text {
            margin-top: 4px;
            font-size: 13px;
            color: #9ca3af;
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
        .uc-btn-primary svg {
            margin-right: 6px;
        }

        .uc-alert-success {
            margin-bottom: 16px;
            padding: 10px 12px;
            border-radius: 8px;
            background-color: #022c22;
            border: 1px solid #16a34a;
            color: #bbf7d0;
            font-size: 13px;
        }

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

        .uc-prop-cell {
            display: flex;
            align-items: center;
        }
        .uc-prop-thumb {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #1f2937;
        }
        .uc-prop-text {
            margin-left: 10px;
        }
        .uc-prop-title {
            font-size: 13px;
            font-weight: 500;
            color: #f9fafb;
        }
        .uc-prop-type {
            font-size: 12px;
            color: #9ca3af;
        }

        .uc-prop-location-main {
            font-size: 13px;
            color: #f9fafb;
        }
        .uc-prop-location-sub {
            font-size: 12px;
            color: #9ca3af;
        }

        .uc-price-main {
            font-size: 13px;
            font-weight: 500;
            color: #6ee7b7;
        }
        .uc-price-sub {
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
            margin-left: 4px;
        }
        .uc-pill-amber {
            background-color: rgba(245,158,11,0.15);
            color: #fef3c7;
            border-color: rgba(245,158,11,0.5);
            margin-left: 4px;
        }

        .uc-link {
            font-size: 13px;
            color: #6366f1;
            text-decoration: none;
            margin-right: 8px;
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
        .uc-pagination {
            margin-top: 16px;
            font-size: 13px;
            color: #9ca3af;
        }
    </style>

    <x-slot name="header">
        <div>
            <h2 class="uc-header-title">
                {{ __('My Properties') }}
            </h2>
            <p class="uc-header-subtitle">
                Manage all your rental properties and keep them up to date.
            </p>
        </div>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            <div class="uc-card">
                <div class="uc-card-inner">
                    <div class="uc-header-main">
                        <div>
                            <h1 class="uc-header-main-title">My Properties</h1>
                            <p class="uc-header-main-text">Manage all your rental properties.</p>
                        </div>
                        <a href="{{ route('landlord.properties.create') }}" class="uc-btn uc-btn-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add New Property
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="uc-alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($properties->count() > 0)
                        <div class="uc-table-wrap">
                            <table class="uc-table">
                                <thead>
                                    <tr>
                                        <th>Property</th>
                                        <th>Location</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($properties as $property)
                                    <tr>
                                        <td>
                                            <div class="uc-prop-cell">
                                                @if($property->images && count($property->images) > 0)
                                                    <img
                                                        class="uc-prop-thumb"
                                                        src="{{ asset('storage/' . $property->images[0]) }}"
                                                        alt="{{ $property->title }}"
                                                    >
                                                @endif
                                                <div class="uc-prop-text">
                                                    <div class="uc-prop-title">{{ $property->title }}</div>
                                                    <div class="uc-prop-type">{{ $property->property_type }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="uc-prop-location-main">
                                                @php
                                                    $areaNames = [
                                                        'mjini' => 'Mjini',
                                                        'ngomongo' => 'Ngomongo', 
                                                        'diaspora' => 'Diaspora',
                                                        'kutus' => 'Kutus'
                                                    ];
                                                @endphp
                                                {{ $areaNames[$property->area] ?? $property->area }}
                                            </div>
                                            <div class="uc-prop-location-sub">
                                                {{ Str::limit($property->address, 30) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="uc-price-main">
                                                KES {{ number_format($property->price) }}
                                            </div>
                                            <div class="uc-price-sub">
                                                per month
                                            </div>
                                        </td>
                                        <td>
                                            @if($property->is_available)
                                                <span class="uc-pill uc-pill-green">Available</span>
                                            @else
                                                <span class="uc-pill uc-pill-red">Rented</span>
                                            @endif

                                            @if($property->is_verified)
                                                <span class="uc-pill uc-pill-blue">Verified</span>
                                            @else
                                                <span class="uc-pill uc-pill-amber">Pending</span>
                                            @endif
                                        </td>
                                        <td style="font-size:13px;">
                                            <a
                                                href="{{ route('landlord.properties.edit', $property) }}"
                                                class="uc-link"
                                            >
                                                Edit
                                            </a>
                                            <form
                                                action="{{ route('landlord.properties.destroy', $property) }}"
                                                method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this property?')"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="uc-link uc-link-danger"
                                                    style="background:none;border:none;padding:0;"
                                                >
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="uc-pagination">
                            {{ $properties->links() }}
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
                            <a href="{{ route('landlord.properties.create') }}" class="uc-btn uc-btn-primary">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Your First Property
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
