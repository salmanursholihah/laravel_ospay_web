@extends('layouts.app')
@section('title', 'Dashboard Agen')

@section('content')
{{-- Saldo: elemen utama halaman, yang paling sering dicek agen --}}
<section class="relative overflow-hidden rounded-3xl bg-ink-900 p-6 sm:p-8 text-white shadow-card mb-6">
    <div class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full bg-brand-600/25 blur-3xl"></div>
    <div class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="text-sm text-slate-300">Saldo tersedia</div>
            <div class="mt-2 text-4xl sm:text-5xl font-extrabold tracking-tight">Rp {{ number_format($wallet->available_balance, 0, ',', '.') }}</div>
            @if ($wallet->held_balance > 0)
                <div class="mt-2 text-sm text-amber-300">Ditahan untuk transaksi pending: Rp {{ number_format($wallet->held_balance, 0, ',', '.') }}</div>
            @endif
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('products.index') }}" class="btn bg-brand-500 text-ink-900 hover:bg-brand-400">Beli produk</a>
            <a href="{{ route('agen.deposits.create') }}" class="btn border border-white/20 text-white hover:bg-white/10">Top up saldo</a>
        </div>
    </div>
</section>

<div class="grid gap-4 sm:grid-cols-3 mb-10">
    <div class="card card-pad">
        <div class="stat-label">Komisi pending</div>
        <div class="stat-value">Rp {{ number_format($pendingCommission, 0, ',', '.') }}</div>
    </div>
    <div class="card card-pad">
        <div class="stat-label">Komisi dibayar</div>
        <div class="stat-value">Rp {{ number_format($paidCommission, 0, ',', '.') }}</div>
    </div>
    <a href="{{ route('agen.sub-users.index') }}" class="card card-pad transition-colors hover:border-brand-600/40">
        <div class="stat-label">Sub-user</div>
        <div class="stat-value">{{ $subUserCount }}</div>
    </a>
</div>

<div class="flex items-center justify-between mb-3">
    <h2 class="text-lg font-bold tracking-tight text-slate-900">Transaksi terakhir</h2>
    <a href="{{ route('transactions.index') }}" class="link text-sm">Lihat semua</a>
</div>
@include('transactions._table', ['transactions' => $latest])
@endsection
