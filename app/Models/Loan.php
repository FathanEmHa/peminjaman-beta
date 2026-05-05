<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{

    protected $fillable = ['user_id', 'approved_by', 'status', 'loan_date', 'return_date', 'photo_before', 'photo_after', 'rejection_note'];

    // ─── Appends buat nambahin field virtual ───────────────────────
    protected $appends = ['nominal_denda'];

    // ─── Status Constants ──────────────────────────────────────────
    const STATUS_PENDING          = 'pending';
    const STATUS_APPROVED         = 'approved';
    const STATUS_ONGOING          = 'ongoing';
    const STATUS_AWAITING_RETURN  = 'awaiting_return';
    const STATUS_RETURNING        = 'returning';
    const STATUS_RETURNED         = 'returned';
    const STATUS_REJECTED         = 'rejected';
    const STATUS_CANCELLED        = 'cancelled';
    const STATUS_OVERDUE          = 'overdue';

    /**
     * Semua status yang valid untuk loan.
     */
    const ALL_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_ONGOING,
        self::STATUS_AWAITING_RETURN,
        self::STATUS_RETURNING,
        self::STATUS_RETURNED,
        self::STATUS_REJECTED,
        self::STATUS_CANCELLED,
        self::STATUS_OVERDUE,
    ];

    /**
     * Cek apakah loan ini overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE;
    }

    // ─── Relationships ─────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function return()
    {
        return $this->hasOne(ReturnModel::class);
    }

    // ─── Accessor Hitung Denda Per Jam ─────────────────────────────
    public function getNominalDendaAttribute()
    {
        // Kalau status belum overdue, berarti denda 0
        if ($this->status !== self::STATUS_OVERDUE || !$this->return_date) {
            return 0;
        }

        $returnDate = \Carbon\Carbon::parse($this->return_date);
        $now = \Carbon\Carbon::now();

        // Cari selisih dalam hitungan menit biar presisi
        $selisihMenit = $returnDate->diffInMinutes($now);

        // Pakai ceil() buat pembulatan ke atas. 
        // Contoh: Telat 15 menit -> 15/60 = 0.25 -> dibulatkan ke atas jadi 1 jam.
        // Telat 65 menit -> 65/60 = 1.08 -> dibulatkan ke atas jadi 2 jam.
        $jamTelat = ceil($selisihMenit / 60);

        $tarifDendaPerJam = 2000; // Ganti sesuai tarif sekolah/sistem lo (misal Rp 2.000)

        return $jamTelat * $tarifDendaPerJam;
    }

    /**
     * Get CSS classes for the status badge.
     */
    public function getStatusBadgeClassAttribute()
    {
        $status = $this->status;

        $badges = [
            'pending'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'approved'  => 'bg-blue-100 text-blue-700 border-blue-200',
            'ongoing'   => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'overdue'   => 'bg-rose-100 text-rose-700 border-rose-200',
            'rejected'  => 'bg-red-100 text-red-700 border-red-200',
            'cancelled' => 'bg-gray-100 text-gray-700 border-gray-200',
        ];

        // Jika statusnya bukan returned, langsung kembalikan warna dari array
        if ($status !== 'returned') {
            return $badges[$status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
        }

        // --- LOGIC KHUSUS RETURNED ---
        if (!$this->return) {
            return 'bg-emerald-100 text-emerald-700 border-emerald-200'; // Default Hijau
        }

        $totalFine = ($this->return->late_fee ?? 0) + ($this->return->damage_fee ?? 0);

        if ($totalFine > 0) {
            return $this->return->fine_status === 'paid'
                ? 'bg-amber-100 text-amber-700 border-amber-200' // Ada Denda, Lunas (Orange)
                : 'bg-rose-100 text-rose-700 border-rose-200';   // Ada Denda, Nunggak (Merah)
        }

        return 'bg-emerald-100 text-emerald-700 border-emerald-200'; // Bersih tanpa denda (Hijau)
    }

    /**
     * Get human-readable label for the status badge.
     */
    public function getStatusLabelAttribute()
    {
        $status = strtoupper($this->status);

        if ($this->status === 'returned' && $this->return) {
            $totalFine = ($this->return->late_fee ?? 0) + ($this->return->damage_fee ?? 0);
            
            if ($totalFine > 0) {
                return $this->return->fine_status === 'paid' ? 'RETURNED' : 'RETURNED'; 
                // Opsional: Kalau mau teksnya beda, misal 'RETURNED (LUNAS)', ubah di baris atas ini
            }
        }

        return $status;
    }

    /**
     * Cek apakah peminjaman ini memiliki denda yang belum atau sudah dibayar.
     */
    public function getHasFineAttribute()
    {
        if ($this->status !== 'returned' || !$this->return) {
            return false;
        }
        
        $totalFine = ($this->return->late_fee ?? 0) + ($this->return->damage_fee ?? 0);
        return $totalFine > 0;
    }
}
