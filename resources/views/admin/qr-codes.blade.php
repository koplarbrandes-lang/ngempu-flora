@extends('layouts.admin')

@section('title', 'Cetak Label QR Code')

@push('styles')
<style>
    .labels-grid {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        margin-top: 18px;
    }
    .lab {
        background: #FFFFFF;
        color: #14231B;
        border: 2px dashed #9DB3A5;
        border-radius: 14px;
        padding: 16px;
        text-align: center;
        font-size: 13.5px;
        break-inside: avoid;
    }
    .lab b {
        display: block;
        font-size: 16px;
        margin-top: 6px;
    }
    .lab .qr-box {
        display: flex;
        justify-content: center;
        margin: 10px 0;
    }
    @media print {
        header, nav, .no-print {
            display: none !important;
        }
        body {
            background: #fff !important;
        }
        .wrap {
            max-width: 100% !important;
            padding: 0 !important;
        }
        .labels-grid {
            gap: 12px;
            grid-template-columns: repeat(3, 1fr);
        }
        .lab {
            border: 1px solid #000;
        }
    }
</style>
@endpush

@section('content')
<section>
    <div class="no-print" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
        <div>
            <h1>Cetak Label QR Code</h1>
            <p class="fine" style="margin:2px 0 0;">Label siap tempel untuk fisik pohon di lapangan.</p>
        </div>
        <button class="btn" onclick="window.print()">🖨️ Cetak / Print Label</button>
    </div>

    <div class="labels-grid">
        @forelse ($trees as $tree)
            <div class="lab">
                <div class="qr-box" id="qr-{{ $tree->id }}" data-url="{{ route('tracker.show', ['codeAndToken' => $tree->full_code_token]) }}"></div>
                <b>{{ $tree->tree_code }}</b>
                <span>{{ $tree->species->name }}</span><br>
                <small class="fine">{{ explode(',', $tree->adopter_name)[0] }}</small>
            </div>
        @empty
            <div class="card" style="grid-column: 1 / -1; text-align:center; padding:36px;">
                <p class="fine" style="font-size:14.5px; margin-bottom:14px;">Belum ada pohon terdaftar untuk dicetak label QR kodenya.</p>
                <a href="{{ route('admin.trees.create') }}" class="btn">+ Tambah Pohon Baru</a>
            </div>
        @endforelse
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.querySelectorAll('.qr-box').forEach(el => {
        const url = el.getAttribute('data-url');
        new QRCode(el, {
            text: url,
            width: 110,
            height: 110,
            colorDark: "#14231B",
            colorLight: "#FFFFFF",
            correctLevel: QRCode.CorrectLevel.M
        });
    });
</script>
@endpush
