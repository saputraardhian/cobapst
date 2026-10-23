<?php

namespace App\Policies;

use App\Models\PstServiceTicket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Tiket Antrian & Layanan PST (SEMAR PST).
 *
 * ID: Kebijakan akses pemanggilan antrian, pengisian resume layanan, dan pencetakan tiket.
 * EN: Authorization policy for queue management, service ticketing, and desk resolution.
 */
class PstServiceTicketPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pst_service_ticket');
    }

    public function view(User $user, PstServiceTicket $model): bool
    {
        return $user->can('view_pst_service_ticket');
    }

    public function create(User $user): bool
    {
        return $user->can('create_pst_service_ticket');
    }

    public function update(User $user, PstServiceTicket $model): bool
    {
        return $user->can('update_pst_service_ticket');
    }

    public function delete(User $user, PstServiceTicket $model): bool
    {
        return $user->can('delete_pst_service_ticket');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_pst_service_ticket');
    }
}
