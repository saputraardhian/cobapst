<?php

namespace App\Policies;

use App\Models\StatisfySurvey;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Survei Kepuasan Statisfy (SEMAR PST).
 *
 * ID: Kebijakan akses hasil pengisian survei kepuasan 15 detik responden pengunjung.
 * EN: Authorization policy for visitor satisfaction survey ratings and qualitative comments.
 */
class StatisfySurveyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_statisfy_survey');
    }

    public function view(User $user, StatisfySurvey $model): bool
    {
        return $user->can('view_statisfy_survey');
    }

    public function create(User $user): bool
    {
        return $user->can('create_statisfy_survey');
    }

    public function update(User $user, StatisfySurvey $model): bool
    {
        return $user->can('update_statisfy_survey');
    }

    public function delete(User $user, StatisfySurvey $model): bool
    {
        return $user->can('delete_statisfy_survey');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_statisfy_survey');
    }
}
