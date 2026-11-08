@extends('layouts.admin')
@section('title', 'Kelola Kategori')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header"><strong>Tambah Kategori</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Nama kategori" required>
                    </div>
                    <div class="mb-2">
                        <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi (opsional)"></textarea>
                    </div>
                    <button class="btn btn-primary w-100">Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header"><strong>Daftar Kategori</strong></div>
            <div class="card-body table-responsive">
                <table class="table">
                    <thead><tr><th>Nama</th><th>Slug</th><th>Produk</th><th></th></tr></thead>
                    <tbody>
                        @foreach($categories as $c)
                            <tr>
                                <td>{{ $c->name }}</td>
                                <td><code>{{ $c->slug }}</code></td>
                                <td>{{ $c->products_count }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCat{{ $c->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Modal Edit --}}
                            <div class="modal fade" id="editCat{{ $c->id }}">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('admin.categories.update', $c) }}" class="modal-content">
                                        @csrf @method('PUT')
                                        <div class="modal-header"><h5>Edit Kategori</h5>
                                            <button class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <input type="text" name="name" class="form-control mb-2" value="{{ $c->name }}" required>
                                            <textarea name="description" class="form-control" rows="2">{{ $c->description }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection