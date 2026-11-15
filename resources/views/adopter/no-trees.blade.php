@extends('layouts.admin')

@section('title', 'Belum Ada Pohon')

@section('content')
<div style="max-width: 600px; margin: 40px auto; text-align: center; background: var(--sf); border: 1px solid var(--line); border-radius: 20px; padding: 40px 24px;">
    <div style="font-size: 54px; margin-bottom: 16px;">🌱</div>
    <h2 style="font-family:'Bricolage Grotesque',sans-serif; color:var(--moss); font-size:24px; margin-bottom:8px;">
        Belum Ada Pohon Terdaftar
    </h2>
    <p style="color:var(--mut); font-size:15px; margin-bottom:24px; line-height:1.6;">
        Halo <b>{{ $user->name }}</b>, akun Anda saat ini belum memiliki pohon adopsi yang terhubung. Silakan hubungi admin atau admin kebun akan menghubungkan pohon Anda ke akun ini segera.
    </p>

    <div style="display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
        <a href="{{ route('landing') }}" class="btn alt">
            ← Kembali ke Beranda
        </a>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn" style="background:var(--badbg); color:var(--bad); border:1px solid var(--bad);">
                Keluar
            </button>
        </form>
    </div>
</div>
@endsection
