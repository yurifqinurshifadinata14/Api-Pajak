<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pajak;
use App\Models\Pphunifikasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Imports\PphunifikasiImport;



class PphunifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get()
    {
        $pphunifikasi =  Pphunifikasi::all();
        $pajak =  Pajak::all();
        return response()->json([
            'pphunifikasi' => $pphunifikasi,
            'pajak' => $pajak
        ]);
    }

    public function import(Request $request)
    {
        Excel::import(new PphunifikasiImport, $request->file('file'));

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
        $validated = Validator::make($request->all(), [
            'id_pajak' => 'required',
            'ntpn' => 'required|numeric',
            'jumlah_bayar' => 'required|numeric',
            'biaya_bulan' => 'required|numeric',
            'bpf' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ]);
        } else {
            $max = DB::table('pphunifikasis')->select(DB::raw('MAX(RIGHT(id_pphuni,3)) as autoid'))->first();
            $kd = "";

            if ($max) {
                $tmp = ((int) $max->autoid) + 1;
                $kd = sprintf("%03s", $tmp);
                $id_pphuni = "PPHU-" . $kd;
            } else {
                $id_pphuni = "PPHU-001";
            }

            Pphunifikasi::create([
                'id_pajak' => $request->id_pajak,
                'id_pphuni' => $id_pphuni,
                'ntpn' => $request->ntpn,
                'jumlah_bayar' => $request->jumlah_bayar,
                'biaya_bulan' => $request->biaya_bulan,
                'bpf' => $request->bpf,
            ]);

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
    public function update(Request $request, $id_pphuni)
    {
        $validated = Validator::make($request->all(), [
            'ntpn' => 'numeric',
            'jumlah_bayar' => 'numeric',
            'biaya_bulan' => 'numeric',
            'bpf' => 'string|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ]);
        } else {
            $pphunifikasi = Pphunifikasi::where('id_pphuni', $id_pphuni)->first();
            $pphunifikasi->update([
                'id_pajak' => $request->id_pajak,
                'id_pphuni' => $id_pphuni,
                'ntpn' => $request->ntpn,
                'jumlah_bayar' => $request->jumlah_bayar,
                'biaya_bulan' => $request->biaya_bulan,
                'bpf' => $request->bpf,
            ]);
            return response()->json([
                'message' => "Data telah tersimpan"
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pphuni)
    {
        //
        $pphunifikasi = Pphunifikasi::where('id_pphuni', $id_pphuni)->first();

        if (!$pphunifikasi) {
            return response()->json([
                'message' => "Data tidak bisa terhapus",
            ]);
        }

        $pphunifikasi->delete();

        return response()->json([
            'message' => "Data telah terhapus"
        ]);
    }
}
