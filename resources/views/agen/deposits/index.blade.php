@extends('layouts.app')
@section('title', 'Deposit')

@section('content')
<a href="{{ route('agen.deposits.create') }}" class="btn-primary mb-4">Top up saldo</a>

<div class="table-wrap">
    <table class="table">
        <thead>
            <tr><th>Waktu</th><th>No. transaksi</th><th class="text-right">Nominal</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
        @forelse ($deposits as $d)
            <tr>
                <td class="whitespace-nowrap">{{ $d->created_at->format('d M Y H:i') }}</td>
                <td class="font-mono text-xs">{{ $d->transaction_number }}</td>
                <td class="text-right">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                <td>@include('partials.badge', ['status' => $d->status])</td>
                <td><a class="link" href="{{ route('agen.deposits.show', $d) }}">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="5" class="empty">Belum ada deposit.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $deposits->links() }}</div>
@endsection
