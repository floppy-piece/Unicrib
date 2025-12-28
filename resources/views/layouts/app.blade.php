{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <x-icon/>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link
            href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
            rel="stylesheet"
        />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                margin: 0;
                font-family: system-ui, -apple-system, BlinkMacSystemFont, "Figtree", sans-serif;
                background-color: #020617;
                color: #e5e7eb;
            }
            .uc-layout-wrapper {
                min-height: 100vh;
                background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
                display: flex;
                flex-direction: column;
            }
            .uc-header {
                background-color: #020617;
                border-bottom: 1px solid #111827;
            }
            .uc-header-inner {
                max-width: 1120px;
                margin: 0 auto;
                padding: 10px 16px;
                display: flex;
                align-items: center;
                color: #e5e7eb;
            }
            .uc-header-inner h5 {
                margin: 0;
                margin-left: 8px;
                font-size: 12px;
                font-weight: 400;
                color: #9ca3af;
            }
            main {
                flex: 1;
            }
        </style>
    </head>
    <body>
        <div class="uc-layout-wrapper">
            {{-- Navigation (Livewire component that contains your nav code below) --}}
            <livewire:layout.navigation />

            {{-- Page Heading --}}
            @if (isset($header))
                <header class="uc-header">
                    <div class="uc-header-inner">
                        {{ $header }}
                        <h5>- {{ Auth()->user()->name }}</h5>
                        <div>
                        

                        </div>
                    </div>
                </header>
            @endif

            {{-- Page Content --}}
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
