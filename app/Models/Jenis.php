<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    // Accessor untuk created_at
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta')->toDateTimeString();
    }

    // Accessor untuk updated_at
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta')->toDateTimeString();
    }
}
