<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Portal Cautivo' }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Livewire is handled automatically via Livewire v3, but we can set custom config if needed -->
    <style>
        :root {
            --color-primary: {{ $zona->color_primario ?? '#1a56db' }};
            --color-secondary: {{ $zona->color_secundario ?? '#ffffff' }};
        }
        body {
            background-color: var(--color-secondary);
        }
        .text-primary { color: var(--color-primary); }
        .bg-primary { background-color: var(--color-primary); }
        .border-primary { border-color: var(--color-primary); }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-center items-center p-4">

    <!-- Header / Logo -->
    <div class="mb-6 flex justify-center w-full">
        @if(isset($zona) && $zona->logo_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($zona->logo_path) }}" alt="{{ $zona->nombre ?? 'Portal' }}" class="h-20 object-contain">
        @else
            <!-- Generic WiFi Icon -->
            <svg class="w-20 h-20 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
            </svg>
        @endif
    </div>

    <!-- Main Content Container -->
    <main class="w-full max-w-md bg-white shadow-2xl rounded-2xl overflow-hidden relative">
        {{ $slot }}
    </main>

</body>
</html>