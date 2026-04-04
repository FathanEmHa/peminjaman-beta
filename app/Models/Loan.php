<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{

    protected $fillable = ['user_id', 'approved_by', 'status', 'loan_date', 'return_date'];
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
