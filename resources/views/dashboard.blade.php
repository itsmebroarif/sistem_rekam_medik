@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
    <div class="row g-3 mb-3">
        <!-- Widget 1: Total Patients -->
        <div class="col-12 col-md-4">
            <div class="card h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.3px;">
                        {{ __('messages.total_patients') }}
                    </span>
                    <h3 class="mb-0 mt-1 fw-bold text-primary">{{ $totalPatients }}</h3>
                </div>
                <div class="rounded-3 p-2"
                    style="background-color: var(--brand-primary-light); color: var(--brand-primary); width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Widget 2: Total RME Today -->
        <div class="col-12 col-md-4">
            <div class="card h-100 p-3 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.3px;">
                        {{ __('messages.total_rme_today') }}
                    </span>
                    <h3 class="mb-0 mt-1 fw-bold text-success">{{ $totalRmeToday }}</h3>
                </div>
                <div class="rounded-3 p-2"
                    style="background-color: rgba(16, 185, 129, 0.06); color: var(--brand-success); width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-journal-medical fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Widget 3: BPJS / SATUSEHAT Sync Status -->
        <div class="col-12 col-md-4">
            <div class="card h-100 p-3 d-flex flex-column justify-content-center">
                <span class="text-muted fw-bold text-uppercase mb-1"
                    style="font-size: 0.7rem; letter-spacing: 0.3px; display: block;">
                    {{ __('messages.bpjs_satusehat_sync_counter') }}
                </span>
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-xs" style="font-size: 0.75rem;"><i
                                class="bi bi-link-45deg me-1 text-info"></i>BPJS</span>
                        <span class="fw-bold text-xs" style="font-size: 0.75rem;">
                            <span class="text-success">{{ $bpjsSynced }}</span> / <span
                                class="text-muted">{{ $bpjsTotal }}</span>
                        </span>
                    </div>
                    <div class="progress" style="height: 4px; border-radius: 50rem;">
                        <div class="progress-bar bg-info" role="progressbar"
                            style="width: {{ $bpjsTotal > 0 ? ($bpjsSynced / $bpjsTotal) * 100 : 0 }}%"
                            aria-valuenow="{{ $bpjsSynced }}" aria-valuemin="0" aria-valuemax="{{ $bpjsTotal }}"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-xs" style="font-size: 0.75rem;"><i
                                class="bi bi-heart-pulse-fill me-1 text-danger"></i>SATUSEHAT</span>
                        <span class="fw-bold text-xs" style="font-size: 0.75rem;">
                            <span class="text-success">{{ $satusehatSynced }}</span> / <span
                                class="text-muted">{{ $satusehatTotal }}</span>
                        </span>
                    </div>
                    <div class="progress" style="height: 4px; border-radius: 50rem;">
                        <div class="progress-bar bg-danger" role="progressbar"
                            style="width: {{ $satusehatTotal > 0 ? ($satusehatSynced / $satusehatTotal) * 100 : 0 }}%"
                            aria-valuenow="{{ $satusehatSynced }}" aria-valuemin="0"
                            aria-valuemax="{{ $satusehatTotal }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <!-- Three.js Pulse Overview -->
        <div class="col-12 col-lg-4">
            <div class="card p-3 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h6 class="fw-bold mb-1">{{ __('messages.system_pulse_overview') }}</h6>
                    <p class="text-muted text-xs mb-2" style="font-size: 0.75rem;">{{ __('messages.system_pulse_subtitle') }}</p>
                </div>

                <div id="canvas-container" style="width: 100%; height: 160px; position: relative;">
                    <canvas id="three-pulse-canvas"
                        style="width: 100%; height: 100%; display: block; border-radius: 10px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Medical Records -->
        <div class="col-12 col-lg-8">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">{{ __('messages.recent_rme') }}</h6>
                    <a href="{{ route('medical-records.create') }}" class="btn btn-primary btn-sm px-2 py-1 rounded-2"
                        style="font-size: 0.75rem;">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('messages.add_rme') }}
                    </a>
                </div>

                <!-- Responsive Table on Desktop, Stacked Cards on Mobile -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle table-sm mb-0" style="font-size: 0.8rem;">
                        <thead class="text-secondary opacity-75 border-bottom">
                            <tr>
                                <th>{{ __('messages.patient_number') }}</th>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.diagnosis') }}</th>
                                <th>{{ __('messages.doctor') }}</th>
                                <th class="text-end">{{ __('messages.sync_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRecords as $record)
                                <tr>
                                    <td class="fw-bold text-primary" style="font-size: 0.75rem;">
                                        {{ $record->patient->patient_number }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $record->patient->name }}</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">NIK:
                                            {{ $record->patient->nik }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-body-emphasis">{{ $record->diagnosis }}</div>
                                        <small class="text-muted d-block text-truncate"
                                            style="max-width: 180px; font-size: 0.7rem;">
                                            {{ $record->subjective }}
                                        </small>
                                    </td>
                                    <td>{{ $record->doctor->name }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end align-items-center">
                                            @if (Auth::user()->role === 'admin')
                                                <!-- BPJS Sync Button -->
                                                <button
                                                    class="btn btn-xs py-1 px-2 {{ $record->bpjs_sync_status == 'synced' ? 'btn-success' : 'btn-outline-info' }}"
                                                    onclick="syncRecord('bpjs', {{ $record->id }})"
                                                    id="bpjs-btn-{{ $record->id }}"
                                                    {{ $record->bpjs_sync_status == 'synced' ? 'disabled' : '' }}
                                                    style="font-size: 0.7rem; border-radius: 6px;">
                                                    <i
                                                        class="bi {{ $record->bpjs_sync_status == 'synced' ? 'bi-check-circle-fill' : 'bi-arrow-left-right' }} me-1"></i>
                                                    BPJS
                                                </button>

                                                <!-- SATUSEHAT Sync Button -->
                                                <button
                                                    class="btn btn-xs py-1 px-2 {{ $record->satusehat_sync_status == 'synced' ? 'btn-success' : 'btn-outline-danger' }}"
                                                    onclick="syncRecord('satusehat', {{ $record->id }})"
                                                    id="satusehat-btn-{{ $record->id }}"
                                                    {{ $record->satusehat_sync_status == 'synced' ? 'disabled' : '' }}
                                                    style="font-size: 0.7rem; border-radius: 6px;">
                                                    <i
                                                        class="bi {{ $record->satusehat_sync_status == 'synced' ? 'bi-check-circle-fill' : 'bi-activity' }} me-1"></i>
                                                    SATUSEHAT
                                                </button>

                                                <!-- Delete Button -->
                                                <form action="{{ route('medical-records.destroy', $record->id) }}"
                                                    method="POST" onsubmit="confirmDeleteRecord(event, this)"
                                                    class="ms-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-outline-danger py-1 px-2"
                                                        style="font-size: 0.7rem; border-radius: 6px;">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span
                                                    class="badge {{ $record->bpjs_sync_status == 'synced' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} border-0 py-1 px-2"
                                                    style="font-size: 0.65rem; border-radius: 6px;">
                                                    BPJS:
                                                    {{ $record->bpjs_sync_status == 'synced' ? 'Synced' : 'Pending' }}
                                                </span>
                                                <span
                                                    class="badge {{ $record->satusehat_sync_status == 'synced' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} border-0 py-1 px-2"
                                                    style="font-size: 0.65rem; border-radius: 6px;">
                                                    SATUSEHAT:
                                                    {{ $record->satusehat_sync_status == 'synced' ? 'Synced' : 'Pending' }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted" style="font-size: 0.8rem;">
                                        <i class="bi bi-inbox fs-4 d-block mb-1"></i> {{ __('messages.no_rme_today') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Layout -->
                <div class="d-md-none d-flex flex-column gap-2">
                    @forelse($recentRecords as $record)
                        <div class="border rounded-3 p-2 bg-body-tertiary shadow-sm" style="font-size: 0.75rem;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="badge bg-primary-subtle text-primary fw-bold"
                                    style="border-radius: 6px; font-size: 0.65rem;">
                                    {{ $record->patient->patient_number }}
                                </span>
                                <small class="text-muted"
                                    style="font-size: 0.65rem;">{{ \Carbon\Carbon::parse($record->record_date)->format('H:i') }}</small>
                            </div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.8rem;">{{ $record->patient->name }}</h6>
                            <p class="mb-1 text-secondary" style="font-size: 0.7rem; line-height: 1.3;">
                                <strong>{{ __('messages.diagnosis') }}:</strong> {{ $record->diagnosis }}<br>
                                <strong>{{ __('messages.doctor') }}:</strong> {{ $record->doctor->name }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center border-top pt-1 mt-1">
                                @if (Auth::user()->role === 'admin')
                                    <form action="{{ route('medical-records.destroy', $record->id) }}" method="POST"
                                        onsubmit="confirmDeleteRecord(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-outline-danger py-1 px-2"
                                            style="font-size: 0.65rem; border-radius: 6px;">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                    <div class="d-flex gap-1">
                                        <button
                                            class="btn btn-xs py-1 px-2 btn-sm {{ $record->bpjs_sync_status == 'synced' ? 'btn-success' : 'btn-outline-info' }}"
                                            onclick="syncRecord('bpjs', {{ $record->id }})"
                                            id="bpjs-btn-mobile-{{ $record->id }}"
                                            {{ $record->bpjs_sync_status == 'synced' ? 'disabled' : '' }}
                                            style="font-size: 0.65rem; border-radius: 6px;">
                                            BPJS
                                        </button>
                                        <button
                                            class="btn btn-xs py-1 px-2 btn-sm {{ $record->satusehat_sync_status == 'synced' ? 'btn-success' : 'btn-outline-danger' }}"
                                            onclick="syncRecord('satusehat', {{ $record->id }})"
                                            id="satusehat-btn-mobile-{{ $record->id }}"
                                            {{ $record->satusehat_sync_status == 'synced' ? 'disabled' : '' }}
                                            style="font-size: 0.65rem; border-radius: 6px;">
                                            SATUSEHAT
                                        </button>
                                    </div>
                                @else
                                    <span
                                        class="badge {{ $record->bpjs_sync_status == 'synced' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} border-0 py-1 px-2"
                                        style="font-size: 0.6rem; border-radius: 6px;">
                                        BPJS: {{ $record->bpjs_sync_status == 'synced' ? 'Synced' : 'Pending' }}
                                    </span>
                                    <span
                                        class="badge {{ $record->satusehat_sync_status == 'synced' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} border-0 py-1 px-2"
                                        style="font-size: 0.6rem; border-radius: 6px;">
                                        SATUSEHAT: {{ $record->satusehat_sync_status == 'synced' ? 'Synced' : 'Pending' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-inbox fs-4 d-block mb-1"></i> {{ __('messages.no_rme_today') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        // Three.js 3D Grid Wave animation implementation
        let scene, camera, renderer, points;
        const container = document.getElementById('canvas-container');

        function initThree() {
            if (!container) return;

            const width = container.clientWidth;
            const height = container.clientHeight;

            scene = new THREE.Scene();

            camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
            camera.position.set(0, 18, 38);
            camera.lookAt(0, 0, 0);

            renderer = new THREE.WebGLRenderer({
                canvas: document.getElementById('three-pulse-canvas'),
                alpha: true,
                antialias: true
            });
            renderer.setSize(width, height);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            // Create Grid of Particle Points
            const countX = 26;
            const countY = 26;
            const spacing = 1.3;

            const geometry = new THREE.BufferGeometry();
            const positions = new Float32Array(countX * countY * 3);
            const colors = new Float32Array(countX * countY * 3);

            let k = 0;
            for (let x = 0; x < countX; x++) {
                for (let y = 0; y < countY; y++) {
                    // Initial Grid coordinates
                    positions[k] = (x - countX / 2) * spacing;
                    positions[k + 1] = 0;
                    positions[k + 2] = (y - countY / 2) * spacing;

                    // Color calculation: Cyan to Violet gradients
                    colors[k] = 0.38 + (x / countX) * 0.2; // R (99 / 255)
                    colors[k + 1] = 0.4 + (y / countY) * 0.3; // G (102 / 255)
                    colors[k + 2] = 0.94; // B (241 / 255)
                    k += 3;
                }
            }

            geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

            const material = new THREE.PointsMaterial({
                size: 0.45,
                vertexColors: true,
                transparent: true,
                opacity: 0.85
            });

            points = new THREE.Points(geometry, material);
            scene.add(points);

            animate();
        }

        let count = 0;

        function animate() {
            requestAnimationFrame(animate);

            const positions = points.geometry.attributes.position.array;
            const countX = 26;
            const countY = 26;

            let k = 0;
            for (let x = 0; x < countX; x++) {
                for (let y = 0; y < countY; y++) {
                    // Wave formula using sine/cosine representing a system pulse heartbeat
                    positions[k + 1] = Math.sin((x + count) * 0.25) * 1.2 + Math.cos((y + count) * 0.2) * 1.2;
                    k += 3;
                }
            }
            points.geometry.attributes.position.needsUpdate = true;
            points.rotation.y = count * 0.02;

            count += 0.04;
            renderer.render(scene, camera);
        }

        // Resize Handler
        window.addEventListener('resize', () => {
            if (!container || !camera || !renderer) return;
            const w = container.clientWidth;
            const h = container.clientHeight;
            camera.aspect = w / h;
            camera.updateProjectionMatrix();
            renderer.setSize(w, h);
        });

        document.addEventListener('DOMContentLoaded', () => {
            initThree();
        });

        // Integration Sync Bridging Handler via AJAX POST
        function syncRecord(provider, recordId) {
            const btn = document.getElementById(`${provider}-btn-${recordId}`);
            const mobileBtn = document.getElementById(`${provider}-btn-mobile-${recordId}`);

            const originalHtml = btn ? btn.innerHTML : '';
            if (btn) btn.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
            if (mobileBtn) mobileBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/integration/sync-${provider}/${recordId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: provider === 'bpjs' ? "{{ __('messages.bpjs_bridging_success') }}" : "{{ __('messages.satusehat_fhir_synced') }}",
                            html: `
                        <div class="text-start mt-2">
                            <p class="mb-1 text-success fw-bold">${data.message}</p>
                            <small class="d-block text-secondary mb-2">Timestamp: ${data.timestamp}</small>
                            <div class="p-3 bg-light rounded-3 font-monospace" style="font-size: 0.75rem;">
                                ${Object.entries(data.data).map(([key, val]) => `<strong>${key}:</strong> ${val}`).join('<br>')}
                            </div>
                        </div>
                    `,
                            background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ?
                                '#1e293b' : '#ffffff',
                            color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ?
                                '#ffffff' : '#000000',
                            confirmButtonColor: '#6366f1'
                        }).then(() => {
                            // Update buttons status to synced
                            if (btn) {
                                btn.className = 'btn btn-sm btn-pill btn-success';
                                btn.innerHTML =
                                    `<i class="bi bi-check-circle-fill me-1"></i> ${provider.toUpperCase()}`;
                                btn.disabled = true;
                            }
                            if (mobileBtn) {
                                mobileBtn.className = 'btn btn-xs py-1 px-2 btn-sm btn-success';
                                mobileBtn.innerHTML = `${provider.toUpperCase()}`;
                                mobileBtn.disabled = true;
                            }

                            // Reload statistics/page to reflect the update
                            setTimeout(() => window.location.reload(), 800);
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(err => {
                    if (btn) {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }
                    if (mobileBtn) mobileBtn.disabled = false;

                    Swal.fire({
                        icon: 'error',
                        title: "{{ __('messages.sync_failed') }}",
                        text: err.message || "{{ __('messages.sync_failed_text') }}",
                        confirmButtonColor: '#ef4444',
                        background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ?
                            '#1e293b' : '#ffffff',
                        color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' :
                            '#000000',
                    });
                });
        }

        function confirmDeleteRecord(e, form) {
            e.preventDefault();
            Swal.fire({
                title: "{{ __('messages.confirm_delete_rme_title') }}",
                text: "{{ __('messages.confirm_delete_rme_text') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: "{{ __('messages.yes_delete') }}",
                cancelButtonText: "{{ __('messages.cancel') }}",
                background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' :
                    '#ffffff',
                color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#000000',
            }).then((result) => {
                if (result.isConfirmed) {
                    sessionStorage.clear();
                    form.submit();
                }
            });
        }
    </script>
@endpush
