<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerView()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:users,email'],
            'alamat' => ['required', 'string', 'max:20'],
            'password' => ['required', 'min:8'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'no_hp.required' => 'No HP wajib diisi.',
            'alamat.required' => 'No HP wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $customerRoleId = DB::table('roles')->where('nama', 'Customer')->value('id');

        $userId = DB::table('users')->insertGetId([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'id_role' => $customerRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('customers')->insert([
            'id_user' => $userId,
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Berhasil membuat akun, silakan login.');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            switch ($user->id_role) {
                case 1: // Admin
                case 3: // Manajer
                    return redirect('/dashboard');
                case 2: // Customer
                    return redirect('/');
                default:
                    return redirect('/login')->with('error', 'Role tidak dikenali.');
            }
        }

        return back()->withErrors([
            'email' => 'Periksa kembali email dan password anda.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
