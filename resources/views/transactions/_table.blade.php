<div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Produk</th>
                <th>Tujuan</th>
                <th class="text-right">Harga</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse ($transactions as $t)
            <tr>
                <td class="whitespace-nowrap">{{ $t->created_at->format('d M Y H:i') }}</td>
                <td>{{ $t->product?->name }} <span class="text-slate-400 text-xs">{{ $t->product?->brand }}</span></td>
                <td>{{ $t->customer_id }}</td>
                <td class="text-right">Rp {{ number_format($t->sell_price, 0, ',', '.') }}</td>
                <td>
                    {{ $t->payment_method === 'wallet' ? 'Wallet' : 'QRIS' }}
                    @if ($t->payment_status) @include('partials.badge', ['status' => $t->payment_status]) @endif
                </td>
                <td>@include('partials.badge', ['status' => $t->status])</td>
                <td><a class="link" href="{{ route('transactions.show', $t) }}">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty">Belum ada transaksi.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
