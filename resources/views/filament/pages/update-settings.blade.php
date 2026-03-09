<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" size="lg">
                Procesar Actualización
            </x-filament::button>
        </div>
    </form>

    <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center">
            <x-filament::icon icon="heroicon-m-information-circle" class="h-5 w-5 text-gray-400 mr-2" />
            Instrucciones de uso
        </h3>
        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            <ol class="list-decimal ml-5 space-y-1">
                <li>Ejecute localmente: <code>php artisan deploy:make-zip</code></li>
                <li>Suba el archivo generado (<code>update-*.zip</code>).</li>
                <li>Ingrese su <code>UPDATE_PASSWORD</code> de seguridad.</li>
                <li>Haga clic en "Procesar Actualización".</li>
            </ol>
        </div>
    </div>
</x-filament-panels::page>