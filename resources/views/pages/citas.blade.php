<x-layouts.app>
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            {{-- Título --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Agendar una Cita Médica</h1>
                <p class="text-xl text-gray-600">Busca por especialidad y selecciona un horario disponible.</p>
            </div>

            {{-- Livewire Component --}}
            <livewire:cita-search/>
        </div>
    </section>
</x-layouts.app>