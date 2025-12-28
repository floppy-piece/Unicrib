<x-app-layout>
    <style>
        .uc-student-pay-page {
            background: radial-gradient(circle at top, #111827 0, #020617 55%);
            min-height: 100vh;
            padding: 16px 0 24px;
        }
        .uc-student-pay-container {
            max-width: 720px;
            margin: 0 auto;
            padding: 0 16px;
        }
        .uc-student-pay-card {
            border-radius: 12px;
            border: 1px solid #111827;
            background-color: #020617;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
            overflow: hidden;
        }
        .uc-student-pay-inner {
            padding: 18px 18px 20px;
        }
        .uc-student-pay-header {
            margin-bottom: 10px;
        }
        .uc-student-pay-title {
            font-size: 18px;
            font-weight: 600;
            color: #f9fafb;
        }
        .uc-student-pay-subtitle {
            font-size: 13px;
            color: #9ca3af;
        }

        .uc-student-pay-section-title {
            font-size: 15px;
            font-weight: 600;
            color: #e5e7eb;
            padding-bottom: 6px;
            border-bottom: 1px solid #111827;
            margin-bottom: 10px;
        }
        .uc-student-pay-text {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 14px;
        }

        .uc-student-pay-error {
            margin-bottom: 12px;
            background-color: #220b0f;
            border: 1px solid #7f1d1d;
            color: #fecaca;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
        }

        .uc-student-pay-info {
            margin-top: 6px;
            font-size: 12px;
            color: #6b7280;
        }

        .uc-pay-btn-row {
            display: flex;
            justify-content: flex-start;
            margin-top: 8px;
        }
        .uc-pay-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 22px;
            border-radius: 999px;
            border: 1px solid #4f46e5;
            background: linear-gradient(90deg, #6366f1 0%, #4f46e5 50%, #8b5cf6 100%);
            color: #f9fafb;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: .02em;
            cursor: pointer;
            box-shadow: 0 18px 40px rgba(79,70,229,0.55);
            transition:
                background-color .18s ease,
                box-shadow .18s ease,
                transform .12s ease,
                border-color .18s ease;
        }
        .uc-pay-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 22px 50px rgba(79,70,229,0.7);
            border-color: #818cf8;
        }
        .uc-pay-btn:active {
            transform: translateY(0);
            box-shadow: 0 14px 30px rgba(15,23,42,0.8);
            background: linear-gradient(90deg, #4f46e5 0%, #4338ca 50%, #7c3aed 100%);
        }
    </style>

    <x-slot name="header">
        <h2 class="uc-header-title">
            {{ __('Student Access Payment') }}
        </h2>
    </x-slot>

    <div class="uc-student-pay-page">
        <div class="uc-student-pay-container">
            <div class="uc-student-pay-card">
                <div class="uc-student-pay-inner">
                    <div class="uc-student-pay-header">
                        <div class="uc-student-pay-title">Unlock full property details</div>
                        <div class="uc-student-pay-subtitle">
                            One-time access fee that covers all properties for 6 hours.
                        </div>
                    </div>

                    <h3 class="uc-student-pay-section-title">Pay KES 50 to view property details</h3>
                    <p class="uc-student-pay-text">
                        This payment unlocks full property details, landlord contacts, and maps for 6 hours on your account.
                    </p>
                    <p class="uc-student-pay-info">
                        You will be redirected back here after payment to continue browsing.
                    </p>

                    @if(session('error'))
                        <div class="uc-student-pay-error">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('payments.student.pay') }}">
                        @csrf
                        <div class="uc-btn-row">
                            <button type="submit" class="uc-pay-btn">
                                Pay KES 50 now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
