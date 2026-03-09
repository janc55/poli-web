<?php

namespace App\Filament\Pages;

use App\Services\UpdateManager;
use Filament\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class UpdateSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Actualizar Sistema';

    protected static ?string $title = 'Gestor de Actualizaciones';

    protected static string $view = 'filament.pages.update-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Subir Paquete de Actualización')
                    ->description('Seleccione el archivo ZIP generado localmente para actualizar el sistema.')
                    ->schema([
                        FileUpload::make('zip_file')
                            ->label('Archivo ZIP')
                            ->required()
                            ->acceptedFileTypes(['application/zip'])
                            ->directory('updates'),

                        TextInput::make('password')
                            ->label('Contraseña de Seguridad')
                            ->password()
                            ->required()
                            ->helperText('Ingrese la contraseña configurada en su archivo .env (UPDATE_PASSWORD)'),
                    ])
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $zipPath = Storage::disk('public')->path($data['zip_file']);

        $manager = new UpdateManager();
        $result = $manager->processUpdate($zipPath, $data['password']);

        if ($result['success']) {
            Notification::make()
                ->title($result['message'])
                ->body("Archivos actualizados: {$result['updated_files']}" . ($result['migration_run'] ? ". Migraciones ejecutadas." : ""))
                ->success()
                ->send();

            // Cleanup
            Storage::disk('public')->delete($data['zip_file']);
            $this->form->fill();
        } else {
            Notification::make()
                ->title('Error en la actualización')
                ->body($result['message'])
                ->danger()
                ->send();
        }
    }
}
