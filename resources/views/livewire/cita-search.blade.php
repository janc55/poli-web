<div>
    {{-- Búsqueda y Filtros (igual que antes) --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Médico</label>
                <input type="text" wire:model.live="search" placeholder="Ej: Dr. Rivera Nogales" 
                       class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Especialidad</label>
                <select wire:model.live="serviceId" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500">
                    <option value="">Todas las especialidades</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                <input type="date" wire:model.live="selectedDate" min="{{ now()->format('Y-m-d') }}" 
                       class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500">
            </div>
        </div>
    </div>

    {{-- Lista de Doctores --}}
    @forelse($doctors as $doctor)
        <div class="bg-white rounded-lg border border-blue-200 p-6 mb-4 shadow-md">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ $doctor->avatar ?? asset('images/default-doctor-avatar.png') }}" alt="{{ $doctor->name }}" 
                         class="w-16 h-16 rounded-full object-cover">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $doctor->fullName }}</h3>
                        <p class="text-red-600">{{ $doctor->services->pluck('name')->implode(', ') }}</p>
                    </div>
                </div>
                <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 mt-2 md:mt-0">
                    Ver Perfil
                </button>
            </div>

            {{-- Horarios Disponibles --}}
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Horarios Disponibles ({{ $selectedDate }} - {{ ucfirst($dayOfWeek) }})</h4>
                    @if($doctor->schedules->count() > 0)
                        @foreach($doctor->schedules as $schedule)
                        
                            <div class="mb-4 p-3 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-600 mb-2">{{ $schedule->service->name ?? 'General' }}: 
                                    {{ $schedule->start_time_formatted }} - {{ $schedule->end_time_formatted }}
                                </p>
                                <ul class="space-y-1 text-sm">
                                    @foreach(collect($schedule->available_slots ?? []) as $slot)
                                        <li class="flex items-center justify-between p-2 bg-white border rounded">
                                            <span>{{ $slot }}</span>
                                            <button class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                                Reservar ({{ $schedule->appointment_duration }} min)
                                            </button>
                                        </li>
                                    @endforeach
                                    @if(collect($schedule->available_slots ?? [])->isEmpty())
                                        <p class="text-gray-400 text-xs italic mt-2">No hay slots disponibles en este rango.</p>
                                    @endif
                                </ul>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 italic">No hay horarios disponibles para este día.</p>
                    @endif
                </div>
                <div class="border-l border-gray-200 pl-4 md:pl-6">
                    <h4 class="font-semibold text-gray-700 mb-2">Horario de Atención</h4>
                    @php
                        $availableDays = $doctor->schedules->pluck('day_of_week')->unique()->sort();  // Días únicos y ordenados
                    @endphp
                    <ul class="text-sm text-gray-600 space-y-1">
                        @forelse($availableDays as $dia)
                            @php
                                $generalSchedule = $doctor->schedules->where('day_of_week', $dia)->first();
                            @endphp
                            <li class="flex items-center space-x-2">
                                <x-lucide-clock class="w-4 h-4 mr-2 flex-shrink-0 text-gray-500" />
                                {{ ucfirst($dia) }}: {{ $generalSchedule->start_time_formatted }} - {{ $generalSchedule->end_time_formatted }}
                            </li>
                        @empty
                            <li class="text-gray-400 italic">No hay horarios disponibles.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-gray-500">
            No se encontraron médicos disponibles para {{ ucfirst($dayOfWeek) }}. Ajusta los filtros.
        </div>
    @endforelse
</div>