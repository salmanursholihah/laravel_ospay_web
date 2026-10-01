@extends('layouts.app')
@section('title', 'Detail Transaksi')

@section('content')
@php
    $awaitingPayment = $transaction->isMidtransPayment() && $transaction->isPaymentPending();
    $inProgress = in_array($transaction->status, ['pending', 'processing'], true) && ! $awaitingPayment;
@endphp

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 card divide-y divide-slate-100 text-sm">
        @foreach ([
            'Ref ID' => $transaction->ref_id,
            'Produk' => $transaction->product?->name . ' (' . $transaction->product?->product_code . ')',
            'Tujuan' => $transaction->customer_id,
            'Harga' => 'Rp ' . number_format($transaction->sell_price, 0, ',', '.'),
            'Metode bayar' => $transaction->payment_method === 'wallet' ? 'Wallet' : 'QRIS (Midtrans)',
            'Waktu' => $transaction->created_at->format('d M Y H:i'),
        ] as $label => $value)
            <div class="dl-row"><div class="dl-key">{{ $label }}</div><div class="font-medium break-all">{{ $value }}</div></div>
        @endforeach

        @if ($transaction->payment_status)
            <div class="dl-row"><div class="dl-key">Status bayar</div>@include('partials.badge', ['status' => $transaction->payment_status])</div>
        @endif
        <div class="dl-row"><div class="dl-key">Status</div>@include('partials.badge', ['status' => $transaction->status])</div>

        @if ($transaction->sn)
            <div class="dl-row"><div class="dl-key">Serial number</div><div class="font-mono break-all">{{ $transaction->sn }}</div></div>
        @endif
        @if ($transaction->failure_reason)
            <div class="dl-row"><div class="dl-key">Alasan gagal</div><div class="text-red-700">{{ $transaction->failure_reason }}</div></div>
        @endif
        @if ($transaction->refund_status && $transaction->refund_status !== 'not_required')
            <div class="dl-row"><div class="dl-key">Refund</div>
                <div>@include('partials.badge', ['status' => $transaction->refund_status])
                    @if ($transaction->refund_amount) &nbsp;Rp {{ number_format($transaction->refund_amount, 0, ',', '.') }} @endif
                </div>
            </div>
        @endif
    </div>

    <div class="space-y-4">
        @if ($awaitingPayment && $transaction->qr_url)
            <div class="card p-5 text-center">
                <div class="font-semibold mb-2">Scan QRIS untuk membayar</div>
                <img src="{{ $transaction->qr_url }}" alt="QRIS" class="mx-auto w-56 h-56 object-contain">
                <div class="text-xs text-slate-500 mt-2">Halaman ini diperbarui otomatis setelah pembayaran diterima.</div>
            </div>
        @endif

        @if ($inProgress || $awaitingPayment)
            <form method="POST" action="{{ route('transactions.refresh', $transaction) }}">
                @csrf
                <button class="btn-secondary w-full">Cek status sekarang</button>
            </form>
        @endif

        <a href="{{ route('transactions.index') }}" class="block text-center text-sm link">&larr; Semua transaksi</a>
    </div>
</div>

@if ($awaitingPayment || $inProgress)
    @push('scripts')
        {{-- Reload tiap 15 detik, maksimal 8 kali (~2 menit). Tiap reload memicu cek status (dibatasi 10 detik di server). --}}
        <script>
            (() => {
                const key = 'trx-reload-{{ $transaction->id }}';
                const nav = performance.getEntriesByType('navigation')[0];
                if (!nav || nav.type !== 'reload') sessionStorage.removeItem(key);
                const n = +(sessionStorage.getItem(key) || 0);
                if (n < 8) {
                    sessionStorage.setItem(key, n + 1);
                    setTimeout(() => location.reload(), 15000);
                }
            })();
        </script>
    @endpush
@endif
@endsection
