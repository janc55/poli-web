<x-filament-panels::page>
    <div class="space-y-6">
        <h1 class="text-2xl font-bold">{{ $record->first_name }} {{ $record->last_name }}</h1>

        <div class="mt-6">
            <h2 class="text-lg font-semibold">Historial de Citas</h2>
            @if($record->appointments->isNotEmpty())
                <div class="mt-4">
                    <x-filament::card>
                        @foreach($record->appointments->sortByDesc('scheduled_at') as $appointment)
                            <div class="border-b py-4">
                                <div class="flex justify-between items-center">
                                    <div class="text-lg font-medium">
                                        Cita con el Dr. {{ $appointment->doctor->first_name }}
                                        ({{ $appointment->service->name }})
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $appointment->scheduled_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                
                                {{-- Buscamos el historial médico relacionado por doctor y fecha --}}
                                @php
                                    $medicalRecord = \App\Models\MedicalRecord::where('patient_id', $record->id)
                                        ->where('doctor_id', $appointment->doctor->user_id)
                                        ->whereDate('date', $appointment->scheduled_at)
                                        ->first();
                                @endphp

                                @if($medicalRecord)
                                    <div class="mt-2 text-sm text-gray-700">
                                        <strong>Síntomas:</strong>
                                        <p>{{ $medicalRecord->symptoms }}</p>
                                        <strong>Diagnóstico:</strong>
                                        <p>{{ $medicalRecord->diagnosis }}</p>
                                        <strong>Tratamiento:</strong>
                                        <p>{{ $medicalRecord->treatment }}</p>
                                    </div>
                                @else
                                    <div class="mt-2 text-sm text-gray-500">
                                        No se ha creado un historial clínico para esta cita.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </x-filament::card>
                </div>
            @else
                <p class="mt-2 text-gray-500">Este paciente no tiene citas registradas.</p>
            @endif
        </div>
    </div>
</x-filament-panels::page>