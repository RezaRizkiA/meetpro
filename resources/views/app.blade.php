<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>{{ config('app.name', 'KeyPerson') }}</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/key-color.png') }}" />

    {{-- Theme Anti-Flash Script (runs before page renders) --}}
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            // Default to light mode if no preference saved
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    {{-- Scripts --}}
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>

<body class="bg-background text-foreground font-sans antialiased selection:bg-blue-100 selection:text-blue-700">
    @inertia
</body>

</html>