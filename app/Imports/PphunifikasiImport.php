<?php

namespace App\Imports;
// use App\Pphunifikasi;
use App\Models\Pphunifikasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Pajak;
use Illuminate\Support\Facades\DB;

class PphunifikasiImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $max = DB::table('pphunifikasis')->select(DB::raw('MAX(RIGHT(id_pphuni,3)) as autoid'));
        $kd = "";

        if ($max->count() > 0) {
            foreach ($max->get() as $a) {
                $tmp = ((int) $a->autoid) + 1;
                $kd = sprintf("%03s", $tmp);
                $id_pphuni = "PPHU-" . $kd;
            }
        } else {
            $id_pphuni = "PPHU-001";
        }

        $pajak = Pajak::where('nama_wp', $row['nama_wp'])->first();
        return new Pphunifikasi([
            'id_pajak' => $pajak->id_pajak,
            'id_pphuni' => $id_pphuni,
            'ntpn' => $row['ntpn'],
            'jumlah_bayar' => $row['jumlah_bayar'],
            'biaya_bulan' => $row['biaya_bulan'],
            'bpf' => $row['bpf'],
        ]);
    }
}
