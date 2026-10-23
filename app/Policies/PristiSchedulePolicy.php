<?php

namespace App\Policies;

use App\Models\PristiSchedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Jadwal Tugas Jaga Loket Pristi (SEMAR PST).
 *
 * ID: Kebijakan akses pengelolaan kalender jadwal shift loket dan pertukaran jaga.
 * EN: Authorization policy for duty shift scheduling and authorized shift swaps.
 */
class PristiSchedulePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pristi_schedule');
    }

    public function view(User $user, PristiSchedule $model): bool
    {
        return $user->can('view_pristi_schedule');
    }

    public function create(User $user): bool
    {
        return $user->can('create_pristi_schedule');
    }

    public function update(User $user, PristiSchedule $model): bool
    {
        return $user->can('update_pristi_schedule');
    }

    public function delete(User $user, PristiSchedule $model): bool
    {
        return $user->can('delete_pristi_schedule');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_pristi_schedule');
    }
}
