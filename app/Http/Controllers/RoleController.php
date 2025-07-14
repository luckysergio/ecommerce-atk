<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $role = Role::create(['nama' => $request->nama]);

        return back()->with('success', 'Karyawan berhasil ditambahkan.');
    }
}
