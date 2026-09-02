{{-- resources/views/admin/properties/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Admin • Properties</h2>
                <p class="text-xs text-slate-500">Review, verify, and control all landlord uploads.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-6">
                
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-xs font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900">All properties</h3>
                    <span class="text-xs text-slate-400 font-medium">Manage listings &amp; verifications</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Title</th>
                                <th class="py-3 px-4 font-semibold">Landlord</th>
                                <th class="py-3 px-4 font-semibold">Phone Number</th>
                                <th class="py-3 px-4 font-semibold">Area</th>
                                <th class="py-3 px-4 font-semibold">Verified</th>
                                <th class="py-3 px-4 font-semibold">Available</th>
                                <th class="py-3 px-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($properties as $property)
                            <tr class="hover:bg-slate-50/60 transition align-middle">
                                <td class="py-3 px-4 font-medium text-slate-900">#{{ $property->id }}</td>
                                <td class="py-3 px-4 font-medium text-slate-900">{{ $property->building_name }}</td>
                                <td class="py-3 px-4 text-slate-700">{{ optional($property->landlord)->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    @if(optional($property->landlord)->phone)
                                        <a href="tel:{{ $property->landlord->phone }}" class="text-indigo-600 hover:underline font-medium">
                                            {{ $property->landlord->phone }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">N/A</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-600">{{ ucfirst($property->area ?? 'n/a') }}</td>
                                <td class="py-3 px-4">
                                    @if($property->is_verified)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Verified</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Not verified</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($property->is_available)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">Available</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Flagged</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.properties.updateStatus', $property) }}"
                                          method="POST"
                                          class="inline-flex flex-col items-end gap-2">
                                        @csrf
                                        
                                        <div class="flex items-center gap-1.5">
                                            <label class="text-[11px] font-medium text-slate-500">Verified:</label>
                                            <select name="is_verified" class="rounded-lg border-slate-200 text-slate-700 text-[11px] py-1 px-2 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                <option value="1" {{ $property->is_verified ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$property->is_verified ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="flex items-center gap-1.5">
                                            <label class="text-[11px] font-medium text-slate-500">Available:</label>
                                            <select name="is_available" class="rounded-lg border-slate-200 text-slate-700 text-[11px] py-1 px-2 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                <option value="1" {{ $property->is_available ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$property->is_available ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-semibold px-3 py-1 rounded-lg transition shadow-sm mt-1">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-8 text-center text-slate-400 text-xs">No properties found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    {{ $properties->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>