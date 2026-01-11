{{-- resources/views/admin/properties/index.blade.php --}}
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
    </style>

    <x-slot name="header">
        <div>
            <h2 class="uc-header-title">Admin • Properties</h2>
            <p class="uc-header-subtitle">Review, verify, and control all landlord uploads.</p>
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

                <h3 style="font-size:15px;font-weight:600;color:#e5e7eb;margin-bottom:6px;">
                    All properties
                </h3>

                <div class="uc-table-wrap">
                    <table class="uc-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Landlord</th>
                            <th>Area</th>
                            <th>Verified</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($properties as $property)
                            <tr>
                                <td>#{{ $property->id }}</td>
                                <td>{{ $property->building_name }}</td>
                                <td>{{ optional($property->landlord)->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($property->area ?? 'n/a') }}</td>
                                <td>
                                    @if($property->is_verified)
                                        <span class="uc-pill uc-pill-green">Verified</span>
                                    @else
                                        <span class="uc-pill uc-pill-red">Not verified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($property->is_available)
                                        <span class="uc-pill uc-pill-blue">Available</span>
                                    @else
                                        <span class="uc-pill uc-pill-red">Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.properties.updateStatus', $property) }}"
                                          method="POST"
                                          style="display:inline-flex;flex-direction:column;gap:4px;">
                                        @csrf
                                        <div style="display:flex;align-items:center;gap:4px;">
                                            <label style="font-size:11px;color:#9ca3af;">
                                                Verified:
                                            </label>
                                            <select name="is_verified" class="uc-select">
                                                <option value="1" {{ $property->is_verified ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$property->is_verified ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div style="display:flex;align-items:center;gap:4px;">
                                            <label style="font-size:11px;color:#9ca3af;">
                                                Available:
                                            </label>
                                            <select name="is_available" class="uc-select">
                                                <option value="1" {{ $property->is_available ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$property->is_available ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="uc-btn-small" style="margin-top:4px;">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7">No properties found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:10px;">
                    {{ $properties->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
