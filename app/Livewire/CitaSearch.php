<?php

namespace App\Livewire;

use App\Models\Doctor;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Component;

class CitaSearch extends Component
{
    public $search = '';
    public $serviceId = '';
    public $selectedDate = '';
    public $services = [];

    public function mount()
    {
        $this->services = Service::orderBy('name')->get();
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->updatedServiceId();
    }

    public function updatedServiceId()
    {
        $this->reset(['search']);
    }

    public function updatedSelectedDate()
    {
        if (Carbon::parse($this->selectedDate)->isPast()) {
            $this->selectedDate = Carbon::today()->format('Y-m-d');
            session()->flash('error', 'Selecciona una fecha futura.');
        }
    }

    public function render()
    {
        // Calcula el día de la semana en español
        $dayOfWeek = Carbon::parse($this->selectedDate)->locale('es')->isoFormat('dddd'); // Ej: 'martes'
        $dayOfWeek = strtolower($dayOfWeek);

        // Variable local para serviceId (para evitar $this en closures internas)
        $currentServiceId = $this->serviceId;

        $doctors = Doctor::query()
            ->when($this->serviceId, function ($q) use ($currentServiceId) {
                return $q->whereHas('services', fn ($s) => $s->where('service_id', $currentServiceId));
            })
            ->when($this->search, function ($q) {
                // 👈 Búsqueda por full_name concatenado
                return $q->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ['%' . $this->search . '%']);
            })
            ->whereHas('schedules', function ($query) use ($dayOfWeek, $currentServiceId) {
                // 👈 Cambio clave: whereJsonContains para array JSON
                $query->whereJsonContains('day_of_week', $dayOfWeek)
                      ->when($currentServiceId, function ($q) use ($currentServiceId) {
                          return $q->where('service_id', $currentServiceId);
                      });
            })
            ->with([
                'services',
                'schedules' => function ($query) use ($dayOfWeek, $currentServiceId) {
                    // 👈 Cambio clave: whereJsonContains aquí también
                    $query->whereJsonContains('day_of_week', $dayOfWeek)
                          ->when($currentServiceId, function ($q) use ($currentServiceId) {
                              return $q->where('service_id', $currentServiceId);
                          });
                }
            ])
            ->get();

        return view('livewire.cita-search', compact('doctors', 'dayOfWeek'));
    }
}