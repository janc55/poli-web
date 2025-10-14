<header class="bg-white shadow-sm" x-data="{ open: false }">
    <div class="container mx-auto px-2 py-2">
        <div class="flex justify-between items-center">
            {{-- Logo --}}
            <div class="flex items-center space-x-3">
                <a href="/">
                    <img src="{{ asset('images/logo-horizontal.png') }}" alt="Policonsultorio UNIOR" class="h-15 w-auto">
                </a>
            </div>

            {{-- Menú en pantallas grandes --}}
            <nav class="hidden md:flex items-center space-x-8">
                <a href="/" class="hover:text-red-600">Inicio</a>
                <a href="/#servicios" class="hover:text-red-600">Servicios</a>
                <a href="/noticias" class="hover:text-red-600">Noticias</a>
                <a href="/#contacto" class="hover:text-red-600">Contacto</a>
                <a href="/admin/login" class="ml-4 px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition duration-150 ease-in-out">
                    Iniciar sesión
                </a>
            </nav>

            {{-- Botón hamburguesa --}}
            <button @click="open = !open" class="md:hidden focus:outline-none text-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        {{-- Menú móvil --}}
        <nav x-show="open" x-transition class="md:hidden mt-4 space-y-2">
            <a href="/" class="block px-4 py-2 text-gray-800 hover:bg-red-50 rounded">Inicio</a>
            <a href="/#servicios" class="block px-4 py-2 text-gray-800 hover:bg-red-50 rounded">Servicios</a>
            <a href="/noticias" class="block px-4 py-2 text-gray-800 hover:bg-red-50 rounded">Noticias</a>
            <a href="/#contacto" class="block px-4 py-2 text-gray-800 hover:bg-red-50 rounded">Contacto</a>
            <a href="/admin/login" class="block px-4 py-2 mt-2 text-gray-800 border border-gray-300 rounded hover:bg-gray-50">
                Iniciar sesión
            </a>
        </nav>
    </div>
</header>
