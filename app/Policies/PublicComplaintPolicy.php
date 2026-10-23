<?php

namespace App\Policies;

use App\Models\PublicComplaint;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Pengaduan Masyarakat (SEMAR PST).
 *
 * ID: Kebijakan akses verifikasi pengaduan, investigasi internal, dan penalti kinerja.
 * EN: Authorization policy for grievance management, investigation notes, and penalty assignments.
 */
class PublicComplaintPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_public_complaint');
    }

    public function view(User $user, PublicComplaint $model): bool
    {
        return $user->can('view_public_complaint');
    }

    public function create(User $user): bool
    {
        return $user->can('create_public_complaint');
    }

    public function update(User $user, PublicComplaint $model): bool
    {
        return $user->can('update_public_complaint');
    }

    public function delete(User $user, PublicComplaint $model): bool
    {
        return $user->can('delete_public_complaint');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_public_complaint');
    }
}
