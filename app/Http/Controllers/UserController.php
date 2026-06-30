<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Pastikan hanya admin yang dapat mengakses controller ini.
     */
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Hanya administrator yang memiliki akses ke menu Manajemen Staff.');
        }
        return null;
    }

    /**
     * Tampilkan daftar user/staff.
     */
    public function index()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $users = User::orderBy('role', 'asc')->orderBy('name', 'asc')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan formulir tambah akun pengguna baru.
     */
    public function create()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        return view('users.create');
    }

    /**
     * Simpan akun pengguna baru ke database.
     */
    public function store(Request $request)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,doctor,staff'],
            'phone' => ['nullable', 'string', 'max:20'],
            'duty_address' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'duty_address' => $request->duty_address,
            'profile_photo' => $photoPath,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    /**
     * Tampilkan formulir ubah data akun pengguna.
     */
    public function edit(User $user)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        return view('users.edit', compact('user'));
    }

    /**
     * Perbarui data akun pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,doctor,staff'],
            'phone' => ['nullable', 'string', 'max:20'],
            'duty_address' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'duty_address' => $request->duty_address,
            'profile_photo' => $user->profile_photo,
        ];

        // Only hash and update password if a new one is typed
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    /**
     * Hapus akun pengguna dari database.
     */
    public function destroy(User $user)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        // Prevent self-deletion
        if ($user->id === auth()->user()->id) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Delete photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
