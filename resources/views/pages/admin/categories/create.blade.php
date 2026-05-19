@extends('layouts.admin')

@section('page-title', 'Tambah Kategori')

@section('content')
    <div class="form-container">
        <h2>Form Tambah Kategori</h2>
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

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Kategori</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Baju Koko">
            </div>
            
            <div style="margin-top: 30px;">
                <a href="{{ route('categories.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan Kategori</button>
            </div>
        </form>
    </div>
@endsection
