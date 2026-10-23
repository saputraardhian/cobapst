<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider Utama Aplikasi (SEMAR PST).
 *
 * ID:
 * Mengonfigurasi otorisasi gerbang (Gate) global, menetapkan hak akses super_admin
 * untuk bypass seluruh permission check, serta inisialisasi layanan inti Laravel.
 *
 * EN:
 * Configures application-wide authorization gates, establishes super_admin bypass
 * logic across all permission checks, and registers core Laravel framework services.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "super_admin" role all permissions via Gate check
        Gate::before(function ($user, string $ability): ?bool {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
