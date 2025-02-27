<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pajak extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'id_pajak', 'id_user', 'nama_wp', 'npwp', 'no_hp', 'no_efin', 'gmail', 'password', 'nik', 'alamat', 'merk_dagang', 'jenis', 'status'
    ];

    public function jenis()
    {
        return $this->hasOne(Jenis::class, 'id_pajak');
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'id_pajak');
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
