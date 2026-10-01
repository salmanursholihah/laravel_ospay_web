@extends('layouts.app')
@section('title', 'Beli Produk')

@section('content')
<div class="max-w-lg card p-6">
    <div class="text-xs font-medium text-brand-700">{{ $product->type }} &middot; {{ $product->brand }}</div>
    <div class="text-lg font-semibold">{{ $product->name }}</div>
    <div class="text-xs text-slate-400 mb-1">{{ $product->product_code }}</div>
    @if ($product->description)
        <p class="text-sm text-slate-600 mb-3">{{ $product->description }}</p>
    @endif
    <div class="text-2xl font-bold mb-4">Rp {{ number_format($price, 0, ',', '.') }}</div>

    <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="product_code" value="{{ $product->product_code }}">
        @include('partials.input', [
            'name' => 'customer_id',
            'label' => 'Nomor HP / ID Pelanggan tujuan',
            'required' => true,
            'placeholder' => 'contoh: 08123456789',
        ])

        <p class="text-xs text-slate-500">
            @if (auth()->user()->isAgent())
                Pembayaran dipotong dari saldo wallet Anda.
            @else
                Setelah klik bayar, Anda akan mendapat QRIS untuk dibayar.
            @endif
        </p>

        <div class="flex gap-3">
            <button class="btn-primary">
                {{ auth()->user()->isAgent() ? 'Beli sekarang' : 'Lanjut bayar' }}
            </button>
            <a href="{{ route('products.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
