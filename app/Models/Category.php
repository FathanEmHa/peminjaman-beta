<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
    use HasFactory;

    // Tambahkan baris ini biar nama bisa di-insert
    protected $fillable = ['name'];

    // Relasi ke Asset sekalian ditambahin biar rapi
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
