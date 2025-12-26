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
        .uc-prop-link {
            display: inline-block;
            margin-top: 8px;
            font-size: 12px;
            color: #93c5fd;
            text-decoration: none;
        }
        .uc-prop-link:hover {
            text-decoration: underline;
        }

        .uc-empty-text {
            font-size: 13px;
            color: #9ca3af;
        }

        .uc-pagination {
            margin-top: 16px;
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
    </style>

    <x-slot name="header">
        <div>
            <h2 class="uc-header-title">
            {{ __('Student') }}
            </h2>
            <p class="uc-header-sub">
                Browse and book available properties near campus.
            </p>
        </div>
    </x-slot>

    <div class="uc-page">
        <div class="uc-container">
            <div class="uc-card">
                @if($properties->count())
                    <div class="uc-grid">
                        @foreach($properties as $property)
                            <div class="uc-prop-card">
                                <h3 class="uc-prop-title">
                                    {{ $property->title }}
                                </h3>
                                <p class="uc-prop-line">
                                    {{ ucfirst($property->property_type) }} •
                                    KES {{ number_format($property->price) }}
                                </p>
                                <p class="uc-prop-line">
                                    Area: {{ ucfirst($property->area) }}
                                </p>
                                <p class="uc-prop-desc">
                                    {{ $property->description }}
                                </p>
                                <a href="{{ route('properties.show', $property->id) }}"
                                   class="uc-prop-link">
                                    View details
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- If you used paginate() --}}
                    <div class="uc-pagination">
                        {{ $properties->links() }}
                    </div>
                @else
                    <p class="uc-empty-text">
                        No properties available yet.
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
