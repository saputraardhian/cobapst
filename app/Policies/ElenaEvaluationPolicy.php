<?php

namespace App\Policies;

use App\Models\ElenaEvaluation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Evaluasi Kinerja Elena (SEMAR PST).
 *
 * ID: Kebijakan akses penilaian berkala, kalkulasi skor tertimbang, dan penguncian peringkat.
 * EN: Authorization policy for periodic evaluations, weighted scoring, and rank locking.
 */
class ElenaEvaluationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_elena_evaluation');
    }

    public function view(User $user, ElenaEvaluation $model): bool
    {
        return $user->can('view_elena_evaluation');
    }

    public function create(User $user): bool
    {
        return $user->can('create_elena_evaluation');
    }

    public function update(User $user, ElenaEvaluation $model): bool
    {
        return $user->can('update_elena_evaluation');
    }

    public function delete(User $user, ElenaEvaluation $model): bool
    {
        return $user->can('delete_elena_evaluation');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_elena_evaluation');
    }
}
