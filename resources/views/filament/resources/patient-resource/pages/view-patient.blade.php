<x-filament-panels::page>
    <div class="space-y-8">
        <!-- Encabezado del Paciente -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                {{ $record->first_name }} {{ $record->last_name }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Paciente desde: {{ $record->created_at->format('d/m/Y') }}
            </p>
        </div>

        <!-- Botón manual como fallback -->
        @if(auth()->user()->can('historialmedico.crear') && auth()->user()->hasRole('doctor'))
            <div class="flex justify-end mb-6">
                <x-filament::button 
                    icon="heroicon-o-document-plus" 
                    wire:click="$dispatch('open-modal', { id: 'create-medical-record' })"
                    class="bg-primary-600 hover:bg-primary-700"
                >
                    Agregar Historial Médico
                </x-filament::button>
            </div>
        @endif

        <!-- Sección de Historiales Médicos -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
                Historial Médico
            </h2>
            
            @if($record->medicalRecords->isNotEmpty())
                <x-filament::card>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($record->medicalRecords->sortByDesc('date') as $medicalRecord)
                            <div class="py-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            Consulta con Dr. {{ $medicalRecord->doctor->first_name }} {{ $medicalRecord->doctor->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $medicalRecord->date->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs font-medium rounded-full">
                                        {{ $medicalRecord->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <strong class="block text-gray-700 dark:text-gray-300 mb-1">Síntomas:</strong>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $medicalRecord->symptoms ?: 'No registrado' }}</p>
                                    </div>
                                    <div>
                                        <strong class="block text-gray-700 dark:text-gray-300 mb-1">Diagnóstico:</strong>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $medicalRecord->diagnosis ?: 'No registrado' }}</p>
                                    </div>
                                    <div>
                                        <strong class="block text-gray-700 dark:text-gray-300 mb-1">Tratamiento:</strong>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $medicalRecord->treatment ?: 'No registrado' }}</p>
                                    </div>
                                </div>
                                
                                @if($medicalRecord->notes)
                                    <div class="mt-3">
                                        <strong class="block text-gray-700 dark:text-gray-300 mb-1">Notas adicionales:</strong>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $medicalRecord->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-filament::card>
            @else
                <x-filament::card>
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400 text-lg">
                            No hay historiales médicos registrados para este paciente.
                        </p>
                    </div>
                </x-filament::card>
            @endif
        </div>

        <!-- Sección de Citas -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">
                Citas del Paciente
            </h2>
            
            @if($record->appointments->isNotEmpty())
                <x-filament::card>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($record->appointments->sortByDesc('scheduled_at') as $appointment)
                            <div class="py-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            Cita con Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $appointment->service->name }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $appointment->scheduled_at->format('d/m/Y H:i') }}
                                        </p>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            {{ $appointment->scheduled_at->isPast() ? 
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : 
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                            {{ $appointment->scheduled_at->isPast() ? 'Completada' : 'Programada' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-3">
                                    <div>
                                        <strong class="block text-gray-700 dark:text-gray-300 mb-1">Estado:</strong>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @switch($appointment->status)
                                                @case('scheduled') 
                                                    bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 
                                                    @break
                                                @case('completed') 
                                                    bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 
                                                    @break
                                                @case('cancelled') 
                                                    bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 
                                                    @break
                                                @default 
                                                    bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                            @endswitch">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong class="block text-gray-700 dark:text-gray-300 mb-1">Duración:</strong>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            @php
                                                // Obtener la duración del horario del doctor
                                                $duration = $appointment->doctor->schedules
                                                    ->where('day_of_week', $appointment->scheduled_at->dayOfWeek)
                                                    ->first()?->appointment_duration ?? 'N/A';
                                            @endphp
                                            {{ $duration }} @if($duration !== 'N/A') minutos @endif
                                        </p>
                                    </div>
                                </div>
                                
                                @if($appointment->notes)
                                    <div class="mt-3">
                                        <strong class="block text-gray-700 dark:text-gray-300 mb-1">Notas de la cita:</strong>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $appointment->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-filament::card>
            @else
                <x-filament::card>
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400 text-lg">
                            No hay citas registradas para este paciente.
                        </p>
                    </div>
                </x-filament::card>
            @endif
        </div>
    </div>
</x-filament-panels::page>