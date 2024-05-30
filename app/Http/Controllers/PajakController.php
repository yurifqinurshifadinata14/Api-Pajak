<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use App\Models\Pajak;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get()
    {
        $pajak =  Pajak::all();
        return response()->json([
            'pajak' => $pajak
        ]);
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

        $pajak = Pajak::create([
            "id_pajak" => $id_pajak,
            "id_user" => auth()->user()->id,
            "nama_wp" => $request->nama_wp,
            "npwp" => $request->npwp,
            "no_hp" => $request->no_hp,
            "no_efin" => $request->no_efin,
            "gmail" => $request->gmail,
            "password" => $request->password,
            "nik" => $request->nik,
            "alamat" => $request->alamat,
            "merk_dagang" => $request->merk_dagang,
        ]);
        Jenis::create([
            "id_pajak" => $id_pajak,
            "jenis" => $request->jenis,
            "jabatan" => $request->jenis == "Badan" ? $request->jabatan : null,
            "alamatBadan" => $request->jenis == "Badan" ? $request->alamatBadan : null,
            "npwpBadan" => $request->jenis == "Badan" ? $request->npwpBadan : null,
            "saham" => $request->jenis == "Badan" ? $request->saham : null,
        ]);

        Status::create([
            "id_pajak" => $id_pajak,
            "status" => $request->status,
            "enofa_password" => $request->status == "PKP" ? $request->enofa_password : null,
            "passphrese" => $request->status == "PKP" ? $request->passphrese : null,
            "user_efaktur" => $request->status == "PKP" ? $request->user_efaktur : null,
            "password_efaktur" => $request->status == "PKP" ? $request->password_efaktur : null,
        ]);

        if ($pajak) {
            return response()->json([
                'message' => "Data telah tersimpan",
                'pajak' => $pajak,
            ], 201);
        } else {
            return response()->json([
                'error' => "Gagal menyimpan data",
            ], 500);
        }
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
        // Validasi data yang diterima dari request
        $validated = Validator::make($request->all(), [
            'nama_wp' => 'string|max:255',
            'npwp' => 'string|max:255',
            'no_hp' => 'string|max:255',
            'no_efin' => 'string|max:255',
            'gmail' => 'email',
            'password' => 'string',
            'nik' => 'string|max:255',
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
        $status = Status::where('id_pajak', $id_pajak)->first();
        $jenis = Jenis::where('id_pajak', $id_pajak)->first();

        // Perbarui data Pajak dengan data yang diterima dari request
        if (!$pajak) {
            return response()->json([
                'message' => "Data pajak dengan ID $id_pajak tidak ditemukan",
            ], 404);
        }

        $pajak->update([
            "id_pajak" => $id_pajak,
            "id_user" => auth()->user()->id,
            "nama_wp" => $request->nama_wp,
            "npwp" => $request->npwp,
            "no_hp" => $request->no_hp,
            "no_efin" => $request->no_efin,
            "gmail" => $request->gmail,
            "password" => $request->password,
            "nik" => $request->nik,
            "alamat" => $request->alamat,
            "merk_dagang" => $request->merk_dagang,
        ]);

        $jenis->update([
            "id_pajak" => $id_pajak,
            "jenis" => $request->jenis,
            "jabatan" => $request->jenis == "Badan" ? $request->jabatan : null,
            "alamatBadan" => $request->jenis == "Badan" ? $request->alamatBadan : null,
            "npwpBadan" => $request->jenis == "Badan" ? $request->npwpBadan : null,
            "saham" => $request->jenis == "Badan" ? $request->saham : null,
        ]);

        $status->update([
            "id_pajak" => $id_pajak,
            "status" => $request->status,
            "enofa_password" => $request->status == "PKP" ? $request->enofa_password : null,
            "passphrese" => $request->status == "PKP" ? $request->passphrese : null,
            "user_efaktur" => $request->status == "PKP" ? $request->user_efaktur : null,
            "password_efaktur" => $request->status == "PKP" ? $request->password_efaktur : null,
        ]);

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

        // Periksa apakah data Pajak ditemukan
        if (!$pajak) {
            return response()->json([
                'message' => "Data pajak dengan ID $id_pajak tidak ditemukan",
            ], 404);
        }

        // Hapus data Pajak
        $pajak->delete();

        // Kembalikan respons sukses
        return response()->json([
            'message' => "Data pajak dengan ID $id_pajak berhasil dihapus",
        ]);
    }
}
