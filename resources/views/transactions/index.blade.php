@extends('layouts.app')
@section('title', 'Transaksi')

@section('content')
<form method="GET" class="mb-4 flex gap-2 text-sm">
    <select name="status" class="field">
        <option value="">Semua status</option>
        @foreach (['pending', 'processing', 'success', 'failed'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
        @endforeach
    </select>
    <button class="btn-primary">Filter</button>
</form>

@include('transactions._table')

<div class="mt-6">{{ $transactions->links() }}</div>
@endsection
