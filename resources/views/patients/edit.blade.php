@extends('layouts.app')

@section('title', __('messages.edit_patient'))

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card p-4">
            <div class="mb-4">
                <a href="{{ route('patients.index') }}" class="btn btn-light btn-sm rounded-3 mb-2">
                    <i class="bi bi-arrow-left me-1"></i> {{ __('messages.back') }}
                </a>
                <h4 class="fw-bold mb-0">{{ __('messages.edit_patient') }}</h4>
                <p class="text-muted text-sm">{{ __('messages.personal_data_accuracy') }}</p>
            </div>

            <form action="{{ route('patients.update', $patient->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-12">
                        <label for="name" class="form-label fw-bold text-secondary text-sm">{{ __('messages.full_name_label') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $patient->name) }}" required placeholder="{{ __('messages.fullname_example') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div class="col-12 col-md-6">
                        <label for="nik" class="form-label fw-bold text-secondary text-sm">{{ __('messages.nik_label') }} <span class="text-danger">*</span></label>
                        <input type="text" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $patient->nik) }}" required placeholder="{{ __('messages.nik_placeholder') }}" maxlength="16">
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="col-12 col-md-6">
                        <label for="gender" class="form-label fw-bold text-secondary text-sm">{{ __('messages.gender') }} <span class="text-danger">*</span></label>
                        <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="" disabled>{{ __('messages.gender_placeholder') }}</option>
                            <option value="L" {{ old('gender', $patient->gender) == 'L' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                            <option value="P" {{ old('gender', $patient->gender) == 'P' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Birth Date -->
                    <div class="col-12 col-md-6">
                        <label for="birth_date" class="form-label fw-bold text-secondary text-sm">{{ __('messages.birth_date') }} <span class="text-danger">*</span></label>
                        <input type="date" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $patient->birth_date) }}" required>
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-12 col-md-6">
                        <label for="phone" class="form-label fw-bold text-secondary text-sm">{{ __('messages.phone') }}</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $patient->phone) }}" placeholder="{{ __('messages.phone_placeholder') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-12">
                        <label for="address" class="form-label fw-bold text-secondary text-sm">{{ __('messages.address') }}</label>
                        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" placeholder="{{ __('messages.address_placeholder') }}">{{ old('address', $patient->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('patients.index') }}" class="btn btn-light">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
