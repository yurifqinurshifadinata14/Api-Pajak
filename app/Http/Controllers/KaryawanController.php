<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Imports\KaryawanImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get()
    {
        $role = Auth::guard('sanctum')->user()->role;
        $karyawan =  Karyawan::all();
        return response()->json([
            'karyawan' => $karyawan,
            'role' => $role
        ]);
    }

    public function import(Request $request)
    {
        Excel::import(new KaryawanImport, $request->file('file'));

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
        //
        $validated = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'nik' => 'required|numeric',
            'npwp' => 'required|numeric',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ]);
        } else {
            Karyawan::create([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
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
    public function update(Request $request, $id)
    {
        //
        $validated = Validator::make($request->all(), [
            'nama' => 'string|max:255',
            'nik' => 'numeric',
            'npwp' => 'numeric',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ]);
        } else {
            $karyawan = Karyawan::where('id', $id)->first();
            $karyawan->update([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
            ]);

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
        $karyawan = Karyawan::where('id', $id)->first();

        if (!$karyawan) {
            return response()->json([
                'message' => "Data tidak bisa terhapus",
            ]);
        }

        $karyawan->delete();

        return response()->json([
            'message' => "Data telah terhapus"
        ]);
    }
}
