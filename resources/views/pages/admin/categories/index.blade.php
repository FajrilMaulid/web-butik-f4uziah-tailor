@extends('layouts.admin')

@section('page-title', 'Kelola Kategori')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <div class="table-header">
            <h2>Daftar Kategori</h2>
            <a href="{{ route('categories.create') }}" class="btn-tambah">+ Tambah Kategori</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th style="text-align: center;">Jumlah Baju</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td style="text-align: center;">
                            <span style="background-color: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 13px;">
                                {{ $category->products_count }} Baju
                            </span>
                        </td>
                        <td>
                            <div class="btn-action-group">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Belum ada data kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
