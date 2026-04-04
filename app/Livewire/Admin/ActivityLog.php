<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ActivityLog extends Component
{
    public function render()
    {
        // Tarik data log dan join dengan tabel users untuk ambil nama
        $logs = DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.activity-log', compact('logs'))
            ->layout('layouts.app');
    }
}