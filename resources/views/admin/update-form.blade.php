@extends('layouts.admin')

@section('title', 'Input & Edit Perkembangan Bulanan')

@push('styles')
<style>
    .two {
        display: grid;
        gap: 0 12px;
        grid-template-columns: 1fr 1fr;
    }
    .seg {
        display: flex;
        gap: 6px;
    }
    .seg label {
        margin: 0;
        flex: 1;
    }
    .seg input {
        position: absolute;
        opacity: 0;
        width: 1px;
    }
    .seg span {
        display: block;
        text-align: center;
        padding: 9px;
        border: 1px solid var(--line);
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        background: var(--sf);
    }
    .seg input:checked + span {
        background: var(--moss);
        color: var(--on);
        border-color: var(--moss);
    }
    .form-grid {
        display: grid;
        gap: 16px;
    }
    @media(min-width: 860px) {
        .form-grid {
            grid-template-columns: 1.35fr 1fr;
            align-items: start;
        }
    }
    .prev .n {
        font-size: 32px;
        color: var(--moss);
        font-weight: 700;
        margin-top: 2px;
    }
    .flag {
        margin-top: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
    }
    .flag.danger { background: var(--badbg); color: var(--bad); }
    .flag.warning { background: var(--warnbg); color: var(--warn); }
    .flag.ok { background: var(--soft); color: var(--moss); }
    .calc {
        font-size: 13.5px;
        color: var(--mut);
        margin: 12px 0 0;
        line-height: 1.5;
        background: var(--bg);
        padding: 10px 12px;
        border-radius: 8px;
    }

    /* NAVIGASI BULAN CEPAT / TIMELINE SELECTOR */
    .timeline-month-bar {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding: 12px 4px;
        margin: 12px 0 18px;
    }
    .month-pill-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 82px;
        padding: 8px 10px;
        border-radius: 12px;
        border: 1.5px solid var(--line);
        background: var(--sf);
        text-decoration: none;
        color: var(--ink);
        transition: all .15s ease;
        position: relative;
    }
    .month-pill-btn:hover {
        border-color: var(--leaf);
        transform: translateY(-2px);
    }
    .month-pill-btn.active {
        background: var(--moss);
        color: #FFFFFF;
        border-color: var(--moss);
        box-shadow: 0 4px 12px rgba(20, 56, 35, 0.25);
    }
    .month-pill-btn.has-data {
        border-color: #A3D866;
    }
    .month-pill-btn .m-title {
        font-size: 13px;
        font-weight: 700;
    }
    .month-pill-btn .m-status {
        font-size: 10.5px;
        opacity: .85;
        margin-top: 2px;
    }
    .month-pill-btn .m-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #2E7D47;
        position: absolute;
        top: 6px;
        right: 6px;
    }
    .month-pill-btn.active .m-dot {
        background: #A3D866;
    }
</style>
@endpush

@section('content')
<section>
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <div>
            <h1>Input & Edit Perkembangan Bulanan</h1>
            <p class="fine" style="margin:2px 0 0;">
                Admin dan staf lapangan dapat menginput bulan baru maupun <b>mengedit riwayat data bulan sebelumnya</b> kapan saja.
            </p>
        </div>
        <a href="{{ route('admin.trees') }}" class="btn alt">← Kembali ke Daftar Pohon</a>
    </div>

    @if ($errors->any())
        <div class="alert" style="background:var(--badbg); color:var(--bad); border:1px solid var(--bad); margin-top:14px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($trees->isEmpty() || !$selectedTree)
        <div class="card" style="text-align:center; padding:48px 24px; max-width:600px; margin:24px auto;">
            <div style="font-size:48px; margin-bottom:12px;">🌱</div>
            <h2 style="font-family:'Bricolage Grotesque',sans-serif; color:var(--moss); font-size:22px; margin-bottom:8px;">
                Belum Ada Pohon Terdaftar
            </h2>
            <p class="fine" style="font-size:14.5px; line-height:1.6; margin-bottom:20px;">
                Anda belum mendaftarkan pohon adopsi di sistem. Silakan tambahkan pohon baru terlebih dahulu sebelum melakukan input perkembangan bulanan.
            </p>
            <div style="display:flex; justify-content:center; gap:10px;">
                <a href="{{ route('admin.trees.create') }}" class="btn">
                    + Daftarkan Pohon Baru
                </a>
                <a href="{{ route('admin.trees') }}" class="btn alt">
                    Lihat Daftar Pohon
                </a>
            </div>
        </div>
    @else
        <!-- SELECTOR POHON -->
        <div class="card" style="margin:16px 0 12px; padding:16px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <div style="flex:1; min-width:280px;">
                    <label for="tree_selector" style="margin:0 0 6px; font-weight:700; color:var(--moss);">
                        🌳 Pilih Pohon yang Ingin Diinput / Diedit:
                    </label>
                    <select id="tree_selector" onchange="onTreeSelectChange(this)" style="font-weight:600; background:var(--sf);">
                        @foreach ($trees as $t)
                            <option value="{{ $t->id }}" @selected($t->id === $selectedTree->id)>
                                {{ $t->tree_code }} • {{ $t->species->name }} — {{ $t->adopter_name }} (Progress: Bulan {{ $t->current_week }}/{{ $t->total_weeks }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; align-items:center; gap:10px;">
                    <a href="{{ route('tracker.show', ['codeAndToken' => $selectedTree->full_code_token]) }}" target="_blank" class="btn alt" style="font-size:13px; padding:8px 14px;">
                        Lihat Dashboard Adopter ↗
                    </a>
                </div>
            </div>

            <!-- TIMELINE PILIH BULAN (BULAN 1 SAMPAI TOTAL DURASI) -->
            <div style="margin-top:14px; border-top:1px dashed var(--line); padding-top:12px;">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:6px;">
                    <span style="font-size:13px; font-weight:700; color:var(--moss);">
                        📅 Pilih Bulan yang Ingin Diisi / Diedit Ulang:
                    </span>
                    <span class="fine">
                        Tanda hijau (🟢) = Data sudah pernah diisi. Klik untuk edit ulang.
                    </span>
                </div>

                <div class="timeline-month-bar">
                    @for ($m = 1; $m <= $selectedTree->total_weeks; $m++)
                        @php
                            $meas = $selectedTree->measurements->firstWhere('week_number', $m);
                            $isCurrentActive = ($m === $targetMonth);
                        @endphp
                        <a href="{{ route('admin.update', ['tree_id' => $selectedTree->id, 'month' => $m]) }}" 
                           class="month-pill-btn {{ $isCurrentActive ? 'active' : '' }} {{ $meas ? 'has-data' : '' }}"
                           title="{{ $meas ? "Bulan {$m}: Sudah ada data ({$meas->measurement_cm} cm). Klik untuk edit." : "Bulan {$m}: Belum ada data. Klik untuk input." }}">
                            @if ($meas)
                                <span class="m-dot"></span>
                            @endif
                            <span class="m-title">Bulan {{ $m }}</span>
                            <span class="m-status">
                                @if ($meas)
                                    {{ $meas->measurement_cm }} cm
                                @else
                                    Kosong
                                @endif
                            </span>
                        </a>
                    @endfor
                </div>
            </div>
        </div>

        @if ($currentMeasurement)
            <div style="background:var(--soft); border-left:4px solid var(--leaf); border-radius:12px; padding:12px 18px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div>
                    <b style="color:var(--moss);">📝 Mode Edit: Memperbarui Data Bulan ke-{{ $targetMonth }}</b>
                    <div style="font-size:13px; color:var(--mut); margin-top:2px;">
                        Data bulan ini sebelumnya telah diisi pada {{ $currentMeasurement->logged_at ? $currentMeasurement->logged_at->translatedFormat('d F Y') : '-' }}. Anda dapat mengubah tinggi, catatan, maupun mengganti fotonya.
                    </div>
                </div>
                <span class="tag" style="background:#FFFFFF; font-size:12px;">Data Tersimpan</span>
            </div>
        @else
            <div style="background:#FFFBEB; border-left:4px solid var(--warn); border-radius:12px; padding:12px 18px; margin-bottom:16px;">
                <b style="color:var(--warn);">🌱 Mode Input Baru: Bulan ke-{{ $targetMonth }}</b>
                <div style="font-size:13px; color:var(--mut); margin-top:2px;">
                    Belum ada rekaman pengukuran untuk Bulan ke-{{ $targetMonth }} pada pohon {{ $selectedTree->tree_code }}.
                </div>
            </div>
        @endif

        <div class="form-grid">
            <!-- FORM INPUT / EDIT BULANAN -->
            <form action="{{ route('admin.update.store') }}" method="POST" enctype="multipart/form-data" class="card" style="margin-top:0;" id="monthly-update-form">
                @csrf
                <input type="hidden" name="tree_id" id="form_tree_id" value="{{ $selectedTree->id }}">

                <div class="two">
                    <div>
                        <label for="week_number">Bulan Ke-</label>
                        <input id="week_number" name="week_number" type="number" min="1" max="120" 
                            value="{{ old('week_number', $targetMonth) }}" required
                            onchange="onMonthInputChange(this)">
                        <div class="hint">Ubah angka di atas jika ingin berpindah ke bulan lain.</div>
                    </div>
                    <div>
                        <label for="measurement_cm" id="measurement_label">
                            {{ $selectedTree->species->label }} (cm)
                        </label>
                        <input id="measurement_cm" name="measurement_cm" type="number" step="0.1" min="0" 
                            value="{{ old('measurement_cm', $currentMeasurement ? $currentMeasurement->measurement_cm : '') }}" 
                            placeholder="Contoh: 155" required>
                    </div>
                </div>

                <div class="two">
                    <div>
                        <label for="leaf_count">Jumlah Daun</label>
                        <input id="leaf_count" name="leaf_count" type="number" min="0" 
                            value="{{ old('leaf_count', $currentMeasurement ? $currentMeasurement->leaf_count : '') }}" 
                            placeholder="Contoh: 68">
                    </div>
                    <div id="pruning_wrapper" style="{{ $selectedTree->species->has_pruning ? '' : 'display:none;' }}">
                        <label for="pruned_weight_g">Berat Pangkasan (gram)</label>
                        <input id="pruned_weight_g" name="pruned_weight_g" type="number" step="0.1" min="0" 
                            value="{{ old('pruned_weight_g', $currentMeasurement ? $currentMeasurement->pruned_weight_g : 0) }}">
                    </div>
                </div>

                <label>Kondisi Kesehatan Tanaman</label>
                @php
                    $healthVal = old('health', $currentMeasurement ? $currentMeasurement->health : 'good');
                @endphp
                <div class="seg">
                    <label>
                        <input type="radio" name="health" value="good" @checked($healthVal === 'good')>
                        <span>Baik (Good)</span>
                    </label>
                    <label>
                        <input type="radio" name="health" value="watch" @checked($healthVal === 'watch')>
                        <span>Pantau (Watch)</span>
                    </label>
                    <label>
                        <input type="radio" name="health" value="poor" @checked($healthVal === 'poor')>
                        <span>Kurang (Poor)</span>
                    </label>
                </div>

                <label for="note">Catatan Perkembangan Bulan ke-{{ $targetMonth }}</label>
                <textarea id="note" name="note" rows="3" 
                    placeholder="Ceritakan kondisi pertumbuhan tanaman bulan ini...">{{ old('note', $currentMeasurement ? $currentMeasurement->note : $defaultNote) }}</textarea>
                <div class="hint">Catatan ini akan langsung ditampilkan ke adopter di linimasa bulanan dashboard mereka.</div>

                <div style="margin-top:14px; background:var(--soft); padding:16px; border-radius:14px; border:1px dashed var(--leaf);">
                    <label for="photo" style="margin-top:0; color:var(--moss); font-weight:700;">
                        📷 {{ $currentMeasurement && $currentMeasurement->photo_path ? 'Ganti / Perbarui Foto Bulan ke-' . $targetMonth : 'Unggah Foto Tanaman Bulan ke-' . $targetMonth }}
                    </label>

                    @if ($currentMeasurement && $currentMeasurement->photo_path)
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px; background:var(--sf); padding:10px 14px; border-radius:10px; border:1px solid var(--line);">
                            <img src="{{ asset('storage/' . $currentMeasurement->photo_path) }}" alt="Foto Tersimpan Bulan {{ $targetMonth }}" style="width:68px; height:68px; object-fit:cover; border-radius:8px; border:1px solid var(--line);">
                            <div>
                                <b style="font-size:13px; color:var(--moss);">Foto Saat Ini Sudah Tersimpan</b>
                                <div class="fine">Jika Anda tidak memilih file baru di bawah, foto saat ini akan tetap dipertahankan.</div>
                            </div>
                        </div>
                    @endif

                    <input id="photo" name="photo" type="file" accept="image/*" capture="environment" onchange="previewUploadPhoto(this)" style="background:var(--sf);">
                    
                    <div id="new-photo-preview-box" style="display:none; align-items:center; gap:12px; margin-top:10px; background:var(--sf); padding:10px 14px; border-radius:10px; border:1px solid var(--leaf);">
                        <img id="new-photo-preview" src="" alt="Pratinjau Foto Baru" style="width:68px; height:68px; object-fit:cover; border-radius:8px;">
                        <div>
                            <b id="new-photo-name" style="font-size:13px; color:var(--moss);"></b>
                            <div class="fine">Foto baru siap diunggah dan menggantikan foto sebelumnya.</div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; margin-top:24px; padding-top:16px; border-top:1px solid var(--line); flex-wrap:wrap;">
                    <div class="fine">
                        Status saat ini: <b>{{ $currentMeasurement ? 'Tersimpan (Bisa Diupdate)' : 'Baru' }}</b>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <button type="submit" name="action" value="draft" class="btn alt">
                            Simpan Draft
                        </button>
                        <button type="submit" name="action" value="publish" class="btn">
                            {{ $currentMeasurement ? '✓ Perbarui & Publikasikan' : '✓ Publikasikan Data Bulan ' . $targetMonth }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- REAL-TIME PREVIEW CARD -->
            <div class="card prev" style="margin-top:0;">
                <h2 style="font-size:18px;">Preview Estimasi Karbon</h2>
                
                <p class="fine" style="margin:12px 0 0;">CO₂ Diserap Bulan Ini</p>
                <div class="n" id="preview_week_co2">—</div>

                <p class="fine" style="margin:14px 0 0;">Total Akumulasi CO₂ Sampai Bulan Ini</p>
                <div class="n" id="preview_total_co2">—</div>

                <div id="preview_anomaly"></div>
                
                <p class="calc" id="preview_calc">
                    Masukkan angka pengukuran ukuran pohon untuk melihat kalkulasi biomassa & serapan CO₂.
                </p>

                <!-- TABEL RINGKASAN DATA SELURUH BULAN POHON INI -->
                <div style="margin-top:24px; border-top:1px solid var(--line); padding-top:16px;">
                    <h3 style="font-size:15px; margin:0 0 10px; color:var(--moss);">📋 Riwayat Seluruh Bulan Pohon Ini</h3>
                    <div style="max-height:260px; overflow-y:auto; border:1px solid var(--line); border-radius:10px;">
                        <table style="font-size:13px; width:100%; border-collapse:collapse;">
                            <thead style="background:var(--bg); position:sticky; top:0;">
                                <tr>
                                    <th style="padding:8px 10px;">Bulan</th>
                                    <th style="padding:8px 10px;">Ukuran</th>
                                    <th style="padding:8px 10px;">Foto</th>
                                    <th style="padding:8px 10px; text-align:right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($selectedTree->measurements as $mItem)
                                    <tr style="{{ $mItem->week_number === $targetMonth ? 'background:var(--soft); font-weight:700;' : '' }}">
                                        <td style="padding:8px 10px;">Bulan {{ $mItem->week_number }}</td>
                                        <td style="padding:8px 10px;">{{ $mItem->measurement_cm }} cm</td>
                                        <td style="padding:8px 10px;">
                                            @if ($mItem->photo_path)
                                                <a href="{{ asset('storage/' . $mItem->photo_path) }}" target="_blank" style="text-decoration:none;" title="Lihat Foto">📷 Ada</a>
                                            @else
                                                <span class="fine">-</span>
                                            @endif
                                        </td>
                                        <td style="padding:8px 10px; text-align:right;">
                                            <a href="{{ route('admin.update', ['tree_id' => $selectedTree->id, 'month' => $mItem->week_number]) }}" class="btn alt" style="padding:3px 8px; font-size:11.5px;">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align:center; padding:16px;" class="fine">
                                            Belum ada data bulanan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection

@push('scripts')
@if (!$trees->isEmpty() && $selectedTree)
<script>
    const formTreeId = document.getElementById('form_tree_id');
    const weekInput = document.getElementById('week_number');
    const measurementInput = document.getElementById('measurement_cm');
    const prunedInput = document.getElementById('pruned_weight_g');

    const previewWeekCO2 = document.getElementById('preview_week_co2');
    const previewTotalCO2 = document.getElementById('preview_total_co2');
    const previewAnomaly = document.getElementById('preview_anomaly');
    const previewCalc = document.getElementById('preview_calc');

    function onTreeSelectChange(sel) {
        window.location.href = "{{ route('admin.update') }}?tree_id=" + sel.value;
    }

    function onMonthInputChange(input) {
        const val = parseInt(input.value);
        if (val && val >= 1) {
            window.location.href = "{{ route('admin.update') }}?tree_id={{ $selectedTree->id }}&month=" + val;
        }
    }

    function previewUploadPhoto(input) {
        const file = input.files && input.files[0];
        const box = document.getElementById('new-photo-preview-box');
        const img = document.getElementById('new-photo-preview');
        const nameEl = document.getElementById('new-photo-name');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                nameEl.textContent = file.name;
                box.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        } else {
            box.style.display = 'none';
        }
    }

    let debounceTimer = null;
    function fetchCarbonPreview() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            if (!measurementInput) return;
            const m = parseFloat(measurementInput.value);
            if (isNaN(m) || m <= 0) {
                if (previewWeekCO2) previewWeekCO2.textContent = '—';
                if (previewTotalCO2) previewTotalCO2.textContent = '—';
                if (previewAnomaly) previewAnomaly.innerHTML = '';
                if (previewCalc) previewCalc.textContent = 'Masukkan angka pengukuran ukuran pohon untuk melihat kalkulasi biomassa & serapan CO₂.';
                return;
            }

            fetch("{{ route('admin.preview_carbon') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    tree_id: formTreeId ? formTreeId.value : '{{ $selectedTree->id }}',
                    week: weekInput ? weekInput.value : 1,
                    measurement_cm: m,
                    pruned_g: prunedInput ? (parseFloat(prunedInput.value) || 0) : 0
                })
            })
            .then(res => res.json())
            .then(data => {
                if (previewWeekCO2) previewWeekCO2.textContent = data.week_co2;
                if (previewTotalCO2) previewTotalCO2.textContent = data.total_co2;
                if (previewCalc) previewCalc.textContent = data.explanation;

                let flagClass = 'ok';
                if (data.anomaly && data.anomaly.status === 'danger') flagClass = 'danger';
                if (data.anomaly && data.anomaly.status === 'warning') flagClass = 'warning';

                if (previewAnomaly) {
                    previewAnomaly.innerHTML = data.anomaly ? `<div class="flag ${flagClass}">${data.anomaly.message}</div>` : '';
                }
            })
            .catch(err => console.error(err));
        }, 200);
    }

    if (measurementInput) measurementInput.addEventListener('input', fetchCarbonPreview);
    if (prunedInput) prunedInput.addEventListener('input', fetchCarbonPreview);
    if (weekInput) weekInput.addEventListener('input', fetchCarbonPreview);

    // Initial check on load
    if (measurementInput && measurementInput.value) {
        fetchCarbonPreview();
    }
</script>
@endif
@endpush
