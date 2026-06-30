@extends('layouts.app')

@section('title', __('messages.patient_list'))

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.patient_list') }}</h4>
            <p class="text-muted text-sm mb-0">{{ __('messages.patient_directory_subtitle') }}</p>
        </div>
        <a href="{{ route('patients.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>{{ __('messages.add_patient') }}
        </a>
    </div>

    <!-- Responsive Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle">
            <thead class="text-secondary opacity-75 border-bottom">
                <tr>
                    <th>{{ __('messages.patient_number') }}</th>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.nik') }}</th>
                    <th>{{ __('messages.gender') }}</th>
                    <th>{{ __('messages.birth_date') }}</th>
                    <th>{{ __('messages.phone') }}</th>
                    <th>{{ __('messages.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td class="fw-bold text-primary">{{ $patient->patient_number }}</td>
                        <td class="fw-bold">{{ $patient->name }}</td>
                        <td><code>{{ $patient->nik }}</code></td>
                        <td>
                            @if($patient->gender == 'L')
                                <span class="badge bg-primary-subtle text-primary badge-pill" style="border-radius: 8px; padding: 0.4rem 0.8rem;">{{ __('messages.male') }}</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger badge-pill" style="border-radius: 8px; padding: 0.4rem 0.8rem;">{{ __('messages.female') }}</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($patient->birth_date)->format('d-m-Y') }}</td>
                        <td>{{ $patient->phone ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="confirmDelete(event, this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            {{ __('messages.no_records') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card Layout -->
    <div class="d-md-none d-flex flex-column gap-3">
        @forelse($patients as $patient)
            <div class="border rounded-3 p-3 bg-body-tertiary shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-primary-subtle text-primary fw-bold" style="border-radius: 8px;">
                        {{ $patient->patient_number }}
                    </span>
                    <span class="text-sm font-monospace text-muted">{{ $patient->nik }}</span>
                </div>
                <h6 class="fw-bold mb-1">{{ $patient->name }}</h6>
                <div class="text-sm text-secondary mb-3">
                    <strong>{{ __('messages.gender') }}:</strong> {{ $patient->gender == 'L' ? __('messages.male') : __('messages.female') }}<br>
                    <strong>{{ __('messages.birth_date') }}:</strong> {{ \Carbon\Carbon::parse($patient->birth_date)->format('d-m-Y') }}<br>
                    <strong>{{ __('messages.phone') }}:</strong> {{ $patient->phone ?? '-' }}
                </div>
                <div class="d-flex justify-content-end gap-2 border-top pt-2">
                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-warning py-1 px-2">
                        <i class="bi bi-pencil-fill me-1"></i> {{ __('messages.edit') }}
                    </a>
                    @if(Auth::user()->role === 'admin')
                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="confirmDelete(event, this)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2">
                            <i class="bi bi-trash-fill me-1"></i> {{ __('messages.delete') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">
                {{ __('messages.no_records') }}
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $patients->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(e, form) {
        e.preventDefault();
        Swal.fire({
            title: "{{ __('messages.confirm_delete_title') }}",
            text: "{{ __('messages.confirm_delete_patient') }}",
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
