<?php

namespace App\Imports;
use App\Models\Pph;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Pajak;
use Illuminate\Support\Facades\DB;

class PphImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $max = DB::table('pphs')->select(DB::raw('MAX(RIGHT(id_pph,3)) as autoid'));
        $kd = "";

        if ($max->count() > 0) {
            foreach ($max->get() as $a) {
                $tmp = ((int) $a->autoid) + 1;
                $kd = sprintf("%03s", $tmp);
                $id_pph = "PPH-" . $kd;
            }
        } else {
            $id_pph = "PPH-001";
        }

        $pajak = Pajak::where('nama_wp', $row['nama_wp'])->first();
        return new Pph([
            'id_pajak' => $pajak->id_pajak,
            'id_pph' => $id_pph,
            'ntpn'    => $row['ntpn'],
            'jumlah_bayar' => $row['jumlah_bayar'],
            'biaya_bulan' => $row['biaya_bulan'],
        ]);
    }
}
