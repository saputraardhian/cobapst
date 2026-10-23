<?php

namespace App\Policies;

use App\Models\PristiMonevReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Laporan Monev Pristi Kab/Kota (SEMAR PST).
 *
 * ID: Kebijakan akses evaluasi 9 dimensi dan pengesahan laporan monev 35 BPS Kab/Kota.
 * EN: Authorization policy for evaluating 9 dimensions and approving regional monev reports.
 */
class PristiMonevReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_pristi_monev_report');
    }

    public function view(User $user, PristiMonevReport $model): bool
    {
        return $user->can('view_pristi_monev_report');
    }

    public function create(User $user): bool
    {
        return $user->can('create_pristi_monev_report');
    }

    public function update(User $user, PristiMonevReport $model): bool
    {
        return $user->can('update_pristi_monev_report');
    }

    public function delete(User $user, PristiMonevReport $model): bool
    {
        return $user->can('delete_pristi_monev_report');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_pristi_monev_report');
    }
}
