<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'patient_id',
    'doctor_id',
    'record_date',
    'subjective',
    'objective',
    'assessment',
    'planning',
    'diagnosis',
    'treatment',
    'bpjs_sync_status',
    'satusehat_sync_status'
])]
class MedicalRecord extends Model
{
    use HasFactory;

    /**
     * Get the patient that owns the medical record.
     *
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the medical record.
     *
     * @return BelongsTo<Doctor, $this>
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
