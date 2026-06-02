<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{

    public function edit()
    {
        $user = Auth::user();

        return view('member.profil', compact('user'));
    }


    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'agama' => 'required|string|max:50',
            'golongan_darah' => 'required|string|max:10',
            'tinggi_badan' => 'required|numeric',
            'berat_badan' => 'required|numeric'
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'numeric' => 'Kolom :attribute harus berupa angka.'
        ]);

        $user = Auth::user();

        $user->update([

            'name' => $request->name,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'no_telp' => $request->no_telp,
            'agama' => $request->agama,
            'golongan_darah' => $request->golongan_darah,
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan,
            'pernah_beladiri' => $request->pernah_beladiri,
            'pernah_sakit' => $request->pernah_sakit,
            'is_profile_complete' => true

        ]);

        return Redirect::route('profil')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
