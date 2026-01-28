<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MedicalRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('historialmedico.ver_todos') || 
               $user->can('historialmedico.ver_asignados');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MedicalRecord $medicalRecord): bool
    {
        // Admin puede ver todo
        if ($user->hasRole('admin')) {
            return true;
        }

        // Doctor solo puede ver historiales que ha creado o son de sus pacientes
        if ($user->hasRole('doctor')) {
            return $user->can('historialmedico.ver_asignados') && 
                   ($medicalRecord->doctor_id === $user->id || 
                    $medicalRecord->patient->doctor_id === $user->id);
        }

        // Paciente solo puede ver sus propios historiales
        if ($user->hasRole('paciente')) {
            return $medicalRecord->patient_id === $user->patient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('historialmedico.crear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
        // Admin puede editar cualquier historial
        if ($user->hasRole('admin')) {
            return $user->can('historialmedico.editar_todos');
        }

        // Doctor solo puede editar sus propios historiales
        if ($user->hasRole('doctor')) {
            return $user->can('historialmedico.editar_asignados') && 
                   $medicalRecord->doctor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalRecord $medicalRecord): bool
    {
        // Solo admin puede eliminar historiales
        return $user->hasRole('admin') && 
               $user->can('historialmedico.eliminar');
    }

    /**
     * Determina si el usuario puede exportar historiales clínicos.
     */
    public function export(User $user, MedicalRecord $medicalRecord): bool
    {
        // Admin puede exportar cualquier historial
        if ($user->hasRole('admin')) {
            return true;
        }

        // Doctor puede exportar sus historiales
        if ($user->hasRole('doctor')) {
            return $medicalRecord->doctor_id === $user->id;
        }

        // Paciente puede exportar sus propios historiales
        if ($user->hasRole('paciente')) {
            return $medicalRecord->patient_id === $user->patient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MedicalRecord $medicalRecord): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MedicalRecord $medicalRecord): bool
    {
        return false;
    }
}
