<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pph21 extends Model
{
    use HasFactory;
    // protected $table = 'pph21s';
    protected $guarded = [];

    public function pajak()
    {
        return $this->belongsTo(Pajak::class, 'id_pajak', 'id_pajak');
    }
}