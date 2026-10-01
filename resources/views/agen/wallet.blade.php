@extends('layouts.app')
@section('title', 'Wallet')

@section('content')
<div class="grid sm:grid-cols-3 gap-4 mb-6">
    <div class="card p-5">
        <div class="text-sm text-slate-500">Saldo tersedia</div>
        <div class="text-2xl font-bold mt-1">Rp {{ number_format($wallet->available_balance, 0, ',', '.') }}</div>
    </div>
    <div class="card p-5">
        <div class="text-sm text-slate-500">Total saldo</div>
        <div class="text-2xl font-bold mt-1">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</div>
    </div>
    <div class="card p-5">
        <div class="text-sm text-slate-500">Ditahan (transaksi pending)</div>
        <div class="text-2xl font-bold mt-1">Rp {{ number_format($wallet->held_balance, 0, ',', '.') }}</div>
    </div>
</div>

<a href="{{ route('agen.deposits.create') }}" class="btn-primary mb-6">Top up saldo</a>

<h2 class="font-semibold mb-3">Mutasi saldo</h2>
<div class="table-wrap">
    <table class="table">
        <thead>
            <tr><th>Waktu</th><th>Tipe</th><th class="text-right">Jumlah</th><th class="text-right">Saldo sebelum</th><th class="text-right">Saldo sesudah</th><th>Keterangan</th></tr>
        </thead>
        <tbody>
        @forelse ($ledger as $l)
            <tr>
                <td class="whitespace-nowrap">{{ $l->created_at->format('d M Y H:i') }}</td>
                <td>@include('partials.badge', ['status' => $l->type])</td>
                <td class="text-right">Rp {{ number_format($l->amount, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($l->balance_before, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($l->balance_after, 0, ',', '.') }}</td>
                <td>{{ $l->description }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty">Belum ada mutasi.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $ledger->links() }}</div>
@endsection
