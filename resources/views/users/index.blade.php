@extends('layouts.app')

@section('title', __('messages.manage_staff'))

@section('content')
<div class="card p-3 shadow-sm border-0 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-people-fill me-2"></i>{{ __('messages.staff_directory') }}</h5>
            <small class="text-secondary text-xs">{{ __('messages.staff_directory_subtitle') }}</small>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i> {{ __('messages.add_staff') }}
        </a>
    </div>

    <!-- Responsive Table on Desktop -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle table-sm mb-0" style="font-size: 0.8rem;">
            <thead class="text-secondary opacity-75 border-bottom">
                <tr>
                    <th style="width: 50px;">{{ __('messages.photo') }}</th>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.email') }}</th>
                    <th>{{ __('messages.phone_label') }}</th>
                    <th>{{ __('messages.role_label') }}</th>
                    <th>{{ __('messages.duty_address_label') }}</th>
                    <th class="text-end" style="width: 100px;">{{ __('messages.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>
                            <div class="rounded-circle border border-primary border-opacity-25" style="width: 36px; height: 36px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: var(--bs-body-bg);">
                                @if($u->profile_photo)
                                    <img src="{{ asset('storage/' . $u->profile_photo) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="bi bi-person-fill text-muted fs-5"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $u->name }}</div>
                            @if($u->id === Auth::user()->id)
                                <span class="badge bg-warning-subtle text-warning" style="font-size: 0.6rem; border-radius: 4px;">{{ app()->getLocale() == 'id' ? 'Aktif (Anda)' : 'Active (You)' }}</span>
                            @endif
                        </td>
                        <td><code>{{ $u->email }}</code></td>
                        <td>{{ $u->phone ?? '-' }}</td>
                        <td>
                            @if($u->role === 'admin')
                                <span class="badge bg-danger-subtle text-danger" style="font-size: 0.7rem; border-radius: 6px;">{{ __('messages.role_admin') }}</span>
                            @elseif($u->role === 'doctor')
                                <span class="badge bg-success-subtle text-success" style="font-size: 0.7rem; border-radius: 6px;">{{ __('messages.role_doctor') }}</span>
                            @else
                                <span class="badge bg-info-subtle text-info" style="font-size: 0.7rem; border-radius: 6px;">{{ __('messages.role_staff') }}</span>
                            @endif
                        </td>
                        <td>{{ $u->duty_address ?? '-' }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('users.edit', $u->id) }}" class="btn btn-xs btn-outline-warning py-1 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                @if($u->id === Auth::user()->id)
                                    <button class="btn btn-xs btn-outline-secondary py-1 px-2" disabled style="font-size: 0.7rem; border-radius: 6px;" title="{{ app()->getLocale() == 'id' ? 'Anda tidak dapat menghapus akun sendiri' : 'You cannot delete your own account' }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                @else
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="confirmDeleteUser(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-outline-danger py-1 px-2" style="font-size: 0.7rem; border-radius: 6px;">
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
                            <i class="bi bi-inbox fs-4 d-block mb-1"></i> {{ __('messages.no_staff') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card Layout -->
    <div class="d-md-none d-flex flex-column gap-2">
        @forelse($users as $u)
            <div class="border rounded-3 p-3 bg-body-tertiary shadow-sm" style="font-size: 0.75rem;">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="rounded-circle border border-primary border-opacity-25" style="width: 44px; height: 44px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: var(--bs-body-bg); flex-shrink: 0;">
                        @if($u->profile_photo)
                            <img src="{{ asset('storage/' . $u->profile_photo) }}" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="bi bi-person-fill text-muted fs-4"></i>
                        @endif
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">
                            {{ $u->name }}
                            @if($u->id === Auth::user()->id)
                                <span class="badge bg-warning-subtle text-warning ms-1" style="font-size: 0.55rem; border-radius: 4px;">{{ app()->getLocale() == 'id' ? 'Anda' : 'You' }}</span>
                            @endif
                        </h6>
                        <small class="text-muted" style="font-size: 0.65rem;">{{ $u->email }}</small>
                    </div>
                </div>
                <div class="text-secondary mb-3">
                    <strong>{{ __('messages.role') }}:</strong> 
                    @if($u->role === 'admin')
                        <span class="badge bg-danger-subtle text-danger" style="font-size: 0.65rem; border-radius: 6px;">{{ __('messages.role_admin') }}</span>
                    @elseif($u->role === 'doctor')
                        <span class="badge bg-success-subtle text-success" style="font-size: 0.65rem; border-radius: 6px;">{{ __('messages.role_doctor') }}</span>
                    @else
                        <span class="badge bg-info-subtle text-info" style="font-size: 0.65rem; border-radius: 6px;">{{ __('messages.role_staff') }}</span>
                    @endif
                    <br>
                    <strong>{{ __('messages.phone_label') }}:</strong> {{ $u->phone ?? '-' }}<br>
                    <strong>{{ __('messages.duty_address_label') }}:</strong> {{ $u->duty_address ?? '-' }}
                </div>
                <div class="d-flex justify-content-end gap-2 border-top pt-2">
                    <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-outline-warning py-1 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                        <i class="bi bi-pencil-fill me-1"></i> {{ __('messages.edit') }}
                    </a>
                    @if($u->id === Auth::user()->id)
                        <button class="btn btn-sm btn-outline-secondary py-1 px-2" disabled style="font-size: 0.7rem; border-radius: 6px;">
                            <i class="bi bi-trash-fill me-1"></i> {{ __('messages.delete') }}
                        </button>
                    @else
                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="confirmDeleteUser(event, this)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size: 0.7rem; border-radius: 6px;">
                                <i class="bi bi-trash-fill me-1"></i> {{ __('messages.delete') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted border rounded-3 bg-body-tertiary">
                <i class="bi bi-inbox fs-4 d-block mb-1"></i> {{ __('messages.no_staff') }}
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDeleteUser(e, form) {
        e.preventDefault();
        Swal.fire({
            title: "{{ __('messages.confirm_delete_staff_title') }}",
            text: "{{ __('messages.confirm_delete_staff_text') }}",
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
