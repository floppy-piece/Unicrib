{{-- resources/views/student/notifications.blade.php --}}
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
    {{-- Student: booking confirmations --}}
    <div class="uc-msg-page">
            <div class="uc-msg-container">
                <div class="uc-msg-card">
                    <div class="uc-msg-inner">
                        @if($user && $user->category === 'student')
                           

                            <h3 class="uc-msg-section-title">Booking updates</h3>

                            @forelse($notifications as $notification)
                                <div class="uc-msg-item">
                                                                     
                                        
                                    @foreach ($bookings as $booking)
                                        {{-- property may be null if missing, so use optional(?) --}}
                                        • {{ ($booking->property)->building_name }}
                                        <p class="uc-price-main">
                                        KES {{ ($booking->property)->price }}
                                        </p>
                                        
                                    @endforeach
                                    <div class="uc-msg-meta">
                                        Request Date &rarr; {{ $notification->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div class="uc-msg-meta">
                                        {{ $notification->status }} On &rarr; {{ $notification->updated_at->format('d M Y, H:i') }}
                                    </div>

                                    <div class="uc-msg-body">
                                        {{ $notification->data['message'] ?? 'Update on your booking.' }}
                                    </div> 
                                    <div>
                                        @if($notification->status === 'pending')
                                            <span class="uc-msg-pill uc-msg-pill-pending">Pending</span>
                                        @elseif($notification->status === 'approved')
                                            <span class="uc-msg-pill uc-msg-pill-approved">Approved</span>
                                        @else
                                            <span class="uc-msg-pill uc-msg-pill-rejected">Rejected</span>
                                        @endif
                                    </div>
                                    <hr style="height:1px;border:none;background-color:white;opacity:0.5;">
                                    {{-- 
                                         
                                    
                                    <p class="uc-subline">
                                    {{ ucfirst($notification->notification_type) }} • Area: {{ ucfirst($notification->area) }}
                                    @if($notification->street)
                                        • {{ $notification->street }}
                                    @endif
                                    @if($notification->building_name)
                                        
                                    @endif
                                    </p>
                                    @if($notification->landmark)
                                        <p class="uc-landmark">
                                            Landmark: {{ $notification->landmark }}
                                        </p>
                                    @endif --}}
                                    
                        @empty
                            <div class="uc-msg-empty">
                                <p class="uc-msg-empty-text">
                                    You have no booking updates yet.
                                </p>
                            </div>
                        @endforelse
                    @endif
                    </div>
                </div>
            </div>
    </div>
</x-app-layout>