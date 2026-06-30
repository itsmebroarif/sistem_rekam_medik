<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- PWA Mobile Configuration -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SIMRS RME">
    <meta name="theme-color" content="#6366f1">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/619/619051.png">
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/619/619051.png">

    <title>@yield('title', 'SIMRS & RME Core')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS CDN Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Theme Inline Script to prevent flickering -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            const themeColor = savedTheme === 'dark' ? '#0f172a' : '#6366f1';
            document.querySelector('meta[name="theme-color"]').setAttribute('content', themeColor);
        })();
    </script>

    <!-- Custom Material Design Styles -->
    <style>
        :root {
            --material-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.02);
            --material-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            --material-shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.05);
            --transition-smooth: all 0.2s ease-in-out;
            
            --brand-primary: #6366f1;
            --brand-primary-light: rgba(99, 102, 241, 0.06);
            --brand-secondary: #06b6d4;
            --brand-success: #10b981;
            --brand-warning: #f59e0b;
            --brand-danger: #ef4444;
        }

        [data-bs-theme="dark"] {
            --material-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.2);
            --material-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            --material-shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.4);
            --brand-primary-light: rgba(99, 102, 241, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem;
            background-color: #f8fafc;
            color: #1e293b;
            transition: var(--transition-smooth);
        }

        [data-bs-theme="dark"] body {
            background-color: #0f172a;
            color: #f1f5f9;
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: var(--material-shadow);
            transition: var(--transition-smooth);
            background-color: #ffffff;
        }

        [data-bs-theme="dark"] .card {
            background-color: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .card:hover {
            box-shadow: var(--material-shadow-lg);
            transform: translateY(-1px);
        }

        .btn {
            border-radius: 10px;
            padding: 0.45rem 1.1rem;
            font-weight: 600;
            font-size: 0.825rem;
            transition: var(--transition-smooth);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-light {
            background-color: #f1f5f9;
            border-color: #f1f5f9;
            color: #475569;
        }

        [data-bs-theme="dark"] .btn-light {
            background-color: #1e293b;
            border-color: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
        }

        [data-bs-theme="dark"] .btn-light:hover {
            background-color: #334155;
            color: #ffffff;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.5rem 0.8rem;
            font-size: 0.825rem;
            border: 1px solid #cbd5e1;
            transition: var(--transition-smooth);
            background-color: #ffffff;
            color: #1e293b;
        }

        [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select {
            border: 1px solid #334155;
            background-color: #0f172a;
            color: #f8fafc;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            border-color: var(--brand-primary);
        }

        /* Sidebar Styles */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            transition: var(--transition-smooth);
            padding-top: 1.25rem;
        }

        [data-bs-theme="dark"] .sidebar {
            background-color: #1e293b;
            border-right: 1px solid rgba(255, 255, 255, 0.04);
        }

        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            transition: var(--transition-smooth);
            background-color: transparent;
        }

        .sidebar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: -0.5px;
            color: var(--brand-primary);
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            padding: 0.7rem 1rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 10px;
            margin: 0.25rem 1rem;
            font-weight: 600;
            font-size: 0.825rem;
            transition: var(--transition-smooth);
        }

        [data-bs-theme="dark"] .nav-link-custom {
            color: #94a3b8;
        }

        .nav-link-custom i {
            font-size: 1.15rem;
            margin-right: 0.75rem;
            color: #94a3b8;
            transition: var(--transition-smooth);
        }

        .nav-link-custom:hover {
            background-color: var(--brand-primary-light);
            color: var(--brand-primary);
        }

        .nav-link-custom:hover i {
            color: var(--brand-primary);
        }

        .nav-link-custom.active {
            background-color: var(--brand-primary);
            color: #ffffff;
        }

        .nav-link-custom.active i {
            color: #ffffff;
        }

        /* Top Navbar */
        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }

        [data-bs-theme="dark"] .top-navbar {
            background-color: #1e293b;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .badge-pill {
            border-radius: 50rem;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: -240px;
            }
            .sidebar.active {
                left: 0;
                box-shadow: var(--material-shadow-lg);
            }
            .main-content {
                margin-left: 0;
            }
            .top-navbar {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>

    @auth
    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="px-4 mb-4 d-flex justify-content-between align-items-center">
            <span class="sidebar-brand">
                <i class="bi bi-heart-pulse-fill me-2"></i>SIMRS RME
            </span>
            <button class="btn btn-sm d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>
        
        <div class="mb-4 text-center">
            <div class="mx-auto mb-2 border border-2 rounded-circle border-primary" style="width: 60px; height: 60px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: var(--bs-body-bg);">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white fw-bold fs-4" style="background: linear-gradient(135deg, #6366f1, #06b6d4);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <h6 class="mb-0 fw-bold">{{ Auth::user()->name }}</h6>
            <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                <i class="bi bi-shield-lock me-1"></i>{{ Auth::user()->role }}
            </small>
        </div>

        <nav class="nav flex-column">
            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>{{ __('messages.dashboard') }}
            </a>
            <a href="{{ route('patients.index') }}" class="nav-link-custom {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>{{ __('messages.patient_list') }}
            </a>
            @if(in_array(Auth::user()->role, ['admin', 'doctor']))
            <a href="{{ route('medical-records.create') }}" class="nav-link-custom {{ request()->routeIs('medical-records.*') ? 'active' : '' }}">
                <i class="bi bi-journal-medical"></i>{{ __('messages.add_rme') }}
            </a>
            @endif
            
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('doctors.index') }}" class="nav-link-custom {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>{{ __('messages.manage_doctors') }}
            </a>
            <a href="{{ route('users.index') }}" class="nav-link-custom {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>{{ __('messages.manage_staff') }}
            </a>
            @endif
            
            <hr class="mx-4 my-3 text-muted">

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">
                @csrf
            </form>
            <a href="#" class="nav-link-custom text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right text-danger"></i>{{ __('messages.logout') }}
            </a>
        </nav>
    </aside>
    @endauth

    <div class="main-content">
        @auth
        <!-- Top Navbar -->
        <header class="top-navbar d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-light d-lg-none me-2" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Language Switcher -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(app()->getLocale() == 'id')
                            <span class="fs-6">🇮🇩</span> <span class="d-none d-sm-inline">Indonesia</span>
                        @else
                            <span class="fs-6">🇬🇧</span> <span class="d-none d-sm-inline">English</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: 12px;">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('lang.switch', 'id') }}">
                                <span>🇮🇩</span> Bahasa Indonesia
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('lang.switch', 'en') }}">
                                <span>🇬🇧</span> English
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Theme Toggle Button -->
                <button class="btn btn-light" onclick="toggleTheme()" id="theme-toggle-btn">
                    <i class="bi bi-moon-stars-fill" id="theme-icon"></i>
                </button>
            </div>
        </header>
        @endauth

        <!-- Main Content Area -->
        <main class="container-fluid p-4">
            @yield('content')
        </main>
    </div>

    <!-- JS CDN Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global Scripts -->
    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('active');
            }
        }

        // Theme Toggle Logic
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const themeColor = newTheme === 'dark' ? '#0f172a' : '#6366f1';
            document.querySelector('meta[name="theme-color"]').setAttribute('content', themeColor);
            
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            if (icon) {
                if (theme === 'dark') {
                    icon.className = 'bi bi-sun-fill';
                } else {
                    icon.className = 'bi bi-moon-stars-fill';
                }
            }
        }

        // Initialize Theme Icon on Load
        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            updateThemeIcon(currentTheme);
        });

        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('PWA Service Worker registered:', reg.scope))
                    .catch(err => console.error('PWA Service Worker registration failed:', err));
            });
        }

        // SweetAlert Toast 
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__slideInUp animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown animate__faster'
            },
            background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' : '#ffffff',
            color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#f1f5f9' : '#1e293b',
            customClass: {
                popup: document.documentElement.getAttribute('data-bs-theme') === 'dark' 
                       ? 'shadow-lg border border-secondary border-opacity-10 rounded-3' 
                       : 'shadow-lg border border-light-subtle rounded-3'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // SweetAlert Flash Message Handler
        @if($successMessage = session()->pull('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ $successMessage }}'
            });
        @endif

        @if($errorMessage = session()->pull('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ $errorMessage }}'
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
