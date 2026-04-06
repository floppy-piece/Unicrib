{{-- resources/views/properties/show.blade.php --}}
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
        .uc-property-title {
            font-size: 18px;
            font-weight: 600;
            color: #f9fafb;
            margin-bottom: 6px;
        }
        .uc-property-desc {
            font-size: 14px;
            color: #e5e7eb;
            margin-bottom: 12px;
        }
        .uc-property-meta {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 4px;
        }
        .uc-property-meta strong {
            color: #e5e7eb;
        }
        .uc-gallery {
            margin-top: 16px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 10px;
        }
        .uc-gallery img {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #1f2937;
            object-fit: cover;
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
                {{ $property->title }}
            </h2>
            <p class="uc-header-subtitle">
                Property details and booking information.
            </p>
        </div>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            <div class="uc-card">
                <h3 class="uc-property-title">
                    {{ $property->title }}
                </h3>

                <p class="uc-property-desc">
                    {{ $property->description }}
                </p>

                <p class="uc-property-meta">
                    <strong>Price:</strong> KES {{ number_format($property->price) }} / month
                </p>
                <p class="uc-property-meta">
                    <strong>Area:</strong> {{ ucfirst($property->area) }}
                </p>
                <p class="uc-property-meta">
                    <strong>Type:</strong> {{ ucfirst($property->property_type) }}
                </p>
                <p class="uc-property-meta">
                    <strong>Capacity:</strong> {{ (int)($property->capacity ?? 0) }} rooms
                </p>

                @if(!empty($property->images))
                    <div class="uc-gallery">
                        @foreach($property->images as $img)
                            <img src="{{ asset('storage/'.$img) }}" alt="{{ $property->title }}">
                        @endforeach
                    </div>
                @endif

                <a href="{{ url()->previous() }}" class="uc-back-link">
                    ← Back
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
