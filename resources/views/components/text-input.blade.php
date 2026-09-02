{{-- resources/views/components/text-input.blade.php --}}
@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full h-8 px-3 text-xs bg-white text-slate-800 border border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed transition']) }}>