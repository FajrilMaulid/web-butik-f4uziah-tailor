@extends('layouts.admin')

@section('page-title', 'Edit Kategori')

@section('content')
    <div class="form-container">
        <h2>Form Edit Kategori</h2>
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

        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Kategori</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="{{ route('categories.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Update Kategori</button>
            </div>
        </form>
    </div>
@endsection
