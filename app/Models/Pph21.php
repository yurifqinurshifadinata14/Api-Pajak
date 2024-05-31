<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pph21 extends Model
{
    use HasFactory;
    // protected $table = 'pph21s';
    protected $guarded = [];

    public function pajak()
    {
        return $this->belongsTo(Pajak::class, 'id_pajak', 'id_pajak');
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
