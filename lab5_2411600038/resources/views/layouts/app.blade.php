<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Portal')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body class="dashboard-page">
<nav class="navbar navbar-dark main-navbar">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold d-flex align-items-center gap-2">
            <span class="brand-mark"><i class="bi bi-mortarboard"></i></span>
            Student Portal
        </span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<aside class="sidebar">
    <div class="sidebar-content">
        <div class="sidebar-profile text-center">
            <div class="profile-icon"><i class="bi bi-person-fill"></i></div>
            <h6>{{ Auth::user()->name }}</h6>
            <small>Staff Account</small>
        </div>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') || request()->routeIs('products.show') ? 'active' : '' }}">
                    <i class="bi bi-boxes"></i> Courses
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('products.create') }}" class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-square"></i> Add Course
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-data"></i> Reports
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Profile
                </a>
            </li>
        </ul>
    </div>
</aside>

<main class="main-content">
    <div class="container-fluid p-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning academic-alert mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Low Stock Alert</strong>
                    <span>{{ session('warning') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
        @isset($slot)
            {{ $slot }}
        @endisset
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
