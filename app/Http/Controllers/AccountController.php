<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Display a listing of users (pegawai only, exclude superadmin and pembeli)
     * Note: Akun pembeli hanya bisa dikelola via phpMyAdmin
     */
    public function index()
    {
        $users = User::where('role', 'pegawai')
            ->orderBy('username')
            ->get();
        
        return view('account.index', compact('users'));
    }

    /**
     * Store a newly created user (pegawai only)
     * Note: Akun pembeli hanya bisa dibuat via phpMyAdmin
     */
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('accountStore', [
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['pegawai'])],
        ], [], [
            'username' => 'username',
            'email' => 'email',
            'password' => 'password',
            'role' => 'role',
        ]);

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pegawai', // Force pegawai role
        ]);

        return redirect()->route('account.index')
            ->with('success', 'Akun pegawai berhasil ditambahkan.');
    }

    /**
     * Update the specified user (pegawai only)
     * Note: Akun pembeli hanya bisa diedit via phpMyAdmin
     */
    public function update(Request $request, User $user)
    {
        // Prevent editing superadmin
        if ($user->role === 'superadmin') {
            return redirect()->route('account.index')
                ->with('error', 'Akun superadmin tidak dapat diedit.');
        }

        // Prevent editing pembeli (only via phpMyAdmin)
        if ($user->role === 'pembeli') {
            return redirect()->route('account.index')
                ->with('error', 'Akun pembeli hanya bisa dikelola via phpMyAdmin.');
        }

        $validated = $request->validateWithBag('accountUpdate', [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['pegawai'])],
        ], [], [
            'username' => 'username',
            'email' => 'email',
            'role' => 'role',
        ]);

        // Superadmin tidak bisa mengubah password
        $updateData = [
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => 'pegawai', // Force pegawai role
        ];

        $user->update($updateData);

        return redirect()->route('account.index')
            ->with('success', 'Akun pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified user (pegawai only)
     * Note: Akun pembeli hanya bisa dihapus via phpMyAdmin
     */
    public function destroy(User $user)
    {
        // Prevent deleting superadmin
        if ($user->role === 'superadmin') {
            return redirect()->route('account.index')
                ->with('error', 'Akun superadmin tidak dapat dihapus.');
        }

        // Prevent deleting pembeli (only via phpMyAdmin)
        if ($user->role === 'pembeli') {
            return redirect()->route('account.index')
                ->with('error', 'Akun pembeli hanya bisa dihapus via phpMyAdmin.');
        }

        $user->delete();

        return redirect()->route('account.index')
            ->with('success', 'Akun pegawai berhasil dihapus.');
    }
}

