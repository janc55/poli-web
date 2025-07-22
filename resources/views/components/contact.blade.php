<section id="contacto" class="py-20 bg-red-700 text-white">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Información de contacto --}}
            <div>
                <h2 class="text-4xl font-bold mb-6">Agenda tu Cita</h2>
                <p class="text-xl mb-8 text-red-100">
                    Estamos aquí para cuidar tu salud. Contáctanos y agenda tu cita de manera fácil y rápida.
                </p>

                <div class="space-y-6">
                    <div class="flex items-center">
                        <x-lucide-phone class="w-6 h-6 mr-4 text-red-200" />
                        <div>
                            <p class="font-semibold">Teléfono</p>
                            <p class="text-red-100">252-81110</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <x-lucide-mail class="w-6 h-6 mr-4 text-red-200" />
                        <div>
                            <p class="font-semibold">Email</p>
                            <p class="text-red-100">policonsultoriounior@gmail.com</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <x-lucide-map-pin class="w-6 h-6 mr-4 text-red-200" />
                        <div>
                            <p class="font-semibold">Dirección</p>
                            <p class="text-red-100">Calle Potosí entre Bolívar y Sucre</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <x-lucide-clock class="w-6 h-6 mr-4 text-red-200" />
                        <div>
                            <p class="font-semibold">Horarios</p>
                            <p class="text-red-100">Lunes a Viernes: 8:00 a 19:00</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario --}}
            <div class="bg-white rounded-2xl p-8 text-gray-800 shadow-md">
                <h3 class="text-2xl font-bold mb-6 text-center">Solicitar Información</h3>
                <form id="whatsappForm" class="space-y-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium mb-2">Nombre completo</label>
                        <input
                            id="nombre"
                            type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Tu nombre completo"
                            required
                        />
                    </div>
                    <div>
                        <label for="telefono" class="block text-sm font-medium mb-2">Teléfono</label>
                        <input
                            id="telefono"
                            type="tel"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Tu número de teléfono"
                            required
                        />
                    </div>
                    <div>
                        <label for="servicio" class="block text-sm font-medium mb-2">Servicio de interés</label>
                        <select id="servicio" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                            <option value="">Selecciona un servicio</option>
                            <option>Pediatría</option>
                            <option>Ginecología</option>
                            <option>Medicina Interna</option>
                            <option>Medicina General</option>
                            <option>Laboratorio</option>
                            <option>Ecografía</option>
                            <option>Rayos X</option>
                            <option>Tomografía</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 rounded-lg transition">
                        Enviar por WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('whatsappForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nombre = document.getElementById('nombre').value;
    const telefono = document.getElementById('telefono').value;
    const servicio = document.getElementById('servicio').value;
    
    const mensaje = `Hola, mi nombre es ${nombre} (Tel: ${telefono}). Estoy interesado en el servicio de ${servicio}. Por favor contáctenme.`;
    const mensajeCodificado = encodeURIComponent(mensaje);
    
    window.open(`https://api.whatsapp.com/send?phone=59171765077&text=${mensajeCodificado}`, '_blank');
});
</script>
