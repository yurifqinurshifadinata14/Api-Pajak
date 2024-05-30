<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Pajak;
use App\Models\Pph;
use App\Models\Pph21;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get()
    {
        $pembayar = Pajak::all();
        $totalpembayar = Pajak::count();
        $jumlahkaryawan = Karyawan::count();
        $totalbayarpph = Pph::sum('jumlah_bayar');
        $totalbayarpph21 = Pph21::sum('jumlah_bayar');



        return response()->json([
            'totalpembayar' => $totalpembayar,
            'jumlahkaryawan'=> $jumlahkaryawan,
            'totalbayarpph'=> $totalbayarpph,
            'totalbayarpph21'=> $totalbayarpph21
            
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}