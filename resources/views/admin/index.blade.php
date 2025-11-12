@extends('layouts.backend')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Dashboard /</span> Admin
    </h4>

    <!-- Statistik -->
    <div class="row">
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Kategori</h6>
                    <h3 class="card-title text-primary mb-0">{{ \App\Models\Category::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Model Produk</h6>
                    <h3 class="card-title text-success mb-0">{{ \App\Models\ModelProduct::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total User</h6>
                    <h3 class="card-title text-info mb-0">{{ \App\Models\User::count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigasi -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">⚙️ Kelola Data</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-primary me-2">
                📦 Kelola Kategori
            </a>
            <a href="{{ route('admin.models.index') }}" class="btn btn-success">
                🧩 Kelola Model Produk
            </a>
        </div>
    </div>

    <!-- Info -->
    <div class="text-center mt-5 text-muted">
        <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong>! Kamu login sebagai <b>Admin</b>.</p>
    </div>

</div>
@endsection
