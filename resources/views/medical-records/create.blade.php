@extends('layouts.app')

@section('title', __('messages.add_rme'))

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card p-4">
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm rounded-3 mb-2">
                    <i class="bi bi-arrow-left me-1"></i> {{ __('messages.back') }}
                </a>
                <h4 class="fw-bold mb-0">{{ __('messages.add_rme') }}</h4>
                <p class="text-muted text-sm">{{ __('messages.rme_subtitle') }}</p>
            </div>

            <form action="{{ route('medical-records.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Patient Select -->
                    <div class="col-12 col-md-6">
                        <label for="patient_id" class="form-label fw-bold text-secondary text-sm">{{ __('messages.select_patient') }} <span class="text-danger">*</span></label>
                        <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="" disabled selected>{{ __('messages.select_patient_placeholder') }}</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} ({{ $patient->patient_number }} - NIK: {{ $patient->nik }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Doctor Select -->
                    <div class="col-12 col-md-6">
                        <label for="doctor_id" class="form-label fw-bold text-secondary text-sm">{{ __('messages.select_doctor') }} <span class="text-danger">*</span></label>
                        <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                            <option value="" disabled selected>{{ __('messages.select_doctor_placeholder') }}</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }} ({{ $doctor->specialization }})
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Record Date -->
                    <div class="col-12 col-md-6">
                        <label for="record_date" class="form-label fw-bold text-secondary text-sm">{{ __('messages.record_date') }} <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="record_date" id="record_date" class="form-control @error('record_date') is-invalid @enderror" value="{{ old('record_date', now()->format('Y-m-d\TH:i')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Diagnosis -->
                    <div class="col-12 col-md-6">
                        <label for="diagnosis" class="form-label fw-bold text-secondary text-sm">{{ __('messages.diagnosis') }} <span class="text-danger">*</span></label>
                        <input type="text" name="diagnosis" id="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" value="{{ old('diagnosis') }}" required placeholder="{{ app()->getLocale() == 'id' ? 'Contoh: Hipertensi, Faringitis Akut, Dyspepsia' : 'Example: Hypertension, Acute Pharyngitis, Dyspepsia' }}">
                        @error('diagnosis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4 text-muted">
                    <h5 class="fw-bold mb-2 text-primary"><i class="bi bi-file-earmark-medical me-1"></i> {{ __('messages.soap_title') }}</h5>

                    <!-- Subjective -->
                    <div class="col-12 col-md-6">
                        <label for="subjective" class="form-label fw-bold text-secondary text-sm">{{ __('messages.subjective') }} <span class="text-danger">*</span></label>
                        <textarea name="subjective" id="subjective" rows="3" class="form-control @error('subjective') is-invalid @enderror" required placeholder="{{ __('messages.subjective_placeholder') }}">{{ old('subjective') }}</textarea>
                        @error('subjective')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Objective -->
                    <div class="col-12 col-md-6">
                        <label for="objective" class="form-label fw-bold text-secondary text-sm">{{ __('messages.objective') }} <span class="text-danger">*</span></label>
                        <textarea name="objective" id="objective" rows="3" class="form-control @error('objective') is-invalid @enderror" required placeholder="{{ __('messages.objective_placeholder') }}">{{ old('objective') }}</textarea>
                        @error('objective')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Assessment -->
                    <div class="col-12 col-md-6">
                        <label for="assessment" class="form-label fw-bold text-secondary text-sm">{{ __('messages.assessment') }} <span class="text-danger">*</span></label>
                        <textarea name="assessment" id="assessment" rows="3" class="form-control @error('assessment') is-invalid @enderror" required placeholder="{{ __('messages.assessment_placeholder') }}">{{ old('assessment') }}</textarea>
                        @error('assessment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Planning -->
                    <div class="col-12 col-md-6">
                        <label for="planning" class="form-label fw-bold text-secondary text-sm">{{ __('messages.planning') }} <span class="text-danger">*</span></label>
                        <textarea name="planning" id="planning" rows="3" class="form-control @error('planning') is-invalid @enderror" required placeholder="{{ __('messages.planning_placeholder') }}">{{ old('planning') }}</textarea>
                        @error('planning')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Treatment -->
                    <div class="col-12">
                        <label for="treatment" class="form-label fw-bold text-secondary text-sm">{{ __('messages.treatment') }}</label>
                        <textarea name="treatment" id="treatment" rows="2" class="form-control @error('treatment') is-invalid @enderror" placeholder="{{ __('messages.treatment_placeholder') }}">{{ old('treatment') }}</textarea>
                        @error('treatment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-light">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save2 me-1"></i> {{ __('messages.save_rme') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
