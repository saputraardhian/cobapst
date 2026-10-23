<?php

namespace App\Policies;

use App\Models\PstVisitorLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Log Kunjungan Buku Tamu (SEMAR PST).
 *
 * ID: Kebijakan akses riwayat sesi check-in dan check-out pengunjung lobi.
 * EN: Authorization policy for visitor check-in and check-out session logs.
 */
class PstVisitorLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pst_visitor_log');
    }

    public function view(User $user, PstVisitorLog $model): bool
    {
        return $user->can('view_pst_visitor_log');
    }

    public function create(User $user): bool
    {
        return $user->can('create_pst_visitor_log');
    }

    public function update(User $user, PstVisitorLog $model): bool
    {
        return $user->can('update_pst_visitor_log');
    }

    public function delete(User $user, PstVisitorLog $model): bool
    {
        return $user->can('delete_pst_visitor_log');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_pst_visitor_log');
    }
}
