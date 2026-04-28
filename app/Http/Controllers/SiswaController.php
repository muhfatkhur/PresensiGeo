<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');

        $query = Siswa::select('siswa.*')->with('user');

        if ($sort === 'email') {
            $query->join('users', 'siswa.id', '=', 'users.siswa_id')
                  ->orderBy('users.email', $order);
        } else {
            $allowedSorts = ['nama', 'kelas', 'no_wa_wali', 'created_at'];
            if (in_array($sort, $allowedSorts)) {
                $query->orderBy('siswa.' . $sort, $order);
            } else {
                $query->orderBy('siswa.created_at', 'desc');
            }
        }

        $siswas = $query->get();

        return view('admin.siswa.index', compact('siswas', 'sort', 'order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'kelas' => 'required|string|max:255',
            'no_wa_wali' => 'required|string|max:255',
        ], [
            'email.unique' => 'Email sudah terdaftar'
        ]);

        DB::transaction(function () use ($request) {
            $siswa = Siswa::create([
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'no_wa_wali' => $request->no_wa_wali
            ]);

            User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'siswa_id' => $siswa->id
            ]);
        });

        return redirect()->back()->with('success', 'Data siswa dan akun berhasil dibuat.');
    }

    public function show(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = $siswa->user;

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($user ? $user->id : ''),
            'kelas' => 'required|string|max:255',
            'no_wa_wali' => 'required|string|max:255',
            'password' => 'nullable|min:6'
        ], [
            'email.unique' => 'Email sudah terdaftar'
        ]);

        DB::transaction(function () use ($request, $siswa, $user) {
            $siswa->update([
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'no_wa_wali' => $request->no_wa_wali
            ]);

            if ($user) {
                $userData = [
                    'name' => $request->nama,
                    'email' => $request->email
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $user->update($userData);
            }
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $siswa = Siswa::findOrFail($id);
            User::where('siswa_id', $id)->delete();
            $siswa->delete();
        });

        return redirect()->back()->with('success', 'Siswa dan akun berhasil dihapus.');
    }
}