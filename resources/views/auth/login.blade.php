@extends('layouts.app')

@section('title', __('messages.login'))

@section('content')
    <style>
        /* Bypassing container gutters for full screen split */
        .main-content {
            margin-left: 0 !important;
        }

        body {
            overflow: hidden;
        }

        @media (max-width: 767.98px) {
            body {
                overflow: auto;
            }
        }

        main.container-fluid {
            padding: 0 !important;
            max-width: 100vw !important;
        }

        .login-container {
            min-height: 100vh;
            width: 100%;
            margin: 0;
        }

        .login-form-side {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1.5rem 3rem;
            background-color: var(--bs-body-bg);
            transition: var(--transition-smooth);
        }

        .login-visual-side {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e1b4b);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #three-canvas-login {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .visual-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #ffffff;
            padding: 2.5rem 3rem;
            backdrop-filter: blur(8px);
            background: rgba(15, 23, 42, 0.45);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            max-width: 600px;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .hover-scale-card {
            transition: var(--transition-smooth);
        }

        .hover-scale-card:hover {
            transform: translateY(-4px) scale(1.02) !important;
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
        }

        @media (max-width: 991.98px) {
            .login-form-side {
                padding: 1.5rem 2rem;
            }
        }

        @media (max-width: 767.98px) {
            .login-form-side {
                padding: 1.5rem 1.25rem;
            }
        }
    </style>

    <div class="row g-0 login-container">
        <!-- Left Column (Unified sliding forms) -->
        <div class="col-12 col-md-6 col-lg-5 login-form-side position-relative bg-body-tertiary shadow-sm">
            <!-- Language Switcher absolute positioned -->
            <div class="position-absolute" style="top: 1.5rem; right: 1.5rem; z-index: 100;">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 12px; padding: 0.35rem 0.85rem;">
                        @if(app()->getLocale() == 'id')
                            <span>🇮🇩</span> <span class="text-xs">ID</span>
                        @else
                            <span>🇬🇧</span> <span class="text-xs">EN</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: 12px; min-width: 100px;">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-xs fw-bold" href="{{ route('lang.switch', 'id') }}">
                                <span>🇮🇩</span> ID
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-xs fw-bold" href="{{ route('lang.switch', 'en') }}">
                                <span>🇬🇧</span> EN
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Auth Slider Wrapper -->
            <div class="auth-slider-wrapper w-100 overflow-hidden py-2" style="max-width: 440px; margin: 0 auto;">
                <div class="auth-slider d-flex" style="width: 200%; transition: transform 0.6s cubic-bezier(0.76, 0, 0.24, 1);">
                    
                    <!-- Slide 1: Login -->
                    <div class="auth-slide w-50 pe-3 pe-md-4">
                        <div class="mb-3">
                            <div class="mb-2 text-white rounded-circle d-flex align-items-center justify-content-center animate__animated animate__bounceIn"
                                style="width: 42px; height: 42px; background: linear-gradient(135deg, #6366f1, #06b6d4);">
                                <i class="bi bi-heart-pulse-fill fs-5"></i>
                            </div>
                            <h4 class="fw-bold mb-0" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">SIMRS & RME Core</h4>
                            <p class="text-secondary text-xs mb-0">{{ __('messages.login_subtitle') }}</p>
                        </div>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-2">
                                <label for="email" class="form-label fw-bold text-secondary text-xs mb-1">{{ __('messages.email') }}</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-body-tertiary border-end-0"
                                        style="border-radius: 12px 0 0 12px;"><i class="bi bi-envelope text-muted"></i></span>
                                    <input type="email" name="email" id="email"
                                        class="form-control border-start-0 @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required autofocus placeholder="admin@simrs.com"
                                        style="border-radius: 0 12px 12px 0;">
                                </div>
                                @error('email')
                                    <div class="text-danger mt-1 text-xs" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-2">
                                <label for="password" class="form-label fw-bold text-secondary text-xs mb-1">{{ __('messages.password') }}</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-body-tertiary border-end-0"
                                        style="border-radius: 12px 0 0 12px;"><i class="bi bi-lock text-muted"></i></span>
                                    <input type="password" name="password" id="password"
                                        class="form-control border-start-0 @error('password') is-invalid @enderror" required
                                        placeholder="••••••••" style="border-radius: 0 12px 12px 0;">
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1 text-xs" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted text-xs" for="remember">
                                        {{ __('messages.remember_me') }}
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-2" style="border-radius: 12px; font-size: 0.8rem;">
                                {{ __('messages.login') }}
                            </button>

                            <div class="text-center text-muted text-xs">
                                {{ __('messages.dont_have_account') }} <a href="{{ route('register') }}"
                                    class="text-primary fw-bold text-decoration-none" onclick="event.preventDefault(); switchTo('register');">{{ __('messages.register_now') }}</a>
                            </div>
                        </form>

                        <!-- Quick Demo Credentials Helper (Mobile Only) -->
                        <div class="mt-3 d-md-none">
                            <div class="p-3 bg-body-tertiary border rounded-4 shadow-sm">
                                <small class="text-secondary fw-bold d-block mb-2">
                                    <i class="bi bi-lightning-charge-fill me-1 text-warning"></i> {{ __('messages.quick_try') }}
                                </small>
                                <div class="d-flex flex-column gap-1">
                                    <button type="button"
                                        class="btn btn-outline-primary btn-sm d-flex justify-content-between align-items-center border-dashed py-1"
                                        onclick="autoLogin('admin@simrs.com', 'password')" style="border-radius: 10px; font-size: 0.75rem;">
                                        <span><i class="bi bi-shield-lock-fill me-2"></i>{{ __('messages.administrator') }}</span>
                                        <small class="font-monospace text-muted" style="font-size: 0.7rem;">admin@simrs.com</small>
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-success btn-sm d-flex justify-content-between align-items-center border-dashed py-1"
                                        onclick="autoLogin('doctor@simrs.com', 'password')" style="border-radius: 10px; font-size: 0.75rem;">
                                        <span><i class="bi bi-heart-pulse-fill me-2"></i>{{ __('messages.specialist_doctor') }}</span>
                                        <small class="font-monospace text-muted"
                                            style="font-size: 0.7rem;">doctor@simrs.com</small>
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-info btn-sm d-flex justify-content-between align-items-center border-dashed py-1"
                                        onclick="autoLogin('staff@simrs.com', 'password')" style="border-radius: 10px; font-size: 0.75rem;">
                                        <span><i class="bi bi-person-fill-gear me-2"></i>{{ __('messages.staff_admin') }}</span>
                                        <small class="font-monospace text-muted" style="font-size: 0.7rem;">staff@simrs.com</small>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2: Register -->
                    <div class="auth-slide w-50 ps-3 ps-md-4">
                        <div class="mb-3">
                            <div class="mb-2 text-white rounded-circle d-flex align-items-center justify-content-center animate__animated animate__bounceIn"
                                style="width: 42px; height: 42px; background: linear-gradient(135deg, #6366f1, #06b6d4);">
                                <i class="bi bi-heart-pulse-fill fs-5"></i>
                            </div>
                            <h4 class="fw-bold mb-0" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">{{ __('messages.register_title') }}</h4>
                            <p class="text-secondary text-xs mb-0">{{ __('messages.register_subtitle') }}</p>
                        </div>

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <div class="row g-2 mb-2">
                                <!-- Name -->
                                <div class="col-12">
                                    <label for="reg_name" class="form-label fw-bold text-secondary text-xs mb-1">{{ __('messages.name') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-body-tertiary border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-person text-muted"></i></span>
                                        <input type="text" name="name" id="reg_name" class="form-control border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="{{ __('messages.name') }}" style="border-radius: 0 12px 12px 0;">
                                    </div>
                                    @error('name')
                                        <div class="text-danger mt-1 text-xxs" style="font-size: 0.7rem;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-6">
                                    <label for="reg_email" class="form-label fw-bold text-secondary text-xs mb-1">{{ __('messages.email') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-body-tertiary border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-envelope text-muted"></i></span>
                                        <input type="email" name="email" id="reg_email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="nama@email.com" style="border-radius: 0 12px 12px 0;">
                                    </div>
                                    @error('email')
                                        <div class="text-danger mt-1 text-xxs" style="font-size: 0.7rem;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="col-6">
                                    <label for="reg_role" class="form-label fw-bold text-secondary text-xs mb-1">{{ __('messages.role_label') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-body-tertiary border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-shield-lock text-muted"></i></span>
                                        <select name="role" id="reg_role" class="form-select border-start-0 @error('role') is-invalid @enderror" required style="border-radius: 0 12px 12px 0;">
                                            <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>{{ __('messages.staff_option') }}</option>
                                            <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>{{ __('messages.doctor_option') }}</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('messages.admin_option') }}</option>
                                        </select>
                                    </div>
                                    @error('role')
                                        <div class="text-danger mt-1 text-xxs" style="font-size: 0.7rem;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-6">
                                    <label for="reg_password" class="form-label fw-bold text-secondary text-xs mb-1">{{ __('messages.password') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-body-tertiary border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-lock text-muted"></i></span>
                                        <input type="password" name="password" id="reg_password" class="form-control border-start-0 @error('password') is-invalid @enderror" required placeholder="{{ __('messages.password_hint') }}" style="border-radius: 0 12px 12px 0;">
                                    </div>
                                    @error('password')
                                        <div class="text-danger mt-1 text-xxs" style="font-size: 0.7rem;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-6">
                                    <label for="reg_password_confirmation" class="form-label fw-bold text-secondary text-xs mb-1">{{ __('messages.confirm_password') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-body-tertiary border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-lock-fill text-muted"></i></span>
                                        <input type="password" name="password_confirmation" id="reg_password_confirmation" class="form-control border-start-0" required placeholder="{{ __('messages.password_confirm_hint') }}" style="border-radius: 0 12px 12px 0;">
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-2" style="border-radius: 12px; font-size: 0.8rem;">
                                {{ __('messages.register_button') }}
                            </button>

                            <div class="text-center text-muted text-xs">
                                {{ __('messages.already_have_account') }} <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none" onclick="event.preventDefault(); switchTo('login');">{{ __('messages.login_now') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (3D Animation & Desktop Demo Logins) -->
        <div class="col-12 col-md-6 col-lg-7 d-none d-md-flex login-visual-side">
            <canvas id="three-canvas-login"></canvas>
            <div class="visual-content animate__animated animate__fadeInUp">
                <h2 class="fw-bold mb-3 text-white"
                    style="font-family: 'Outfit', sans-serif; font-size: 2.2rem; letter-spacing: -0.8px;">{{ __('messages.hero_title') }}</h2>
                <p class="text-white-50 mx-auto mb-4" style="font-size: 0.9rem; line-height: 1.6; max-width: 480px;">
                    {{ __('messages.hero_subtitle') }}
                </p>

                <!-- Horizontal Demo Cards -->
                <div class="mt-4 pt-2 border-top border-white border-opacity-10">
                    <small class="text-white-50 fw-bold d-block mb-3 text-uppercase"
                        style="font-size: 0.7rem; letter-spacing: 1.5px;">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i> {{ __('messages.quick_try_instant') }}
                    </small>
                    <div class="row g-3 justify-content-center">
                        <!-- Administrator -->
                        <div class="col-4">
                            <div class="card bg-white bg-opacity-10 border border-white border-opacity-10 p-3 h-100 cursor-pointer hover-scale-card text-start"
                                onclick="autoLogin('admin@simrs.com', 'password')"
                                style="border-radius: 16px; backdrop-filter: blur(10px);">
                                <div class="mb-2 text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px; background: rgba(255,255,255,0.15);">
                                    <i class="bi bi-shield-lock-fill text-white fs-6"></i>
                                </div>
                                <h6 class="fw-bold text-white mb-1" style="font-size: 0.8rem;">{{ __('messages.role_admin') }}</h6>
                                <small class="text-white-50 font-monospace"
                                    style="font-size: 0.65rem;">admin@simrs.com</small>
                            </div>
                        </div>
                        <!-- Dokter -->
                        <div class="col-4">
                            <div class="card bg-white bg-opacity-10 border border-white border-opacity-10 p-3 h-100 cursor-pointer hover-scale-card text-start"
                                onclick="autoLogin('doctor@simrs.com', 'password')"
                                style="border-radius: 16px; backdrop-filter: blur(10px);">
                                <div class="mb-2 text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px; background: rgba(255,255,255,0.15);">
                                    <i class="bi bi-heart-pulse-fill text-white fs-6"></i>
                                </div>
                                <h6 class="fw-bold text-white mb-1" style="font-size: 0.8rem;">{{ __('messages.role_doctor') }}</h6>
                                <small class="text-white-50 font-monospace"
                                    style="font-size: 0.65rem;">doctor@simrs.com</small>
                            </div>
                        </div>
                        <!-- Staff Admin -->
                        <div class="col-4">
                            <div class="card bg-white bg-opacity-10 border border-white border-opacity-10 p-3 h-100 cursor-pointer hover-scale-card text-start"
                                onclick="autoLogin('staff@simrs.com', 'password')"
                                style="border-radius: 16px; backdrop-filter: blur(10px);">
                                <div class="mb-2 text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px; background: rgba(255,255,255,0.15);">
                                    <i class="bi bi-person-fill-gear text-white fs-6"></i>
                                </div>
                                <h6 class="fw-bold text-white mb-1" style="font-size: 0.8rem;">{{ __('messages.role_staff') }}</h6>
                                <small class="text-white-50 font-monospace"
                                    style="font-size: 0.65rem;">staff@simrs.com</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Load Three.js Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
        <script>
            // Bersihkan kunci notifikasi tersimpan saat memuat halaman masuk
            sessionStorage.clear();

            // Unified Auth SPA switching logic
            function switchTo(tab, animate = true) {
                const slider = document.querySelector('.auth-slider');
                if (!slider) return;

                if (!animate) {
                    slider.style.transition = 'none';
                } else {
                    slider.style.transition = 'transform 0.6s cubic-bezier(0.76, 0, 0.24, 1)';
                }

                if (tab === 'register') {
                    slider.style.transform = 'translateX(-50%)';
                    if (window.location.pathname !== '/register') {
                        history.pushState({ tab: 'register' }, '', '/register');
                    }
                    document.title = "{{ __('messages.register') }} | SIMRS & RME Core";
                } else {
                    slider.style.transform = 'translateX(0)';
                    if (window.location.pathname !== '/login') {
                        history.pushState({ tab: 'login' }, '', '/login');
                    }
                    document.title = "{{ __('messages.login') }} | SIMRS & RME Core";
                }

                if (!animate) {
                    // Force reflow and re-enable transition
                    slider.offsetHeight;
                    slider.style.transition = 'transform 0.6s cubic-bezier(0.76, 0, 0.24, 1)';
                }
            }

            // Handle back/forward navigation
            window.addEventListener('popstate', (e) => {
                if (e.state && e.state.tab) {
                    switchTo(e.state.tab, true);
                } else {
                    const path = window.location.pathname;
                    if (path.includes('register')) {
                        switchTo('register', true);
                    } else {
                        switchTo('login', true);
                    }
                }
            });

            // Set initial state on load
            document.addEventListener('DOMContentLoaded', () => {
                const path = window.location.pathname;
                const hasRegErrors = {{ ($errors->has('name') || $errors->has('role') || $errors->has('password_confirmation')) ? 'true' : 'false' }};
                if (path.includes('register') || hasRegErrors) {
                    switchTo('register', false);
                } else {
                    switchTo('login', false);
                }
            });

            function autoLogin(email, password) {
                document.getElementById('email').value = email;
                document.getElementById('password').value = password;

                Swal.fire({
                    title: "{{ __('messages.preparing_login') }}",
                    html: "{{ __('messages.demo_auth_processing') }}",
                    timer: 800,
                    timerProgressBar: true,
                    background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' :
                        '#ffffff',
                    color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#000000',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        document.querySelector('form[action="{{ route('login') }}"]').submit();
                    }
                });
            }

            // Three.js Login Animation
            (function() {
                const container = document.querySelector('.login-visual-side');
                const canvas = document.getElementById('three-canvas-login');

                if (canvas && container) {
                    const scene = new THREE.Scene();

                    // Camera
                    const camera = new THREE.PerspectiveCamera(60, container.clientWidth / container.clientHeight, 0.1,
                        100);
                    camera.position.z = 4.8;

                    // Renderer
                    const renderer = new THREE.WebGLRenderer({
                        canvas: canvas,
                        antialias: true,
                        alpha: true
                    });
                    renderer.setSize(container.clientWidth, container.clientHeight);
                    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

                    // Root Group for all objects
                    const visualGroup = new THREE.Group();
                    scene.add(visualGroup);

                    // 1. Dotted Globe (Fibonacci distribution for hospital node network representation)
                    const globeGroup = new THREE.Group();
                    const globeCount = 800;
                    const globeGeometry = new THREE.BufferGeometry();
                    const globePositions = new Float32Array(globeCount * 3);
                    const globeColors = new Float32Array(globeCount * 3);

                    const colGlobe1 = new THREE.Color('#06b6d4'); // Cyan
                    const colGlobe2 = new THREE.Color('#6366f1'); // Indigo

                    for (let i = 0; i < globeCount; i++) {
                        const phi = Math.acos(-1 + (2 * i) / globeCount);
                        const theta = Math.sqrt(globeCount * Math.PI) * phi;
                        const r = 2.4;

                        const x = r * Math.cos(theta) * Math.sin(phi);
                        const y = r * Math.sin(theta) * Math.sin(phi);
                        const z = r * Math.cos(phi);

                        globePositions[i * 3] = x;
                        globePositions[i * 3 + 1] = y;
                        globePositions[i * 3 + 2] = z;

                        // Color gradient based on Y height
                        const mixed = colGlobe1.clone().lerp(colGlobe2, (y + r) / (2 * r));
                        globeColors[i * 3] = mixed.r;
                        globeColors[i * 3 + 1] = mixed.g;
                        globeColors[i * 3 + 2] = mixed.b;
                    }

                    globeGeometry.setAttribute('position', new THREE.BufferAttribute(globePositions, 3));
                    globeGeometry.setAttribute('color', new THREE.BufferAttribute(globeColors, 3));

                    const globeMaterial = new THREE.PointsMaterial({
                        size: 0.04,
                        vertexColors: true,
                        transparent: true,
                        opacity: 0.7,
                        blending: THREE.AdditiveBlending,
                        depthWrite: false
                    });

                    const globePoints = new THREE.Points(globeGeometry, globeMaterial);
                    globeGroup.add(globePoints);

                    // Faint grid wireframe sphere
                    const wireSphereGeo = new THREE.SphereGeometry(2.38, 16, 16);
                    const wireSphereMat = new THREE.MeshBasicMaterial({
                        color: 0x06b6d4,
                        wireframe: true,
                        transparent: true,
                        opacity: 0.05,
                        blending: THREE.AdditiveBlending
                    });
                    const wireSphere = new THREE.Mesh(wireSphereGeo, wireSphereMat);
                    globeGroup.add(wireSphere);

                    visualGroup.add(globeGroup);

                    // 2. DNA Double Helix in the center (Attending doctor/genomics medical representation)
                    const dnaGroup = new THREE.Group();
                    const helixCount = 70;
                    const radiusHelix = 0.75;
                    const heightHelix = 3.6;

                    const strandAGeo = new THREE.BufferGeometry();
                    const strandBGeo = new THREE.BufferGeometry();
                    const rungsGeo = new THREE.BufferGeometry();

                    const posA = new Float32Array(helixCount * 3);
                    const posB = new Float32Array(helixCount * 3);
                    const posRungs = new Float32Array(helixCount * 2 * 3);

                    const colA = new THREE.Color('#6366f1'); // Indigo
                    const colB = new THREE.Color('#06b6d4'); // Cyan

                    const colorAArray = new Float32Array(helixCount * 3);
                    const colorBArray = new Float32Array(helixCount * 3);
                    const colorRungsArray = new Float32Array(helixCount * 2 * 3);

                    for (let i = 0; i < helixCount; i++) {
                        const pct = i / (helixCount - 1);
                        const y = (pct - 0.5) * heightHelix;
                        const angle = pct * Math.PI * 4; // 2 turns

                        const xA = radiusHelix * Math.cos(angle);
                        const zA = radiusHelix * Math.sin(angle);
                        posA[i * 3] = xA;
                        posA[i * 3 + 1] = y;
                        posA[i * 3 + 2] = zA;

                        colorAArray[i * 3] = colA.r;
                        colorAArray[i * 3 + 1] = colA.g;
                        colorAArray[i * 3 + 2] = colA.b;

                        const xB = radiusHelix * Math.cos(angle + Math.PI);
                        const zB = radiusHelix * Math.sin(angle + Math.PI);
                        posB[i * 3] = xB;
                        posB[i * 3 + 1] = y;
                        posB[i * 3 + 2] = zB;

                        colorBArray[i * 3] = colB.r;
                        colorBArray[i * 3 + 1] = colB.g;
                        colorBArray[i * 3 + 2] = colB.b;

                        // Rungs (bond line segments)
                        posRungs[i * 6] = xA;
                        posRungs[i * 6 + 1] = y;
                        posRungs[i * 6 + 2] = zA;

                        posRungs[i * 6 + 3] = xB;
                        posRungs[i * 6 + 4] = y;
                        posRungs[i * 6 + 5] = zB;

                        colorRungsArray[i * 6] = colA.r;
                        colorRungsArray[i * 6 + 1] = colA.g;
                        colorRungsArray[i * 6 + 2] = colA.b;

                        colorRungsArray[i * 6 + 3] = colB.r;
                        colorRungsArray[i * 6 + 4] = colB.g;
                        colorRungsArray[i * 6 + 5] = colB.b;
                    }

                    strandAGeo.setAttribute('position', new THREE.BufferAttribute(posA, 3));
                    strandAGeo.setAttribute('color', new THREE.BufferAttribute(colorAArray, 3));

                    strandBGeo.setAttribute('position', new THREE.BufferAttribute(posB, 3));
                    strandBGeo.setAttribute('color', new THREE.BufferAttribute(colorBArray, 3));

                    rungsGeo.setAttribute('position', new THREE.BufferAttribute(posRungs, 3));
                    rungsGeo.setAttribute('color', new THREE.BufferAttribute(colorRungsArray, 3));

                    const dnaPointMaterial = new THREE.PointsMaterial({
                        size: 0.085,
                        vertexColors: true,
                        transparent: true,
                        opacity: 0.95,
                        blending: THREE.AdditiveBlending,
                        depthWrite: false
                    });

                    const rungsMaterial = new THREE.LineBasicMaterial({
                        vertexColors: true,
                        transparent: true,
                        opacity: 0.4,
                        blending: THREE.AdditiveBlending
                    });

                    const strandAPoints = new THREE.Points(strandAGeo, dnaPointMaterial);
                    const strandBPoints = new THREE.Points(strandBGeo, dnaPointMaterial);
                    const rungsLines = new THREE.LineSegments(rungsGeo, rungsMaterial);

                    dnaGroup.add(strandAPoints);
                    dnaGroup.add(strandBPoints);
                    dnaGroup.add(rungsLines);

                    visualGroup.add(dnaGroup);

                    // 3. Glowing Scanner/Telemetry Rings (ECG heartbeat scanner concept)
                    const scannerGroup = new THREE.Group();
                    const ringPointsCount = 72;
                    const ringGeometry = new THREE.BufferGeometry();
                    const ringPositions = new Float32Array(ringPointsCount * 3);
                    const ringRadius = 1.7;

                    for (let i = 0; i < ringPointsCount; i++) {
                        const theta = (i / ringPointsCount) * Math.PI * 2;
                        ringPositions[i * 3] = ringRadius * Math.cos(theta);
                        ringPositions[i * 3 + 1] = 0;
                        ringPositions[i * 3 + 2] = ringRadius * Math.sin(theta);
                    }

                    ringGeometry.setAttribute('position', new THREE.BufferAttribute(ringPositions, 3));

                    const topRing = new THREE.Points(ringGeometry, new THREE.PointsMaterial({
                        color: 0x06b6d4,
                        size: 0.05,
                        transparent: true,
                        opacity: 0.85,
                        blending: THREE.AdditiveBlending,
                        depthWrite: false
                    }));

                    const bottomRing = new THREE.Points(ringGeometry, new THREE.PointsMaterial({
                        color: 0x6366f1,
                        size: 0.05,
                        transparent: true,
                        opacity: 0.85,
                        blending: THREE.AdditiveBlending,
                        depthWrite: false
                    }));

                    topRing.position.y = 0.8;
                    bottomRing.position.y = -0.8;

                    scannerGroup.add(topRing);
                    scannerGroup.add(bottomRing);
                    visualGroup.add(scannerGroup);

                    // 4. Upward Drifting Ambient Data Packets
                    const ambientGeometry = new THREE.BufferGeometry();
                    const ambientCount = 100;
                    const ambientPositions = new Float32Array(ambientCount * 3);

                    for (let i = 0; i < ambientCount; i++) {
                        const angle = Math.random() * Math.PI * 2;
                        const dist = Math.random() * 2.2;
                        ambientPositions[i * 3] = dist * Math.cos(angle);
                        ambientPositions[i * 3 + 1] = (Math.random() - 0.5) * 5;
                        ambientPositions[i * 3 + 2] = dist * Math.sin(angle);
                    }

                    ambientGeometry.setAttribute('position', new THREE.BufferAttribute(ambientPositions, 3));

                    const ambientMaterial = new THREE.PointsMaterial({
                        color: 0xffffff,
                        size: 0.035,
                        transparent: true,
                        opacity: 0.5,
                        blending: THREE.AdditiveBlending,
                        depthWrite: false
                    });

                    const ambientPoints = new THREE.Points(ambientGeometry, ambientMaterial);
                    visualGroup.add(ambientPoints);

                    // Mouse Interaction
                    let mouseX = 0;
                    let mouseY = 0;

                    window.addEventListener('mousemove', (e) => {
                        mouseX = (e.clientX / window.innerWidth) - 0.5;
                        mouseY = (e.clientY / window.innerHeight) - 0.5;
                    });

                    // Animation Loop
                    const clock = new THREE.Clock();

                    const tick = () => {
                        const elapsedTime = clock.getElapsedTime();

                        // 1. Globe Rotations
                        globeGroup.rotation.y = elapsedTime * 0.06;
                        globeGroup.rotation.x = elapsedTime * 0.03;

                        // 2. DNA Helix twists
                        dnaGroup.rotation.y = -elapsedTime * 0.15;

                        // 3. Telemetry scanning waves (up & down)
                        topRing.position.y = 0.8 + Math.sin(elapsedTime * 1.6) * 0.7;
                        topRing.rotation.y = elapsedTime * 0.4;

                        bottomRing.position.y = -0.8 - Math.sin(elapsedTime * 1.6) * 0.7;
                        bottomRing.rotation.y = -elapsedTime * 0.4;

                        // 4. Ambient data packets rising
                        const ambientPos = ambientPoints.geometry.attributes.position.array;
                        for (let i = 0; i < ambientCount; i++) {
                            ambientPos[i * 3 + 1] += 0.009; // rise speed
                            if (ambientPos[i * 3 + 1] > 2.5) {
                                ambientPos[i * 3 + 1] = -2.5; // recycle to bottom
                            }
                        }
                        ambientPoints.geometry.attributes.position.needsUpdate = true;

                        // 5. Parallax mouse drift
                        visualGroup.position.x += (mouseX * 1.2 - visualGroup.position.x) * 0.05;
                        visualGroup.position.y += (-mouseY * 1.2 - visualGroup.position.y) * 0.05;

                        // Rotate container very slightly
                        visualGroup.rotation.y = Math.sin(elapsedTime * 0.1) * 0.1;

                        renderer.render(scene, camera);
                        requestAnimationFrame(tick);
                    };
                    tick();

                    // Resize handler
                    window.addEventListener('resize', () => {
                        camera.aspect = container.clientWidth / container.clientHeight;
                        camera.updateProjectionMatrix();
                        renderer.setSize(container.clientWidth, container.clientHeight);
                        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
                    });
                }
            })();
        </script>
    @endpush
@endsection
