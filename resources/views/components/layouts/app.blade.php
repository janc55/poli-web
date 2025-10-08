<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policonsultorio UNIOR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/logo-symbol.png') }}">
    <link rel="shortcut icon" sizes="192x192" href="{{ asset('/images/logo-symbol.png') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
</head>
<body class="font-sans antialiased">
    @include('components.partials.header')
    
    <main>
        {{ $slot }}
    </main>
    
    @include('components.partials.footer')
</body>
</html>