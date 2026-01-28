<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Models\MedicalRecord;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;
    protected static string $view = 'filament.resources.patient-resource.pages.view-patient';

    // Propiedades para el formulario
    public $symptoms = '';
    public $diagnosis = '';
    public $treatment = '';
    public $notes = '';

    // Método para abrir el modal (si decides usar modal)
    public function openCreateMedicalRecordModal()
    {
        $this->dispatch('open-modal', id: 'create-medical-record');
    }

    // Método para crear el historial médico
    public function createMedicalRecord()
    {
        $this->validate([
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        MedicalRecord::create([
            'patient_id' => $this->record->id,
            'doctor_id' => auth()->id(),
            'date' => now(),
            'symptoms' => $this->symptoms,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
        ]);

        // Limpiar campos
        $this->reset(['symptoms', 'diagnosis', 'treatment', 'notes']);

        // Mostrar notificación de éxito
        Notification::make()
            ->title('Historial médico creado')
            ->body('El historial médico se ha creado correctamente.')
            ->success()
            ->send();

        // Cerrar modal si estás usando uno
        $this->dispatch('close-modal', id: 'create-medical-record');
    }

    // Opcional: Si quieres usar acciones de Filament
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('createMedicalRecord')
                ->label('Agregar Historial Médico')
                ->icon('heroicon-o-document-plus')
                ->visible(fn() => auth()->user()->can('historialmedico.crear') && 
                                 auth()->user()->hasRole('doctor'))
                ->form([
                    \Filament\Forms\Components\Textarea::make('symptoms')
                        ->label('Síntomas')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                    \Filament\Forms\Components\Textarea::make('diagnosis')
                        ->label('Diagnóstico')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                    \Filament\Forms\Components\Textarea::make('treatment')
                        ->label('Tratamiento')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                    \Filament\Forms\Components\Textarea::make('notes')
                        ->label('Notas adicionales')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    MedicalRecord::create([
                        'patient_id' => $this->record->id,
                        'doctor_id' => auth()->id(),
                        'date' => now(),
                        'symptoms' => $data['symptoms'],
                        'diagnosis' => $data['diagnosis'],
                        'treatment' => $data['treatment'],
                        'notes' => $data['notes'] ?? null,
                    ]);

                    Notification::make()
                        ->title('Historial médico creado exitosamente')
                        ->success()
                        ->send();
                })
                ->modalWidth('4xl'), // Opcional: hacer el modal más ancho
        ];
    }
}
