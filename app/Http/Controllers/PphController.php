<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PphController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get()
    {
        $pph =  Pph::all();
        return response()->json([
            'pph' => $pph
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
            'ntpn' => 'required|numeric',
            'biaya_bulan' => 'required|numeric',
            'jumlah_bayar' => 'required|numeric',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ]);
        } else {

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

            Pph::create([
                'id_pajak' => $request->id_pajak,
                'id_pph' => $id_pph,
                'ntpn' => $request->ntpn,
                'biaya_bulan' => $request->biaya_bulan,
                'jumlah_bayar' => $request->jumlah_bayar,
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
    public function update(Request $request, $id_pph)
    {
        //
        $validated = Validator::make($request->all(), [
            'ntpn' => 'numeric',
            'biaya_bulan' => 'numeric',
            'jumlah_bayar' => 'numeric',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ]);
        } else {

            Pph::where('id_pph', $id_pph)->update([
                'id_pajak' => $request->id_pajak,
                'id_pph' => $id_pph,
                'ntpn' => $request->ntpn,
                'biaya_bulan' => $request->biaya_bulan,
                'jumlah_bayar' => $request->jumlah_bayar,
            ]);

            return response()->json([
                'message' => "Data telah tersimpan",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pph)
    {
        // Temukan data Pajak berdasarkan id_pajak
        $pph = Pph::where('id_pph', $id_pph)->first();

        // Periksa apakah data Pajak ditemukan
        if (!$pph) {
            return response()->json([
                'message' => "Data PPH dengan ID $id_pph tidak ditemukan",
            ], 404);
        }

        // Hapus data Pajak
        $pph->delete();

        // Kembalikan respons sukses
        return response()->json([
            'message' => "Data PPH dengan ID $id_pph berhasil dihapus",
        ]);
    }
}
