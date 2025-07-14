<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function indexdata(Request $request)
    {
        $query = Customer::with('user.role');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama', 'like', "%$search%");
        }

        $customers = $query->paginate(10)->withQueryString();
        $roles = Role::where('nama', 'Customer')->get();

        return view('pages.customer.index', compact('customers', 'roles'));
    }

    public function create()
    {
        $roles = Role::where('nama', 'Customer')->get();
        return view('pages.customer.create', compact('roles'));
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

        Customer::create([
            'id_user' => $user->id,
            'nama'    => $request->nama,
            'no_hp'   => $request->no_hp,
            'alamat'  => $request->alamat,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        $roles = Role::where('nama', 'Customer')->get();

        return view('pages.customer.edit', compact('customer', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::with('user')->findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $customer->user->id,
            'no_hp'    => 'required|string|max:15',
            'alamat'   => 'required|string|max:255',
            'id_role'  => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6',
        ]);

        $customer->update([
            'nama'   => $request->nama,
            'no_hp'  => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        $customer->user->update([
            'email'   => $request->email,
            'id_role' => $request->id_role,
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $customer->user->password,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->user()->delete();

        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus.');
    }
}
