<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel transactions
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            
            // Menghubungkan ke tabel products
            $table->foreignId('product_id')->constrained();
            
            $table->integer('quantity');
            $table->decimal('price', 12, 2); // Harga jual saat transaksi dilakukan
            $table->decimal('subtotal', 12, 2); // Qty x Price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
