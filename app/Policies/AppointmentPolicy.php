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
    public function view(User $user, Appointment $appointment)
{
    return $appointment->user_id === $user->id || $user->role === 'admin';
}

public function update(User $user, Appointment $appointment)
{
    return $appointment->user_id === $user->id || $user->role === 'admin';
}

public function delete(User $user, Appointment $appointment)
{
    return $appointment->user_id === $user->id || $user->role === 'admin';
}

}
