<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Mitra Grosir')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="admin-body">
<div class="d-flex">
    <aside class="admin-sidebar">
        <div class="text-center py-3 border-bottom border-secondary">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="45">
        </div>
        <ul class="nav flex-column p-3">
            <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Produk</a></li>
            <li><a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Kategori</a></li>
            <li><a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i> Pesanan</a></li>
                
            <!-- MENU BARU: BARANG DATANG & REKAP PENJUALAN -->
            <li><a href="{{ route('admin.goods-receipts.index') }}" class="nav-link {{ request()->routeIs('admin.goods-receipts.*') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-in-down"></i> Barang Datang</a></li>
            <li><a href="{{ route('admin.sales-report.index') }}" class="nav-link {{ request()->routeIs('admin.sales-report.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Rekap Penjualan</a></li>
            <!-- END MENU BARU -->

            <li><a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Customer</a></li>
            <li><a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Pengaturan</a></li>
        </ul>
    </aside>

    <div class="flex-grow-1">
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <span class="navbar-text fw-bold">@yield('title', 'Admin Panel')</span>
            <div class="dropdown">
                <a class="dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house"></i> Halaman Utama</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>