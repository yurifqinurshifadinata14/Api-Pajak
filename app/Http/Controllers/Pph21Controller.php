<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Pph21;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Pph21Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //jhvjhjh
    public function get()
    {
        $pph21 = Pph21::join('karyawans', 'karyawans.nik', '=', 'pph21s.nik')->join('pajaks', 'pajaks.id_pajak', '=', 'pph21s.id_pajak')
            ->get(['pph21s.id', 'pph21s.id_pajak', 'karyawans.nik', 'karyawans.nama', 'jumlah_bayar', 'biaya_bulan', 'bpf', 'nama_wp']);
        return response()->json([
            'pph21' => $pph21
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
        //
        $validated = Validator::make($request->all(), [
            'id_pajak' => 'required',
            'nik' => 'required',
            'jumlah_bayar' => 'required',
            'bpf' => 'required',
            'biaya_bulan' => 'required',

        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ]);
        } else {
            $pph21 = Pph21::create([
                'id_pajak' => $request->id_pajak,
                'nik' => $request->nik,
                'jumlah_bayar' => $request->jumlah_bayar,
                'bpf' => $request->bpf,
                'biaya_bulan' => $request->biaya_bulan,
            ]);

            $karyawan = Karyawan::where('id', $request->id_karyawan)->first();
            Karyawan::updateOrCreate([
                'id_pph21' => $pph21->id,
                'nama' => $karyawan->nama,
                'nik' => $karyawan->nik,
                'npwp' => $karyawan->npwp,
            ]);
            $pph21 = Pph21::where('nik', $karyawan->nik)->update(['nik' => $request->nik]);

            return response()->json([
                'message' => "Data telah tersimpan",
            ]);
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
    public function update(Request $request, string $id)
    {
        $validated = Validator::make($request->all(), [
            'nik' => 'numeric',
            'jumlah_bayar' => 'numeric',
            'bpf' => 'string',
            'bpf' => 'string|max:255',
            'biaya_bulan' => 'numeric',

        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ]);
        } else {
            $pph21 = Pph21::where('id', $id)->first();

            $pph21->update([
                'id_pajak' => $request->id_pajak,
                'nik' => $request->nik,
                'jumlah_bayar' => $request->jumlah_bayar,
                'bpf' => $request->bpf,
                'biaya_bulan' => $request->biaya_bulan,
            ]);

            $karyawan = Karyawan::where('id', $request->id_karyawan)->first();
            Karyawan::updateOrCreate([
                'id_pph21' => $pph21->id,
                'nama' => $karyawan->nama,
                'nik' => $karyawan->nik,
                'npwp' => $karyawan->npwp,
            ]);
            $pph21 = Pph21::where('nik', $karyawan->nik)->update(['nik' => $request->nik]);

            return response()->json([
                'message' => "Data telah tersimpan",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $pph21 = Pph21::where('id', $id)->first();

        if (!$pph21) {
            return response()->json([
                'message' => "Data tidak bisa terhapus",
            ]);
        }

        $pph21->delete();

        return response()->json([
            'message' => "Data telah terhapus",
        ]);
    }
}
