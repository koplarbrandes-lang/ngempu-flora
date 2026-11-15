@extends('layouts.admin')

@section('title', $isEdit ? 'Edit Data Pohon ' . $tree->tree_code : 'Pendaftaran Pohon Baru')

@push('styles')
<style>
    .two {
        display: grid;
        gap: 0 14px;
        grid-template-columns: 1fr 1fr;
    }
    @media(max-width: 600px) {
        .two {
            grid-template-columns: 1fr;
        }
    }
    .hint {
        font-size: 12.5px;
        color: var(--mut);
        margin-top: 3px;
    }
    .token-row {
        display: flex;
        gap: 8px;
    }
    .token-row input {
        flex: 1;
    }
    .section-divider {
        margin: 20px 0 14px;
        padding-top: 16px;
        border-top: 1px dashed var(--line);
    }
</style>
@endpush

@section('content')
<section>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
        <div>
            <h1>{{ $isEdit ? 'Edit Data Pohon: ' . $tree->tree_code : 'Pendaftaran Pohon Baru' }}</h1>
            <p class="fine" style="margin:2px 0 0;">
                {{ $isEdit ? 'Perbarui informasi adopsi dan spesifikasi pohon.' : 'Isi formulir untuk menambahkan pohon baru ke sistem adopsi.' }}
            </p>
        </div>
        <a href="{{ route('admin.trees') }}" class="btn alt">← Kembali ke Daftar</a>
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

    <div class="card" style="max-width:760px; margin-top:8px;">
        <form action="{{ $isEdit ? route('admin.trees.update', $tree) : route('admin.trees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <h2 style="font-size:17px; margin-bottom:8px; color:var(--moss);">1. Identitas Pohon & QR Code</h2>
            
            <div class="two">
                <div>
                    <label for="tree_code">Kode Pohon (Tree Code)</label>
                    <input id="tree_code" name="tree_code" type="text" 
                        value="{{ old('tree_code', $isEdit ? $tree->tree_code : $suggestedCode) }}" 
                        placeholder="Contoh: TR-0156" required>
                    <div class="hint">Kode unik pengenal fisik pohon di lapangan.</div>
                </div>

                <div>
                    <label for="token">Token Keamanan QR (6 Karakter)</label>
                    <div class="token-row">
                        <input id="token" name="token" type="text" 
                            value="{{ old('token', $isEdit ? $tree->token : $suggestedToken) }}" 
                            placeholder="Contoh: k7f2q9" required>
                        <button type="button" class="btn alt" style="padding:0 12px;" onclick="generateToken()" title="Acak Token">
                            🎲
                        </button>
                    </div>
                    <div class="hint">Token acak mencegah URL adopsi ditebak orang lain.</div>
                </div>
            </div>

            <div class="two" style="margin-top:6px;">
                <div>
                    <label for="species_id">Spesies / Jenis Pohon</label>
                    <select id="species_id" name="species_id" required onchange="updateSpeciesInfo()">
                        @foreach ($species as $sp)
                            <option value="{{ $sp->id }}" 
                                data-label="{{ $sp->label }}" 
                                data-start="{{ $sp->start }}"
                                @selected(old('species_id', $tree->species_id) == $sp->id)>
                                {{ $sp->name }} ({{ $sp->display_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="plot">Plot Lahan / Lokasi Tanam</label>
                    <input id="plot" name="plot" type="text" 
                        value="{{ old('plot', $tree->plot) }}" 
                        placeholder="Contoh: Semaya, Lajur 4 - 5 Pohon">
                </div>
            </div>

            <div class="section-divider"></div>
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:8px;">
                <h2 style="font-size:17px; margin:0; color:var(--moss);">2. Informasi Adopter & Penanaman</h2>
                <a href="{{ route('admin.adopters.create', ['redirect_to' => 'trees_create']) }}" class="btn alt" style="font-size:12px; padding:4px 10px;">
                    + Daftarkan Adopter Baru
                </a>
            </div>

            <!-- PILIH ADOPTER DARI USER TERSEDIA -->
            <div style="margin-bottom:14px; background:var(--soft); padding:14px; border-radius:14px; border:1px solid var(--line);">
                <label for="user_id" style="margin-top:0; color:var(--moss);">
                    Pilih Adopter dari Pengguna yang Tersedia
                </label>
                <select id="user_id" name="user_id" onchange="onAdopterSelect(this)" style="background:var(--sf); font-weight:600;">
                    <option value="" data-name="" data-city="" data-formatted="">-- Pilih Adopter atau Isi Manual di Bawah --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" 
                            data-name="{{ $user->name }}" 
                            data-city="{{ $user->city }}" 
                            data-formatted="{{ $user->formatted_adopter_name }}"
                            @selected(old('user_id', $tree->user_id) == $user->id)>
                            👤 {{ $user->name }} {{ $user->city ? '• ' . $user->city : '' }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                <div class="hint" style="color:var(--moss);">
                    💡 Memilih pengguna di atas akan otomatis mengisi nama & domisili adopter serta langsung menghubungkan pohon ke dashboard pengguna tersebut.
                </div>
            </div>

            <div class="two">
                <div>
                    <label for="adopter_name">Nama Adopter & Lokasi</label>
                    <input id="adopter_name" name="adopter_name" type="text" 
                        value="{{ old('adopter_name', $tree->adopter_name) }}" 
                        placeholder="Contoh: BUDI SANTOSO, Surabaya" required>
                    <div class="hint">Nama yang tampil di halaman monitoring dan sertifikat pohon.</div>
                </div>

                <div>
                    <label for="planted_date">Tanggal Penanaman</label>
                    <input id="planted_date" name="planted_date" type="date" 
                        value="{{ old('planted_date', $isEdit ? $tree->planted_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="two" style="margin-top:6px;">
                <div>
                    <label for="total_weeks">Target Durasi Program (Total Bulan)</label>
                    <div style="display:flex; gap:6px; align-items:center;">
                        <button type="button" class="btn alt" style="padding:9px 14px; font-size:16px; font-weight:700;" onclick="changeWeeks(-1)">−</button>
                        <input id="total_weeks" name="total_weeks" type="number" min="1" max="120" 
                            value="{{ old('total_weeks', $isEdit ? $tree->total_weeks : 12) }}" 
                            style="text-align:center; font-weight:700; width:100%;" required>
                        <button type="button" class="btn alt" style="padding:9px 14px; font-size:16px; font-weight:700;" onclick="changeWeeks(1)">+</button>
                    </div>
                    <div class="hint">Durasi standar adalah 12 bulan. Admin dapat menambah atau mengurangi durasi kapan saja.</div>
                </div>
                <div></div>
            </div>

            @if (!$isEdit)
                <div class="section-divider"></div>
                <h2 style="font-size:17px; margin-bottom:4px; color:var(--moss);">3. Pengukuran Awal (Bulan ke-1) — Opsional</h2>
                <p class="fine" style="margin:0 0 10px;">Jika pohon langsung diukur saat ditanam, data ini akan otomatis disimpan sebagai Bulan 1.</p>

                <div class="two">
                    <div>
                        <label for="initial_measurement_cm" id="initial_label">Tinggi Awal (cm)</label>
                        <input id="initial_measurement_cm" name="initial_measurement_cm" type="number" step="0.1" min="0" 
                            value="{{ old('initial_measurement_cm') }}" 
                            placeholder="Contoh: 20">
                    </div>

                    <div>
                        <label for="initial_leaf_count">Jumlah Daun Awal</label>
                        <input id="initial_leaf_count" name="initial_leaf_count" type="number" min="0" 
                            value="{{ old('initial_leaf_count') }}" 
                            placeholder="Contoh: 8">
                    </div>
                </div>

                <div style="margin-top:8px;">
                    <label for="initial_note">Catatan Penanaman Awal</label>
                    <textarea id="initial_note" name="initial_note" rows="2" 
                        placeholder="Contoh: Pohon baru ditanam dengan pupuk kompos organik. Kondisi daun segar.">{{ old('initial_note') }}</textarea>
                </div>

                <div style="margin-top:14px; background:var(--soft); padding:16px; border-radius:14px; border:1px dashed var(--leaf);">
                    <label for="initial_photo" style="margin-top:0; color:var(--moss); font-weight:700;">
                        📷 Foto Kondisi Pohon Saat Pertama Didaftarkan (Bulan ke-1)
                    </label>
                    <input id="initial_photo" name="initial_photo" type="file" accept="image/jpeg,image/png,image/jpg,image/webp" 
                        onchange="previewInitialPhoto(this)" style="background:var(--sf); padding:8px;">
                    <div class="hint" style="margin-top:6px;">
                        Unggah foto dokumentasi bibit/pohon yang baru ditanam. Format: JPG, PNG, WEBP (Maksimal 5MB).
                    </div>
                    
                    <div id="photo-preview-wrap" style="display:none; margin-top:12px; align-items:center; gap:12px;">
                        <img id="photo-preview" src="" alt="Pratinjau Foto Awal" 
                            style="width:90px; height:90px; object-fit:cover; border-radius:10px; border:2px solid var(--leaf); box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                        <div>
                            <b id="photo-filename" style="font-size:13px; color:var(--moss); display:block;"></b>
                            <small class="fine">Foto siap diunggah sebagai foto resmi Bulan ke-1.</small>
                        </div>
                    </div>
                </div>
            @endif

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:24px; padding-top:16px; border-top:1px solid var(--line);">
                <a href="{{ route('admin.trees') }}" class="btn alt">Batal</a>
                <button type="submit" class="btn">
                    {{ $isEdit ? '✓ Simpan Perubahan' : '🌱 Daftarkan Pohon Baru' }}
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function generateToken() {
        const chars = 'abcdefghjkmnpqrstuvwxyz23456789';
        let token = '';
        for (let i = 0; i < 6; i++) {
            token += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('token').value = token;
    }

    function changeWeeks(delta) {
        const input = document.getElementById('total_weeks');
        if (!input) return;
        let val = parseInt(input.value) || 12;
        val = Math.max(1, Math.min(120, val + delta));
        input.value = val;
    }

    function onAdopterSelect(sel) {
        const opt = sel.selectedOptions[0];
        if (opt && opt.value) {
            const formatted = opt.getAttribute('data-formatted') || opt.getAttribute('data-name');
            const nameInput = document.getElementById('adopter_name');
            if (nameInput) {
                nameInput.value = formatted;
                nameInput.style.borderColor = 'var(--leaf)';
                setTimeout(() => nameInput.style.borderColor = '', 1000);
            }
        }
    }

    function updateSpeciesInfo() {
        const sel = document.getElementById('species_id');
        const opt = sel.selectedOptions[0];
        const label = opt.getAttribute('data-label') || 'Ukuran';
        const start = opt.getAttribute('data-start') || '20';

        const initLabel = document.getElementById('initial_label');
        const initInput = document.getElementById('initial_measurement_cm');
        if (initLabel && initInput) {
            initLabel.textContent = label + ' Awal (cm)';
            if (!initInput.value) {
                initInput.placeholder = 'Estimasi awal: ' + start + ' cm';
            }
        }
    }

    function previewInitialPhoto(input) {
        const file = input.files && input.files[0];
        const wrap = document.getElementById('photo-preview-wrap');
        const img = document.getElementById('photo-preview');
        const nameEl = document.getElementById('photo-filename');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                nameEl.textContent = file.name;
                wrap.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        } else {
            wrap.style.display = 'none';
        }
    }

    // Run on load
    updateSpeciesInfo();
</script>
@endpush
