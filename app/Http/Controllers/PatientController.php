<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Tampilkan daftar pasien.
     */
    public function index()
    {
        $patients = Patient::orderBy('created_at', 'desc')->paginate(10);
        return view('patients.index', compact('patients'));
    }

    /**
     * Tampilkan formulir pasien baru.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Simpan data pasien baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16', 'unique:patients,nik'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $year = date('Y');
        $count = Patient::whereYear('created_at', $year)->count() + 1;
        $patientNumber = 'RM-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        Patient::create([
            'patient_number' => $patientNumber,
            'name' => $request->name,
            'nik' => $request->nik,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('patients.index')
            ->with('success', __('messages.success_patient_create'));
    }

    /**
     * Tampilkan formulir ubah data pasien.
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Perbarui data pasien.
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16', 'unique:patients,nik,' . $patient->id],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $patient->update([
            'name' => $request->name,
            'nik' => $request->nik,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('patients.index')
            ->with('success', __('messages.success_patient_update'));
    }

    /**
     * Hapus data pasien dari database.
     */
    public function destroy(Patient $patient)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('patients.index')
                ->with('error', 'Hanya administrator yang dapat menghapus data pasien.');
        }

        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', __('messages.success_patient_delete'));
    }
}
