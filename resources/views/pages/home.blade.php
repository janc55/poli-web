<x-layouts.app>
    {{-- Hero principal --}}
    <x-hero />

    <x-services/>

    <x-promotions :promotions="$promotions"/>

    <livewire:news-section />

    <x-contact/>

    <!-- Más secciones aquí -->
</x-layouts.app>