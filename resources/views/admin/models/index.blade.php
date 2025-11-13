@extends('layouts.backend')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">🧩 Daftar Model Produk</h5>
            <a href="{{ route('admin.models.create') }}" class="btn btn-light btn-sm fw-bold">
                + Tambah Model
            </a>
        </div>

        <div class="card-body">
            {{-- Alert Sukses --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form Search --}}
            <form action="{{ route('admin.models.index') }}" method="GET" class="mb-3 d-flex justify-content-end">
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari model...">
                    <button class="btn btn-sm btn-outline-secondary" type="submit">
                        <i class="bx bx-search"></i>
                    </button>
                </div>
            </form>

            {{-- Tabel Data --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Gambar</th>
                            <th>Nama Model</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($models as $model)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($model->image)
                                        <img src="{{ asset('storage/' . $model->image) }}" class="rounded" style="width:60px; height:60px; object-fit:cover;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $model->name }}</td>
                                <td>{{ $model->category->name ?? '-' }}</td>
                                <td>{{ $model->description ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.models.edit', $model) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-edit-alt"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.models.destroy', $model) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin mau hapus model ini?')" class="btn btn-sm btn-outline-danger">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada model produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Optional: Pagination --}}
            @if(method_exists($models, 'links'))
                <div class="mt-3">
                    {{ $models->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
