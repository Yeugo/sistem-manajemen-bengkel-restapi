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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->string('name');
            $table->string('sku')->unique();
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);
            
            // Pemisahan Harga
            $table->decimal('purchase_price', 12, 2); // Harga dari Supplier
            $table->decimal('selling_price', 12, 2);  // Harga Jual Standar ke Konsumen
            
            $table->text('description')->nullable();
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
