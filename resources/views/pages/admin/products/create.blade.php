@extends('layouts.admin')

@section('page-title', 'Tambah Produk')

@section('content')
    <div class="form-container">
        <h2>Form Tambah Produk / Layanan</h2>
        <br>
        
        @if($errors->any())
            <div style="color: red; margin-bottom: 15px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Produk / Layanan</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Jahit Gamis Syari">
            </div>

            <div class="form-group">
                <label for="category_id">Kategori</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Harga (Rp)</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" required min="0">
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" required placeholder="Jelaskan detail layanan atau produk ini...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Foto Produk (Opsional)</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
            </div>
            
            <div style="margin-top: 30px;">
                <a href="{{ route('products.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan Produk</button>
            </div>
        </form>
    </div>
@endsection
