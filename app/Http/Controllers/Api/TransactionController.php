<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Store;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\StoreTransactionRequest;

class TransactionController extends Controller
{
    /**
     * Menampilkan semua riwayat transaksi (Laporan Penjualan)
     */
    public function index(Request $request)
    {
        $query = Transaction::with('details.product');

        if (request()->has('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('reference_no', 'LIKE', "%{$search}%")
                ->orWhere('notes', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . " 00:00:00", $request->end_date . " 23:59:59"]);
        }
        
        return TransactionResource::collection($query->latest()->paginate(10));
    }

    /**
     * Memproses transaksi baru (Kasir)
     */
    public function store(StoreTransactionRequest $request)
    {
        try {
            // Memulai database transaction untuk keamanan data
            $transaction = DB::transaction(function () use ($request) {
                
                // 1. Generate Nomor Invoice Otomatis
                $refNo = 'INV/' . now()->format('Ymd') . '/' . strtoupper(Str::random(5));

                // 2. Simpan Data Induk Transaksi
                $trx = Transaction::create([
                    'reference_no' => $refNo,
                    'labor_cost'   => $request->labor_cost,
                    'notes'        => $request->notes,
                    'total_price'  => 0, // Placeholder, diupdate setelah hitung item
                ]);

                $totalItemsPrice = 0;

                // 3. Proses Item Barang
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    // Cek ketersediaan stok
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi (Tersedia: {$product->stock})");
                    }

                    $subtotal = $product->selling_price * $item['quantity'];
                    $totalItemsPrice += $subtotal;

                    // Simpan ke Detail
                    $trx->details()->create([
                        'product_id' => $product->id,
                        'quantity'   => $item['quantity'],
                        'price'      => $product->selling_price, // Simpan harga saat ini
                        'subtotal'   => $subtotal,
                    ]);

                    // POTONG STOK OTOMATIS
                    $product->decrement('stock', $item['quantity']);
                }

                // 4. Update Grand Total (Harga Barang + Jasa)
                $trx->update([
                    'total_price' => $totalItemsPrice + $request->labor_cost
                ]);

                return $trx;
            });

            return (new TransactionResource($transaction->load('details.product')))
                ->additional(['message' => 'Transaksi berhasil diproses']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Melihat detail satu nota (Cetak Ulang Nota)
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction->load('details.product'));
    }
}
