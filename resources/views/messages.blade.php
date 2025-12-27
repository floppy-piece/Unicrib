<x-app-layout>
    <x-slot name="header">
        <h2 class="uc-header-title">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            <div class="uc-card">
                <div class="uc-card-inner">
                    @php
                        $user = auth()->user();
                    @endphp

                    {{-- Landlord section: booking requests --}}
                    @if($user && $user->category === 'landlord')
                        <h3 class="uc-section-title">Booking Requests</h3>

                        @forelse($landlordBookings as $booking)
                            <div style="margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #111827;">
                                <p style="color:#e5e7eb;font-size:14px;">
                                    <strong>Student:</strong> {{ $booking->student->name }}<br>
                                    <strong>Property:</strong> {{ $booking->property->title }}<br>
                                    <strong>Status:</strong> {{ ucfirst($booking->status) }}
                                </p>

                                @if($booking->status === 'pending')
                                    <form method="POST"
                                          action="{{ route('landlord.bookings.approve', $booking->id) }}"
                                          style="display:inline;">
                                        @csrf
                                        <button type="submit" class="uc-btn uc-btn-primary">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('landlord.bookings.reject', $booking->id) }}"
                                          style="display:inline;margin-left:8px;">
                                        @csrf
                                        <button type="submit" class="uc-btn uc-btn-ghost">
                                            Reject
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <p style="color:#9ca3af;font-size:14px;">
                                No pending booking requests.
                            </p>
                        @endforelse
                    @endif

                    {{-- Student section: booking confirmations --}}
                    @if($user && $user->category === 'student')
                        @php
                            $notifications = $user->notifications()->latest()->get();
                        @endphp

                        <h3 class="uc-section-title">Booking Updates</h3>

                        @forelse($notifications as $notification)
                            <div style="margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid #111827;">
                                {{ $notification->data['message'] ?? 'Update on your booking.' }}
                            </div>
                        @empty
                            <p style="color:#9ca3af;font-size:14px;">
                                You have no booking updates yet.
                            </p>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

