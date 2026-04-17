<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{

    protected $fillable = ['user_id', 'approved_by', 'status', 'loan_date', 'return_date'];

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
}
