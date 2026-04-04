<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{

    protected $fillable = ['loan_id', 'asset_id', 'quantity'];
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
