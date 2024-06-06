<?php

namespace App\Imports;

use App\Models\Pph21;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Pajak;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

class Pph21Import implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        // $max = DB::table('pph21s')->select(DB::raw('MAX(RIGHT(id_pph21,3)) as autoid'));
        // $kd = "";

        // if ($max->count() > 0) {
        //     foreach ($max->get() as $a) {
        //         $tmp = ((int) $a->autoid) + 1;
        //         $kd = sprintf("%03s", $tmp);
        //         $id_pph21 = "Pph21-" . $kd;
        //     }
        // } else {
        //     $id_pph21 = "Pph21-001";
        // }

        $pajak = Pajak::where('nama_wp', $row['nama_wp'])->first();

        $karyawan = Karyawan::where('nik', $row['nik'])->first();
        return new Pph21([
            'id_pajak' => $pajak->id_pajak,
            'jumlah_bayar' => $row['jumlah_bayar'],
            'bpf' => $row['bpf'],
            'biaya_bulan' => $row['biaya_bulan'],
            // 'nik' => $karyawan->nik,
            'nik' => $karyawan ? $karyawan->nik : null,
        ]);
    }
}
