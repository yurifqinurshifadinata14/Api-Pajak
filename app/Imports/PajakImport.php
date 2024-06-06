<?php

namespace App\Imports;
use App\Models\Pajak;
use App\Models\Jenis;
use App\Models\Status;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class PajakImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $max = DB::table('pajaks')->select(DB::raw('MAX(RIGHT(id_pajak,3)) as autoid'));
        $kd = "";

        if ($max->count() > 0) {
            foreach ($max->get() as $a) {
                $tmp = ((int) $a->autoid) + 1;
                $kd = sprintf("%03s", $tmp);
                $id_pajak = "P-" . $kd;
            }
        } else {
            $id_pajak = "P-001";
        }

        $data=[
            'id_pajak' => $row['id_pajak'],
            'nama_wp' => $row['nama_wp'],
            'npwp' => $row['npwp'],
            'no_hp' => $row['no_hp'],
            'no_efin' => $row['no_efin'],
            'gmail' => $row['gmail'],
            'password' => $row['password'],
            'nik' => $row['nik'],
            'alamat' => $row['alamat'],
            'merk_dagang' => $row['merk_dagang'],
        ];

        $pajak=new Pajak();
        $pajak->id_pajak = $id_pajak;
        $pajak->nama_wp = $row['nama_wp'];
        $pajak->npwp = $row['npwp'];
        $pajak->no_hp = $row['no_hp'];
        $pajak->no_efin = $row['no_efin'];
        $pajak->gmail = $row['gmail'];
        $pajak->password = $row['password'];
        $pajak->nik = $row['nik'];
        $pajak->alamat = $row['alamat'];
        $pajak->merk_dagang = $row['merk_dagang'];
        $pajak->save();

        $jenis = new Jenis();
        $jenis->id_pajak = $id_pajak;
        $jenis->jenis = $row['jenis'];
        $jenis->alamatBadan = $row['alamatbadan'];
        $jenis->jabatan = $row['jabatan'];
        $jenis->saham = $row['saham'];
        $jenis->npwpBadan = $row['npwpbadan'];
        $jenis->save();

        $status = new Status();
        $status->id_pajak = $id_pajak;
        $status->status = $row['status'];
        $status->enofa_password = $row['enofa_password'];
        $status->user_efaktur = $row['user_efaktur'];
        $status->passphrese = $row['passphrese'];
        $status->password_efaktur = $row['password_efaktur'];
        $status->save();


        }
}
