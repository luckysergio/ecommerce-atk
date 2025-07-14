<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User; // pastikan ini ada!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $customer = Customer::where('id_user', $user->id)->firstOrFail();

        return view('pages.customer.profile-edit', compact('user', 'customer'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|max:255',
            'no_hp'    => ['required', 'regex:/^[0-9]{10,15}$/'],
            'alamat'   => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . Auth::id(),
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if (empty($request->password) && !empty($request->password_confirmation)) {
                $validator->errors()->add('password', 'Password harus diisi jika konfirmasi password diisi.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customer = Customer::where('id_user', $user->id)->firstOrFail();

        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $customer->update([
            'nama'   => $request->nama,
            'no_hp'  => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
