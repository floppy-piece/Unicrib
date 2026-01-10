<x-app-layout>
    <style>
        .uc-msg-page {
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            min-height: 100vh;
            padding: 16px 0 24px;
        }
        .uc-msg-container {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 16px;
        }
        .uc-msg-card {
            border-radius: 12px;
            border: 1px solid #111827;
            background-color: #020617;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
            overflow: hidden;
        }
        .uc-msg-inner {
            padding: 18px 18px 20px;
        }
        .uc-msg-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .uc-msg-title {
            font-size: 18px;
            font-weight: 600;
            color: #f9fafb;
        }
        .uc-msg-subtitle {
            font-size: 13px;
            color: #9ca3af;
        }

        .uc-msg-section-title {
            font-size: 14px;
            font-weight: 600;
            color: #e5e7eb;
            padding-bottom: 6px;
            border-bottom: 1px solid #111827;
            margin-bottom: 12px;
        }

        .uc-msg-item {
            padding: 10px 0 12px;
            border-bottom: 1px solid #111827;
        }
        .uc-msg-item:last-child {
            border-bottom: none;
        }
        .uc-msg-meta {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .uc-msg-body {
            font-size: 14px;
            color: #e5e7eb;
        }
        .uc-msg-label {
            font-weight: 500;
            color: #9ca3af;
        }

        .uc-msg-pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 500;
            border: 1px solid transparent;
            margin-top: 4px;
        }
        .uc-msg-pill-pending {
            background-color: rgba(245,158,11,0.15);
            color: #fef3c7;
            border-color: rgba(245,158,11,0.5);
        }
        .uc-msg-pill-approved {
            background-color: rgba(16,185,129,0.15);
            color: #6ee7b7;
            border-color: rgba(16,185,129,0.5);
        }
        .uc-msg-pill-rejected {
            background-color: rgba(239,68,68,0.15);
            color: #fecaca;
            border-color: rgba(239,68,68,0.5);
        }

        .uc-msg-actions {
            margin-top: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .uc-msg-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 14px;
            border-radius: 999px;
            border: 1px solid transparent;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s ease, border-color .15s ease, color .15s ease;
        }
        .uc-msg-btn-primary {
            background-color: #6366f1;
            border-color: #4f46e5;
            color: #ffffff;
        }
        .uc-msg-btn-primary:hover {
            background-color: #4f46e5;
        }
        .uc-msg-btn-ghost {
            background-color: #020617;
            border-color: #1f2937;
            color: #e5e7eb;
        }
        .uc-msg-btn-ghost:hover {
            border-color: #6366f1;
        }

        .uc-msg-empty {
            padding: 24px 0 6px;
            text-align: center;
        }
        .uc-msg-empty-text {
            font-size: 13px;
            color: #9ca3af;
        }

        .uc-btn-primary {
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid #4f46e5;
            background-color: #6366f1;
            color: #ffffff;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        .uc-btn-primary:hover {
            background-color: #4f46e5;
        }
    </style>

    <x-slot name="header">
        <h2 class="uc-header-title">
            {{ __('Tenants') }}
        </h2>
    </x-slot>

    <div class="uc-msg-page">
        <div class="uc-msg-container">
            <div class="uc-msg-card">
                <div class="uc-msg-inner">
                    @php $user = auth()->user(); @endphp

                    <div class="uc-msg-header">
                        <div>
                            <div class="uc-msg-title">
                                {{ $user && $user->category === 'landlord' ? 'Booking Requests' : 'Booking Updates' }}
                            </div>
                            <div class="uc-msg-subtitle">
                                {{ $user && $user->category === 'landlord'
                                    ? 'Review and manage student booking requests for your properties.'
                                    : 'Track confirmations and updates for your property bookings.' }}
                            </div>
                        </div>
                    </div>

                    {{-- Landlord: booking requests --}}
                    @if($user && $user->category === 'landlord')
                        <h3 class="uc-msg-section-title">Pending requests</h3>

                        @forelse($pendingBookings as $booking)
                            <div class="uc-msg-item">
                                <div class="uc-msg-meta">
                                    Request #{{ $booking->id }}
                                </div>
                                <div class="uc-msg-body">
                                    <span class="uc-msg-label">Student:</span>
                                    {{ $booking->student->name }}<br>
                                    <span class="uc-msg-label">Property:</span>
                                    {{ $booking->property->title }}<br>
                                    <span class="uc-msg-label">Status:</span>
                                    {{ ucfirst($booking->status) }}

                                    <div>
                                        @if($booking->status === 'pending')
                                            <span class="uc-msg-pill uc-msg-pill-pending">Pending</span>
                                        @elseif($booking->status === 'approved')
                                            <span class="uc-msg-pill uc-msg-pill-approved">Approved</span>
                                        @else
                                            <span class="uc-msg-pill uc-msg-pill-rejected">Rejected</span>
                                        @endif
                                    </div>
                                </div>

                                @if($booking->status === 'pending')
                                    <div class="uc-msg-actions">
                                        <form method="POST"
                                              action="{{ route('landlord.bookings.approve', $booking->id) }}">
                                            @csrf
                                            <button type="submit" class="uc-msg-btn uc-msg-btn-primary">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('landlord.bookings.reject', $booking->id) }}">
                                            @csrf
                                            <button type="submit" class="uc-msg-btn uc-msg-btn-ghost">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="uc-msg-empty">
                                <p class="uc-msg-empty-text">
                                    No pending booking requests at the moment.
                                </p>
                            </div>
                        @endforelse
                    @endif


                    {{-- Landlord: current tenants --}}
                    @if($user && $user->category === 'landlord')
                        <h3 class="uc-msg-section-title" style="margin-top: 18px;">Your tenants</h3>

                       
                        @forelse($approvedBookings as $booking)
                            <div class="uc-msg-item">
                                <div class="uc-msg-meta">
                                    Tenant #{{ $booking->student->id }} • Booking #{{ $booking->id }}
                                </div>
                                <div class="uc-msg-body">
                                    <span class="uc-msg-label">Name:</span>
                                    {{ $booking->student->name }}<br>

                                    @if(!empty($booking->student->email))
                                        <span class="uc-msg-label">Email:</span>
                                        {{ $booking->student->email }}<br>
                                    @endif

                                    @if(!empty($booking->student->phone))
                                        <span class="uc-msg-label">Phone:</span>
                                        <a href="tel:{{ $booking->student->phone }}" style="text-decoration:underline;"><strong>{{ $booking->student->phone }}</strong></a><br>
                                    @endif

                                    <span class="uc-msg-label">Property:</span>
                                    {{ $booking->property->building_name }}<br>

                                    <span class="uc-msg-label">Since:</span>
                                    {{ $booking->created_at->format('d M Y') }}

                                    <div class="uc-msg-pill uc-msg-pill-approved" style="margin-top: 8px;">
                                        Active tenant
                                    </div>
                                </div>

                                <div class="uc-msg-actions">
                                    <a href="{{ route('landlord.properties.show', $booking->property->id) }}"
                                       class="uc-msg-btn uc-msg-btn-ghost">
                                        View property
                                    </a>

                                    <a href="{{ route('landlord.bookings.show', $booking->id) }}"
                                       class="uc-msg-btn uc-msg-btn-primary">
                                        View booking
                                    </a>

                                    <form action="{{ route('landlord.bookings.cancelStudent', ['booking' => $booking->id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="uc-btn-primary uc-msg-btn-danger" 
                                                style="background-color:#b91c1c;border-color:#7f1d1d;">
                                            Cancel tenant
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="uc-msg-empty">
                                <p class="uc-msg-empty-text">
                                    You have no active tenants yet. Approved bookings will appear here.
                                </p>
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
