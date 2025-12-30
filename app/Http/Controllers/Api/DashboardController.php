<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
// use Illuminate\Container\Attributes\DB;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        $stats = [
            'omzet_hari_ini' => Transaction::where('created_at', '>=', $today)->sum('total_price'),
            'transaksi_hari_ini' => Transaction::where('created_at', '>=', $today)->count(),
            'omzet_bulan_ini' => Transaction::where('created_at', '>=', $thisMonth)->sum('total_price'),
            'transaksi_bulan_ini' => Transaction::where('created_at', '>=', $thisMonth)->count(),
            'stok_menipis_count' => Product::whereColumn('stock', '<=', 'min_stock')->count(),
        ];

        $topProducts = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name ?? 'Produk tidak ditemukan',
                    'sold' => (int) $item->total_qty
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary'       => $stats,
                'top_products'  => $topProducts
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
