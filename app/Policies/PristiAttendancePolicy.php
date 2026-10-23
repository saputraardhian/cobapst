<?php

namespace App\Policies;

use App\Models\PristiAttendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Presensi Kehadiran Jaga Loket (SEMAR PST).
 *
 * ID: Kebijakan akses pencatatan presensi kedatangan dan perhitungan keterlambatan.
 * EN: Authorization policy for recording check-in attendance and late arrival durations.
 */
class PristiAttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pristi_attendance');
    }

    public function view(User $user, PristiAttendance $model): bool
    {
        return $user->can('view_pristi_attendance');
    }

    public function create(User $user): bool
    {
        return $user->can('create_pristi_attendance');
    }

    public function update(User $user, PristiAttendance $model): bool
    {
        return $user->can('update_pristi_attendance');
    }

    public function delete(User $user, PristiAttendance $model): bool
    {
        return $user->can('delete_pristi_attendance');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_pristi_attendance');
    }
}
