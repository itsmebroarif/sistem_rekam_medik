@extends('layouts.app')

@section('title', __('messages.manage_doctors'))

@section('content')
<div class="card p-3 shadow-sm border-0 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-person-badge me-2"></i>{{ __('messages.doctor_directory') }}</h5>
            <small class="text-secondary text-xs">{{ __('messages.doctor_directory_subtitle') }}</small>
        </div>
        <a href="{{ route('doctors.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i> {{ __('messages.add_doctor') }}
        </a>
    </div>

    <!-- Responsive Table on Desktop -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle table-sm mb-0" style="font-size: 0.8rem;">
            <thead class="text-secondary opacity-75 border-bottom">
                <tr>
                    <th style="width: 50px;">{{ __('messages.photo') }}</th>
                    <th>{{ __('messages.doctor_number_label') }}</th>
                    <th>{{ __('messages.doctor_name') }}</th>
                    <th>{{ __('messages.specialization_label') }}</th>
                    <th>{{ __('messages.phone_label') }}</th>
                    <th>{{ __('messages.duty_address_label') }}</th>
                    <th class="text-end" style="width: 100px;">{{ __('messages.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td>
                            <div class="rounded-circle border border-primary border-opacity-25" style="width: 36px; height: 36px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: var(--bs-body-bg);">
                                @if($doctor->profile_photo)
                                    <img src="{{ asset('storage/' . $doctor->profile_photo) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="bi bi-person-fill text-muted fs-5"></i>
                                @endif
                            </div>
                        </td>
                        <td class="fw-bold text-primary">{{ $doctor->doctor_number }}</td>
                        <td class="fw-bold">{{ $doctor->name }}</td>
                        <td><span class="badge bg-primary-subtle text-primary" style="font-size: 0.7rem; border-radius: 6px;">{{ $doctor->specialization }}</span></td>
                        <td>{{ $doctor->phone ?? '-' }}</td>
                        <td>{{ $doctor->duty_address ?? '-' }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-xs btn-outline-warning py-1 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" onsubmit="confirmDeleteDoctor(event, this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger py-1 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-1"></i> {{ __('messages.no_doctors') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card Layout -->
    <div class="d-md-none d-flex flex-column gap-2">
        @forelse($doctors as $doctor)
            <div class="border rounded-3 p-3 bg-body-tertiary shadow-sm" style="font-size: 0.75rem;">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="rounded-circle border border-primary border-opacity-25" style="width: 44px; height: 44px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: var(--bs-body-bg); flex-shrink: 0;">
                        @if($doctor->profile_photo)
                            <img src="{{ asset('storage/' . $doctor->profile_photo) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="bi bi-person-fill text-muted fs-4"></i>
                        @endif
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">{{ $doctor->name }}</h6>
                        <small class="text-primary fw-bold" style="font-size: 0.65rem;">{{ $doctor->doctor_number }}</small>
                    </div>
                </div>
                <div class="text-secondary mb-3">
                    <strong>{{ __('messages.specialist') }}:</strong> {{ $doctor->specialization }}<br>
                    <strong>{{ __('messages.phone_label') }}:</strong> {{ $doctor->phone ?? '-' }}<br>
                    <strong>{{ __('messages.duty_address_label') }}:</strong> {{ $doctor->duty_address ?? '-' }}
                </div>
                <div class="d-flex justify-content-end gap-2 border-top pt-2">
                    <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-sm btn-outline-warning py-1 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                        <i class="bi bi-pencil-fill me-1"></i> {{ __('messages.edit') }}
                    </a>
                    <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" onsubmit="confirmDeleteDoctor(event, this)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                            <i class="bi bi-trash-fill me-1"></i> {{ __('messages.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted border rounded-3 bg-body-tertiary">
                <i class="bi bi-inbox fs-4 d-block mb-1"></i> {{ __('messages.no_doctors') }}
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $doctors->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDeleteDoctor(e, form) {
        e.preventDefault();
        Swal.fire({
            title: "{{ __('messages.confirm_delete_doctor_title') }}",
            text: "{{ __('messages.confirm_delete_doctor_text') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: "{{ __('messages.yes_delete') }}",
            cancelButtonText: "{{ __('messages.cancel') }}",
            background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' : '#ffffff',
            color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#000000',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
