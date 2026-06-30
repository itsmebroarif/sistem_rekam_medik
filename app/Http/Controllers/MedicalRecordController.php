<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Tampilkan Dashboard Pemantauan dengan 3 Widget.
     */
    public function dashboard()
    {
        $totalPatients = Patient::count();
        $totalRmeToday = MedicalRecord::whereDate('record_date', today())->count();
        
        $bpjsSynced = MedicalRecord::where('bpjs_sync_status', 'synced')->count();
        $bpjsTotal = MedicalRecord::count();
        $satusehatSynced = MedicalRecord::where('satusehat_sync_status', 'synced')->count();
        $satusehatTotal = $bpjsTotal;

        $recentRecords = MedicalRecord::with(['patient', 'doctor'])
            ->orderBy('record_date', 'desc')
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalPatients',
            'totalRmeToday',
            'bpjsSynced',
            'bpjsTotal',
            'satusehatSynced',
            'satusehatTotal',
            'recentRecords'
        ));
    }

    /**
     * Tampilkan formulir rekam medis baru.
     */
    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'doctor'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Staff tidak memiliki akses untuk membuat rekam medis SOAP.');
        }

        $patients = Patient::orderBy('name', 'asc')->get();
        $doctors = Doctor::orderBy('name', 'asc')->get();
        return view('medical-records.create', compact('patients', 'doctors'));
    }

    /**
     * Simpan entri RME baru.
     */
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'doctor'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Staff tidak memiliki akses untuk membuat rekam medis SOAP.');
        }

        $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'record_date' => ['required', 'date'],
            'subjective' => ['required', 'string'],
            'objective' => ['required', 'string'],
            'assessment' => ['required', 'string'],
            'planning' => ['required', 'string'],
            'diagnosis' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string'],
        ]);

        MedicalRecord::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'record_date' => $request->record_date,
            'subjective' => $request->subjective,
            'objective' => $request->objective,
            'assessment' => $request->assessment,
            'planning' => $request->planning,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'bpjs_sync_status' => 'pending',
            'satusehat_sync_status' => 'pending',
        ]);

        return redirect()->route('dashboard')
            ->with('success', __('messages.success_rme_create'));
    }

    /**
     * Hapus entri rekam medis dari database.
     */
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Hanya administrator yang dapat menghapus catatan rekam medis.');
        }

        $record = MedicalRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Catatan rekam medis berhasil dihapus.');
    }
}
