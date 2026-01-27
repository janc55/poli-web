<?php

namespace App\Observers;

use App\Models\Patient;
use Illuminate\Support\Facades\Storage;

class PatientObserver
{
    /**
     * Handle the Patient "created" event.
     */
    public function created(Patient $patient): void
    {
        //
    }

    /**
     * Handle the Patient "updated" event.
     */
    // Eliminar imagen anterior cuando se actualiza el avatar
    public function updating(Patient $patient)
    {
        if ($patient->isDirty('avatar') && $patient->getOriginal('avatar')) {
            Storage::disk('public')->delete($patient->getOriginal('avatar'));
        }
    }

    // Eliminar imagen cuando se elimina el paciente
    public function deleted(Patient $patient)
    {
        if ($patient->avatar) {
            Storage::disk('public')->delete($patient->avatar);
        }
    }

    /**
     * Handle the Patient "restored" event.
     */
    public function restored(Patient $patient): void
    {
        //
    }

    /**
     * Handle the Patient "force deleted" event.
     */
    public function forceDeleted(Patient $patient): void
    {
        //
    }
}
