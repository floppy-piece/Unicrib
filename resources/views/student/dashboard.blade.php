<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($properties->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                                    {{ $property->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ ucfirst($property->property_type) }} • KES {{ number_format($property->price) }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Area: {{ ucfirst($property->area) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2 line-clamp-2">
                                    {{ $property->description }}
                                </p>
                                <a href="{{ route('properties.show', $property->id) }}"
                                   class="inline-block mt-3 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                    View details
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- If you used paginate() --}}
                    <div class="mt-4">
                        {{ $properties->links() }}
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">
                        No properties available yet.
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
