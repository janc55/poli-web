<?php

namespace App\Policies;

use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DoctorSchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admins can view all doctor schedules
        return $user->can('horariodoctor.ver_todos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DoctorSchedule $doctorSchedule): bool
    {
        // Admins can view all doctor schedules
        if ($user->can('horariodoctor.ver_todos')) {
            return true;
        }

        // Doctors can view their own schedules
        if ($user->hasRole('doctor') && $doctorSchedule->doctor_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('horariodoctor.crear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DoctorSchedule $doctorSchedule): bool
    {
        // Only admins can update doctor schedules
        return $user->can('horariodoctor.editar');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return $user->can('horariodoctor.eliminar');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DoctorSchedule $doctorSchedule): bool
    {
        return false;
    }
}
