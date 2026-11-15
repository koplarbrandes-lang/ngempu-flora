@extends('layouts.admin')

@section('title', $isEdit ? 'Edit Adopter: ' . $adopter->name : 'Tambah Adopter Baru')

@push('styles')
<style>
    .two {
        display: grid;
        gap: 0 14px;
        grid-template-columns: 1fr 1fr;
    }
    @media(max-width: 600px) {
        .two { grid-template-columns: 1fr; }
    }
    .hint {
        font-size: 12.5px;
        color: var(--mut);
        margin-top: 3px;
    }
</style>
@endpush

@section('content')
<section>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
        <div>
            <h1>{{ $isEdit ? 'Edit Data Adopter: ' . $adopter->name : 'Tambah Adopter Baru' }}</h1>
            <p class="fine" style="margin:2px 0 0;">
                {{ $isEdit ? 'Perbarui informasi kontak dan akun adopter.' : 'Daftarkan akun adopter baru agar bisa langsung dipilih saat registrasi pohon.' }}
            </p>
        </div>
        <a href="{{ $redirectTo === 'trees_create' ? route('admin.trees.create') : route('admin.adopters.index') }}" class="btn alt">
            ← Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert" style="background:var(--badbg); color:var(--bad); border:1px solid var(--bad);">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="max-width:650px; margin-top:8px;">
        <form action="{{ $isEdit ? route('admin.adopters.update', $adopter) : route('admin.adopters.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            @if ($redirectTo)
                <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
            @endif

            <label for="name">Nama Lengkap Adopter</label>
            <input id="name" name="name" type="text" value="{{ old('name', $adopter->name) }}" placeholder="Contoh: BUDI SANTOSO" required autofocus>
            <div class="hint">Nama yang akan tampil pada sertifikat dan halaman pemantauan pohon.</div>

            <div class="two" style="margin-top:6px;">
                <div>
                    <label for="email">Alamat Email (Akun Login)</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $adopter->email) }}" placeholder="adopter@email.com" required>
                    <div class="hint">Digunakan adopter untuk login ke dashboard pribadi.</div>
                </div>

                <div>
                    <label for="phone">Nomor Telepon / WhatsApp</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $adopter->phone) }}" placeholder="Contoh: 081234567890">
                </div>
            </div>

            <div class="two" style="margin-top:6px;">
                <div>
                    <label for="city">Kota / Asal Domisili</label>
                    <input id="city" name="city" type="text" value="{{ old('city', $adopter->city) }}" placeholder="Contoh: Surabaya atau Denpasar">
                </div>

                <div>
                    <label for="password">Password Akun {{ $isEdit ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                    <input id="password" name="password" type="text" value="{{ old('password', $isEdit ? '' : 'user123') }}" placeholder="{{ $isEdit ? '••••••••' : 'Minimal 6 karakter' }}" {{ $isEdit ? '' : 'required' }}>
                    <div class="hint">{{ $isEdit ? 'Isi hanya jika ingin mengganti password adopter.' : 'Default disarankan: user123' }}</div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:24px; padding-top:16px; border-top:1px solid var(--line);">
                <a href="{{ $redirectTo === 'trees_create' ? route('admin.trees.create') : route('admin.adopters.index') }}" class="btn alt">Batal</a>
                <button type="submit" class="btn">
                    {{ $isEdit ? '✓ Simpan Perubahan' : ($redirectTo === 'trees_create' ? '✓ Daftarkan & Pilih untuk Pohon' : '✓ Simpan Adopter Baru') }}
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
