@extends('layouts.admin')

@section('title', 'Daftar Pohon')

@section('content')
<section>
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
        <div>
            <h1>Daftar Pohon</h1>
            <p class="fine" style="margin:2px 0 0;">Kelola pohon adopsi, cetak label QR, atau tambahkan pohon baru.</p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <a href="{{ route('admin.trees.create') }}" class="btn">
                + Tambah Pohon Baru
            </a>
            <button type="button" class="btn alt" id="btn-print-qr">
                🖨️ Cetak QR Terpilih
            </button>
            <form id="form-bulk-delete" action="{{ route('admin.trees.bulk_destroy') }}" method="POST" style="margin:0; display:inline;">
                @csrf
                <input type="hidden" name="ids" id="bulk-delete-ids" value="">
                <button type="submit" class="btn alt" id="btn-bulk-delete" style="color:var(--bad); border-color:var(--bad); display:inline-flex; align-items:center; gap:5px;">
                    🗑️ Delete All / Terpilih <span id="count-selected" style="display:none; background:var(--bad); color:#fff; border-radius:10px; padding:1px 6px; font-size:11px;">0</span>
                </button>
            </form>
        </div>
    </div>

    <div class="card tw" style="margin-top:8px;">
        <table>
            <thead>
                <tr>
                    <th style="width:30px;"><input type="checkbox" id="check-all" aria-label="Pilih Semua"></th>
                    <th>Kode</th>
                    <th>Spesies</th>
                    <th>Adopter</th>
                    <th>Plot Lahan</th>
                    <th>Bulan</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($trees as $tree)
                    <tr>
                        <td>
                            <input type="checkbox" class="tree-cb" value="{{ $tree->id }}" data-code="{{ $tree->tree_code }}">
                        </td>
                        <td>
                            <b>{{ $tree->tree_code }}</b><br>
                            <small class="fine" style="font-family:monospace;">#{{ $tree->token }}</small>
                        </td>
                        <td>{{ $tree->species->name }}<br><small class="fine">{{ $tree->species->display_name }}</small></td>
                        <td>{{ $tree->adopter_name }}</td>
                        <td>{{ $tree->plot ?? '-' }}</td>
                        <td>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <span>Bulan <b>{{ $tree->current_week }}</b>/{{ $tree->total_weeks }}</span>
                                <div style="display:inline-flex; gap:3px;">
                                    <form action="{{ route('admin.trees.adjust_weeks', $tree) }}" method="POST" style="margin:0; display:inline;" class="form-adjust-weeks" data-action="minus" data-code="{{ $tree->tree_code }}" data-target="{{ max(1, $tree->total_weeks - 1) }}">
                                        @csrf
                                        <input type="hidden" name="delta" value="-1">
                                        <button type="submit" class="btn alt" style="padding:2px 7px; font-size:12px; font-weight:700; line-height:1;" title="Kurangi 1 Bulan" {{ $tree->total_weeks <= max(1, $tree->current_week) ? 'disabled style=opacity:0.3;cursor:not-allowed;' : '' }}>−</button>
                                    </form>
                                    <form action="{{ route('admin.trees.adjust_weeks', $tree) }}" method="POST" style="margin:0; display:inline;" class="form-adjust-weeks" data-action="plus" data-code="{{ $tree->tree_code }}" data-target="{{ $tree->total_weeks + 1 }}">
                                        @csrf
                                        <input type="hidden" name="delta" value="1">
                                        <button type="submit" class="btn alt" style="padding:2px 7px; font-size:12px; font-weight:700; line-height:1;" title="Tambah 1 Bulan">+</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($tree->isUpdatedThisMonth())
                                <span class="tag">Updated</span>
                            @elseif ($tree->isLate())
                                <span class="tag late">Terlambat</span>
                            @else
                                <span class="tag due">Perlu Update</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; justify-content:flex-end; align-items:center;">
                                <a href="{{ route('user.certificate', $tree) }}" target="_blank" class="btn alt" style="padding:5px 9px; font-size:12.5px;" title="Lihat & Cetak Sertifikat Adopsi">
                                    📜
                                </a>
                                <a href="{{ route('admin.update', ['tree_id' => $tree->id]) }}" class="btn alt" style="padding:5px 9px; font-size:12.5px;" title="Input / Edit Perkembangan Bulanan">
                                    Input / Riwayat Bulan
                                </a>
                                <a href="{{ route('admin.trees.edit', $tree) }}" class="btn alt" style="padding:5px 9px; font-size:12.5px;" title="Edit Data Pohon">
                                    Edit
                                </a>
                                <a href="{{ route('tracker.show', ['codeAndToken' => $tree->full_code_token]) }}" target="_blank" class="btn alt" style="padding:5px 9px; font-size:12.5px;" title="Buka Halaman Adopter">
                                    Adopter ↗
                                </a>
                                <form action="{{ route('admin.trees.destroy', $tree) }}" method="POST" class="form-delete-tree" data-code="{{ $tree->tree_code }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn alt" style="padding:5px 8px; font-size:12.5px; color:var(--bad);" title="Hapus Pohon">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:24px;" class="fine">
                            Belum ada pohon yang terdaftar. Klik tombol <b>+ Tambah Pohon Baru</b> di atas untuk mendaftarkan pohon pertama.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const checkAll = document.getElementById('check-all');
    const cbs = document.querySelectorAll('.tree-cb');
    const btnPrint = document.getElementById('btn-print-qr');
    const formBulkDelete = document.getElementById('form-bulk-delete');
    const bulkDeleteIds = document.getElementById('bulk-delete-ids');
    const countSelectedBadge = document.getElementById('count-selected');

    function updateSelectionState() {
        const selected = Array.from(cbs).filter(cb => cb.checked);
        const count = selected.length;
        if (countSelectedBadge) {
            if (count > 0) {
                countSelectedBadge.textContent = count;
                countSelectedBadge.style.display = 'inline-block';
            } else {
                countSelectedBadge.style.display = 'none';
            }
        }
        if (checkAll && cbs.length > 0) {
            checkAll.checked = (count === cbs.length);
            checkAll.indeterminate = (count > 0 && count < cbs.length);
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', () => {
            cbs.forEach(cb => cb.checked = checkAll.checked);
            updateSelectionState();
        });
    }

    cbs.forEach(cb => {
        cb.addEventListener('change', updateSelectionState);
    });

    if (btnPrint) {
        btnPrint.addEventListener('click', async () => {
            const selected = Array.from(cbs).filter(cb => cb.checked).map(cb => cb.value);
            if (selected.length === 0) {
                await showAdminConfirm({
                    type: 'info',
                    icon: 'ℹ️',
                    title: 'Pilih Pohon Terlebih Dahulu',
                    message: 'Silakan beri centang pada satu atau beberapa pohon yang ingin dicetak label QR fisiknya.',
                    confirmText: 'Mengerti',
                    confirmColor: 'var(--moss)'
                });
                return;
            }

            const ok = await showAdminConfirm({
                type: 'info',
                icon: '🖨️',
                title: 'Cetak Label QR Pohon',
                message: `Anda akan membuka halaman cetak label QR untuk ${selected.length} pohon terpilih. Lanjutkan?`,
                confirmText: 'Buka Halaman Cetak',
                confirmColor: 'var(--moss)'
            });

            if (ok) {
                window.location.href = "{{ route('admin.qr_codes') }}?ids=" + selected.join(',');
            }
        });
    }

    // Modal konfirmasi untuk Delete All / Bulk Delete
    if (formBulkDelete) {
        formBulkDelete.addEventListener('submit', async (e) => {
            e.preventDefault();
            const selected = Array.from(cbs).filter(cb => cb.checked).map(cb => cb.value);
            if (selected.length === 0) {
                await showAdminConfirm({
                    type: 'warn',
                    icon: '⚠️',
                    title: 'Tidak Ada Pohon Dipilih',
                    message: 'Silakan centang pohon yang ingin dihapus (bisa gunakan centang di judul kolom untuk Pilih Semua).',
                    confirmText: 'Mengerti',
                    confirmColor: 'var(--moss)'
                });
                return;
            }

            const ok = await showAdminConfirm({
                type: 'danger',
                icon: '🗑️',
                title: 'Konfirmasi Hapus Massal',
                message: `PERINGATAN: Anda akan menghapus ${selected.length} pohon terpilih secara permanen.\n\nSeluruh riwayat pengukuran bulanan dan foto terkait pohon ini juga akan terhapus. Tindakan ini tidak dapat dibatalkan.`,
                confirmText: `Ya, Hapus ${selected.length} Pohon`,
                confirmColor: 'var(--bad)'
            });

            if (ok) {
                bulkDeleteIds.value = selected.join(',');
                formBulkDelete.submit();
            }
        });
    }

    // Modal konfirmasi untuk Hapus Satuan Pohon (Tombol ✕)
    document.querySelectorAll('.form-delete-tree').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const code = form.getAttribute('data-code');
            const ok = await showAdminConfirm({
                type: 'danger',
                icon: '🗑️',
                title: `Hapus Pohon ${code}?`,
                message: `Apakah Anda yakin ingin menghapus pohon ${code}?\n\nSemua riwayat pengukuran bulanan, foto, dan data pohon ini akan dihapus secara permanen dari sistem.`,
                confirmText: 'Ya, Hapus Pohon',
                confirmColor: 'var(--bad)'
            });
            if (ok) {
                form.submit();
            }
        });
    });

    // Modal konfirmasi untuk Tambah/Kurang Bulan Durasi Pohon (+ / -)
    document.querySelectorAll('.form-adjust-weeks').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const action = form.getAttribute('data-action');
            const code = form.getAttribute('data-code');
            const target = form.getAttribute('data-target');

            const isPlus = action === 'plus';
            const ok = await showAdminConfirm({
                type: 'warn',
                icon: isPlus ? '➕' : '➖',
                title: `${isPlus ? 'Tambah' : 'Kurangi'} Durasi Program?`,
                message: `Apakah Anda ingin ${isPlus ? 'menambah' : 'mengurangi'} durasi monitoring pohon ${code} menjadi ${target} bulan?`,
                confirmText: isPlus ? 'Ya, Tambah 1 Bulan' : 'Ya, Kurangi 1 Bulan',
                confirmColor: isPlus ? 'var(--moss)' : 'var(--warn)'
            });

            if (ok) {
                form.submit();
            }
        });
    });
</script>
@endpush
