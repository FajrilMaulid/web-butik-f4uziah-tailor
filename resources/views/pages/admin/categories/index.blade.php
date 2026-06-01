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

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('categories.index') }}" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
            <div style="position: relative; flex: 1; max-width: 400px;">
                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama kategori..." style="width: 100%; padding: 9px 12px 9px 38px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 14px; outline: none; box-sizing: border-box; transition: border 0.2s;" onfocus="this.style.borderColor='var(--cokelat-utama)'" onblur="this.style.borderColor='#ddd'">
            </div>
            <button type="submit" class="btn-tambah" style="padding: 9px 20px;">Cari</button>
            @if($search)
                <a href="{{ route('categories.index') }}" style="padding: 9px 16px; border-radius: 8px; border: 1px solid #ddd; background: #f9f9f9; color: #666; font-size: 14px; text-decoration: none; font-family: 'Nunito', sans-serif;">✕ Reset</a>
            @endif
        </form>
        @if($search)
            <p style="font-size: 13px; color: #888; margin-bottom: 12px;">Menampilkan hasil untuk: <strong>"{{ $search }}"</strong> ({{ $categories->total() }} ditemukan)</p>
        @endif

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
        <div style="margin-top: 25px;">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
