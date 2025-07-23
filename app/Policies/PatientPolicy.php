<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver todos los pacientes') || 
               $user->can('ver pacientes asignados');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        // Doctores pueden ver pacientes asignados
        if ($user->hasRole('doctor')) {
            return $user->hasPermissionTo('ver pacientes asignados') && 
                   $patient->doctor_id === $user->id;
        }
        
        // Pacientes solo pueden verse a sí mismos
        if ($user->hasRole('paciente')) {
            return $user->patient->id === $patient->id;
        }
        
        return $user->can('ver todos los pacientes');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear pacientes');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patient $patient): bool
    {
        // Doctores solo pueden editar pacientes asignados
        if ($user->hasRole('doctor')) {
            return $user->can('editar pacientes') && 
                   $patient->doctor_id === $user->id;
        }
        
        // Pacientes solo pueden editar su propia información
        if ($user->hasRole('paciente')) {
            return $user->patient->id === $patient->id;
        }
        
        return $user->can('editar pacientes');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patient $patient): bool
    {
        return $user->can('eliminar pacientes');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Patient $patient): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Patient $patient): bool
    {
        return false;
    }

    public function export(User $user, Patient $patient): bool
    {
        return $user->can('exportar datos de pacientes');
    }
}
