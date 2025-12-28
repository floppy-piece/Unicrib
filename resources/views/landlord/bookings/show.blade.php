{{-- resources/views/landlord/bookings/show.blade.php --}}
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
        .uc-card {
            border-radius: 12px;
            border: 1px solid #1f2937;
            background-color: #020617;
            padding: 18px 18px;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
        }
        .uc-section-title {
            font-size: 15px;
            font-weight: 600;
            color: #e5e7eb;
            margin-bottom: 8px;
        }
        .uc-text {
            font-size: 13px;
            color: #e5e7eb;
        }
        .uc-text strong {
            color: #f9fafb;
        }
        .uc-divider {
            border-color: #111827;
            margin: 14px 0;
        }
        .uc-status-pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
        }
        .uc-status-approved {
            background-color: rgba(16,185,129,0.15);
            color: #6ee7b7;
            border: 1px solid rgba(16,185,129,0.5);
        }
        .uc-status-pending {
            background-color: rgba(59,130,246,0.15);
            color: #bfdbfe;
            border: 1px solid rgba(59,130,246,0.5);
        }
        .uc-status-rejected {
            background-color: rgba(239,68,68,0.15);
            color: #fecaca;
            border: 1px solid rgba(239,68,68,0.5);
        }
        .uc-back-link {
            display: inline-flex;
            align-items: center;
            margin-top: 18px;
            font-size: 13px;
            color: #6366f1;
            text-decoration: none;
        }
        .uc-back-link:hover {
            color: #a5b4fc;
        }
    </style>

    <x-slot name="header">
        <div>
            <h2 class="uc-header-title">
                Booking #{{ $booking->id }}
            </h2>
            <p class="uc-header-subtitle">
                Tenant and property details for this booking.
            </p>
        </div>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            <div class="uc-card">
                <h3 class="uc-section-title">
                    Tenant details
                </h3>

                <p class="uc-text">
                    <strong>Name:</strong> {{ $booking->student->name }}
                </p>

                @if(!empty($booking->student->email))
                    <p class="uc-text">
                        <strong>Email:</strong> {{ $booking->student->email }}
                    </p>
                @endif

                @if(!empty($booking->student->phone))
                    <p class="uc-text">
                        <strong>Phone:</strong> {{ $booking->student->phone }}
                    </p>
                @endif

                <hr class="uc-divider">

                <h3 class="uc-section-title">
                    Property
                </h3>
                <p class="uc-text">
                    <strong>Title:</strong> {{ $booking->property->title }}
                </p>
                <p class="uc-text">
                    <strong>Price:</strong> KES {{ number_format($booking->property->price) }} / month
                </p>

                <hr class="uc-divider">

                <h3 class="uc-section-title">
                    Booking info
                </h3>
                <p class="uc-text">
                    <strong>Status:</strong>
                    @php
                        $status = $booking->status;
                        $statusClass = match ($status) {
                            'approved' => 'uc-status-approved',
                            'rejected' => 'uc-status-rejected',
                            default    => 'uc-status-pending',
                        };
                    @endphp
                    <span class="uc-status-pill {{ $statusClass }}">
                        {{ ucfirst($status) }}
                    </span>
                </p>
                <p class="uc-text">
                    <strong>Created at:</strong> {{ $booking->created_at->format('d M Y H:i') }}
                </p>

                <a href="{{ url()->previous() }}" class="uc-back-link">
                    ← Back
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
