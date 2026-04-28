<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');

        $query = Guru::select('guru.*')->with('user');

        if ($sort === 'email') {
            $query->join('users', 'guru.id', '=', 'users.guru_id')
                  ->orderBy('users.email', $order);
        } else {
            $allowedSorts = ['nama', 'mata_pelajaran', 'created_at'];
            if (in_array($sort, $allowedSorts)) {
                $query->orderBy('guru.' . $sort, $order);
            } else {
                $query->orderBy('guru.created_at', 'desc');
            }
        }

        $gurus = $query->get();

        return view('admin.guru.index', compact('gurus', 'sort', 'order'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'mata_pelajaran' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email sudah terdaftar'
        ]);

        DB::transaction(function () use ($request) {
            $guru = Guru::create([
                'nama' => $request->nama,
                'mata_pelajaran' => $request->mata_pelajaran
            ]);

            User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'guru',
                'guru_id' => $guru->id
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru dan akun login berhasil dibuat.');
    }

    public function show(string $id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.show', compact('guru'));
    }

    public function edit(string $id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, string $id)
    {
        $guru = Guru::findOrFail($id);
        $user = $guru->user;

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($user ? $user->id : ''),
            'password' => 'nullable|min:6',
            'mata_pelajaran' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email sudah terdaftar'
        ]);

        DB::transaction(function () use ($request, $guru, $user) {
            $guru->update([
                'nama' => $request->nama,
                'mata_pelajaran' => $request->mata_pelajaran
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

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $guru = Guru::findOrFail($id);
            User::where('guru_id', $id)->delete();
            $guru->delete();
        });

        return redirect()->route('admin.guru.index')->with('success', 'Guru dan akun login berhasil dihapus.');
    }
}