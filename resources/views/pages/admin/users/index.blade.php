@extends('layouts.admin')

@section('page-title', 'Kelola Pengguna')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-container">
        <div class="table-header">
            <h2>Daftar Pengguna</h2>
        </div>

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('users.index') }}" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
            <div style="position: relative; flex: 1; max-width: 400px;">
                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, email, atau no. HP..." style="width: 100%; padding: 9px 12px 9px 38px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 14px; outline: none; box-sizing: border-box;" onfocus="this.style.borderColor='var(--cokelat-utama)'" onblur="this.style.borderColor='#ddd'">
            </div>
            <button type="submit" class="btn-tambah" style="padding: 9px 20px;">Cari</button>
            @if($search)
                <a href="{{ route('users.index') }}" style="padding: 9px 16px; border-radius: 8px; border: 1px solid #ddd; background: #f9f9f9; color: #666; font-size: 14px; text-decoration: none; font-family: 'Nunito', sans-serif;">✕ Reset</a>
            @endif
        </form>
        @if($search)
            <p style="font-size: 13px; color: #888; margin-bottom: 12px;">Menampilkan hasil untuk: <strong>"{{ $search }}"</strong> ({{ $users->total() }} ditemukan)</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number ?? '-' }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span style="background-color: var(--cokelat-utama); color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px;">Admin</span>
                            @else
                                <span style="background-color: #e2e8f0; color: #475569; padding: 4px 10px; border-radius: 20px; font-size: 12px;">Pelanggan</span>
                            @endif
                        </td>
                        <td>
                            @if($user->role !== 'admin')
                                <div class="btn-action-group">
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">Hapus</button>
                                    </form>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada data pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 25px;">
            {{ $users->links() }}
        </div>
    </div>
@endsection
