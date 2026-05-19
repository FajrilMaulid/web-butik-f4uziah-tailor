<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $fillable = [
        'user_id',
        'lingkar_dada',
        'lingkar_pinggang',
        'lingkar_pinggul',
        'panjang_bahu',
        'panjang_lengan',
        'panjang_baju',
        'lingkar_leher'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
