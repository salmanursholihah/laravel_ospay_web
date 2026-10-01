# Perubahan perbaikan (30 Sep 2026)

File yang diubah/ditambah:

- app/Services/TransactionService.php
- app/Http/Controllers/Web/TransactionController.php
- app/Services/Midtrans/CreateTransactionVaService.php
- resources/views/transactions/show.blade.php
- app/Http/Controllers/Web/AdminCommissionController.php   (BARU)
- resources/views/admin/commissions/index.blade.php        (BARU)
- app/Services/CommissionService.php                       (tambah backfill())
- routes/web.php                                           (route admin komisi)
- resources/views/layouts/app.blade.php                    (menu "Aturan Komisi")
- resources/views/agen/commissions.blade.php               (teks kosong lebih jelas)

## Ringkasan

1. Validasi nomor tujuan: produk pulsa/data/SMS/paket wajib 08xxxxxxxx (10-13 digit),
   +62/62 dinormalisasi; PLN prepaid wajib nomor meter 11 digit.
2. Guard harga: transaksi ditolak kalau harga jual < harga modal.
3. Semua respons IAK diproses lewat applyIakResponse() dengan lock baris,
   jadi callback + cek manual tidak bisa commit/release saldo atau refund dua kali.
4. Status PENDING dari IAK tidak lagi menimpa status final.
5. User (QRIS): status pembayaran ditarik langsung dari Midtrans
   (syncPaymentWithMidtrans), tidak hanya menunggu callback.
6. Halaman detail transaksi (user & agen) otomatis cek status saat dibuka
   (dibatasi 10 detik), reload otomatis maksimal 8 kali, tombol "Cek status
   sekarang" memberi pesan hasil.

7. Superadmin: menu "Aturan Komisi" untuk mengatur aturan komisi agen
   (umum / per produk, nominal / persen) dan tombol "Hitung sekarang" yang
   membuat komisi dari transaksi sub-user nyata yang sudah sukses. Tanpa seeder.

## Cara pakai

Ekstrak di atas proyek yang sudah ada (.env dan vendor/ tidak disertakan,
jadi milikmu tidak tertimpa), lalu:

    php artisan optimize:clear

## Catatan sandbox IAK

Hasil ditentukan 8 digit terakhir nomor tujuan: 11111111 = sukses,
22222222 = gagal, selain itu = pending. PLN: 96511111111 (sukses),
96522222222 (gagal).
