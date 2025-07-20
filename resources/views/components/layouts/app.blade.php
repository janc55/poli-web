<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policonsultorio UNIOR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="font-sans antialiased">
    @include('components.partials.header')
    
    <main>
        {{ $slot }}
    </main>
    
    @include('components.partials.footer')
</body>
</html>