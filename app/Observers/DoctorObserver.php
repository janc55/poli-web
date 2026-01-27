<?php

namespace App\Observers;

use App\Models\Doctor;
use Illuminate\Support\Facades\Storage;

class DoctorObserver
{
    /**
     * Handle the Doctor "created" event.
     */
    public function created(Doctor $doctor): void
    {
        //
    }

    /**
     * Handle the Doctor "updated" event.
     */
    // Eliminar imagen anterior cuando se actualiza el avatar
    public function updating(Doctor $doctor)
    {
        if ($doctor->isDirty('avatar') && $doctor->getOriginal('avatar')) {
            Storage::disk('public')->delete($doctor->getOriginal('avatar'));
        }
    }

    // Eliminar imagen cuando se elimina el doctor
    public function deleted(Doctor $doctor)
    {
        if ($doctor->avatar) {
            Storage::disk('public')->delete($doctor->avatar);
        }
    }

    /**
     * Handle the Doctor "restored" event.
     */
    public function restored(Doctor $doctor): void
    {
        //
    }

    /**
     * Handle the Doctor "force deleted" event.
     */
    public function forceDeleted(Doctor $doctor): void
    {
        //
    }
}
