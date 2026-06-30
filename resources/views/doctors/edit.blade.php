@extends('layouts.app')

@section('title', __('messages.edit_doctor'))

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card p-4 shadow-sm border-0">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-secondary py-1 px-2" style="border-radius: 8px;">
                    <i class="bi bi-arrow-left"></i> {{ __('messages.back') }}
                </a>
                <h5 class="fw-bold mb-0 text-primary">{{ __('messages.edit_doctor') }}: {{ $doctor->name }}</h5>
            </div>

            <form action="{{ route('doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Photo Upload -->
                    <div class="col-12 text-center mb-3">
                        <div class="mx-auto rounded-circle border border-primary border-opacity-25 mb-2 position-relative" style="width: 100px; height: 100px; overflow: hidden; background-color: var(--bs-body-bg); display: flex; align-items: center; justify-content: center;">
                            @if($doctor->profile_photo)
                                <img id="photo-preview" src="{{ asset('storage/' . $doctor->profile_photo) }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                <i id="photo-placeholder" class="bi bi-person-fill text-muted d-none" style="font-size: 3.5rem;"></i>
                            @else
                                <img id="photo-preview" src="#" alt="Preview" class="d-none" style="width: 100%; height: 100%; object-fit: cover;">
                                <i id="photo-placeholder" class="bi bi-person-fill text-muted" style="font-size: 3.5rem;"></i>
                            @endif
                        </div>
                        <label for="profile_photo" class="btn btn-sm btn-outline-primary px-3" style="border-radius: 8px; font-size: 0.75rem;">
                            <i class="bi bi-camera-fill me-1"></i> {{ __('messages.profile_photo') }}
                        </label>
                        <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*" onchange="previewImage(this)">
                        @error('profile_photo')
                            <div class="text-danger text-xs mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="col-12">
                        <label for="name" class="form-label fw-bold text-secondary text-sm">{{ __('messages.doctor_name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $doctor->name) }}" required placeholder="{{ __('messages.fullname_example') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Specialization -->
                    <div class="col-12 col-md-6">
                        <label for="specialization" class="form-label fw-bold text-secondary text-sm">{{ __('messages.specialization_label') }} <span class="text-danger">*</span></label>
                        <input type="text" name="specialization" id="specialization" class="form-control @error('specialization') is-invalid @enderror" value="{{ old('specialization', $doctor->specialization) }}" required placeholder="{{ __('messages.specialization_placeholder') }}">
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-12 col-md-6">
                        <label for="phone" class="form-label fw-bold text-secondary text-sm">{{ __('messages.phone_label') }}</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $doctor->phone) }}" placeholder="{{ __('messages.phone_placeholder') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Duty Address -->
                    <div class="col-12">
                        <label for="duty_address" class="form-label fw-bold text-secondary text-sm">{{ __('messages.duty_address_label') }}</label>
                        <input type="text" name="duty_address" id="duty_address" class="form-control @error('duty_address') is-invalid @enderror" value="{{ old('duty_address', $doctor->duty_address) }}" placeholder="{{ __('messages.duty_address_placeholder') }}">
                        @error('duty_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('doctors.index') }}" class="btn btn-light px-4">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn btn-primary px-4">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('photo-preview');
        const placeholder = document.getElementById('photo-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
