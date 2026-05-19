@extends('layouts.admin')

@section('page-title', 'Edit Produk')

@section('content')
    <div class="form-container">
        <h2>Form Edit Produk / Layanan</h2>
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

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Nama Produk / Layanan</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="form-group">
                <label for="category_id">Kategori</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Harga (Rp)</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price', (int)$product->price) }}" required min="0">
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" required>{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Foto Produk (Opsional)</label>
                @if($product->image)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100px; border-radius: 8px;">
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <small style="color: #666;">Biarkan kosong jika tidak ingin mengubah gambar.</small>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="{{ route('products.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Update Produk</button>
            </div>
        </form>
    </div>
@endsection
