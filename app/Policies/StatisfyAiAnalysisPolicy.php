<?php

namespace App\Policies;

use App\Models\StatisfyAiAnalysis;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy Otorisasi Hasil Analisis AI Statisfy (SEMAR PST).
 *
 * ID: Kebijakan akses hasil analisis sentimen AI agregat bulanan dan tahunan.
 * EN: Authorization policy for AI sentiment analysis results and executive summaries.
 */
class StatisfyAiAnalysisPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_statisfy_ai_analysis');
    }

    public function view(User $user, StatisfyAiAnalysis $model): bool
    {
        return $user->can('view_statisfy_ai_analysis');
    }

    public function create(User $user): bool
    {
        return $user->can('create_statisfy_ai_analysis');
    }

    public function update(User $user, StatisfyAiAnalysis $model): bool
    {
        return $user->can('update_statisfy_ai_analysis');
    }

    public function delete(User $user, StatisfyAiAnalysis $model): bool
    {
        return $user->can('delete_statisfy_ai_analysis');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_statisfy_ai_analysis');
    }
}
