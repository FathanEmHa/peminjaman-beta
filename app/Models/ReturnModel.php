<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'loan_id',
        'returned_by',
        'received_by',
        'return_date',
        'condition_notes',
        'late_fee',
        'damage_fee',
        'fine_status',
    ];

    protected $casts = [
        'late_fee'    => 'integer',
        'damage_fee'  => 'integer',
        'fine_status' => 'string',
    ];

    /**
     * Loan yang dikembalikan.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * User (Peminjam) yang mengembalikan alat.
     */
    public function returnedByUser()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    /**
     * User (Petugas/Admin) yang menerima pengembalian.
     */
    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
