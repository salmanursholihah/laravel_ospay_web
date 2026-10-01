@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="grid gap-4 sm:grid-cols-3 mb-10">
    <div class="card card-pad">
        <div class="stat-label">Total transaksi</div>
        <div class="stat-value">{{ $total }}</div>
    </div>
    <div class="card card-pad">
        <div class="stat-label">Berhasil</div>
        <div class="stat-value">{{ $success }}</div>
    </div>
    <a href="{{ route('products.index') }}" class="rounded-2xl bg-brand-700 p-5 sm:p-6 text-white shadow-card transition-colors hover:bg-brand-800">
        <div class="text-sm text-brand-100">Butuh pulsa atau token?</div>
        <div class="mt-1.5 text-2xl font-bold tracking-tight">Beli produk</div>
    </a>
</div>

<div class="flex items-center justify-between mb-3">
    <h2 class="text-lg font-bold tracking-tight text-slate-900">Transaksi terakhir</h2>
    <a href="{{ route('transactions.index') }}" class="link text-sm">Lihat semua</a>
</div>
@include('transactions._table', ['transactions' => $latest])
@endsection
