<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;

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
        // 1. Nangkap Event saat User BERHASIL LOGIN
        Event::listen(function (Login $event) {
            DB::table('activity_logs')->insert([
                'user_id' => $event->user->id,
                'action'  => 'User berhasil login (' . strtoupper($event->user->role) . ')',
                'created_at' => now(),
            ]);
        });

        // 2. Nangkap Event saat User LOGOUT
        Event::listen(function (Logout $event) {
            // Kadang pas session habis/force logout, $event->user bisa null
            if ($event->user) {
                DB::table('activity_logs')->insert([
                    'user_id' => $event->user->id,
                    'action'  => 'User melakukan logout',
                    'created_at' => now(),
                ]);
            }
        });
    }
}