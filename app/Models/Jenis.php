<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;
    protected $table = 'jenis';
    protected $guarded = [];
    protected $fillable = [
        'id_pajak', 'alamatBadan', 'jabatan', 'saham', 'npwpBadan'
    ];

    public function pajak()
    {
        return $this->belongsTo(Pajak::class, 'id_pajak');
    }
}