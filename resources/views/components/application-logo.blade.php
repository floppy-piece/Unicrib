{{-- resources/views/components/application-logo.blade.php --}}
<img src="{{ asset('images/application-logo.svg') }}" 
     alt="{{ config('app.name') }} Logo" 
     {{ $attributes->merge(['class' => 'h-16 w-auto object-contain']) }}>