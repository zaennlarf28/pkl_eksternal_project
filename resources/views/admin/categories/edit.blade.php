@extends('layouts.backend')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">✏️ Edit Kategori</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
