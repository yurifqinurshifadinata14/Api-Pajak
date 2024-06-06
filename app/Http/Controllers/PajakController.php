<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use App\Models\Pajak;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\PajakImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get()
    {
        $pajak = Pajak::join('jenis', 'jenis.id_pajak', '=', 'pajaks.id_pajak')
            ->join('statuses', 'statuses.id_pajak', '=', 'pajaks.id_pajak')
            ->get();
        return response()->json([
            'pajak' => $pajak
        ]);
    }

    public function import(Request $request)
    {
        Excel::import(new PajakImport, $request->file('file'));

        return response()->json(['message' => 'Data imported successfully']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info($request->all());
        $validated = Validator::make($request->all(), [
            'nama_wp' => 'required|max:255',
            'npwp' => 'required',
            'no_hp' => 'required',
            'no_efin' => 'required',
            'gmail' => 'required',
            'password' => 'required',
            'nik' => 'required',
            'alamat' => 'required',
            'merk_dagang' => 'required',
        ]);


        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()->first(),
            ], 422);
        }

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

        $pajak = new Pajak;
        $pajak->id_pajak = $id_pajak;
        $pajak->nama_wp = $request->nama_wp;
        $pajak->npwp = $request->npwp;
        $pajak->no_hp = $request->no_hp;
        $pajak->no_efin = $request->no_efin;
        $pajak->gmail = $request->gmail;
        $pajak->password = $request->password;
        $pajak->nik = $request->nik;
        $pajak->alamat = $request->alamat;
        $pajak->merk_dagang = $request->merk_dagang;


        $jenis = new Jenis;
        $jenis->id_pajak = $id_pajak;
        $jenis->jenis = $request->jenis;
        $jenis->alamatBadan = $request->alamatBadan;
        $jenis->jabatan = $request->jabatan;
        $jenis->saham = $request->saham;
        $jenis->npwpBadan = $request->npwpBadan;

        $status = new Status;
        $status->id_pajak = $id_pajak;
        $status->status = $request->status;
        $status->enofa_password = $request->enofa_password;
        $status->user_efaktur = $request->user_efaktur;
        $status->passphrese = $request->passphrese;
        $status->password_efaktur = $request->password_efaktur;


        if ($pajak->save() && $jenis->save() && $status->save()) {

            return response()->json([
                'message' => "Data telah tersimpan",
            ], 200);
        }

        /*   if ($pajak->save() && $jenis->save() && $status->save()) {
            return response()->json([
                'message' => "Data telah tersimpan",
            ], 201);
        } else {
            return response()->json([
                'error' => "Gagal menyimpan data",
            ], 500);
        } */
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_pajak)
    {
        // return response()->json([
        //     $id_pajak
        // ]);
        // Validasi data yang diterima dari request
        $validated = Validator::make($request->all(), [
            'nama_wp' => 'max:255',
            'npwp' => 'digits_between:1,11',
            'no_hp' => 'string|max:255',
            'no_efin' => 'string|max:255',
            'gmail' => 'email',
            'password' => 'string',
            'nik' => 'max:255',
            'alamat' => 'string|max:255',
            'merk_dagang' => 'string|max:255',
        ]);

        // Jika validasi gagal, kembalikan pesan kesalahan
        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ], 400);
        }

        // Temukan data Pajak berdasarkan id_pajak
        $pajak = Pajak::where('id_pajak', $id_pajak)->first();
        $pajak->nama_wp = $request->nama_wp;
        $pajak->id_user = auth('sanctum')->user()->id;
        $pajak->npwp = $request->npwp;
        $pajak->no_hp = $request->no_hp;
        $pajak->no_efin = $request->no_efin;
        $pajak->gmail = $request->gmail;
        $pajak->password = $request->password;
        $pajak->nik = $request->nik;
        $pajak->alamat = $request->alamat;
        $pajak->merk_dagang = $request->merk_dagang;
        $pajak->update();

        $jenis = Jenis::where('id_pajak', $id_pajak)->first();
        $jenis->jenis = $request->jenis;
        $jenis->alamatBadan = $request->alamatBadan;
        $jenis->jabatan = $request->jabatan;
        $jenis->saham = $request->saham;
        $jenis->npwpBadan = $request->npwpBadan;
        $jenis->update();

        $status = Status::where('id_pajak', $id_pajak)->first();
        $status->status = $request->status;
        $status->enofa_password = $request->enofa_password;
        $status->user_efaktur = $request->user_efaktur;
        $status->passphrese = $request->passphrese;
        $status->password_efaktur = $request->password_efaktur;
        $status->update();

        // Perbarui data Pajak dengan data yang diterima dari request
        if (!$pajak) {
            return response()->json([
                'message' => "Data pajak dengan ID $id_pajak tidak ditemukan",
            ], 404);
        }

        /*  $pajak->update($request->all());
        $jenis->update($request->all());
        $status->update($request->all()); */

        // Kembalikan respons JSON yang menyatakan data telah tersimpan
        return response()->json([
            'message' => "Data telah tersimpan",
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pajak)
    {
        // Temukan data Pajak berdasarkan id_pajak
        $pajak = Pajak::where('id_pajak', $id_pajak)->first();
        $jenis = Jenis::where('id_pajak', $id_pajak)->first();
        $status = Status::where('id_pajak', $id_pajak)->first();

        // Periksa apakah data Pajak ditemukan
        if (!$pajak) {
            return response()->json([
                'message' => "Data pajak dengan ID $id_pajak tidak ditemukan",
            ], 404);
        }

        // Hapus data Pajak
        $pajak->delete();
        $jenis->delete();
        $status->delete();

        // Kembalikan respons sukses
        return response()->json([
            'message' => "Data pajak dengan ID $id_pajak berhasil dihapus",
        ]);
    }
}
