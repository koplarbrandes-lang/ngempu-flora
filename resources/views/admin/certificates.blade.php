@extends('layouts.admin')

@section('title', 'Sertifikat Barcode Adopter')

@push('styles')
<style>
    .search-row {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }
    .search-row input {
        flex: 1;
        min-width: 200px;
        padding: 9px 14px;
        border-radius: 10px;
        border: 1px solid var(--line);
        font: inherit;
        background: var(--sf);
        color: var(--ink);
    }
    .cert-grid {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        margin-top: 4px;
    }
    .cert-card {
        background: var(--sf);
        border: 1.5px solid var(--line);
        border-radius: 16px;
        padding: 16px;
        transition: border-color .2s;
    }
    .cert-card:hover { border-color: var(--leaf); }
    .cert-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }
    .cert-tree-code {
        font-family: monospace;
        font-size: 13px;
        font-weight: 700;
        color: var(--moss);
        background: var(--soft);
        padding: 3px 8px;
        border-radius: 6px;
    }
    .cert-adopter {
        font-weight: 700;
        font-size: 14.5px;
        color: var(--ink);
        margin-bottom: 2px;
    }
    .cert-species {
        font-size: 12.5px;
        color: var(--mut);
        margin-bottom: 8px;
    }
    .cert-co2 {
        font-size: 13px;
        color: var(--moss);
        font-weight: 600;
        margin-bottom: 10px;
    }
    .barcode-wrap {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 10px 8px;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .cert-num-code {
        font-family: monospace;
        font-size: 10.5px;
        color: var(--mut);
        text-align: center;
        margin-top: 4px;
        letter-spacing: 0.06em;
    }
    .cert-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .cert-week-badge {
        font-size: 11px;
        background: var(--soft);
        color: var(--moss);
        border-radius: 99px;
        padding: 2px 10px;
        font-weight: 600;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<section>
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:14px;">
        <div>
            <h1>📜 Sertifikat Barcode Adopter</h1>
            <p class="fine" style="margin:2px 0 0;">Kelola, pratinjau, dan cetak sertifikat resmi setiap adopter pohon beserta barcode verifikasi autentisitas.</p>
        </div>
        <a href="{{ route('admin.trees') }}" class="btn alt">← Kembali ke Daftar Pohon</a>
    </div>

    {{-- Search --}}
    <div class="search-row">
        <form action="{{ route('admin.certificates') }}" method="GET" style="display:flex; gap:8px; flex:1; flex-wrap:wrap;">
            <input type="text" name="q" placeholder="Cari kode pohon, nama adopter..." value="{{ $search ?? '' }}" autocomplete="off">
            <button type="submit" class="btn">🔍 Cari</button>
            @if($search)
                <a href="{{ route('admin.certificates') }}" class="btn alt">✕ Reset</a>
            @endif
        </form>
        <div class="fine">Total: {{ $trees->count() }} sertifikat</div>
    </div>

    @if ($trees->isEmpty())
        <div class="card" style="text-align:center; padding:32px;">
            <div style="font-size:40px; margin-bottom:10px;">🌱</div>
            <p>Belum ada pohon yang terdaftar{{ $search ? ' dengan kata kunci tersebut' : '' }}.</p>
            <a href="{{ route('admin.trees.create') }}" class="btn" style="margin-top:14px;">+ Tambah Pohon Baru</a>
        </div>
    @else
        <div class="cert-grid">
            @foreach ($trees as $tree)
                <div class="cert-card">
                    <div class="cert-card-header">
                        <span class="cert-tree-code">{{ $tree->tree_code }}</span>
                        <span class="cert-week-badge">Bulan {{ $tree->current_week }}/{{ $tree->total_weeks }}</span>
                    </div>
                    <div class="cert-adopter">{{ $tree->adopter_name }}</div>
                    <div class="cert-species">🌿 {{ $tree->species->name }} <i>• {{ $tree->species->display_name }}</i></div>
                    <div class="cert-co2">🍃 CO₂ diserap: {{ $tree->carbon_absorbed_formatted }}</div>

                    {{-- Barcode SVG --}}
                    <div class="barcode-wrap">
                        {!! $tree->barcode_svg !!}
                        <div class="cert-num-code">{{ $tree->certificate_number }}</div>
                    </div>

                    {{-- Actions --}}
                    <div class="cert-actions">
                        <a href="{{ route('user.certificate', $tree) }}" target="_blank" class="btn" style="flex:1; justify-content:center;">
                            🖨️ Cetak Sertifikat
                        </a>
                        <a href="{{ route('certificate.verify', ['codeAndToken' => $tree->full_code_token]) }}" target="_blank" class="btn alt" style="padding:9px 12px;" title="Halaman Verifikasi Publik">
                            🔗
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
