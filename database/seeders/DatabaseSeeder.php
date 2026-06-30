<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\MedicalRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users for each role
        User::updateOrCreate(
            ['email' => 'admin@simrs.com'],
            [
                'name' => 'Administrator SIMRS',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'doctor@simrs.com'],
            [
                'name' => 'Dr. Bambang Utomo, Sp.PD',
                'password' => Hash::make('password'),
                'role' => 'doctor'
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@simrs.com'],
            [
                'name' => 'Siti Aminah (Staff Admin)',
                'password' => Hash::make('password'),
                'role' => 'staff'
            ]
        );

        // 2. Seed Doctors
        $doctor1 = Doctor::updateOrCreate(
            ['doctor_number' => 'D-001'],
            [
                'name' => 'Dr. Bambang Utomo, Sp.PD',
                'specialization' => 'Spesialis Penyakit Dalam',
                'phone' => '081234567890'
            ]
        );

        $doctor2 = Doctor::updateOrCreate(
            ['doctor_number' => 'D-002'],
            [
                'name' => 'Dr. Sarah Wijaya, Sp.A',
                'specialization' => 'Spesialis Anak',
                'phone' => '081234567891'
            ]
        );

        // 3. Seed Patients
        $patient1 = Patient::updateOrCreate(
            ['nik' => '3171012345670001'],
            [
                'patient_number' => 'RM-2026-0001',
                'name' => 'Budi Santoso',
                'gender' => 'L',
                'birth_date' => '1985-05-12',
                'phone' => '081987654321',
                'address' => 'Jl. Jenderal Sudirman No. 12, Jakarta Selatan'
            ]
        );

        $patient2 = Patient::updateOrCreate(
            ['nik' => '3171012345670002'],
            [
                'patient_number' => 'RM-2026-0002',
                'name' => 'Dewi Lestari',
                'gender' => 'P',
                'birth_date' => '1990-08-24',
                'phone' => '081987654322',
                'address' => 'Jl. Gatot Subroto No. 45, Jakarta Selatan'
            ]
        );

        $patient3 = Patient::updateOrCreate(
            ['nik' => '3171012345670003'],
            [
                'patient_number' => 'RM-2026-0003',
                'name' => 'Joko Widodo',
                'gender' => 'L',
                'birth_date' => '1961-06-21',
                'phone' => '081987654323',
                'address' => 'Jl. Slamet Riyadi No. 100, Solo'
            ]
        );

        // 4. Seed Medical Records
        MedicalRecord::updateOrCreate(
            [
                'patient_id' => $patient1->id,
                'doctor_id' => $doctor1->id,
                'record_date' => '2026-06-30 09:30:00'
            ],
            [
                'subjective' => 'Pasien mengeluh pusing dan mual sejak 2 hari yang lalu. Ada riwayat hipertensi.',
                'objective' => 'TD: 140/90 mmHg, Nadi: 88x/menit, Suhu: 36.8 C.',
                'assessment' => 'Essential Hypertension',
                'planning' => 'Berikan Amlodipine 5mg sekali sehari. Edukasi kurangi konsumsi garam.',
                'diagnosis' => 'Hipertensi',
                'treatment' => 'Amlodipine 5mg (10 tablet), KIE pola makan',
                'bpjs_sync_status' => 'synced',
                'satusehat_sync_status' => 'synced'
            ]
        );

        MedicalRecord::updateOrCreate(
            [
                'patient_id' => $patient2->id,
                'doctor_id' => $doctor2->id,
                'record_date' => '2026-06-30 10:15:00'
            ],
            [
                'subjective' => 'Anak panas naik turun sejak kemarin sore, batuk pilek ringan.',
                'objective' => 'TD: - (tidak dilakukan), Nadi: 110x/menit, Suhu: 38.5 C. Tenggorokan hiperemis (+).',
                'assessment' => 'Acute Pharyngitis / Common Cold',
                'planning' => 'Berikan Paracetamol sirup 3x120mg prn demam. Banyak minum air putih hangat.',
                'diagnosis' => 'Faringitis Akut',
                'treatment' => 'Paracetamol sirup (1 botol), Vitamin C sirup (1 botol)',
                'bpjs_sync_status' => 'pending',
                'satusehat_sync_status' => 'pending'
            ]
        );
    }
}
