<footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <img src="{{ asset('images/logo-symbol.png') }}" alt="Policonsultorio UNIOR" class="h-10 w-10">
                    <div>
                        <h3 class="font-bold">POLICONSULTORIO UNIOR</h3>
                    </div>
                </div>
                <p class="text-gray-400">
                    Innovación en salud, educación y servicio. Tu bienestar es nuestra prioridad.
                </p>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Servicios</h4>
                <ul class="space-y-2 text-gray-400">
                    <li>Pediatría</li>
                    <li>Ginecología</li>
                    <li>Medicina Interna</li>
                    <li>Medicina General</li>
                    <li>Laboratorio Clínico</li>
                    <li>Ecografía</li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Contacto</h4>
                <div class="space-y-2 text-gray-400">
                    <p>📞 252-81110</p>
                    <p>✉️ policonsultoriunior@gmail.com</p>
                    <p>📍 Calle Potosí entre Bolívar y Sucre</p>
                    <p>🕒 Lunes a Viernes: 8:00 a 19:00</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ now()->year }} Policonsultorio UNIOR. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>