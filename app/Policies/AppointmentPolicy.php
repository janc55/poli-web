<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('cita.ver_todas') || 
               $user->can('cita.ver_propias');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // Admin y recepcionistas pueden ver todas las citas
        if ($user->can('cita.ver_todas')) {
            return true;
        }

        // Doctores solo pueden ver sus propias citas
        if ($user->hasRole('doctor')) {
            return $user->can('cita.ver_propias') && 
                   $appointment->doctor_id === $user->id;
        }

        // Pacientes solo pueden ver sus propias citas
        if ($user->hasRole('paciente') && $user->patient) {
            return $user->can('cita.ver_propias') &&
                $appointment->patient_id === $user->patient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('cita.crear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Admin y recepcionistas pueden editar cualquier cita
        if ($user->can('cita.editar_todas')) {
            return true;
        }

        // Doctores solo pueden editar sus propias citas
        if ($user->hasRole('doctor')) {
            return $user->can('cita.editar_propias') && 
                   $appointment->doctor_id === $user->id;
        }

        // Pacientes solo pueden editar/cancelar sus propias citas
        if ($user->hasRole('paciente') && $user->patient) {
            return $user->can('cita.editar_propias') &&
                $appointment->patient_id === $user->patient->id;
        }

        return false;
    }

    /**
     * Determina si el usuario puede cancelar una cita.
     */
    public function cancel(User $user, Appointment $appointment): bool
    {
        // Recepcionistas y admin pueden cancelar cualquier cita
        if ($user->can('cita.cancelar') && ($user->hasRole('admin') || $user->hasRole('recepcionista'))) {
            return true;
        }

        // Doctores solo pueden cancelar sus propias citas
        if ($user->hasRole('doctor')) {
            return $user->can('cita.cancelar') && 
                   $appointment->doctor_id === $user->id;
        }

        // Pacientes solo pueden cancelar sus propias citas
        if ($user->hasRole('paciente') && $user->patient) {
            return $user->can('cita.cancelar') &&
                $appointment->patient_id === $user->patient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // Solo admin y recepcionistas pueden eliminar citas
        return $user->can('cita.eliminar') && 
               ($user->hasRole('admin') || $user->hasRole('recepcionista'));
    }

    /**
     * Determina si el usuario puede reasignar citas.
     */
    public function reassign(User $user): bool
    {
        // Solo recepcionistas y admin pueden reasignar citas
        return $user->can('cita.reasignar');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return false;
    }
}
