<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DataadminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get()
    {
        $dataadmin =  User::all();
        return response()->json([
            'dataadmin' => $dataadmin
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
            'name' => 'required|max:255',
            'email' => 'required|email:dns|unique:users,email',
            'password' => 'required|min:5|max:255',
            'role' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ]);
        } else {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
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
        $validated = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email:dns|unique:users,email,' . $id,
            'password' => 'nullable|string|min:5|max:255',
            'password_confirmation' => 'nullable|string|min:5|max:255|same:password',
            'role' => 'string',
        ]);
    
        if ($validated->fails()) {
            return response()->json([
                'message' => $validated->messages(),
            ], 400); // Set status code 400 for bad request
        }
    
        $dataadmin = User::findOrFail($id);
    
        $data = [
            'name' => $request->has('name') ? $request->name : $dataadmin->name,
            'email' => $request->has('email') ? $request->email : $dataadmin->email,
            'role' => $request->has('role') ? $request->role : $dataadmin->role,
        ];
    
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
    
        $dataadmin->update($data);
    
        return response()->json([
            'message' => "Data telah tersimpan",
        ]);
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Temukan data Pajak berdasarkan id_pajak
        $dataadmin = User::where('id', $id)->first();
    
        // Periksa apakah data Pajak ditemukan
        if (!$dataadmin) {
            return response()->json([
                'message' => "Data Admin dengan ID $id tidak ditemukan",
            ], 404);
        }
    
        // Hapus data Pajak
        $dataadmin->delete();
    
        // Kembalikan respons sukses
        return response()->json([
            'message' => "Data Admin dengan ID $id berhasil dihapus",
        ]);
    }
    
}