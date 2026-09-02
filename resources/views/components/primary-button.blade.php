{{-- resources/views/components/primary-button.blade.php --}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-sm shadow-indigo-200 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition']) }}>
    {{ $slot }}
</button>