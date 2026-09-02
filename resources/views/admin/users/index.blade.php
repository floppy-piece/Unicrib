{{-- resources/views/admin/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Admin • Users</h2>
                <p class="text-xs text-slate-500">Manage all UniCrib users, roles, and statuses.</p>
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
                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-xs font-semibold text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-4">
                    <h3 class="text-base font-bold text-slate-900">Unicrib Users</h3>

                    {{-- Status filter links --}}
                    <div class="flex items-center gap-1.5 flex-wrap text-xs">
                        <a href="{{ route('admin.users.index') }}"
                           class="px-3 py-1 rounded-lg font-semibold transition {{ !$category ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            All ({{ array_sum($categoryCounts) }})
                        </a>
                        @foreach (['student','landlord','admin'] as $st)
                            <a href="{{ route('admin.users.index', ['category' => $st]) }}"
                               class="px-3 py-1 rounded-lg font-semibold transition {{ $category === $st ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ ucfirst($st) }} ({{ $categoryCounts[$st] ?? 0 }})
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4 font-semibold">ID</th>
                                <th class="py-3 px-4 font-semibold">Name &amp; email</th>
                                <th class="py-3 px-4 font-semibold">Category</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold">Updated at</th>
                                <th class="py-3 px-4 font-semibold">Expires at</th>
                                <th class="py-3 px-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/60 transition align-middle">
                                <td class="py-3 px-4 font-medium text-slate-900">#{{ $user->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-900">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ $user->email }}
                                    </div>
                                </td>

                                <td class="py-3 px-4">                        
                                    @php $st = $user->category; @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>

                                <td class="py-3 px-4">
                                    {{-- show verification for all users based on boolean is_verified --}}
                                    @if($user->category === 'student')
                                        @if($user->hasValidStudentAccess())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Verified</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Unverified</span>
                                        @endif
                                    @else
                                        @if ($user->status === 'verified')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $user->is_verified ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">Verified</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $user->is_verified ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">Unverified</span>
                                        @endif
                                    @endif
                                </td>

                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                                    {{ $user->updated_at }}
                                </td>
                                
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                                    {{ $user->access_expires_at ?? '—' }}
                                </td>

                                <td class="py-3 px-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        {{-- Toggle is_verified --}}
                                        <form action="{{ route('admin.users.updateStatus', $user) }}"
                                              method="POST"
                                              class="inline-flex items-center gap-1">
                                            @csrf
                                            <select name="is_verified" class="rounded-xl border-slate-200 text-slate-700 text-xs py-1 px-2 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                <option value="1" {{ $user->is_verified ? 'selected' : '' }}>Verified</option>
                                                <option value="0" {{ !$user->is_verified ? 'selected' : '' }}>Unverified</option>
                                            </select>
                                            <button type="submit" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg transition shadow-sm">
                                                Save
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.users.destroy', $user) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Delete this user? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-rose-600 hover:text-rose-800 font-medium text-xs bg-transparent border-none p-0 cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-8 text-center text-slate-400 text-xs">No users found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>