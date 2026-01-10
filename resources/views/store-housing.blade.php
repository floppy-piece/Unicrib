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
            padding: 18px 18px 20px;
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

        .uc-grid {
            display: grid;
            grid-template-columns: minmax(0,1fr);
            gap: 16px;
        }
        @media (min-width: 768px) {
            .uc-grid {
                grid-template-columns: repeat(2, minmax(0,1fr));
            }
        }
        @media (min-width: 1024px) {
            .uc-grid {
                grid-template-columns: repeat(3, minmax(0,1fr));
            }
        }

        .uc-prop-card {
            border-radius: 10px;
            border: 1px solid #1f2937;
            background-color: #020617;
            padding: 12px 13px;
            transition: border-color 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
        }
        .uc-prop-card:hover {
            border-color: #6366f1;
            transform: translateY(-2px);
            box-shadow: 0 18px 40px rgba(15,23,42,0.8);
        }

        .uc-prop-title {
            font-size: 15px;
            font-weight: 600;
            color: #f9fafb;
            margin: 0;
        }
        .uc-prop-line {
            margin-top: 4px;
            font-size: 13px;
            color: #9ca3af;
        }
        .uc-prop-desc {
            margin-top: 6px;
            font-size: 12px;
            color: #6b7280;
            max-height: 3.2em;
            overflow: hidden;
        }

        .uc-empty-text {
            font-size: 13px;
            color: #9ca3af;
        }

        .uc-header-title {
            font-size: 18px;
            font-weight: 600;
            color: #f9fafb;
        }
        .uc-header-sub {
            margin-top: 2px;
            font-size: 13px;
            color: #9ca3af;
        }

        .uc-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .uc-pill-green {
            background-color: #064e3b;
            color: #a7f3d0;
            border: 1px solid #059669;
        }
        .uc-pill-amber {
            background-color: #78350f;
            color: #fed7aa;
            border: 1px solid #f59e0b;
        }

        .uc-form-label {
            display: block;
            font-size: 13px;
            color: #e5e7eb;
            margin-bottom: 4px;
        }
        .uc-form-input {
            width: 100%;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #374151;
            background-color: #020617;
            color: #f9fafb;
            font-size: 13px;
        }
        .uc-form-input:focus {
            outline: none;
            border-color: #6366f1;
        }
        .uc-form-help {
            margin-top: 4px;
            font-size: 11px;
            color: #9ca3af;
        }

        .uc-alert-success {
            background:#16a34a33;
            color:#bbf7d0;
            border:1px solid #16a34a;
            padding:0.75rem 1rem;
            border-radius:0.5rem;
            font-size:13px;
            margin-bottom:1rem;
        }
        .uc-alert-error {
            background:#7f1d1d33;
            color:#fecaca;
            border:1px solid #b91c1c;
            padding:0.75rem 1rem;
            border-radius:0.5rem;
            font-size:13px;
            margin-bottom:1rem;
        }
        .uc-alert-error ul {
            padding-left:1.2rem;
            margin:0;
        }

        .uc-tag {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 999px;
            background-color: #111827;
            color: #e5e7eb;
        }
    </style>

    <x-slot name="header">
        <div>
            <h2 class="uc-header-title">
                {{ __('Store Housing') }}
            </h2>
            <p class="uc-header-sub">
                Book secure storage for your belongings during holidays at our store house for KSh {{ $monthlyPrice }} per month.
            </p>
        </div>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            @if(auth()->user()->category === 'student')
                @if(auth()->user()->hasValidStudentAccess())
                    <span class="uc-pill uc-pill-green">Your student account is verified. You can book storage.</span>
                @else
                    <span class="uc-pill uc-pill-amber">Your account is not fully verified. Some services may be limited.</span>
                @endif
            @endif

            <div class="uc-card" style="margin-bottom:16px;">
                {{-- Messages --}}
                @if(session('success'))
                    <div class="uc-alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="uc-alert-error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Booking form --}}
                <h3 class="uc-prop-title">
                    Book storage space
                </h3>
                <p class="uc-prop-line">
                    Choose your start date and the number of months. You will be charged KSh {{ $monthlyPrice }} per month.
                </p>

                <form action="{{ route('storage.store') }}" method="POST" style="margin-top:12px;">
                    @csrf

                    <div class="uc-grid" style="grid-template-columns: repeat(2,minmax(0,1fr));">
                        <div>
                            <label for="start_date" class="uc-form-label">Start date</label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                class="uc-form-input"
                                required
                            >
                            <p class="uc-form-help">
                                The day you drop your belongings at the storage facility.
                            </p>
                        </div>

                        <div>
                            <label for="months" class="uc-form-label">Months (1–12)</label>
                            <input
                                type="number"
                                id="months"
                                name="months"
                                min="1"
                                max="12"
                                value="{{ old('months', 1) }}"
                                class="uc-form-input"
                                required
                            >
                            <p class="uc-form-help">
                                Storage duration. Total cost is months × KSh {{ $monthlyPrice }}.
                            </p>
                        </div>
                    </div>

                    <div style="margin-top:10px;display:flex;justify-content:space-between;align-items:center;font-size:13px;">
                        <span>Estimated total:</span>
                        <span id="storageTotal" style="font-weight:600;">KSh {{ $monthlyPrice }}</span>
                    </div>

                    <button type="submit" class="uc-btn-primary" style="width:100%;justify-content:center;margin-top:12px;">
                        Proceed to payment
                    </button>
                </form>
            </div>

            {{-- Existing storage bookings --}}
            <div class="uc-card">
                <h3 class="uc-prop-title">
                    Your storage bookings
                </h3>
                <p class="uc-prop-line">
                    Track active and past storage periods linked to your {{ config('app.name') }} account.
                </p>

                @if($bookings->isEmpty())
                    <p class="uc-empty-text" style="margin-top:10px;">
                        You have not booked any storage yet. Use the form above to create your first booking.
                    </p>
                @else
                    <div class="uc-grid" style="margin-top:12px;">
                        @foreach($bookings as $booking)
                            <div class="uc-prop-card">
                                <h4 class="uc-prop-title">
                                    Storage #{{ $booking->id }}
                                </h4>
                                <p class="uc-prop-line">
                                    {{ $booking->start_date }} – {{ $booking->end_date }}
                                </p>
                                <p class="uc-prop-line">
                                    Duration: {{ $booking->months }} month(s)
                                </p>
                                <p class="uc-prop-line">
                                    Total: KSh {{ number_format($booking->amount) }}
                                </p>
                                <p class="uc-prop-line">
                                    <span class="uc-tag">Status: {{ ucfirst($booking->status) }}</span>
                                    @if($booking->reference)
                                        <span style="margin-left:6px;font-size:11px;color:#9ca3af;">
                                            Ref: {{ $booking->reference }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        (function () {
            const monthly = {{ $monthlyPrice }};
            const monthsInput = document.getElementById('months');
            const totalSpan = document.getElementById('storageTotal');

            if (monthsInput && totalSpan) {
                function updateTotal() {
                    const m = parseInt(monthsInput.value || '1', 10);
                    const total = m * monthly;
                    totalSpan.textContent = 'KSh ' + total.toLocaleString();
                }

                monthsInput.addEventListener('input', updateTotal);
                updateTotal();
            }
        })();
    </script>
</x-app-layout>
