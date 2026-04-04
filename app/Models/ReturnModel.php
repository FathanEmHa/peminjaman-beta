<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
