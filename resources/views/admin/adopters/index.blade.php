@extends('layouts.admin')

@section('title', 'Daftar Adopter')

@section('content')
<section>
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
        <div>
            <h1>Daftar Adopter (Penanam)</h1>
            <p class="fine" style="margin:2px 0 0;">Kelola akun adopter yang terdaftar di program adopsi pohon.</p>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <a href="{{ route('admin.adopters.create') }}" class="btn">
                + Tambah Adopter Baru
            </a>
            <form id="form-bulk-delete-adopters" action="{{ route('admin.adopters.bulk_destroy') }}" method="POST" style="margin:0; display:inline;">
                @csrf
                <input type="hidden" name="ids" id="bulk-delete-adopter-ids" value="">
                <button type="submit" class="btn alt" id="btn-bulk-delete-adopters" style="color:var(--bad); border-color:var(--bad); display:inline-flex; align-items:center; gap:5px;">
                    🗑️ Delete All / Terpilih <span id="count-selected-adopters" style="display:none; background:var(--bad); color:#fff; border-radius:10px; padding:1px 6px; font-size:11px;">0</span>
                </button>
            </form>
        </div>
    </div>

    <div class="card tw" style="margin-top:8px;">
        <table>
            <thead>
                <tr>
                    <th style="width:30px;"><input type="checkbox" id="check-all-adopters" aria-label="Pilih Semua Adopter"></th>
                    <th>Nama Adopter</th>
                    <th>Kontak (Email / Telepon)</th>
                    <th>Kota / Asal</th>
                    <th>Pohon Diadopsi</th>
                    <th>Total CO₂ Terserap</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($adopters as $adopter)
                    <tr>
                        <td>
                            <input type="checkbox" class="adopter-cb" value="{{ $adopter->id }}" data-name="{{ $adopter->name }}">
                        </td>
                        <td>
                            <b>{{ $adopter->name }}</b>
                        </td>
                        <td>
                            <span>{{ $adopter->email }}</span><br>
                            <small class="fine">{{ $adopter->phone ?? '-' }}</small>
                        </td>
                        <td>
                            <span>{{ $adopter->city ?? '-' }}</span>
                        </td>
                        <td>
                            @if ($adopter->trees->isNotEmpty())
                                <div style="display:flex; gap:4px; flex-wrap:wrap;">
                                    @foreach ($adopter->trees as $t)
                                        <a href="{{ route('tracker.show', ['codeAndToken' => $t->full_code_token]) }}" target="_blank" class="tag" style="text-decoration:none;" title="Lihat {{ $t->species->name }}">
                                            🌱 {{ $t->tree_code }} ({{ $t->species->name }})
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span class="fine">Belum ada pohon</span>
                            @endif
                        </td>
                        <td>
                            <b style="color:var(--moss);">{{ $adopter->total_co2_formatted }}</b>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; justify-content:flex-end; align-items:center;">
                                <a href="{{ route('admin.trees.create', ['user_id' => $adopter->id]) }}" class="btn alt" style="padding:5px 9px; font-size:12px;" title="Tambah Pohon untuk Adopter Ini">
                                    + Pohon
                                </a>
                                <a href="{{ route('admin.adopters.edit', $adopter) }}" class="btn alt" style="padding:5px 9px; font-size:12px;" title="Edit Data Adopter">
                                    Edit
                                </a>
                                <form action="{{ route('admin.adopters.destroy', $adopter) }}" method="POST" class="form-delete-adopter" data-name="{{ $adopter->name }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn alt" style="padding:5px 8px; font-size:12px; color:var(--bad);" title="Hapus Adopter">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:28px;" class="fine">
                            Belum ada adopter terdaftar. Klik <b>+ Tambah Adopter Baru</b> di atas untuk mendaftarkan adopter pertama.
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
    const checkAllAdopters = document.getElementById('check-all-adopters');
    const adopterCbs = document.querySelectorAll('.adopter-cb');
    const formBulkDeleteAdopters = document.getElementById('form-bulk-delete-adopters');
    const bulkDeleteAdopterIds = document.getElementById('bulk-delete-adopter-ids');
    const countSelectedAdoptersBadge = document.getElementById('count-selected-adopters');

    function updateAdopterSelectionState() {
        const selected = Array.from(adopterCbs).filter(cb => cb.checked);
        const count = selected.length;
        if (countSelectedAdoptersBadge) {
            if (count > 0) {
                countSelectedAdoptersBadge.textContent = count;
                countSelectedAdoptersBadge.style.display = 'inline-block';
            } else {
                countSelectedAdoptersBadge.style.display = 'none';
            }
        }
        if (checkAllAdopters && adopterCbs.length > 0) {
            checkAllAdopters.checked = (count === adopterCbs.length);
            checkAllAdopters.indeterminate = (count > 0 && count < adopterCbs.length);
        }
    }

    if (checkAllAdopters) {
        checkAllAdopters.addEventListener('change', () => {
            adopterCbs.forEach(cb => cb.checked = checkAllAdopters.checked);
            updateAdopterSelectionState();
        });
    }

    adopterCbs.forEach(cb => {
        cb.addEventListener('change', updateAdopterSelectionState);
    });

    if (formBulkDeleteAdopters) {
        formBulkDeleteAdopters.addEventListener('submit', async (e) => {
            e.preventDefault();
            const selected = Array.from(adopterCbs).filter(cb => cb.checked).map(cb => cb.value);
            if (selected.length === 0) {
                await showAdminConfirm({
                    type: 'warn',
                    icon: '⚠️',
                    title: 'Tidak Ada Adopter Dipilih',
                    message: 'Silakan centang akun adopter yang ingin dihapus (bisa gunakan centang di judul kolom untuk Pilih Semua).',
                    confirmText: 'Mengerti',
                    confirmColor: 'var(--moss)'
                });
                return;
            }

            const ok = await showAdminConfirm({
                type: 'danger',
                icon: '🗑️',
                title: 'Konfirmasi Hapus Massal Adopter',
                message: `PERINGATAN: Apakah Anda yakin ingin menghapus ${selected.length} akun adopter terpilih?\n\nPohon-pohon yang sebelumnya diadopsi tetap aman tersimpan di sistem, namun akun adopter ini tidak akan bisa login lagi.`,
                confirmText: `Ya, Hapus ${selected.length} Akun`,
                confirmColor: 'var(--bad)'
            });

            if (ok) {
                bulkDeleteAdopterIds.value = selected.join(',');
                formBulkDeleteAdopters.submit();
            }
        });
    }

    // Modal konfirmasi untuk Hapus Satuan Adopter (Tombol ✕)
    document.querySelectorAll('.form-delete-adopter').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = form.getAttribute('data-name');
            const ok = await showAdminConfirm({
                type: 'danger',
                icon: '🗑️',
                title: `Hapus Akun Adopter?`,
                message: `Apakah Anda yakin ingin menghapus akun adopter "${name}"?\n\nPohon yang pernah diadopsi tidak akan terhapus dan tetap tersimpan aman di sistem.`,
                confirmText: 'Ya, Hapus Akun',
                confirmColor: 'var(--bad)'
            });
            if (ok) {
                form.submit();
            }
        });
    });
</script>
@endpush
