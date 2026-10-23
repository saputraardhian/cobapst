<?php

namespace App\Policies;

use App\Models\PstVisitor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Master Buku Tamu Pengunjung PST (SEMAR PST).
 *
 * ID: Kebijakan akses pengelolaan profil buku tamu pengunjung lobi BPS Jateng.
 * EN: Authorization policy for managing master guestbook visitor profiles.
 */
class PstVisitorPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pst_visitor');
    }

    public function view(User $user, PstVisitor $model): bool
    {
        return $user->can('view_pst_visitor');
    }

    public function create(User $user): bool
    {
        return $user->can('create_pst_visitor');
    }

    public function update(User $user, PstVisitor $model): bool
    {
        return $user->can('update_pst_visitor');
    }

    public function delete(User $user, PstVisitor $model): bool
    {
        return $user->can('delete_pst_visitor');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_pst_visitor');
    }
}
