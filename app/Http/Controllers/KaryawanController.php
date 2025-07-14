<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with('user.role')->get();
        $roles = Role::where('nama', '!=', 'Customer')->get();

        return view('pages.karyawan.index', compact('karyawans', 'roles'));
    }

    public function create()
    {
        $roles = Role::where('nama', '!=', 'Customer')->get();
        return view('pages.karyawan.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'no_hp'    => 'required|string|max:15',
            'alamat'   => 'required|string|max:255',
            'id_role'  => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'id_role'  => $request->id_role,
        ]);

        Karyawan::create([
            'id_user' => $user->id,
            'nama'    => $request->nama,
            'no_hp'   => $request->no_hp,
            'alamat'  => $request->alamat,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $karyawan = Karyawan::with('user')->findOrFail($id);
        $roles = Role::where('nama', '!=', 'Customer')->get();

        return view('pages.karyawan.edit', compact('karyawan', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::with('user')->findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $karyawan->user->id,
            'no_hp'    => 'required|string|max:15',
            'alamat'   => 'required|string|max:255',
            'id_role'  => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6',
        ]);

        $karyawan->update([
            'nama'   => $request->nama,
            'no_hp'  => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        $karyawan->user->update([
            'email'   => $request->email,
            'id_role' => $request->id_role,
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $karyawan->user->password,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->user()->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
