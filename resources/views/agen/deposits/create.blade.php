@extends('layouts.app')
@section('title', 'Top Up Saldo')

@section('content')
<form method="POST" action="{{ route('agen.deposits.store') }}" class="max-w-md card card-pad space-y-5">
    @csrf
    @include('partials.input', ['name' => 'amount', 'label' => 'Nominal (min. Rp 10.000)', 'type' => 'number', 'min' => 10000, 'required' => true])
    <div class="flex flex-wrap gap-2 text-sm">
        @foreach ([50000, 100000, 250000, 500000, 1000000] as $n)
            <button type="button" onclick="document.getElementById('amount').value = {{ $n }}"
                    class="btn-secondary btn-sm">{{ number_format($n, 0, ',', '.') }}</button>
        @endforeach
    </div>
    <p class="text-xs text-slate-500">Pembayaran melalui QRIS. Saldo bertambah otomatis setelah pembayaran diterima.</p>
    <button class="btn-primary">Buat QRIS</button>
</form>
@endsection
