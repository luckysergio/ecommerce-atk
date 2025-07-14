<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Merk;
use Illuminate\Http\Request;

class JenisMerkController extends Controller
{
    public function index()
    {
        $jenis = Jenis::all();
        $merks = Merk::all();
        return view('pages.Jenis&Merk.index', compact('jenis', 'merks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:jenis,merk',
            'nama' => 'required|string|max:255',
        ]);

        if ($request->type === 'jenis') {
            Jenis::create(['nama' => $request->nama]);
        } else {
            Merk::create(['nama' => $request->nama]);
        }

        return redirect()->back()->with('success', ucfirst($request->type) . ' berhasil ditambahkan.');
    }

    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if ($type === 'jenis') {
            $item = Jenis::findOrFail($id);
        } else {
            $item = Merk::findOrFail($id);
        }

        $item->update(['nama' => $request->nama]);

        return redirect()->back()->with('success', ucfirst($type) . ' berhasil diperbarui.');
    }

    public function destroy($type, $id)
    {
        if ($type === 'jenis') {
            $item = Jenis::findOrFail($id);
        } else {
            $item = Merk::findOrFail($id);
        }

        $item->delete();

        return redirect()->back()->with('success', ucfirst($type) . ' berhasil dihapus.');
    }
}
