<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    /**
     * Pastikan hanya admin yang dapat mengakses controller ini.
     */
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Hanya administrator yang memiliki akses ke menu Manajemen Dokter.');
        }
        return null;
    }

    /**
     * Tampilkan daftar dokter.
     */
    public function index()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $doctors = Doctor::orderBy('name', 'asc')->paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Tampilkan formulir tambah dokter baru.
     */
    public function create()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        return view('doctors.create');
    }

    /**
     * Simpan data dokter baru ke database.
     */
    public function store(Request $request)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'duty_address' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Auto-generate doctor number (e.g. DR-2026-0001)
        $year = date('Y');
        $count = Doctor::whereYear('created_at', $year)->count() + 1;
        $doctorNumber = 'DR-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        Doctor::create([
            'doctor_number' => $doctorNumber,
            'name' => $request->name,
            'specialization' => $request->specialization,
            'phone' => $request->phone,
            'duty_address' => $request->duty_address,
            'profile_photo' => $photoPath,
        ]);

        return redirect()->route('doctors.index')
            ->with('success', 'Profil dokter berhasil didaftarkan.');
    }

    /**
     * Tampilkan formulir ubah data dokter.
     */
    public function edit(Doctor $doctor)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Perbarui data dokter di database.
     */
    public function update(Request $request, Doctor $doctor)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'duty_address' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($doctor->profile_photo && Storage::disk('public')->exists($doctor->profile_photo)) {
                Storage::disk('public')->delete($doctor->profile_photo);
            }
            $doctor->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $doctor->update([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'phone' => $request->phone,
            'duty_address' => $request->duty_address,
            'profile_photo' => $doctor->profile_photo,
        ]);

        return redirect()->route('doctors.index')
            ->with('success', 'Profil dokter berhasil diperbarui.');
    }

    /**
     * Hapus data dokter dari database.
     */
    public function destroy(Doctor $doctor)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        // Delete photo from storage if exists
        if ($doctor->profile_photo && Storage::disk('public')->exists($doctor->profile_photo)) {
            Storage::disk('public')->delete($doctor->profile_photo);
        }

        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Profil dokter berhasil dihapus.');
    }
}
