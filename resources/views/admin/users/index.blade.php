{{-- resources/views/admin/users/index.blade.php --}}
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
        .uc-link-danger {
            color:#fb7185;
            font-size:11px;
            text-decoration:none;
        }
        .uc-link-danger:hover { color:#fecaca; }

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
            <h2 class="uc-header-title">Admin • Users</h2>
            <p class="uc-header-subtitle">Manage all UniCrib users, roles, and statuses.</p>
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
                    Unicrib Users
                </h3>

                {{-- Status filter links --}}
                <div class="uc-filter-links">
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ !$category ? 'active' : '' }}">
                        All ({{ array_sum($categoryCounts) }})
                    </a>
                    @foreach (['student','landlord','admin'] as $st)
                        <a href="{{ route('admin.users.index', ['category' => $st]) }}"
                           class="{{ $category === $st ? 'active' : '' }}">
                            {{ ucfirst($st) }} ({{ $categoryCounts[$st] }})
                        </a>
                    @endforeach
                </div>

                <div class="uc-table-wrap">
                    <table class="uc-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name & email</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Updated at</th>
                            <th>Expires at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td>
                                    <div style="font-size:13px;font-weight:500;color:#f9fafb;">
                                        {{ $user->name }}
                                    </div>
                                    <div style="font-size:11px;color:#9ca3af;">
                                        {{ $user->email }}
                                    </div>
                                </td>

                                <td>                        
                                    @php $st = $user->category; @endphp
                                    <span class="uc-pill uc-pill-blue">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>

                                <td>

                                    {{-- show verification for all users based on boolean is_verified --}}
                                    @if($user->category === 'student')
                                        @if($user->hasValidStudentAccess())
                                            <span class="uc-pill uc-pill-green">Verified</span>
                                        @else
                                            <span class="uc-pill uc-pill-red">Unverified</span>
                                        @endif
                                    @else
                                        @if ($user->status === 'verified')
                                            <span class="uc-pill {{ $user->is_verified ? 'uc-pill-green' : 'uc-pill-red' }}">Verified</span>
                                        @else
                                            <span class="uc-pill {{ $user->is_verified ? 'uc-pill-green' : 'uc-pill-red' }}">Unverified</span>
                                        @endif
                                    @endif
                                    
                                </td>
                                <td>
                                    <span class="uc-pill"> {{ $user->updated_at }} </span>
                                </td>
                                <td>
                                    <span class="uc-pill"> {{ $user->access_expires_at }} </span>
                                </td>

                                <td>
                                    {{-- Toggle is_verified --}}
                                    <form action="{{ route('admin.users.updateStatus', $user) }}"
                                        method="POST"
                                        style="display:inline-flex;align-items:center;gap:4px;margin-right:6px;">
                                        @csrf
                                        <select name="is_verified" class="uc-select">
                                            <option value="1" {{ $user->is_verified ? 'selected' : '' }}>Verified</option>
                                            <option value="0" {{ !$user->is_verified ? 'selected' : '' }}>Unverified</option>
                                        </select>
                                        <button type="submit" class="uc-btn-small">
                                            Save
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Delete this user? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="uc-link-danger"
                                                style="background:none;border:none;padding:0;">
                                            Delete
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr><td colspan="5">No users found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:10px;">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
