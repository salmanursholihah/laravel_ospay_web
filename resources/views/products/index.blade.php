@extends('layouts.app')
@section('title', 'Produk')

@section('content')
<form method="GET" class="card p-4 mb-6 grid sm:grid-cols-4 gap-3 text-sm">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama / operator / kode"
           class="field sm:col-span-2">
    <select name="type" class="field">
        <option value="">Semua tipe</option>
        @foreach ($types as $t)
            <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
        @endforeach
    </select>
    <button class="btn-primary">Filter</button>
</form>

@if ($products->isEmpty())
    <div class="text-slate-500 text-sm">Produk tidak ditemukan.</div>
@endif

<div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach ($products as $p)
        <div class="card p-4 flex flex-col">
            <div class="text-xs font-medium text-brand-700">{{ $p->type }} &middot; {{ $p->brand }}</div>
            <div class="font-semibold mt-1">{{ $p->name }}</div>
            <div class="text-xs text-slate-400 mb-3">{{ $p->product_code }}</div>
            <div class="mt-auto flex items-center justify-between">
                <div class="font-bold">Rp {{ number_format($p->priceForRole($role), 0, ',', '.') }}</div>
                <a href="{{ route('transactions.create', ['product' => $p->product_code]) }}"
                   class="btn-primary btn-sm">Beli</a>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-6">{{ $products->links() }}</div>
@endsection
