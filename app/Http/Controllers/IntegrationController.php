<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IntegrationController extends Controller
{
    /**
     * Integrasi BPJS Bridging (Simulasi).
     * TODO: Implementasikan koneksi API VClaim BPJS menggunakan signature header.
     */
    public function syncBpjs($id)
    {
        try {
            $record = MedicalRecord::with('patient')->findOrFail($id);

            // TODO: Ambil data pasien, rujukan, diagnosis, dan buat claim payload
            // Kirim ke endpoint BPJS VClaim menggunakan Guzzle/Http Client
            // $response = Http::withHeaders([...])->post('...', $payload);

            // Update status sinkronisasi di database
            $record->update(['bpjs_sync_status' => 'synced']);

            return response()->json([
                'status' => 200,
                'message' => 'Success Bridging BPJS',
                'timestamp' => now()->toIso8601String(),
                'data' => [
                    'sep_number' => 'SEP-18062026-' . rand(100000, 999999),
                    'patient_name' => $record->patient->name,
                    'diagnosis_code' => 'I10', // Hipertensi
                    'bridging_system' => 'BPJS VClaim v2.0'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Integrasi SATUSEHAT Kemenkes (Simulasi).
     * TODO: Implementasikan OAuth2 token retrieval dan FHIR resource upload.
     */
    public function syncSatusehat($id)
    {
        try {
            $record = MedicalRecord::with(['patient', 'doctor'])->findOrFail($id);

            // TODO: Buat FHIR resource (Encounter, Observation, Condition)
            // Lakukan POST ke SATUSEHAT API Gateway dengan Bearer Token
            // $response = Http::withToken($token)->post('...', $fhirPayload);

            // Update status sinkronisasi di database
            $record->update(['satusehat_sync_status' => 'synced']);

            return response()->json([
                'status' => 200,
                'message' => 'Success Bridging SATUSEHAT',
                'timestamp' => now()->toIso8601String(),
                'data' => [
                    'encounter_id' => 'enc-' . Str::uuid(),
                    'patient_uuid' => 'pat-' . Str::uuid(),
                    'doctor_uuid' => 'doc-' . Str::uuid(),
                    'fhir_status' => 'completed',
                    'bridging_system' => 'SATUSEHAT FHIR R4 API'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
