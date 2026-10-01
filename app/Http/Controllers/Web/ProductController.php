<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Services\Iak\ProductSyncService;
use Illuminate\Http\Request;
use RuntimeException;

class ProductController extends Controller
{
    /**
     * Katalog produk aktif untuk agen & user.
     * Harga otomatis mengikuti role yang login.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $products = Products::where('status', 'active')
            ->when($request->category, fn ($q, $v) => $q->where('category', $v))
            ->when($request->type, fn ($q, $v) => $q->where('type', $v))
            ->when($request->q, function ($q, $v) {
                $q->where(function ($q) use ($v) {
                    $q->where('name', 'like', "%{$v}%")
                        ->orWhere('brand', 'like', "%{$v}%")
                        ->orWhere('product_code', 'like', "%{$v}%");
                });
            })
            ->orderBy('type')->orderBy('brand')->orderBy('base_price')
            ->paginate(24)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'role' => $user->role,
            'types' => Products::where('status', 'active')->whereNotNull('type')
                ->distinct()->orderBy('type')->pluck('type'),
        ]);
    }

    /**
     * Hanya superadmin: sync price list dari IAK API ke tabel products.
     */
    public function sync(ProductSyncService $productSyncService)
    {
        try {
            $result = $productSyncService->sync();
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with(
            'success',
            "Sync selesai. Produk baru: {$result['created']}, produk diupdate: {$result['updated']}."
        );
    }
}
