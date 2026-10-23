<?php

namespace App\Policies;

use App\Models\PstCounter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Meja Loket PST (SEMAR PST).
 *
 * ID: Kebijakan akses pengelolaan meja loket dan penugasan operator aktif.
 * EN: Authorization policy for managing physical counters and active operator assignments.
 */
class PstCounterPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pst_counter');
    }

    public function view(User $user, PstCounter $model): bool
    {
        return $user->can('view_pst_counter');
    }

    public function create(User $user): bool
    {
        return $user->can('create_pst_counter');
    }

    public function update(User $user, PstCounter $model): bool
    {
        return $user->can('update_pst_counter');
    }

    public function delete(User $user, PstCounter $model): bool
    {
        return $user->can('delete_pst_counter');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_pst_counter');
    }
}
