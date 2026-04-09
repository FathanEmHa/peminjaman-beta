<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{

    protected $fillable = ['user_id', 'approved_by', 'status', 'loan_date', 'return_date'];

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
}
