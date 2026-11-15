@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<section>
    <h1>Perkembangan Bulan Ini</h1>
    <p class="sub" style="color:var(--mut); margin:4px 0 16px;">
        {{ $updatedCount }} dari {{ $totalTrees }} pohon telah diperbarui datanya.
    </p>

    <div class="grid g4">
        <div class="card stat" style="margin:0;">
            <div class="n">{{ $totalTrees }}</div>
            <small>Pohon Aktif</small>
        </div>
        <div class="card stat" style="margin:0;">
            <div class="n">{{ $dueTrees->count() }}</div>
            <small>Perlu Update</small>
        </div>
        <div class="card stat" style="margin:0;">
            <div class="n">{{ $lateTrees->count() }}</div>
            <small>Terlambat (> 1 bulan)</small>
        </div>
        <div class="card stat" style="margin:0;">
            <div class="n">{{ $totalCO2Formatted }}</div>
            <small>Total CO₂ Terserap</small>
        </div>
    </div>

    <div class="card" style="margin-top:16px;">
        <h2 style="font-size:18px; margin-bottom:12px;">Pohon yang Memerlukan Update</h2>
        
        @if ($dueTrees->isNotEmpty())
            @foreach ($dueTrees as $tree)
                <div class="row">
                    <div>
                        <b>{{ $tree->tree_code }}</b> • {{ $tree->species->name }} (<i>{{ $tree->species->display_name }}</i>)<br>
                        <span class="fine">{{ $tree->adopter_name }} • {{ $tree->plot }}</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span class="tag {{ $tree->isLate() ? 'late' : 'due' }}">
                            {{ $tree->isLate() ? 'Terlambat' : 'Jatuh Tempo' }}
                        </span>
                        <a href="{{ route('admin.update', ['tree_id' => $tree->id]) }}" class="btn alt">
                            Update
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <p class="fine" style="margin:8px 0;">Semua pohon sudah diperbarui untuk bulan ini.</p>
        @endif
    </div>
</section>
@endsection
