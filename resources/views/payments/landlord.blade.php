<x-app-layout>
    <style>
        .uc-pay-page {
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            min-height: 100vh;
            padding: 16px 0 24px;
        }
        .uc-pay-container {
            max-width: 720px;
            margin: 0 auto;
            padding: 0 16px;
        }
        .uc-pay-card {
            border-radius: 12px;
            border: 1px solid #111827;
            background-color: #020617;
            box-shadow: 0 24px 60px rgba(15,23,42,0.7);
            overflow: hidden;
        }
        .uc-pay-inner {
            padding: 18px 18px 20px;
        }
        .uc-pay-header {
            margin-bottom: 10px;
        }
        .uc-pay-title {
            font-size: 18px;
            font-weight: 600;
            color: #f9fafb;
        }
        .uc-pay-subtitle {
            font-size: 13px;
            color: #9ca3af;
        }

        .uc-pay-section-title {
            font-size: 15px;
            font-weight: 600;
            color: #e5e7eb;
            padding-bottom: 6px;
            border-bottom: 1px solid #111827;
            margin-bottom: 10px;
        }
        .uc-pay-text {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 14px;
        }

        .uc-pay-error {
            margin-bottom: 12px;
            background-color: #220b0f;
            border: 1px solid #7f1d1d;
            color: #fecaca;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
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
            {{ __('Landlord Verification Payment') }}
        </h2>
    </x-slot>

    <div class="uc-pay-page">
        <div class="uc-pay-container">
            <div class="uc-pay-card">
                <div class="uc-pay-inner">
                    <div class="uc-pay-header">
                        <div class="uc-pay-title">Verify your property as a landlord</div>
                        <div class="uc-pay-subtitle">
                            Pay once per semester to unlock property uploads and verification.
                        </div>
                    </div>

                    <h3 class="uc-pay-section-title">Pay KES 500 per semester</h3>
                    <p class="uc-pay-text">
                        This payment verifies your landlord account for one semester and allows you to upload and manage verified properties for students.
                    </p>

                    @if(session('error'))
                        <div class="uc-pay-error">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('payments.landlord.pay') }}">
                        @csrf
                        <div class="uc-pay-btn-row">
                            <button type="submit" class="uc-pay-btn">
                                Pay KES 500 now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
